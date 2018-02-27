@extends ('layouts.master')

@section ('content')


<h1>Rediger</h1>

<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">Værelse</th>
      <th scope="col">Navn</th>
      <th scope="col">Aktiv</th>
      <th scope="col">Gem</th>
    </tr>
  </thead>
  <tbody>

@foreach ($rooms as $room)
  <form method="POST" action="/rooms">

    {{ csrf_field() }}
    {{ method_field('PATCH') }}
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
  </form>
@endforeach

  </tbody>
</table>

<hr>



<h2>Ny</h2>

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

@endsection ('content')