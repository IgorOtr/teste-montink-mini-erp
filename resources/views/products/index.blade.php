@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Produtos</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Novo Produto</a>

    @foreach($products as $product)
        <div class="card mb-2">
            <div class="card-body">
                <h5>{{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}</h5>
                <p>{{ $product->description }}</p>

                <ul>
                    @foreach($product->variations as $variation)
                        <li>{{ $variation->name }} | Estoque: {{ $variation->stock }}</li>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="variation_id" value="{{ $variation->id }}">
                            <button class="btn btn-sm btn-primary">Comprar</button>
                        </form>
                        
                    @endforeach
                </ul>

                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Remover</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
