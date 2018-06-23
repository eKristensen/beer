
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Beer!!!</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <div class="starter-template">
        @yield ('content')
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/tether.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/sweetalert.min.js"></script>
    <script>
      jQuery.ajaxSetup({
        "error":function() { swal("Det skete en fejl.","","error");  }
      });

      function buy(room,type,quantity) {
        jQuery.getJSON('{{env('APP_URL')}}api/buy/'+room+'/'+type+'/'+quantity,
            function(data){
              if (type == "cider") what = "Cider";
              if (type == "beer") what = "Øl / Sodavand";
              if (type == "somersby") what = "Somersby tilbud";
              swal('Køb: '+quantity+' '+what, 'Værelse '+data.data.id+' \n Ny saldo: '+data.data.sum+' kr.', 'success');
            })
      }

      function sum(room,type,quantity) {
        jQuery.getJSON('{{env('APP_URL')}}api/sum/'+room,
            function(data){
              swal('Saldo: '+data.data.sum + ' kr.', 'Øl /Sodavand: '+data.data.beer+'\nCider: '+data.data.cider+'\nVærelse '+data.data.id);
            })
      }

      function refund(id) {
        jQuery.getJSON('{{env('APP_URL')}}api/refund/'+id,
            function(data){
              swal('Refunderet', 'Beløb: '+data.data.amount,'success').then((value) => location.reload());
            })
      }
    </script>
  </body>
</html>
