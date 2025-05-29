<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<?php $__env->appendSection(); ?>
<?php $__env->startSection('listpagejs'); ?>
<script src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"></script>
    <script>
		$(document).ready(function() {
			$('#users-table').DataTable();
		} );
	</script>
<?php $__env->appendSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="box box-info">
    <div class="box-body table-responsive">
        <table id="users-table" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date/Time Added</th>
                    <th>User Roles</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>

                    <td><a href="<?php echo e(route('users.show', $user->id )); ?>"><?php echo e($user->name); ?></a></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->created_at->format('F d, Y')); ?></td>
                    <td><?php echo e($user->roles()->pluck('display_name')->implode(', ')); ?></td>
                    <td>
                    <a href="<?php echo e(route('users.edit', $user->id)); ?>"> <i class="fa fa-fw fa-edit"></i> Edit</a>
					<a href="<?php echo e(route('users.destroy', $user->id)); ?>"> <i class="fa fa-ban"></i> De-activate</a>
                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]); ?>

                    <?php echo Form::close(); ?>


                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>