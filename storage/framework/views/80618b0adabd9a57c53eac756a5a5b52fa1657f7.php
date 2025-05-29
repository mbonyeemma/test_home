<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('js'); ?> 
<script src="<?php echo e(asset('js/fastclick.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery.sparkline.min.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery-jvectormap-1.2.2.min.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery-jvectormap-world-mill-en.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery.slimscroll.min.js')); ?>"></script> 
<script src="<?php echo e(asset('js/Chart.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery.slimscroll.min.js')); ?>"></script> 
<script src="<?php echo e(asset('js/jquery.stickytabs.js')); ?>"></script> 
<script>
	$(document).ready(function() {
		$('.nav-tabs').stickyTabs();
		$(".stats").html('<img class="img-responsive soslogo" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading">');
		$.ajax({
			type: 'GET',
			url: "<?php echo url('sampletracking/statistics'); ?>",
			success: function(data) {
				console.log(data);
				$('#destinedforcphl').html(data.destinedforcphl);
				$('#receivedatcphl').html(data.receivedatcphl);
				$('#hubpackages').html(data.hubpackages);				
			}
		});
		$.ajax({
			type: 'GET',
			url: "<?php echo url('sampletracking/outbreak'); ?>",
			success: function(data) {
				$('#outbreak').html(data);				
			}
		});
	});
	$(function() {
	var options = { 
			selectorAttribute: "data-target",
			backToTop: true
		};
		$('.nav-tabs').stickyTabs(options);
	});
	//get all the package counts for the tabs
	
</script> 
<?php $__env->appendSection(); ?> 
<!-- Info boxes -->
<div class="row panel-body">
  <div class="btn-group container col-md-12"> <?php if (\Entrust::hasRole(['administrator','hub_coordinator','national_hub_coordinator'])) : ?>
  
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Manage Samples</a></li>
        <li class="" style="display:none"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Equipment</a></li>
        <li class="hidden"><a href="#tab_3" data-toggle="tab" aria-expanded="false">Routing</a></li>
        <li class="hidden"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Bikes</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="destinedforcphl" class="stats"></h3>

              <p>Destined for CPHL</p>
            </div>
            <div class="icon">
              <i class="ion ion-plane"></i>
            </div>
            <a href="<?php echo e(route('samples.cphl',['status' => 5])); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="receivedatcphl" class="stats"></h3>

              <p>Received at CPHL</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?php echo e(route('samples.cphl',['status' => 7])); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id="hubpackages" class="stats"></h3>

              <p>Destined for hub</p>
            </div>
            <div class="icon">
              <i class="ion ion-plane"></i>
            </div>
            <a href="<?php echo e(route('samples.all',['status' => 1])); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id="outbreak" class="stats"></h3>

              <p>COVID-19 this month</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars "></i>
            </div>
            <a href="<?php echo e(route('covid.index')); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
          </div>
        
        <div class="tab-pane" id="tab_2" style="display:none"> 
        	<div class="row"> 
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="info-box"> <span class="info-box-icon bg-red"><i class="fa fa-tripadvisor"></i></span>
                <div class="info-box-content"> <span class="info-box-text"><a class="link-tip" href="<?php echo e(route('labequipment.list',['status' => 2])); ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Bikes Broken down">Equipment broken down </a></span> <span class="info-box-number"><?php echo e(count($lab_equipment_broken_down)); ?></span> </div>
                <!-- /.info-box-content --> 
              </div>
              <!-- /.info-box --> 
            </div>
            <!-- /.col -->
          </div>
        </div>
        <div class="tab-pane" id="tab_3"> <div class="row"> 
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="info-box"> <span class="info-box-icon bg-red"><i class="fa fa-h-square"></i></span>
                <div class="info-box-content"> <span class="info-box-text"><a class="link-tip" href="<?php echo e(route('dailyrouting.notvisited',['status' => 1])); ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Facilities not visited last week">Facilities Not visted last week </a></span> <span class="info-box-number"><?php echo e(count($facilities_not_visited)); ?></span> </div>
                <!-- /.info-box-content --> 
              </div>
              <!-- /.info-box --> 
            </div>
            <!-- /.col -->
          </div> </div>
          <div class="tab-pane" id="tab_4">
          <div class="row"> 
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box"> <span class="info-box-icon bg-red"><i class="fa fa-motorcycle"></i></span>
                <div class="info-box-content"> <span class="info-box-text"><a class="link-tip" href="<?php echo e(url('equipment/list/status/2')); ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Bikes Broken down">Bikes Broken down</a></span> <span class="info-box-number"><?php echo e(count($equipment_broken_down)); ?></span> </div>
                <!-- /.info-box-content --> 
              </div>
              <!-- /.info-box --> 
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box"> <span class="info-box-icon bg-yellow"><i class="fa fa-motorcycle"></i></span>
                <div class="info-box-content"> <span class="info-box-text"><a class="link-tip" href="<?php echo e(url('equipment/list/service/0')); ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Bikes without Service Contract">Bikes without Service Contract</a></span> <span class="info-box-number"><?php echo e(count($equipment_no_service)); ?></span> </div>
                <!-- /.info-box-content --> 
              </div>
              <!-- /.info-box --> 
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="fa fa-motorcycle"></i></span>
                <div class="info-box-content"> <span class="info-box-text"><a class="link-tip" href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Bikes Due for Service">Bikes Due for Service</a></span> <span class="info-box-number">comming soon</span> </div>
                <!-- /.info-box-content --> 
              </div>
              <!-- /.info-box --> 
            </div>
            
            <!-- /.col --> 
          </div>
        </div>
          
      </div>
    </div>
    <?php endif; // Entrust::hasRole ?> 
    <!-- /.row -->
    <div class="row" style="background-color:#fff; margin-left:0; margin-right:0; border-radius: 2px;"> 
      <!-- Left col -->
      <section class="col-lg-9">
        <div id="samples-chart" >
          <?php //echo lava::render('LineChart', 'samples', 'samples-chart'); ?>
          <?php echo lava::render('ColumnChart', 'samples', 'samples-chart'); ?> </div>
      </section>
      <section class="col-lg-3">
        <h2 style="margin-bottom:5px; margin-left:5px;">Summary</h2>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th>Sample Type</th>
              <th>No. samples</th>
            </tr>
            <?php $__currentLoopData = $samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($line->sampletype); ?> </td>
              <td><?php echo e($line->samples); ?> </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </table>
        </div>
      </section>
      <div class="pull-right" style="margin-right:20px;"><a href="<?php echo e(route('dailyrouting.samplelist')); ?>"><i class="fa fa-fw fa-list"></i>View all</a></div>
    </div>
    
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>