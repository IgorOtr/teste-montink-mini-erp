@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Carrinho</h2>

    @if(count($cart))
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['variation_name'] }}</td>
                        <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="variation_id" value="{{ $id }}">
                                <button class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Total: R$ {{ number_format($subtotal, 2, ',', '.') }}</h4>

        <a href="#" class="btn btn-success">Finalizar Pedido</a>
    @else
        <p>Seu carrinho está vazio.</p>
    @endif
</div>
@endsection
