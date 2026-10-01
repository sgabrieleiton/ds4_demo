<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    //para filtrar registros deacuerdo a su status y la inicial de su codigo
        $status=$request->input('status');//request('status');
        $q=$request->input('q');

        $data=Order::status($status)
        ->when($q, function($query, $q){
            return $query->where('codigo', 'like', "$q%")
            ->orwhereHas('customer', function($query) use ($q){
                $query->where('name', 'like', "$q%");
            });
        })

        ->paginate(10);

         return $data;
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'codigo' => ['required','string','max:225','unique:orders,codigo'],
            'customer_id' => ['required','exists:customers,id'],
            'total' => ['required','numeric','min:0'],
            'status' => ['required','in:draft,confirmed,canceled'],
        ]);

        $order = Order::create($validatedData);
        return $order;
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order);             //$order->load('customer'); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'codigo' => ['required','string','max:225', Rule::unique('orders','codigo')
            ->ignore($order->id)],
            'customer_id' => ['required','exists:customers,id'],
            'total' => ['required','numeric','min:0'],
            'status' => ['required','in:draft,confirmed,canceled'],
        ]);

        $order->update($validatedData);

        return $order;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
       
    }
}