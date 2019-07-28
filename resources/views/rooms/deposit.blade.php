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
                            <input type="text" class="input" id="amount" name="amount" placeholder="Name">
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
    </div>
@endsection
