<?php

namespace App\OrderStatus;

use App\Models\Order;
use App\OrderStatus\OrderStatusInterface;

class OrderPendingStatus implements OrderStatusInterface
{
    public function getValue(): string
    {
        return 'pending';
    }


    public function canTransitionTo(string $targetStatus): bool
    {
        return match ($targetStatus) {
            'processing', 'cancelled' => true,
            default => false,
        };
    }

    public function transitionTo(Order $order, string $targetStatus): void
    {
        if ($this->canTransitionTo($targetStatus)) {
            $order->status = $targetStatus;
            $order->save();
        } else {
            throw new \Exception("Invalid transition from 'pending' to '$targetStatus'");
        }
    }
}
