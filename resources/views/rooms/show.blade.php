@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns is-marginless is-centered">
            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Transtrationer for konto id: {{ $room->id }}
                        </p>
                    </header>

                    <div class="card-content">
                      <table class="table is-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Produkt</th>
                            <th>Antal</th>
                            <th>Total</th>
                            <th>IP</th>
                            <th>Oprettet</th>
                            <th>Ændret</th>
                            <th>Refunderet</th>
                          </tr>
                        </thead>

                        <tbody>
                          @foreach ($beers as $beer)

                              <tr>
                                <th scope="row">{{$beer->id}}</th>

                                <td>{{$beer->type}}</td>
                                <td>{{$beer->product}}</td>
                                <td>{{$beer->quantity}}</td>
                                <td>{{$beer->amount}}</td>
                                <td>{{$beer->ipAddress}}</td>
                                <td>{{$beer->created_at}}</td>
                                <td>{{$beer->updated_at}}</td>
                                <td>{{$beer->refunded}}</td>

                              </tr>
                          @endforeach

                        </tbody>
                      </table>

                    </div>
                </nav>
            </div>
        </div>
    </div>
@endsection
