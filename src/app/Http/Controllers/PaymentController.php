<?php
// src/app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Jobs\ProcessPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Payment::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'description' => 'nullable|string',
        ]);

        $order = Order::findOrFail($data['order_id']);
        $externalId = (string) Str::uuid();

        // 1) Cria a cobrança na Paggue
        $response = Http::withToken(config('services.paggue.token'))
            ->withHeaders(['x-company-id' => config('services.paggue.company_id')])
            ->post('https://ms.paggue.io/cashin/api/billing_order', [
                'payer_name' => $request->user()->name,
                'amount' => intval($order->total * 100),
                'external_id' => $externalId,
                'description' => $data['description'] ?? 'Pagamento de pedido',
                'meta' => [
                    'webhook_url' => route('webhooks.payment'),
                    'order_id' => $order->id,
                ],
            ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Falha ao criar cobrança na Paggue',
                'detail' => $response->json(),
            ], 502);
        }

        $payload = $response->json();

        // 2) Persiste todos os campos no banco
        $payment = Payment::create([
            'order_id' => $order->id,
            'external_id' => $externalId,
            'payer_name' => $payload['payer_name'] ?? $request->user()->name,
            'amount' => $payload['amount'] ?? 0,
            'description' => $payload['description'] ?? $data['description'] ?? null,
            'pix_payload' => $payload,
            'payment' => $payload['payment'] ?? null,
            'end_to_end_id' => $payload['endToEndId'] ?? null,
            'reference' => $payload['reference'] ?? null,
            'status' => 'waiting',
            'expiration_at' => isset($payload['expiration_at'])
                ? Carbon::parse($payload['expiration_at'])
                : null,
        ]);

        // 3) Retorna ao front apenas o que ele precisa
        return response()->json([
            'id' => $payment->id,
            'qr_code' => $payment->payment,
            'expires_at' => $payment->expiration_at,
            'created_at' => $payment->created_at,
        ], 201);
    }

    public function show(Payment $payment)
    {
        return $payment;
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['waiting', 'success', 'failed'])],
        ]);

        $old = $payment->status;
        $payment->update($data);

        // Se passou para success, dispara o job
        if ($old !== 'success' && $payment->status === 'success') {
            ProcessPayment::dispatch($payment->id);
        }

        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }

    public function fake(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => ['required', Rule::in(['waiting', 'success', 'failed'])],
        ]);

        $externalId = (string) Str::uuid();
        $now = Carbon::now();

        $payment = Payment::create([
            'order_id' => $data['order_id'],
            'external_id' => $externalId,
            'payer_name' => 'Fake Payer',
            'amount' => intval($data['amount'] * 100),
            'description' => 'Fake payment for order ' . $data['order_id'],
            'pix_payload' => [],
            'payment' => 'FAKEPIXCODE',
            'end_to_end_id' => null,
            'reference' => null,
            'status' => $data['status'],
            'expiration_at' => $now->copy()->addHour(),
        ]);

        if ($payment->status === 'success') {
            ProcessPayment::dispatch($payment->id);
        }

        return response()->json($payment, 201);
    }
}
