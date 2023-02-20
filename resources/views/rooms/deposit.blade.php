@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns is-marginless is-centered">

            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Indsæt penge
                        </p>
                    </header>

                    <div class="card-content">
                      <p>Total difference {{ $diff }}</p>

                      <br>

                      <form method="POST" action="/deposit">
                        {{ csrf_field() }}

                        <div class="field">

                          <label class="label" for="room">Værelse</label>
                          <div class="control">
                            <div class="select">
                              <select name="room">
                                @foreach ($rooms as $room)
                                  <option value="{{ $room->id }}">{{$room->id}} {{$room->name}} {{$room->sum}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="amount">Beløb</label>
                          <div class="control">
                            <input type="text" class="input" id="amount" name="amount" placeholder="Beløb">
                          </div>
                        </div>

                        <div class="field">
                          <div class="control">
                            <button class="button is-link">Indsæt</button>
                          </div>
                        </div>
                      </form>

                    </div>
                </nav>
            </div>
        </div>

        <div class="columns is-marginless is-centered">
            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Oversigt
                        </p>
                    </header>

                    <div class="card-content">

                      Bruger <code>user-select</code> til nem copy-paste

                        <table class="table">
                        <tr>
                          <th style="user-select: none;">Oversigt ID</th>
                          <th style="user-select: none;">Værelse</th>
                          <th style="user-select: none;">Sum</th>
                        </tr>
                                @foreach ($summary as $item)

                        <tr>
                          <td style="user-select: none;">{{$item->id}}</td>
                          <td style="user-select: none;">{{$item->room->id}} {{$item->room->name}}</td>
                          <td style="user-select: all;">{{$item->room->sum}}</td>
                        </tr>
                                  
                                   
                                  
                                @endforeach

                       </table>

                    </div>
                </nav>
            </div>
        </div>


        <div class="columns is-marginless is-centered">
            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Rediger oversigt
                        </p>
                    </header>

                    <div class="card-content">

                      <p>Ændre hvem der er på oversigten</p>

                      <br>


                      <table class="table is-striped">
                        <thead>
                          <tr>
                            <th>Oversigt ID</th>
                            <th>Nyt værelse</th>
                            <th>Gem</th>
                          </tr>
                        </thead>
                      </table>

                      @foreach ($summary as $item)
                        <form method="POST" action="/summary">

                          {{ csrf_field() }}
                          {{ method_field('PATCH') }}

                      <table class="table is-striped">
                        <tbody>
                            <tr>
                              <th>{{$item->id}}</th>
                              <input type="hidden" name="id" value="{{$item->id}}">

                            <td>
                              <div class="control">
                                <div class="select">
                                  <select name="room_id">
                                    @foreach ($rooms as $room)
                                      <option value="{{ $room->id }}">{{$room->id}} {{$room->name}} {{$room->sum}}</option>
                                    @endforeach
                                  </select>
                                </div>
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
            </div>
        </div>

        <div class="columns is-marginless is-centered">
            <div class="column is-7">
                <nav class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Tilføj til oversigt
                        </p>
                    </header>

                    <div class="card-content">

                      <form method="POST" action="/summary">
                        {{ csrf_field() }}
                        <div class="field">
                          <label class="label" for="id">Number:</label>
                          <div class="control">
                            <input type="text" class="input" id="id" placeholder="Nummer i oversigt" name="id">
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="room">Værelse</label>
                          <div class="control">
                            <div class="select">
                              <select name="room_id">
                                @foreach ($rooms as $room)
                                  <option value="{{ $room->id }}">{{$room->id}} {{$room->name}} {{$room->sum}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="field">
                          <div class="control">
                            <button class="button is-link">Tilføj</button>
                          </div>
                        </div>
                      </form>

                    </div>
                </nav>
            </div>
        </div>

    </div>
@endsection
