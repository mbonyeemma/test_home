@extends('layouts.app')

@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('js') 
<script>
$(document).ready(function() {
} );

</script> 
@append
@section('content')
<style>
	div.dataTables_length label {
    font-weight: normal;
    float: left;
    text-align: left;
    margin-bottom: 0;
}
div.dataTables_length select {
    min-width: 60px;
    margin-right: 4px;
}
</style>
<div class="box box-info">
  <section class="content"> 
    <div class="row"> 
      <!-- Left col -->
      <section class="col-lg-6"> 
      <h2>Total No. Samples</h2>
         <div id="stocks-chart" >
    <?php echo lava::render('LineChart', 'MyStocks', 'stocks-chart'); ?></div>
      </section>
       <section class="col-lg-6"> 
       <h2 style="margin-bottom:47px;">Summary</h2>
         <div class="table-responsive">
         	<table class="table table-bordered">
            	<tr>
                	@foreach($samples as $line)
                    <th>{{$line->sampletype}}
                    </th>
					@endforeach
		
                </tr>
                <tr>
                	@foreach($samples as $line)
                    <td>{{$line->samples}}
                    </td>
					@endforeach
                </tr>
            </table>
         </div>
      </section>
     </div>
     
     
     
     <div class="row"> 
      <!-- Left col -->
      <section class="col-lg-6"> 
      <h2>Total No. Results</h2>
         <div id="results-chart" >
    <?php echo lava::render('LineChart', 'theresults', 'results-chart'); ?></div>
      </section>
       <section class="col-lg-6"> 
       <h2 style="margin-bottom:47px;">Summary</h2>
         <div class="table-responsive">
         	<table class="table table-bordered">
            	<tr>
                	@foreach($results as $line)
                    <th>{{$line->resulttype}}
                    </th>
					@endforeach
		
                </tr>
                <tr>
                	@foreach($results as $line)
                    <td>{{$line->results}}
                    </td>
					@endforeach
                </tr>
            </table>
         </div>
      </section>
     </div>
     
  </section>
</div>
@endsection