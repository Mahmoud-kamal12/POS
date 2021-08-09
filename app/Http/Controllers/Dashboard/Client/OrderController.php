<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create(Request $request , Client $client)
    {
        $categories = Category::with('products')->get();
        return view('dashboard.clients.orders.create')->with('categories' , $categories )->with('client' , $client);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , Client $client)
    {
        $order = $client->orders()->create([]);
        $total = 0;
        foreach ($request->products as $id => $product_quantity){
            $product = Product::findOrFail($id);
            if ($product->stock <= 0 || $product->stock < $product_quantity['quantity']) {
                session()->flash('error' , __('site.no_stock'));
                $order->delete();
                return back();
            }
            $total += $product->sale_price * $product_quantity['quantity'];
            $order->products()->attach($id , ['quantity' => $product_quantity['quantity'] ]);
            $product->update([
                'stock' =>  $product->stock - $product_quantity['quantity']
            ]);
        }
        $order->update(['total' => $total]);
        session()->flash('success' , __('site.added_successfuly'));
        return redirect()->route('dashboard.orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
