<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>UNHLS | Sample Tracker</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo e(asset('css/ionicons.min.css')); ?>">
 <?php echo $__env->yieldContent('css'); ?>
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('css/AdminLTE.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-datepicker.min.css')); ?>">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo e(asset('css/skin-blue.min.css')); ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">

  <!-- Main Header -->
  <?php echo $__env->make('index.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php echo $__env->make('index.leftband', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <!-- Content Wrapper. Contains page content -->
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $__env->yieldContent('title'); ?>
      </h1>
       <ul class="list-inline context-menu">
      	<li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
       <?php if (\Entrust::hasRole(['hub_coordinator','administrator','national_hub_coordinator'])) : ?>
	   <?php echo generateContextMenu(Request::url());?>
       <?php endif; // Entrust::hasRole ?>
      </ul>
    </section>

    <!-- Main content -->
    <section class="content">

      <?php echo $__env->yieldContent('content'); ?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->

  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo e(date('Y')); ?> <a href="#">UNHLS</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<?php if (\Entrust::hasRole('In_charge')) : ?>
<div class="modal fade" tabindex="-1" role="dialog" id="thedate">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Select the details for the route</h4>
      </div>
      <div class="modal-body">
      	<div class="box box-info no-border"> 
      	<?php echo e(Form::open(array('url' => 'dailyrouting/checkdatedata', 'class' => 'form-horizontal', 'id' => 'routesinitials'))); ?>

  <?php echo e(csrf_field()); ?>

  
  			<div class="form-group">
              <label for="dateofweek" class="col-sm-3 control-label"><?php echo e(Form::label('dateofweek', 'Date')); ?></label>
              <div class="col-sm-9">
                <?php echo e(Form::text('thedate', null, ['class' => 'form-control', 'id' => 'routedate'])); ?>

              </div>
            </div>
            <div class="form-group">
              <label for="dateofweek" class="col-sm-3 control-label"><?php echo e(Form::label('dateofweek', 'Date')); ?></label>
              <div class="col-sm-9">
                <?php echo e(Form::select('facilityid', array_merge_maintain_keys(array(''=>'Facility'),getFacilitiesforHub(Auth::user()->hubid)), null, ['class'=>'form-control'])); ?>

              </div>
            </div>
            <div class="form-group">
              <label for="dateofweek" class="col-sm-3 control-label"><?php echo e(Form::label('bikeid', 'Motorcyle')); ?></label>
              <div class="col-sm-9">
                <?php echo e(Form::select('bikeid', array_merge_maintain_keys(array(''=>'Motorcycle'),getAssignedBikesforHub(Auth::user()->hubid)), null, ['class'=>'form-control'])); ?>

              </div>
            </div>
            <div class="form-group">
              <label for="transporterid" class="col-sm-3 control-label"><?php echo e(Form::label('transporterid', 'Transporter')); ?></label>
              <div class="col-sm-9">
                <?php echo e(Form::select('transporterid', array_merge_maintain_keys(array(''=>'Transporter'),getSampleTransportersforHub(Auth::user()->hubid)), null, ['class'=>'form-control'])); ?>

              </div>
            </div>
  			<div class="box-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </button>
            <?php echo e(Form::submit('Continue', array('class' => 'btn btn-info pull-right'))); ?> </div>
          <!-- /.box-footer --> 
          
          <?php echo e(Form::close()); ?> </div>
  		</div> 
      </div>
     </div>
    </div>
    <?php endif; // Entrust::hasRole ?>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<?php echo $__env->yieldContent('listpagejs'); ?>
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
<?php echo $__env->yieldContent('js'); ?>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('js/adminlte.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap-datepicker.min.js')); ?>"></script> 


<script src="<?php echo e(asset('js/hom.js')); ?>"></script>

<!-- AdminLTE for demo purposes -->
<script>
	$(document).ready(function() {
		$('#routedate,#datebrokendown, #datereported').datepicker({
		   format: 'mm/dd/yyyy',
           endDate: '+0d',
		   autoclose: true
		});
		//on selecting a hub, get the facilities it serves
		$("select[name='hubid']").change(function(){
		  var hubid = $(this).val();
		  var hiddenvalue = $("input[name='_token']").val();
		  
		  $.ajax({
			  url: "<?php echo url('dailyrouting/facilitiesforhub'); ?>",
			  method: 'POST',
			  data: {hubid:hubid, _token:hiddenvalue},
			  success: function(data) {
				  	$("select[name='facilityid'").empty();
					$("select[name='facilityid'").html(data.options);
				  }
			  });
		  });
	});
</script>
</body>
</html>