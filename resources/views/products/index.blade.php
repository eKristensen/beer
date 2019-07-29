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
                            <th>Produktnavn</th>
                            <th>Farve (hex)</th>
                            <th>Quantity (digits seperated by commas)</th>
                            <th>Pris</th>
                            <th>Aktiv</th>
                            <th>Gem</th>
                          </tr>
                        </thead>
                      </table>
                      @foreach ($products as $product)
                        <form method="POST" action="/products">

                          {{ csrf_field() }}
                          {{ method_field('PATCH') }}

                      <table class="table is-striped">
                        <tbody>
                            <tr>
                              <input type="hidden" name="id" value="{{$product->id}}">

                            <td>
                              <div class="control">
                                <input type="text" class="input" id="name" name="name" placeholder="Name" value="{{$product->name}}">
                              </div>
                            </td>

                            <td>
                              <div class="control">
                                <input type="text" class="input" id="color" name="color" placeholder="Farve" value="{{$product->color}}">
                              </div>
                            </td>

                            <td>
                              <div class="control">
                                <input type="text" class="input" id="quantity" name="quantity" placeholder="Quantity" value="{{$product->quantity}}">
                              </div>
                            </td>

                            <td>
                              <div class="control">
                                <input type="number" step="0.01" class="input" id="price" name="price" placeholder="Pris" value="{{$product->price}}">
                              </div>
                            </td>

                            <td>
                              <div class="control">
                                <input @if($product->active) checked @endif type="checkbox" class="checkbox" id="active" name="active" placeholder="Name">
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

                      <form method="POST" action="/products">
                        {{ csrf_field() }}
                        <div class="field">
                          <label class="label" for="name">Produktnavn</label>
                          <div class="control">
                            <input type="text" class="input" id="name" name="name" placeholder="Name">
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="color">Farve (hex)</label>
                          <div class="control">
                            <input type="text" class="input" id="color" name="color" placeholder="Farve">
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="quantity">Quantity (digits seperated by commas)</label>
                          <div class="control">
                            <input type="text" class="input" id="quantity" name="quantity" placeholder="Quantity (digits seperated by commas)">
                          </div>
                        </div>

                        <div class="field">
                          <label class="label" for="price">Pris</label>
                          <div class="control">
                            <input type="number" step="0.01" class="input" id="price" name="price" placeholder="Pris">
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
