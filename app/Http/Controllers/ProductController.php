<?php

namespace App\Http\Controllers;

use App\Product;

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
            'price'    => 'required|numeric|gt:0',
        ]);

        Product::create($validated);

        return back();
    }

    public function patch()
    {
        $validated = request()->validate([
            'id'       => 'required',
            'name'     => 'required',
            'color'    => 'required',
            'quantity' => 'required',
            'price'    => 'required|numeric|gt:0',
            'active'   => 'nullable|in:on',
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
