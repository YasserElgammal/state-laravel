<?php

namespace App\OrderStatus;

use App\Models\Order;
use App\OrderStatus\OrderStatusInterface;

class OrderShippedStatus implements OrderStatusInterface
{
    public function getValue(): string
    {
        return 'shipped';
    }

    public function canTransitionTo(string $targetStatus): bool
    {
        return match ($targetStatus) {
            'completed' => true,
            default     => false,
        };
    }

    public function transitionTo(Order $order, string $targetStatus): void
    {
        if ($this->canTransitionTo($targetStatus)) {
            $order->status = $targetStatus;
            $order->save();
        } else {
            throw new \Exception("Invalid transition from 'shipped' to '$targetStatus'");
        }
    }
}
