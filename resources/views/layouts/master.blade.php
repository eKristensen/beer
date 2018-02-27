
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Beer!!!</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      jQuery.ajaxSetup({
        "error":function() { swal("Det skete en fejl.","","error");  }
      });

      function buy(room,type,quantity) {
        jQuery.getJSON('{{env('APP_URL')}}api/buy/'+room+'/'+type+'/'+quantity, 
            function(data){
              if (type == "cider") what = "Cider";
              if (type == "beer") what = "Øl / Sodavand";
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
