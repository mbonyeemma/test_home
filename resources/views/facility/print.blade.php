<html>
<head>    
</head>
   <body>
   
<script>
   
    window.onload = function () {
        window.print();
    }
  </script>

   
    <div style="width:100%; text-align:center; padding-top:50px; padding-bottom:50px;">
    <h1 style="text-transform:uppercase">{{$facility->name}}</h1>
    	{!! QrCode::size(750)->generate($facility->id)!!}
    </div>
    
    </body>
</html>