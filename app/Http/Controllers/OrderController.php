<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\OrderStatus\OrderPendingStatus;
use App\OrderStatus\OrderProcessingStatus;
use App\OrderStatus\OrderShippedStatus;

class OrderController extends Controller
{
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'newStatus' => 'required|in:processing,shipped,completed,cancelled',
        ]);

        $newStatus = $request->input('newStatus');
        $success = false;

        try {
            $handler = match (true) {
                $newStatus === 'processing' && $order->status === 'pending' => new OrderPendingStatus(),
                $newStatus === 'shipped' && $order->status === 'processing' => new OrderProcessingStatus(),
                $newStatus === 'completed' && $order->status === 'shipped' => new OrderShippedStatus(),
                $newStatus === 'cancelled' && in_array($order->status, ['pending', 'processing']) => new OrderPendingStatus(),
                default => null,
            };

            if (isset($handler)) {
                $handler->transitionTo($order, $newStatus);
                $success = true;
            }

            return response()->json(['message' => $success ? 'Order status updated successfully' :
                'Failed to update order status'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
