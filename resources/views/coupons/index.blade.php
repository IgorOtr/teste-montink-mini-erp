@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cupons</h2>

    <a href="{{ route('coupons.create') }}" class="btn btn-primary mb-3">Novo Cupom</a>

    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Valor (R$)</th>
                <th>Mínimo Subtotal</th>
                <th>Validade</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
            <tr>
                <td>{{ $coupon->code }}</td>
                <td>{{ number_format($coupon->value, 2, ',', '.') }}</td>
                <td>{{ number_format($coupon->min_subtotal, 2, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($coupon->valid_until)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
