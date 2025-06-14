<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Payment;
use App\Jobs\ProcessPayment;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1) (Opcional) validar assinatura/webhook secret aqui...
        //    Exemplo: if (! $this->isValidSignature($request)) { return response()->json(['error'=>'Invalid signature'], 403); }

        $payload = $request->all();

        // 2) Extrai o external_id que você enviou no momento da criação
        $externalId = $payload['external_id'] ?? null;
        if (!$externalId) {
            return response()->json(['error' => 'external_id not provided'], 400);
        }

        // 3) Procura o pagamento na nossa base
        $payment = Payment::where('external_id', $externalId)->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // 4) Atualiza campos do Payment com o que veio no webhook
        $payment->update([
            // converte o status numérico da Paggue para nossas strings
            'status' => ($payload['status'] ?? 0) === 1 ? 'success' : 'failed',
            'payment' => $payload['payment'] ?? $payment->payment,
            'end_to_end_id' => $payload['endToEndId'] ?? $payment->end_to_end_id,
            'reference' => $payload['reference'] ?? $payment->reference,
            'paid_at' => isset($payload['paid_at'])
                ? Carbon::parse($payload['paid_at'])
                : $payment->paid_at,
            'expiration_at' => isset($payload['expiration_at'])
                ? Carbon::parse($payload['expiration_at'])
                : $payment->expiration_at,
            // opcional: atualizar pix_payload bruto se quiser armazenar tudo
            'pix_payload' => $payload,
        ]);

        // 5) Enfileira o processamento (gera tickets, notifica, etc.)
        ProcessPayment::dispatch($payment->id);

        // 6) Responde imediatamente ao gateway
        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * (Opcional) Exemplo de validação de assinatura.
     */
    protected function isValidSignature(Request $request): bool
    {
        // $signature = $request->header('X-Hub-Signature');
        // calcular hash HMAC e comparar...
        return true;
    }
}
