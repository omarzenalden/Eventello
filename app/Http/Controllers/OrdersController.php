<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pending_request;
use App\Models\Order;


class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Order::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_order(Request $request,$id)
    {
    $order_pend = Pending_request::find($id);

    if (!$order_pend) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    if ($order_pend->status !== 'accepted') {
        return response()->json(['error' => 'Previous order is rejected, cannot add to your orders'], 403);
    }

    $order = new Order($request->all());
    $order->Pending_request()->associate($order_pend);
    $order->save();

    return response()->json($order, 201);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'cost_planner' => 'required|integer',
            'payment_status' => 'required|string|max:45',
            'date_of_expired' => 'required|string|max:45',
        ]);

        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'cost_planner' => 'integer',
            'payment_status' => 'string|max:45',
            'date_of_expired' => 'string|max:45',
            'planner_idplanner' => 'exists:planners,idplanner',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
