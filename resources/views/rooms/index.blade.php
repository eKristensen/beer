@extends ('layouts.master')

@section ('content')


<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">Værelse</th>
      <th scope="col" colspan="2">Køb Øl / Sodavand (4 kr)</th>
      <th scope="col" colspan="2">Køb Cider (5 kr)</th>
      <th scope="col" colspan="2" style="color:red;">TILBUD: Somersby  (2 kr)</th>
      <th scope="col">Tjek saldo</th>
    </tr>
  </thead>
  <tbody>

@foreach ($rooms as $room)
    <tr>
      <th scope="row">{{ $room->name }}</th>

      @foreach ([1,5] as $amount)
      <td><button type="button" class="btn btn-success" onclick="buy({{ $room->id }},'beer',{{$amount}})">+{{$amount}}</button></td>
      @endforeach

      @foreach ([1,5] as $amount)
      <td><button type="button" class="btn btn-primary" onclick="buy({{ $room->id }},'cider',{{$amount}})">+{{$amount}}</button></td>
      @endforeach

      @foreach ([1,5] as $amount)
      <td><button type="button" class="btn btn-primary" onclick="buy({{ $room->id }},'somersby',{{$amount}})">+{{$amount}}</button></td>
      @endforeach

      <td><button type="button" class="btn btn-info" onclick="sum({{ $room->id }})">Tjek</button></td>
    </tr>
@endforeach

  </tbody>
</table>

<hr>

Inden for 30 minutter kan dit køb fortrydes. Vælg de køb der skal refunderes. <a href=/refund role="button" class="btn btn-danger">Fortryd</a>

@endsection ('content')