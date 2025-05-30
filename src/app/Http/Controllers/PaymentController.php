<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Jobs\ProcessPayment;
use Illuminate\Validation\Rule;    // ← add this

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Payment::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pix_payload' => 'nullable|array',
            'pix_transaction_id' => 'nullable|string',
            'status' => 'required|in:waiting,success,failed',
        ]);
        return Payment::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $payment;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->only(['status']));
        return $payment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }

    public function webhook(Request $request)
    {
        // Validate payload...
        $paymentId = $request->input('payment_id');

        // Dispatch a job to handle everything:
        ProcessPayment::dispatch($paymentId);

        return response()->json([], 200);
    }

    public function fake(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => [
                'required',
                Rule::in(['waiting', 'success', 'failed']),
                // if you really want “success” here, add it to both this list
                // and to your DB enum constraint (or migration)
            ],
            'pix_payload' => 'nullable|string',
            'pix_transaction_id' => 'nullable|string',
        ]);

        $payment = Payment::create($data);

        ProcessPayment::dispatch($payment->id);

        return response()->json($payment, 201);
    }
}
