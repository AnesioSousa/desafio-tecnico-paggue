<?php
// src/app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Order::class, 'order');
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Order::with('tickets');

        if ($request->user()->hasRole('client')) {
            $query->where('user_id', $request->user()->id);
        }

        return $query->get();
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
            'total' => 'required|numeric',
            'tickets' => 'required|array|min:1',
            'tickets.*.batch_id' => 'required|exists:batches,id',
            'tickets.*.coupon_id' => 'nullable|exists:coupons,id',
        ]);

        $data['user_id'] = $request->user()->id;

        // Transaction to ensure atomicity
        $order = DB::transaction(function () use ($data) {
            $tickets = $data['tickets'];
            unset($data['tickets']);

            $order = Order::create($data);
            $order->tickets()->createMany($tickets);

            return $order;
        });

        return response()->json($order->load('tickets'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return $order->load('tickets');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->only(['total', 'status']));
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
}
