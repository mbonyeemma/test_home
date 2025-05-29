<?php $__env->startSection('title', 'Mobile App Registrations'); ?>


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
        $('#stafflisttable').DataTable({
            dom: 'Bfrtip',
            buttons: [

                {
                    extend: 'excelHtml5'
                }
            ]
        });
    });
</script>
<?php $__env->appendSection(); ?>
<div class="box box-info">

    <!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="stafflisttable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <!-- <th>District Where Hub is Located</th>
                    <th>Hub</th>
                    <th>Facilities Served</th> -->

                    <th>Transporter Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Riding / Driving Permit</th>
                    <th>Hub Name</th>
                    <th>Defensive Driving</th>
                    <th>Trained in BB</th>
                    <th>Is Immunised for HB</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $staff;
                $__env->addLoop($__currentLoopData);
                foreach ($__currentLoopData as $st): $__env->incrementLoopIndices();
                    $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($st->name); ?></td>
                        <td><?php echo e($st->telephone_number); ?></td>
                        <td><?php echo e($st->email); ?></td>
                        <td><?php echo e($st->driving_permit); ?></td>
                        <td><?php echo e($st->hubname); ?></td>
                        <td><?php echo e($st->defensive_driving); ?></td>
                        <td><?php echo e($st->bb_training); ?></td>
                        <td><?php echo e($st->hep_b_immunisation); ?></td>
                        <td><a href="<?php echo e(route('staff.edit', $st->id)); ?>"><i class="fa fa-fw fa-edit"></i>Activate User</a>&nbsp;
                            <a href="<?php echo e(route('staff.destroy', $st->id)); ?>" class="hidden"><i class=" fa fa-fw fa-trash-o"></i>Delete</a>
                            <a href="<?php echo e(url('user/resetpassword', ['id' => $st->id])); ?>"><i class=" fa fa-user"></i>Change Password</a>
                            <a href="<?php echo e(url('user/resetpassword', ['id' => $st->id])); ?>"><i class=" fa fa-user"></i>Deactivate</a>
                        </td>
                    </tr>
                <?php endforeach;
                $__env->popLoop();
                $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>