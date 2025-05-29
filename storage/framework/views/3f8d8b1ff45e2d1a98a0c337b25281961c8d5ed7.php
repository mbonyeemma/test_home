<?php if($pagetype == 1): ?>
	<?php $__env->startSection('title', 'Add New Sample Transporter'); ?>
<?php elseif($pagetype == 4): ?>
	<?php $__env->startSection('title', 'Add New Driver'); ?>
  <?php elseif($pagetype == 3): ?>
  <?php $__env->startSection('title', 'Add New Ref Lab Receptionist'); ?>
<?php elseif($pagetype == 2): ?>
	<?php $__env->startSection('title', 'Add New Sample Receptionist'); ?>
<?php elseif($pagetype == 5): ?>	
	<?php $__env->startSection('title', 'Add New EOC Staff'); ?>
<?php elseif($pagetype == 6): ?> 
  <?php $__env->startSection('title', 'Add New POE User'); ?>
<?php elseif($pagetype == 7): ?> 
  <?php $__env->startSection('title', 'Community User'); ?>
<?php elseif($pagetype == 8): ?> 
  <?php $__env->startSection('title', 'Private Transporter'); ?>
<?php else: ?>
<?php endif; ?>
<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('js/bootstrapValidator.min-0.5.1.js')); ?>"></script>
 <script>
	$(document).ready(function() {
	$('#permitexpirydate').datepicker({
		   format: 'mm/dd/yyyy',
		   autoclose: true
		});
		
	$("input[name='hasdrivingpermit']").change(function(){
		if( $(this).is(":checked") ){ // check if the radio is checked
            var val = $(this).val(); // retrieve the value
			//alert(val);
			if(val == 1){
				$('#permitexpirydate').removeClass('hidden');
			}else{
				$('#permitexpirydate').val('');
				$('#permitexpirydate').addClass('hidden');
			}
        }	
		
	});
	$("input[name='permitexpirydate']").change(function(){
		$('#staffform').bootstrapValidator('revalidateField', 'permitexpirydate');
	});
	$("select[name='facilityid']").change(function(){
      var id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
          url: "<?php echo url('staff/bikewithoutrider'); ?>",
          method: 'POST',
          data: {hubid:id, _token:token},
          success: function(data) {
			  	$("#motorbikeid").html("").prepend("<option value=''>Select One</option>"); 
			    $("select[name='motorbikeid'").html('');
				$("select[name='motorbikeid'").html(data.options);
			  }
		  });
	  });
		
		$('#staffform').bootstrapValidator({
       
        fields: {
			facilityid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a hub'
                        }
                    }
                },
                
			firstname: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the first name'
                        }
                    }
                },
				hasdrivingpermit: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a number'
                        }
                     }
                },
        permitexpirydate: {
            validators: {
                notEmpty: {
                    message: 'Please select the permit expiry date'
                }
             }
        },
		    password: {
            validators: {
                notEmpty: {
                    message: 'Please the password the user will use to login into the mobile app'
                }
             }
        },
        username: {
            validators: {
                notEmpty: {
                    message: 'Please the username the user will use to login into the mobile app'
                }
             }
        },
        isimmunizedforhb: {
            validators: {
                notEmpty: {
                    message: 'Please select whether the transporter is Immunized for HB'
                }
             }
        },
        hasdrivingpermit: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter has driving permit'
                }
             }
        },
        hasbbtraining: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter is trained in BB'
                }
             }
        },
        hasdefensiveriding: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter has undergone defensive driving'
                }
             }
        },
        poe_site: {
            validators: {
                notEmpty: {
                    message: 'Please select the POE site'
                }
             }
        },
				nationalid: {
                    validators: {
						stringLength: {
							  min: 14,
							  max: 14,
							  message: 'The NIN should be 14 characters long'
						}
                    }
                },
				lastname: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the last name'
                        }
                    }
                },
				telephonenumber: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the telephone number'
                        }
                    }
                },
					email: {          
				validators: {
							regexp: {
							  regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
							  message: 'The value is not a valid email address'
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
            
            <!-- /.box-header -->
            <!-- form start -->
            
    		<?php echo e(Form::open(array('route' => 'staff.store', 'class' => 'form-horizontal', 'id' => 'staffform'))); ?>

            	<?php echo e(csrf_field()); ?>

              <div class="box-body">
              <?php if (\Entrust::hasRole(['Admin','Regional_hub_coordinator','Program_officer','national_hub_coordinator'])) : ?> 
                <?php if($pagetype == 1 || $pagetype == 2 || $pagetype == 4 || $pagetype == 8): ?> 
                  <div class="form-group">
                      <label for="facility" class="col-sm-2 control-label"><?php echo e(Form::label('facility', 'Hub')); ?></label>

                      <div class="col-sm-10">
                        <?php echo e(Form::select('facilityid', $hubsdropdown, null, ['class' => 'form-control', 'id' => 'facilityid'])); ?>

                         
                      </div>
                    </div>
                  <?php endif; ?>             
                <?php endif; // Entrust::hasRole ?>
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                <?php if($pagetype == 6): ?> 
                <div class="form-group">
                    <label for="facility" class="col-sm-2 control-label"><?php echo e(Form::label('poe_site', 'POE Site')); ?></label>

                    <div class="col-sm-10">
                      <?php echo e(Form::select('poe_site', $poe_sites, null, ['class' => 'form-control', 'id' => 'poe_site'])); ?>

                       
                    </div>
                </div>             
                <?php endif; ?> 
                <?php if($pagetype == 3): ?> 
                <div class="form-group">
                    <label for="facility" class="col-sm-2 control-label"><?php echo e(Form::label('ref_lab', 'Ref Lab')); ?></label>

                    <div class="col-sm-10">
                      <?php echo e(Form::select('ref_lab', $ref_labs, null, ['class' => 'form-control', 'id' => 'ref_lab'])); ?>

                       
                    </div>
                </div>             
                <?php endif; ?>
                <?php if($pagetype == 1): ?>
                 <div class="form-group">
                  <label for="motorbikeid" class="col-sm-2 control-label"><?php echo e(Form::label('bikes', 'Motor Bike')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::select('motorbikeid', $bikes, null, ['class' => 'form-control'])); ?>

                     
                  </div>
                </div>
                <?php endif; ?>
              <?php if($pagetype == 2): ?>
              	<div class="form-group" style="display:none">
                  <label for="designation" class="col-sm-2 control-label"><?php echo e(Form::label('designation', 'Designation')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::select('designation', $designation, null, ['class' => 'form-control'])); ?>

                     
                  </div>
                </div>
              <?php endif; ?>
                <div class="form-group">
                  <label for="firstname" class="col-sm-2 control-label"><?php echo e(Form::label('firstname', 'First Name')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname'))); ?>

                  </div>
                </div>
               <div class="form-group">
                  <label for="lastname" class="col-sm-2 control-label"><?php echo e(Form::label('lastname', 'Last Name')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname'))); ?>

                  </div>
                </div>
                <div class="form-group">
                  <label for="othernames" class="col-sm-2 control-label"><?php echo e(Form::label('othernames', 'Other Names')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('othernames', null, array('class' => 'form-control', 'id' => 'othernames'))); ?>

                  </div>
                </div>
                <div class="form-group">
                  <label for="emailaddress" class="col-sm-2 control-label"><?php echo e(Form::label('emailaddress', 'Email Address')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('emailaddress', null, array('class' => 'form-control', 'id' => 'emailaddress'))); ?>

                  </div>
                </div>
                <div class="form-group">
                  <label for="telephonenumber" class="col-sm-2 control-label"><?php echo e(Form::label('telephonenumber', 'Telephone Number')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber'))); ?>

                  </div>
                </div>
                <div class="form-group">
                  <label for="telephonenumber2" class="col-sm-2 control-label"><?php echo e(Form::label('telephonenumber2', 'Telephone Number2')); ?></label>

                  <div class="col-sm-10">
                  <?php echo e(Form::text('telephonenumber2', null, array('class' => 'form-control', 'id' => 'telephonenumber2'))); ?>

                  </div>
                </div> 

                <div class="form-group">
                  <label for="telephonenumber3" class="col-sm-2 control-label"><?php echo e(Form::label('telephonenumber3', 'Preferred Whatsapp Number')); ?></label>

                  <div class="col-sm-10">
                  <?php echo e(Form::text('telephonenumber3', null, array('class' => 'form-control', 'id' => 'telephonenumber3'))); ?>

                  </div>
                </div> 
                <div class="form-group">
                  <label for="username" class="col-sm-2 control-label"><?php echo e(Form::label('username', 'Username')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('username', null, array('class' => 'form-control', 'id' => 'username'))); ?>

                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label"><?php echo e(Form::label('password', 'Password')); ?></label>

                  <div class="col-sm-10">
                    <?php echo e(Form::text('password', null, array('class' => 'form-control', 'id' => 'password'))); ?>

                  </div>
                </div>
                
                <?php if($pagetype == 1): ?>
                    <div class="form-group">
                      <label for="drivingpermit" class="col-sm-2 control-label"><?php echo e(Form::label('drivingpermit', 'Has Driving Permit')); ?></label>
    
                      <div class="col-sm-3">
                        <?php echo generateRationInput($yes_no, 'hasdrivingpermit'); ?>

                        <?php if($errors->has('hasdrivingpermit')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('hasdrivingpermit')); ?></strong>
                            </span>
                        <?php endif; ?>
                      </div>
                      <div class="col-sm-3">
                        <?php echo e(Form::text('permitexpirydate', null, array('class' => 'form-control hidden', 'id' => 'permitexpirydate', 'placeholder' => 'Expiry Date'))); ?>

                      </div>
                    </div>
                    <div class="form-group">
                      <label for="hasdefensiveriding" class="col-sm-2 control-label"><?php echo e(Form::label('hasdefensiveriding', 'Has Defensive Driving')); ?></label>

                      <div class="col-sm-10">
                        <?php echo generateRationInput($yes_no, 'hasdefensiveriding'); ?>

                        <?php if($errors->has('hasdefensiveriding')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('hasdefensiveriding')); ?></strong>
                            </span>
                        <?php endif; ?>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="hasbbtraining" class="col-sm-2 control-label"><?php echo e(Form::label('hasbbtraining', 'Has BB Training')); ?></label>

                      <div class="col-sm-10">
                        <?php echo generateRationInput($yes_no, 'hasbbtraining'); ?>

                        <?php if($errors->has('hasbbtraining')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('hasbbtraining')); ?></strong>
                            </span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="isimmunizedforhb" class="col-sm-2 control-label"><?php echo e(Form::label('isimmunizedforhb', 'Is Immunized for Hepatitis B')); ?></label>

                      <div class="col-sm-10">
                        <?php echo generateRationInput($yes_no, 'isimmunizedforhb'); ?>

                        <?php if($errors->has('isimmunizedforhb')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('isimmunizedforhb')); ?></strong>
                            </span>
                        <?php endif; ?>
                      </div>
                    </div>  
                 <?php endif; ?>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <?php echo e(Form::hidden('type', $pagetype)); ?>

                <a class="btn btn-sm btn-danger" href="<?php echo e(URL::previous()); ?>">Cancel</a>
               
                <?php echo e(Form::submit('Create', array('class' => 'btn btn-sm btn-info pull-right'))); ?>

                
              </div>
              <!-- /.box-footer -->
            
            <?php echo e(Form::close()); ?>

          </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>