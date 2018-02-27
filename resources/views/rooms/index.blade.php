@extends ('layouts.master')

@section ('content')


<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">Rum</th>
      <th scope="col" colspan="3">Køb Øl / Sodavand (4 kr)</th>
      <th scope="col" colspan="3">Køb Cider (5 kr)</th>
    </tr>
  </thead>
  <tbody>

@foreach ($rooms as $room)
    <tr>
      <th scope="row">{{ $room->name }}</th>

      @foreach ([1,5,10] as $amount)
      <td><button type="button" class="btn btn-success" onclick="buy({{ $room->id }},'beer',{{$amount}})">+{{$amount}}</button></td>
      @endforeach

      @foreach ([1,5,10] as $amount)
      <td><button type="button" class="btn btn-primary" onclick="buy({{ $room->id }},'cider',{{$amount}})">+{{$amount}}</button></td>
      @endforeach
    </tr>
@endforeach

  </tbody>
</table>

<hr>

<div class="row">
  <div class="col-sm-6">
  	<h2>Fortryd</h2>
	Inden for 30 minutter kan dit køb fortrydes. Vælg de køb der skal refunderes ved at trykke på følgende knap. <br> <button type="button" class="btn btn-danger">Fortryd</button>
  </div>
  <div class="col-sm-6">
  	<h2>Statestik</h2>
	Se hvor meget du har købt her <br> <button type="button" class="btn btn-info">Info</button>
  </div>
</div>

@endsection ('content')