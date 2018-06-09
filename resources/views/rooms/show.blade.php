@extends('layouts.app')


@section ('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transtrationer for konto id: {{ $room->id }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Type</th>
      <th scope="col">Antal</th>
      <th scope="col">Total</th>
      <th scope="col">IP</th>
      <th scope="col">Oprettet</th>
      <th scope="col">Ændret</th>
      <th scope="col">Refunderet</th>
    </tr>
  </thead>

  <tbody>
@foreach ($beers as $beer)
    
        <tr>
      <th scope="row">{{$beer->id}}</th>

    <td>{{$beer->type}}</td>
    <td>{{$beer->quantity}}</td>
    <td>{{$beer->amount}}</td>
    <td>{{$beer->ip}}</td>
    <td>{{$beer->created_at}}</td>
    <td>{{$beer->updated_at}}</td>
    <td>{{$beer->refunded}}</td>

     </tr>
@endforeach

  </tbody>
</table>

                </div>
            </div>
            


@endsection ('content')