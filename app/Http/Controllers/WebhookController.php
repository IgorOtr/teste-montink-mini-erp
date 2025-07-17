<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'status' => 'required|string'
        ]);

        $order = Order::find($request->id);

        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        if (strtolower($request->status) === 'cancelado') {
            $order->delete();
            return response()->json(['message' => 'Pedido cancelado e removido.']);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }
}

