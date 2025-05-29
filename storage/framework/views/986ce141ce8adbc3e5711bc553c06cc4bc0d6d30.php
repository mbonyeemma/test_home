<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>UNHLS | Sample Tracker</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('css/ionicons.min.css')); ?>">
	<?php echo $__env->yieldContent('css'); ?>
	<link rel="stylesheet" href="<?php echo e(asset('css/AdminLTE.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-datepicker.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('css/skin-blue.min.css')); ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
	<link rel="stylesheet" href="<?php echo e(asset('css/select2.min.css')); ?>">
</head>

<style>
	.input-field {
		width: 120px;
		resize: vertical;
	}

	.filter-date {
		width: 180px;
	}

	.select-hubs {
		width: 250px;
	}

	.selectdropdown {
		width: 200px;
		border-radius: 3px;
		border-radius: 25px:
	}

	.label-bs {
		font-size: medium;
		display: inline-block;
		min-width: 10px;
		padding: 3px 7px;
		font-weight: bold;
		line-height: 1;
		color: #fff;
		text-align: center;
		white-space: nowrap;
		vertical-align: baseline;
		background-color: #777;
		border-radius: 10px;
	}

	.selectdropdown {
		height: 34px;
		padding: 6px 12px;
		font-size: 14px;
		line-height: 1.42857143;
		color: #555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-top-color: rgb(204, 204, 204);
		border-right-color: rgb(204, 204, 204);
		border-bottom-color: rgb(204, 204, 204);
		border-left-color: rgb(204, 204, 204);
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	}
</style>

<body class="hold-transition skin-blue sidebar-collapse">
	<div class="wrapper">
		<?php echo $__env->make('index.header1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					<?php echo $__env->yieldContent('title'); ?>
				</h1>
				<ul class="list-inline context-menu">
					<li><a href="<?php echo e(route('new')); ?>"><i class="fa fa-home"></i> Home</a></li>
					<li><a href="<?php echo e(url('/data/archives')); ?>"><i class="fa fa-cart-dashboard"></i> Data Archives</a></li>
					<!-- <li><a href="<?php echo e(url('/monitor/samples')); ?>"><i class="fa fa-cart-plus"></i> Delivered Packages</a></li> -->
					<li><a href="<?php echo e(url('/samples/movement')); ?>"><i class="fa fa-flask"></i> Package Movement</a></li>
					<li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="fa fa-dashboard"></i> Login</a></li>
				</ul>
			</section>
			<section class="content">
				<?php echo $__env->yieldContent('content'); ?>
			</section>
		</div>
		<footer class="main-footer">
			<!-- To the right -->
			<div class="pull-right hidden-xs">

			</div>
			<strong>Copyright &copy; <?php echo e(date('Y')); ?> <a href="#">UNHLS</a>.</strong> All rights reserved.
		</footer>
	</div>



	<!-- REQUIRED JS SCRIPTS -->

	<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
	<?php echo $__env->yieldContent('js'); ?>
	<script src="<?php echo e(asset('js/adminlte.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/bootstrap-datepicker.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/hom.js')); ?>"></script>
	<script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>


	<script>
		$(document).ready(function() {
			$('#routedate,#datebrokendown, #datereported').datepicker({
				format: 'mm/dd/yyyy',
				endDate: '+0d',
				autoclose: true
			});
			//on selecting a hub, get the facilities it serves
			$("select[name='hubid']").change(function() {
				var hubid = $(this).val();
				var hiddenvalue = $("input[name='_token']").val();

				$.ajax({
					url: "<?php echo url('dailyrouting/facilitiesforhub'); ?>",
					method: 'POST',
					data: {
						hubid: hubid,
						_token: hiddenvalue
					},
					success: function(data) {
						$("select[name='facilityid'").empty();
						$("select[name='facilityid'").html(data.options);
					}
				});
			});
		});
	</script>

	<?php echo $__env->yieldContent('page-js-script'); ?>


</body>

</body>

</html>