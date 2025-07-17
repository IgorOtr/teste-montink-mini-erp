@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($coupon) ? 'Editar Cupom' : 'Novo Cupom' }}</h2>

    <form action="{{ isset($coupon) ? route('coupons.update', $coupon) : route('coupons.store') }}" method="POST">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif

        <div class="mb-3">
            <label>Código</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Valor (desconto em R$)</label>
            <input type="number" name="value" step="0.01" class="form-control" value="{{ old('value', $coupon->value ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Subtotal mínimo para aplicar</label>
            <input type="number" name="min_subtotal" step="0.01" class="form-control" value="{{ old('min_subtotal', $coupon->min_subtotal ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Data de validade</label>
            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', isset($coupon) ? $coupon->valid_until->format('Y-m-d') : '') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
