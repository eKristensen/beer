@extends ('layouts.master')

@section ('content')
	<h1>Add a room</h1>

	<hr>

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