<?php


namespace App\Http\Services;

use App\Models\Order;

class OrderService
{

    public function crear(array $data)
    {

        $order = Order::create($data);

        return $order;
    }

    


    public function confirmar(Order $order)
    {
       
    //if

    if ($order->total > 500) {
        throw new \RuntimeException('El pedido no está en estado borrador');
    }


    $order->status = 'confirmed';
    $order->save();

    }
}