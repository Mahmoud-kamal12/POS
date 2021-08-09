<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProducts;
use App\Http\Requests\Products\UpdateProducts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $products = Product::when($request->search ,function ($q) use ($request){

            return $q->whereTranslationLike('name' , '%'.$request->search.'%');

        })->when($request->category_id ,function ($q) use ($request){

            return $q->where('category_id' , '=' , $request->category_id);

        })->latest()->paginate(8);

        return  view('dashboard.products.index' , [
            'products' => $products,
            'categories' =>  Category::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.products.create')->with('categories' , Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProducts $request)
    {
        $request_data = $request->all();

        if ($request->image){
            $hashnameimage = $request->image->hashName();
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                ->save(public_path('uploads/products_images/') . $hashnameimage  , '75' , 'png' );
            $request_data['image'] = $hashnameimage;

        }else{
            $request_data['image'] =  "defualt_product.png";
        }

        Product::create($request_data);
        session()->flash('success' , __('site.added_successfuly'));
        return  redirect()->route('dashboard.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('dashboard.products.edit' , [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProducts $request, Product $product)
    {
        $request_data = $request->all();

        if ($request->image){
            Storage::disk('public_uploads')->delete('/products_images/'. $product->image);
            $hashnameimage = $request->image->hashName();
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                ->save(public_path('uploads/products_images/') . $hashnameimage  , '75' , 'png' );
            $request_data['image'] = $hashnameimage;

        }

        $product->update($request_data);
        session()->flash('success' , __('site.added_successfuly'));
        return  redirect()->route('dashboard.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product = Product::find($product)->first();
        Storage::disk('public_uploads')->delete('products_images/'.$product->image);
        $product->delete();
        return redirect()->route('dashboard.products.index');
    }
}
