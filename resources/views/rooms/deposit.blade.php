@extends('layouts.app')

@section ('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Indsæt penge</div>

                <div class="card-body">
                  <form method="POST" action="/deposit">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="room">Værelse</label>
                      <select name="room" class="form-control">

                      @foreach ($rooms as $room)
                              <option value="{{ $room->id }}">{{$room->id}} {{$room->name}}</option>
                      @endforeach

                      </select>
                    </div>

                    <div class="form-group">
                      <label for="amount">Beløb</label>
                      <input type="text" class="form-control" id="amount" name="amount" placeholder="Name">
                    </div>

                    <div class="form-group">
                    <button type="submit" class="btn btn-primary">Indsæt</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection ('content')