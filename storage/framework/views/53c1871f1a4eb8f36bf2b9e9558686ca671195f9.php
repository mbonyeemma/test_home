<?php if($pagetype == 1): ?>
	<?php $__env->startSection('title', 'All Sample Transporters'); ?>
<?php elseif($pagetype == 4): ?>
	<?php $__env->startSection('title', 'All Drivers'); ?>
<?php elseif($pagetype == 3): ?>
  <?php $__env->startSection('title', 'Ref Lab Receptionists'); ?>
<?php elseif($pagetype == 2): ?>
	<?php $__env->startSection('title', 'All Sample Receptionists'); ?>
<?php elseif($pagetype == 5): ?>	
	<?php $__env->startSection('title', 'All EOC Staff Members '); ?>
<?php elseif($pagetype == 6): ?> 
  <?php $__env->startSection('title', 'All POE Users '); ?>
<?php else: ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
<?php $__env->appendSection(); ?>
<?php $__env->startSection('listpagejs'); ?>
<script src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/buttons.colVis.min.js')); ?>"></script>
<script>
    $(document).ready(function() {
      //$('#listtable').DataTable();
      $('#stafflisttable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
          
          {
            extend: 'excelHtml5'
          }
        ]
      } );
    } );
  </script> 
<?php $__env->appendSection(); ?>
<div class="box box-info">
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="stafflisttable" class="table table-striped table-bordered">
    <thead>
    	<tr> <?php if($pagetype == 2 || $pagetype == 1 || $pagetype == 3): ?>
          <th>District Where Hub is Located</th>
          <th>Hub</th>
          <th>Facilities Served</th>
          <?php endif; ?>
          <th>First Name</th>
          <th>Last Name</th>
          <?php if($request->showcontact): ?>
         <th>Other Names</th>
         <th>Email Address</th>
         <th>Telephone Number 1</th>
         <th>Telephone Number 2</th>
         <th>Preferred Whatsapp Number</th>
         <?php else: ?>
          <?php if($pagetype == 2): ?>
          <th>Designation</th>
          <?php endif; ?>
          <?php if($pagetype == 1): ?>
          <th>Has Driving Permit</th>
          <th>Densive Driving</th>
          <th>Trained in BB</th>
          <th>Is Immunised for HB</th>
          <?php endif; ?>
          
          <th>Actions</th>
          <?php endif; ?>
        </tr>
    </thead>
      <tbody>
        
      <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <?php if($pagetype == 2 || $pagetype == 1 || $pagetype == 3): ?>
        <td><?php echo e($st->district); ?></td>
        <td><?php echo e($st->facility); ?></td>
        <td><?php echo e(getFacilitiesforHub($st->hubid)->count()); ?></td>

        <?php endif; ?>
        <td><a href="<?php echo e(route('staff.show', $st->id )); ?>"><?php echo e($st->firstname); ?></a></td>
        <td><?php echo e($st->lastname); ?></td>
        <?php if($request->showcontact): ?>
         <td><?php echo e($st->othernames); ?></td>
         <td><?php echo e($st->emailaddress); ?></td>
         <td><?php echo e($st->telephonenumber); ?></td>
         <td><?php echo e($st->telephonenumber2); ?></td>
         <td><?php echo e($st->telephonenumber3); ?></td>
         <?php else: ?>
        <?php if($pagetype == 2): ?>
        <td><?php echo e($st->designation); ?></td>
        <?php endif; ?>
        <?php if($pagetype == 1): ?>
        <td><?php if($st->hasdrivingpermit): ?><?php echo e(getLookupValueDescription('YES_NO', $st->hasdrivingpermit)); ?> <?php endif; ?></td>
        <td><?php if($st->hasdefensiveriding): ?><?php echo e(getLookupValueDescription('YES_NO', $st->hasdefensiveriding)); ?> <?php endif; ?></td>
        <td><?php if($st->hasbbtraining): ?><?php echo e(getLookupValueDescription('YES_NO', $st->hasbbtraining)); ?> <?php endif; ?></td>
        <td><?php if($st->isimmunizedforhb): ?><?php echo e(getLookupValueDescription('YES_NO', $st->isimmunizedforhb)); ?> <?php endif; ?></td>
        <?php endif; ?>

        <td><a href="<?php echo e(route('staff.edit', $st->id )); ?>"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;
        	<a href="<?php echo e(route('staff.destroy', $st->id )); ?>" class="hidden"><i class=" fa fa-fw fa-trash-o"></i>Delete</a>
          <a href="<?php echo e(url('user/resetpassword',['id' => $st->user_id])); ?>"><i class=" fa fa-user"></i>Change Password</a>
        </td>
        <?php endif; ?>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->
  
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>