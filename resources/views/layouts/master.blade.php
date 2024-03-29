
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Beer!!!</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield ('header')

    <!-- Keep page fresh, reload every 24 hours -->
    <meta http-equiv="refresh" content="86400;URL='/rooms/'" /> 
  </head>

  <body>

    <div class="container">

      <div class="starter-template">
        @yield ('content')
      </div>

    </div><!-- /.container -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
