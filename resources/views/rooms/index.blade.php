@extends ('layouts.master')

@section ('content')


<table class="table is-striped is-fullwidth">
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
      <td><button type="button" class="button is-success" @if ($product->color != '') style="background-color: #{{ $product->color }};border-color: #{{ $product->color }};" @endif onclick="buy({{ $room->id }},'{{ $product->id }}',{{$amount}})">+{{$amount}}</button></td>
      @endforeach
@endforeach

      <td><button type="button" class="button is-info" onclick="sum({{ $room->id }})">Tjek</button></td>
    </tr>
@endforeach

  </tbody>
</table>

<nav class="card">
  <header class="card-header">
      <p class="card-header-title">
          Inden for 30 minutter kan dit køb fortrydes. Vælg de køb der skal refunderes.
      </p>
      <p class="card-content">
          <a href=/highscore role="button" class="button is-warning">Highscore</a> <a href=/refund role="button" class="button is-danger">Fortryd</a>
      </p>
  </header>
</nav>



@endsection ('content')
