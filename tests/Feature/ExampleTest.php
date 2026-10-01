<?php

use App\Http\Services\OrderService;
use App\Models\Order;

it('revierte todo si falla el descuento de inventario', function () {
    $pedido = Order::factory()->create([
        'status' => 'draft',
        'total' => 600,
    ]);

    

    expect(fn () => app(OrderService::class)->confirmar($pedido))
        ->toThrow(RuntimeException::class);

        expect($pedido->fresh()->status)->toBe('draft');

    
});

