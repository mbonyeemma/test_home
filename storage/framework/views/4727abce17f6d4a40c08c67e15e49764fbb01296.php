<?php $__env->startSection('title', 'Reset Password'); ?>
<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('js/bootstrapValidator.min-0.5.1.js')); ?>"></script>
 <script>
	$(document).ready(function() {
		
		$('#resetpasswordform').bootstrapValidator({
       
        fields: {
				oldpassword: {          
				validators: {
							notEmpty: {
								message: 'Please enter your old password'
							}
						}
					},
            	password: {          
				validators: {
							notEmpty: {
								message: 'Please enter a password'
							},
							securePassword: {
								message: 'The password is not valid'
							}
						}
					},
				password_confirmation: {
                validators: {
					notEmpty: {
								message: 'Please confirm your password'
							},
                    identical: {
                        field: 'password',
                        message: 'The passwords do not match'
						}
					}
				}
		}//endo of validation rules
    });// close form validation function
	});
</script>
<?php $__env->appendSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="box box-info">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
         <?php endif; ?>

    <?php echo e(Form::open(array('action'=>'UserController@saveresetpassword','id'=>'resetpasswordform','method' => 'POST'))); ?>

<div class="box-body">
    <?php if($userid == Auth::user()->id): ?>
	<div class="form-group">
        <?php echo e(Form::label('oldpassword', 'Old Password')); ?><br>
        <?php echo e(Form::password('oldpassword', array('class' => 'form-control'))); ?>


    </div>
    <?php endif; ?>
    <div class="form-group">
        <?php echo e(Form::label('password', 'New Password')); ?><br>
        <?php echo e(Form::password('password', array('class' => 'form-control'))); ?>

        <?php echo e(Form::hidden('userid', $userid)); ?>


    </div>

    <div class="form-group">
        <?php echo e(Form::label('password', 'Confirm New Password')); ?><br>
        <?php echo e(Form::password('password_confirmation', array('class' => 'form-control'))); ?>


    </div>
</div>
              <!-- /.box-body -->
              <div class="box-footer">
              <a class="btn btn-sm btn-danger" href="<?php echo e(URL::previous()); ?>">Cancel</a>
    <?php echo e(Form::submit('Reset', array('class' => 'btn btn-sm btn-info pull-right'))); ?>


    <?php echo e(Form::close()); ?>

</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>