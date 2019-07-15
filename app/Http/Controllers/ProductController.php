<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function store()
    {
        $validated = request()->validate([
            'name'     => 'required',
            'color'    => 'nullable',
            'quantity' => 'required',
            'price'    => 'required',
        ]);

        Product::create($validated);

        return back();
    }

    public function patch()
    {
        $validated = request()->validate([
            'id'       => 'required',
            'name'     => 'required',
            'color'    => 'nullable',
            'quantity' => 'required',
            'price'    => 'required',
            'active'   => 'nullable',
        ]);

        $product = Product::find($validated['id']);
        if ($validated['name'] != '') {
            $product->name = $validated['name'];
        }
        if ($validated['price'] != '') {
            $product->price = $validated['price'];
        }
        if ($validated['color'] != '') {
            $product->color = $validated['color'];
        }
        if (isset($validated['active'])) {
            $product->active = true;
        } else {
            $product->active = false;
        }

        $product->quantity = $validated['quantity'];

        $product->save();

        return back();
    }
}
