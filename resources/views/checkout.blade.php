@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Finalizar Pedido</h2>

    @php
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $frete = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15;
        if ($subtotal > 200) $frete = 0;
    @endphp

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>CEP</label>
            <input type="text" name="cep" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cupom (opcional)</label>
            <input type="text" name="coupon_code" class="form-control">
        </div>

        <p>Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
        <p>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</p>
        <p><strong>Total: R$ {{ number_format($subtotal + $frete, 2, ',', '.') }}</strong></p>

        <button type="submit" class="btn btn-success">Confirmar Pedido</button>
    </form>
</div>
@endsection
