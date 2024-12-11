<?php

namespace App\OrderStatus;

use App\Models\Order;

interface OrderStatusInterface
{
    public function getValue(): string;
    public function canTransitionTo(string $targetStatus): bool;
    public function transitionTo(Order $order, string $targetStatus): void;
}
