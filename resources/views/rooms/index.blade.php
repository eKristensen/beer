@extends ('layouts.master')

@section ('content')


<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">Navn</th>
@foreach ($products as $product)
      <th scope="col" colspan="{{ count($product->quantities()) }}">{{ $product->name }} ({{ $product->price }} kr)</th>
@endforeach
      <th scope="col">Tjek saldo</th>
    </tr>
  </thead>
  <tbody>

@foreach ($rooms as $room)
    <tr>
      <th scope="row">{{ $room->name }}</th>
@foreach ($products as $product)
      @foreach ($product->quantities() as $amount)
      <td><button type="button" class="btn btn-success" @if ($product->color != '') style="background-color: #{{ $product->color }};border-color: #{{ $product->color }};" @endif onclick="buy({{ $room->id }},'{{ $product->id }}',{{$amount}})">+{{$amount}}</button></td>
      @endforeach
@endforeach

      <td><button type="button" class="btn btn-info" onclick="sum({{ $room->id }})">Tjek</button></td>
    </tr>
@endforeach

  </tbody>
</table>

<hr>

Inden for 30 minutter kan dit køb fortrydes. Vælg de køb der skal refunderes. <a href=/refund role="button" class="btn btn-danger">Fortryd</a>

@endsection ('content')
