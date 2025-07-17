<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variation_id' => 'required|exists:product_variations,id',
        ]);

        $variation = ProductVariation::with('product')->findOrFail($request->variation_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$variation->id])) {
            $cart[$variation->id]['quantity']++;
        } else {
            $cart[$variation->id] = [
                'product_name' => $variation->product->name,
                'variation_name' => $variation->name,
                'price' => $variation->product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->variation_id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removido.');
    }
}

