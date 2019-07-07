@extends ('layouts.master')

@section ('content')

<h1>Siden er hentet: {{ \Carbon\Carbon::now() }}</h1>

<a href=/rooms role="button" class="btn btn-primary">Tilbage</a>

<p>Betalinger for de sidste 30 minutter kan fortrydes</p>

<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Tidspunkt</th>
      <th scope="col">Navn</th>
      <th scope="col">Hvad</th>
      <th scope="col">Beløb</th>
      <th scope="col">Refunder</th>
    </tr>
  </thead>
<tbody>

@foreach ($beers as $beer)
    <tr>
      <th scope="row">{{ $beer->id }}</th>
      <td>{{ $beer->created_at }}</td>
      <td>{{ $beer->getRoom->name }}</td>
      <td>{{ $beer->products->name }}</td>
      <td>
        @if ($beer->refunded)
        <del>
        @endif
        {{ $beer->amount }} kr
        @if ($beer->refunded)
        </del>
        @endif
      </td>
      <td>
        @if ($beer->refunded)
        Refunderet
        @else
        <button type="button" class="btn btn-danger" onclick="refund({{ $beer->id }})">Refunder</button>
        @endif
        </td>
    </tr>
@endforeach

  </tbody>
</table>

<hr>

<a href=/rooms role="button" class="btn btn-primary">Tilbage</a>

@endsection ('content')
