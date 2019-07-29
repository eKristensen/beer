@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns is-marginless is-centered">
            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Rediger
                        </p>
                    </header>

                    <div class="card-content">

                      <table class="table is-striped">
                        <thead>
                          <tr>
                            <th>Værelse</th>
                            <th>Navn</th>
                            <th>Aktiv</th>
                            <th>Gem</th>
                          </tr>
                        </thead>
                      </table>
                      @foreach ($rooms as $room)
                        <form method="POST" action="/rooms">

                          {{ csrf_field() }}
                          {{ method_field('PATCH') }}

                      <table class="table is-striped">
                        <tbody>
                            <tr>
                              <th>{{$room->id}}</th>
                              <input type="hidden" name="id" value="{{$room->id}}">

                            <td>
                              <div class="control">
                                <input type="text" class="input" id="name" name="name" placeholder="Name" value="{{$room->name}}">
                              </div>
                            </td>

                            <td>
                              <div class="control">
                                <input @if($room->active) checked @endif type="checkbox" class="checkbox" id="active" name="active" placeholder="Name">
                              </div>
                            </td>

                            <td>
                            <button type="submit" class="button">Rediger</button>
                            </td>
                          </tr>
                            </tbody>
                         </table>
                        </form>
                      @endforeach
                    </div>
                </nav>
                <br>
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Ny
                        </p>
                    </header>

                    <div class="card-content">

                      <form method="POST" action="/rooms">
                        {{ csrf_field() }}
                        <div class="field">
                          <label class="label" for="id">Number:</label>
                          <div class="control">
                            <input type="text" class="input" id="id" placeholder="Room number" name="id">
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="name">Name</label>
                          <div class="control">
                            <input type="text" class="input" id="name" name="name" placeholder="Name">
                          </div>
                        </div>

                        <div class="field">
                          <div class="control">
                            <button class="button is-link">Add</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </nav>
            </div>
        </div>
    </div>
@endsection
