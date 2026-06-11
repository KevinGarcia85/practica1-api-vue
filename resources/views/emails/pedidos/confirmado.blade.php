<x-mail::message>
# ¡Gracias por tu compra!

Tu pedido **#{{ $pedido->id }}** ha sido registrado con éxito de forma asíncrona.

**Total pagado:** ${{ number_format($pedido->total, 2) }}

<x-mail::button :url="config('app.url')">
Ir a la Tienda
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>