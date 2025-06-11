@extends('layouts.app4')
@section('title', 'Contact')

@section('js') 
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script>
    $(document).ready(function() {
      //$('#listtable').DataTable();
      $('#myTable1').DataTable( {
        dom: 'Bfrtip',
        buttons: [
          
          {
            extend: 'excelHtml5'
          }
        ]
      } );
      $('#myTable2').DataTable().search().draw();
      $('#myTable3').DataTable().search().draw();
      $('#myTable4').DataTable().search().draw();
      $('#myTable5').DataTable().search().draw();
    } );
  </script> 
@append

@section('content')
<!-- Info boxes -->
<div class="row panel-body">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">District Lab Focal Persons</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Hub Coordinators</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Sample Transporters</a></li>
        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Sample Riders</a></li>
        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">Hub List</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="box box-info">
              <div class="box-body table-responsive">
              <table id="myTable1" class="table table-striped table-bordered ">
                  <thead>
                    <tr>
                        
                        <th>Hub</th>
                        <th>District Where DLFP is Located</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        
                      </tr>
                  </thead>
                  <tbody>
                    
                  @foreach ($contacts as $contact)
                  @if($contact->category == 2 && $contact->type == 6)
                  <tr>
                    <td>{{ $contact->hub }}</td>
                    <td>{{ $contact->district }}</td>
                    <td>{{ $contact->firstname }}</td>
                    <td>{{ $contact->lastname }}</td>
                    <td>{{ $contact->emailaddress}}</td>
                    
                  </tr>
                  @endif
                  @endforeach
                  </tbody>
              </table>
            </div>
            </div>   
        </div>
        
        <div class="tab-pane" id="tab_2"> 
        <div class="box box-info">
          <div class="box-body table-responsive">
              <table id="myTable2" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        
                        <!-- <th>District Where Hub is Located</th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Hub</th>
                        
                      </tr>
                  </thead>
                  <tbody>
                    
                  @foreach ($contacts as $contact)
                  @if($contact->category == NULL && $contact->type == 2)
                  <tr>
                    <!-- <td>{{ $contact->district }}</td> -->
                    <td>{{ $contact->firstname }}</td>
                    <td>{{ $contact->lastname }}</td>
                    <td>{{ $contact->emailaddress}}</td>
                    <td>{{ $contact->hub }}</td>
                    
                  </tr>
                  @endif
                  @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_3">
          <div class="box box-info">
          <div class="box-body table-responsive">
              <table id="myTable3" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        
                        <!-- <th>Hub Name</th> -->
                        <!-- <th style="display: none">District Where DLFP is Located</th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        
                      </tr>
                  </thead>
                  <tbody>
                    
                  @foreach ($contacts as $contact)
                  @if($contact->desgn == 4)
                  <tr>
                    <!-- <td>{{ $contact->hub }}</td> -->
                    <!-- <td style="display: none">{{ $contact->district }}</td> -->
                    <td>{{ $contact->firstname }}</td>
                    <td>{{ $contact->lastname }}</td>
                    <td>{{ $contact->emailaddress}}</td>
                    
                  </tr>
                  @endif
                  @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_4">
          <div class="box box-info">
          <div class="box-body table-responsive">
              <table id="myTable4" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        
                        <th>Hub</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <!-- <th>District Where Rider is Located</th> -->
                        
                      </tr>
                  </thead>
                  <tbody>
                    
                  @foreach ($contacts as $contact)
                  @if($contact->desgn == 1)
                  <tr>
                    <td>{{ $contact->hub }}</td>
                    <td>{{ $contact->firstname }}</td>
                    <td>{{ $contact->lastname }}</td>
                    <td>{{ $contact->emailaddress}}</td>
                    <!-- <td>{{ $contact->district }}</td> -->
                    
                  </tr>
                  @endif
                  @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_5">
          <div class="box box-info">
          <div class="box-body table-responsive">
              <table id="myTable5" class="table table-striped table-bordered">
                  <thead>
                    <tr>
          <th>Name</th>
          <th>IP(s)</th>
          <th>Health Region</th>
          <!-- <th>Resident District</th> -->
          <th>No.Facilities Served</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($hubs as $hub)
      <tr>
        <td>{{ $hub->hubname }}</a></td>
        <td>{{$hub->ip}}</td>
        <td>{{ $hub->healthregion }}</td>
        <!-- <td>{{ $hub->district }}</td> -->
        <td>{{ count(getFacilitiesforHub($hub->id)) }}</td>
      </tr>
      @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
          
      </div>
    </div>
 
    
  </div>
</div>
@endsection