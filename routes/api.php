<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('order-status-update/{order}', [OrderController::class, 'updateOrderStatus']);
