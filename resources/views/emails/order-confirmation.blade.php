<h2>Pedido #{{ $order->id }}</h2>

<p><strong>Cliente:</strong> {{ $order->customer_name }}</p>
<p><strong>Status:</strong> {{ $order->status }}</p>
<p><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>

<h4>Itens:</h4>
<ul>
@foreach($order->items as $item)
    <li>{{ $item->productVariation->product->name }} ({{ $item->productVariation->variation }}) - R$ {{ number_format($item->price, 2, ',', '.') }}</li>
@endforeach
</ul>

@if($order->coupon)
    <p><strong>Cupom aplicado:</strong> {{ $order->coupon->code }} - R$ {{ number_format($order->coupon->value, 2, ',', '.') }}</p>
@endif
