<?php
// src/app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Batch;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Order::class, 'order');
    }

    public function index(Request $request)
    {
        $query = Order::with('items.batch', 'items.coupon', 'payment');
        if ($request->user()->hasRole('client')) {
            $query->where('user_id', $request->user()->id);
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        // 1) validação
        $payload = $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.batch_id' => 'required|exists:batches,id',
            'tickets.*.quantity' => 'required|integer|min:1',
            'tickets.*.coupon_id' => 'nullable|exists:coupons,id',
        ]);

        $user = $request->user();

        // 2) transação: calcula total e cria order+items
        $order = DB::transaction(function () use ($payload, $user) {
            $total = 0;
            $items = [];

            foreach ($payload['tickets'] as $t) {
                $batch = Batch::lockForUpdate()->find($t['batch_id']);
                $qty = $t['quantity'];
                $price = $batch->price;
                $discount = 0;

                if (!empty($t['coupon_id'])) {
                    $coupon = Coupon::find($t['coupon_id']);
                    $discount = $coupon->discount;
                }

                $line = $price * $qty - $discount;
                if ($line < 0) {
                    throw ValidationException::withMessages([
                        'tickets' => "Desconto maior que o total do lote #{$batch->id}"
                    ]);
                }
                $total += $line;

                $items[] = [
                    'batch_id' => $batch->id,
                    'quantity' => $qty,
                    'coupon_id' => $t['coupon_id'] ?? null,
                    'unit_price' => $price,
                    'discount' => $discount,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $it) {
                $it['order_id'] = $order->id;
                OrderItem::create($it);
            }

            return $order;
        });

        // 3) resposta: apenas order_id e total
        return response()->json([
            'order_id' => $order->id,
            'total' => $order->total,
        ], 201);
    }

    public function show(Order $order)
    {
        return $order->load('items.batch', 'items.coupon', 'payment');
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->only(['status']));
        return response()->json($order->fresh());
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
}
