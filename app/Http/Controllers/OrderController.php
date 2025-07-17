<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        return view('checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cep' => 'required',
            'email' => 'required|email',
            'coupon_code' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        DB::beginTransaction();

        try {
            // Subtotal
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            // Frete
            $shipping = 20;
            if ($subtotal >= 52 && $subtotal <= 166.59) $shipping = 15;
            if ($subtotal > 200) $shipping = 0;

            // Cupom
            $discount = 0;
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                                ->whereDate('valid_until', '>=', now())
                                ->first();

                if ($coupon && $subtotal >= $coupon->min_subtotal) {
                    $discount = $coupon->value;
                }
            }

            // Total com desconto e frete
            $total = max(0, $subtotal - $discount) + $shipping;

            // Criar pedido
            $order = Order::create([
                'status' => 'pendente',
                'total_price' => $total,
                'shipping_price' => $shipping,
                'cep' => $request->cep,
                'email' => $request->email
            ]);

            // Criar itens e reduzir estoque
            foreach ($cart as $id => $item) {
                $variation = ProductVariation::findOrFail($id);

                if ($variation->stock < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para {$item['product_name']} - {$item['variation_name']}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variation_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $variation->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');
            Mail::to('cliente@exemplo.com')->send(new OrderConfirmationMail($order));

            return redirect()->route('products.index')->with('success', 'Pedido realizado com sucesso!');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao finalizar pedido: ' . $e->getMessage()]);
        }
    }
}

