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
    	@if($facility && $facility->id)
    		{!! QrCode::size(750)->generate((string)$facility->id)!!}
    	@else
    		<p>Error: Invalid facility data</p>
    	@endif
    </div>
    
    </body>
</html>