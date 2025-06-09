<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->     
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        @role(['eoc_admin']) <li><a href="{{ url('staff/new/5') }}">Add New EOC Staff</a></li> 
        @endrole
        <!--<li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li> -->
       @role(['administrator','national_hub_coordinator']) 
        <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Access Control</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          	<li><a href="{{ route('roles.create') }}">Create Role</a></li>
           	<li><a href="{{ route('roles.index') }}">View All Roles</a></li>
            <li><a href="{{ route('permissions.create') }}">Create Permission</a></li>
            <li><a href="{{ route('permissions.index') }}">View All Permissions</a></li>
            <li><a href="{{ route('users.create') }}">Create User</a></li>
           	<li><a href="{{ route('users.index') }}">View All Users</a></li>
          </ul>
          
        </li>
       @endrole
        @role(['hub_coordinator','administrator','national_hub_coordinator'])  
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Sample Managers</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            @role(['hub_coordinator','administrator','national_hub_coordinator']) 
            <li><a href="{{ url('staff/mobile/app') }}">View Mobile App Registrations</a></li>
            <li><a href="{{ url('staff/new/1') }}">Add New Sample Transporter</a></li>
            <li><a href="{{ url('staff/list/1') }}">View All Sample Transporters</a></li>   
              
            @endrole
            @role(['national_hub_coordinator']) 
            <li><a href="{{ url('staff/new/4') }}">Add New Driver</a></li>
            <li><a href="{{ url('staff/list/4') }}">View All Drivers</a></li>
            <li><a href="{{ url('staff/new/8') }}">Add Private Transporter</a></li>
            <li><a href="{{ url('staff/list/8') }}">View All Private Transporters</a></li>  
            <li class="hidden"><a href="{{ url('staff/new/5') }}">Add New EOC Staff</a></li> 
            <li class="hidden"><a href="{{ url('staff/list/5') }}">View All EOC Staff</a></li> 
            <li><a href="{{ url('staff/new/3') }}">New Ref Lab Receptionist</a></li> 
            <li><a href="{{ url('staff/list/3') }}">Ref Lab Receptionists</a></li>
             <li><a href="{{ url('staff/new/6') }}">Add New POE User</a></li>
            <li><a href="{{ url('staff/list/6') }}">View All POE Users</a></li> 
            <li><a href="{{ url('staff/new/7') }}">Add Community User</a></li>
            <li><a href="{{ url('staff/list/7') }}">View Community Users</a></li>   
            <li><a href="{{ url('staff/new/2') }}">Add New Hub Coordinator</a></li> 
            <li><a href="{{ url('staff/list/2') }}">Hub Coordinators</a></li>
            @endrole

          </ul>
        </li>
        <li class="treeview hidden">
          <a href="#"><i class="fa fa-users"></i> <span>Manage Contacts</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('contact/list/2/2') }}">Hub Coordinators</a></li>
            <li><a href="{{ url('contact/list/2/6') }}">DLFPs</a></li>
            <li><a href="{{ url('staff/list/1?showcontact=1') }}">Sample Transporters</a></li>
          </ul>
        </li>
       @permission(['view-assessment-list','create-assessment'])
        <li class="treeview" style="display:none;">
          <a href="#"><i class="fa fa-user"></i> <span>Infrastructure Assessment</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          	<li><a href="{{ route('infrastructure.create') }}">Provide Assessment</a></li>
           	<li><a href="{{ route('infrastructure.index') }}">View All Assessment</a></li>
          </ul>          
        </li>
        @endpermission
       @role(['administrator','national_hub_coordinator','regional_hub_coordinator']) 
       <li class="treeview">
          <a href="#"><i class="fa fa-institution"></i> <span>Manage IPs</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          	@if(Auth::user()->can('Create_IP'))
          	<li><a href="{{ route('organization.create') }}">Add New IP</a></li>
          	@endif
           	<li><a href="{{ route('organization.index') }}">View All IPs</a></li>
          </ul>
          
        </li>
       <li class="treeview">
          <a href="#"><i class="fa fa-hospital-o"></i> <span>Manage Hubs</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          	@if(Auth::user()->can('create_facility'))
          	<li><a href="{{ route('hub.create') }}">Add New Hub</a></li>
          	@endif
           	<li><a href="{{ route('hub.index') }}">View All Hubs</a></li>
          </ul>
          
        </li>
        @endrole
        @if(Entrust::can(['create_facility','update_facility','View_facility']))
        <li class="treeview">
          <a href="#"><i class="fa  fa-plus"></i> <span>Manage Facilities</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
           	<li><a href="{{ route('facility.index') }}">View All Facilities</a></li>
             @if(Auth::user()->can('create_facility'))
            <li><a href="{{ route('facility.create') }}">Add Facility</a></li>
            @endif
          </ul>
          
        </li>
        @endif
        @if(Auth::user()->can('manage-routings'))
        <li class="treeview">
          <a href="#"><i class="fa fa-motorcycle"></i> <span>Manage Routing</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
                @role(['hub_coordinator','administrator','national_hub_coordinator'])  
                	<li><a href="{{ route('equipment.create') }}">Add New Bike</a></li>
                @endrole
                	<li><a href="{{ route('equipment.index') }}">View All Bikes</a></li>
                @role(['hub_coordinator','administrator','national_hub_coordinator','implementing_partner']) 
                	<li><a href="{{ route('routingschedule.show', ['id' => Auth::user()->hubid]) }}">Routing Schedule</a></li>
                @endrole

          </ul>
        </li>
        @endif
        
       @if(Auth::user()->can('manage-samples'))
        @endif
         <li class="treeview" style="display:none;">
          <a href="#"><i class="fa  fa-plus"></i> <span>Manage Equipment</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          @permission('can-view-lab-equipment')
            <li><a href="{{ route('labequipment.create') }}">Add Equipment</a></li>
           @endpermission
            <li><a href="{{ route('labequipment.index') }}">View All Equipment</a></li>
          </ul>
          
        </li>
        <li class="treeview" style="display:none;">
          <a href="#"><i class="fa  fa-plus"></i> <span>Management Meetings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          
          <ul class="treeview-menu">
          @permission('can-view-lab-equipment')
            
            <li><a href="#">Upload quartery report</a></li>
           @endpermission<li><a href="{{route('meetingreport.create')}}">Upload weekly report</a></li>
            <li><a href="#">View all reports</a></li>
          </ul>
          
        </li>
       
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Out Breaks</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('covid.index') }}">All Samples</a></li>
            @role(['national_hub_coordinator'])
            <li><a href="{{ route('covid.create') }}">Add New Sample</a></li>
            @endrole
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Manage Results</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
           
          	<li><a href="{{ route('results.tracking') }}">All results</a></li>
            
          </ul>
        </li>
        @endrole
        <li class="treeview" style = "display:none">
          <a href="#"><i class="fa fa-arrows"></i> <span>Sample Tracking</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            @if (!Auth::guest())
            <li><a href="{{ route('sampletracking.create') }}">Refer Sample</a></li>
          	<li><a href="{{ route('sampletracking.index') }}">All referred Samples</a></li>
          
          @endif

          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-motorcycle"></i> <span>Manage Samples</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">             
                   @role(['cphl_sample_receptionist']) 
                    <li><a href="{{ route('reception.list') }}">Receive Packages</a></li>
                    @endrole  
                     @role(['implementing_partner'])
                    <li><a href="{{ route('ip.hubsamples') }}">Samples by Hub</a></li>
                      @endrole

                    <!-- <li><a href="{{ route('samples.all') }}">All Packages</a></li> -->
                    <!-- <li><a href="{{ route('reports.hubsamples') }}">Samples by Hub</a></li> -->
                   
                @role(['hub_coordinator'])  
                    <li><a href="{{ route('samples.all') }}">My Packages</a></li>
                    <li><a href="{{ route('samples.cphl') }}">My CPHL Packages</a></li>
                @endrole       
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-cog"></i> <span>Restrack</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">             
                <li><a href="{{ route('approval.index') }}">Approval Setting</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-clipboard"></i> <span>Data Collections</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">             
                <li><a href="">Forms</a></li>
          </ul>
        </li>
        @role(['implementing_partner'])
        <li class="active"><a href="{{ route('ip.facility') }}"><i class="fa fa-hospital-o"></i>
          <span>My Hubs</span></a>
        </li>
        @endrole
                   
      @role(['national_hub_coordinator']) 
        <li class="treeview">
          <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">             
              <li><a href="{{ route('all.sample') }}">Specimen Types / Hub</a></li>
               
                <li><a href="{{ route('samples.all') }}">Turn Around Time</a></li>
              <li><a href="{{ route('reports.hubsamples') }}">Samples by Hub</a></li>
              <li><a href="{{ route('hub.visit') }}">Hub Visit Data</a></li>
          </ul>
        </li>
      @endrole  
    </ul>
		
    
    
  
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
