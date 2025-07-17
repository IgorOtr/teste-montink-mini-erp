<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variations')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'variations.*.name' => 'required|string',
            'variations.*.stock' => 'required|integer|min:0'
        ]);

        $product = Product::create($request->only(['name', 'price', 'description']));

        foreach ($request->variations as $variation) {
            $product->variations()->create($variation);
        }

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product)
    {
        $product->load('variations');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'variations.*.id' => 'nullable|exists:product_variations,id',
            'variations.*.name' => 'required|string',
            'variations.*.stock' => 'required|integer|min:0'
        ]);

        $product->update($request->only(['name', 'price', 'description']));

        foreach ($request->variations as $variationData) {
            if (isset($variationData['id'])) {
                $variation = ProductVariation::find($variationData['id']);
                $variation->update($variationData);
            } else {
                $product->variations()->create($variationData);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produto atualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto removido.');
    }
}
