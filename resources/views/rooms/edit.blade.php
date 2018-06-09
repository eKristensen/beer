@extends('layouts.app')


@section ('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Rediger</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">Værelse</th>
      <th scope="col">Navn</th>
      <th scope="col">Aktiv</th>
      <th scope="col">Gem</th>
    </tr>
  </thead>
</table>
@foreach ($rooms as $room)
  <form method="POST" action="/rooms">

    {{ csrf_field() }}
    {{ method_field('PATCH') }}

<table class="table table-striped table-sm">
  <tbody>
        <tr>
      <th scope="row">{{$room->id}}</th>
      <input type="hidden" name="id" value="{{$room->id}}">

    <td>
      <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$room->name}}">
    </td>

    <td>
      <input @if($room->active) checked @endif type="checkbox" class="form-control" id="active" name="active" placeholder="Name">
    </td>

    <td>
    <button type="submit" class="btn btn-primary">Rediger</button>
    </td>
  </tr>
      </tbody>
</table>
  </form>
@endforeach


                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Ny</div>

                <div class="card-body">

                  <form method="POST" action="/rooms">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="id">Number:</label>
                      <input type="text" class="form-control" id="id" placeholder="Room number" name="id">
                    </div>

                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>

                    <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                  </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection ('content')