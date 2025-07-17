@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</h2>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Preço</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <hr>
        <h4>Variações</h4>
        <div id="variations">
            @if(isset($product))
                @foreach($product->variations as $i => $variation)
                    <div class="row mb-2">
                        <input type="hidden" name="variations[{{ $i }}][id]" value="{{ $variation->id }}">
                        <div class="col">
                            <input type="text" name="variations[{{ $i }}][name]" class="form-control" value="{{ $variation->name }}">
                        </div>
                        <div class="col">
                            <input type="number" name="variations[{{ $i }}][stock]" class="form-control" value="{{ $variation->stock }}">
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" name="variations[0][name]" class="form-control" placeholder="Nome da Variação">
                    </div>
                    <div class="col">
                        <input type="number" name="variations[0][stock]" class="form-control" placeholder="Estoque">
                    </div>
                </div>
            @endif
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addVariation()">+ Adicionar Variação</button>

        <br>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>

<script>
let variationIndex = {{ isset($product) ? $product->variations->count() : 1 }};
function addVariation() {
    const container = document.getElementById('variations');
    const row = `
    <div class="row mb-2">
        <div class="col">
            <input type="text" name="variations[${variationIndex}][name]" class="form-control" placeholder="Nome da Variação">
        </div>
        <div class="col">
            <input type="number" name="variations[${variationIndex}][stock]" class="form-control" placeholder="Estoque">
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', row);
    variationIndex++;
}
</script>
@endsection
