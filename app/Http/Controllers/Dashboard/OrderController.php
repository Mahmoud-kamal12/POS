<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){

        $orders = Order::whereHas('client' , function ($q) use($request){
            return $q->where('name' , 'like' , '%'. $request->search .'%');
        })->paginate(8);
        return view('dashboard.orders.index' , compact('orders' , ));
    }

    public function products(Order  $order){
        $products = $order->products()->paginate(5);

        return view('dashboard.orders._products' ,compact('order' , 'products'));
    }

    public function destroy(Order $order)
    {
        $order = Order::find($order)->first();
        $products = $order->products;
        foreach ($products as $product) {
            $product->stock += $product->pivot->quantity;
            $product->update();
        }
        $order->delete();
        return redirect()->route('dashboard.orders.index');
    }

    public function edit(Order $order)
    {

        return  view('dashboard.clients.orders.edit')->with('order' , $order);
    }
}
