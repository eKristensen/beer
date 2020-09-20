@extends ('layouts.master')

@section ('content')

<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Siden er hentet: {{ \Carbon\Carbon::now() }}
            </h1>
        </div>
    </div>
</section>

<br>

<nav class="card">
  <header class="card-header">
      <p class="card-header-title">
          Fortrydelsesretten bortfalder ved brud på plomberingen dog senest 30 minutter efter køb.
      </p>
      <p class="card-content">
          <a href=/rooms role="button" class="button is-primary">Tilbage</a>
      </p>
  </header>
</nav>

<br>

<table class="table is-striped is-fullwidth">
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
        {{ $beer->amount }} kr.
        @if ($beer->refunded)
        </del>
        @endif
      </td>
      <td>
        @if ($beer->refunded)
        Refunderet
        @else
        <button type="button" class="button is-danger" onclick="refund({{ $beer->id }})">Refunder</button>
        @endif
        </td>
    </tr>
@endforeach

  </tbody>
</table>

<hr>

<a href=/rooms role="button" class="button is-primary is-pulled-right">Tilbage</a>

@endsection ('content')
