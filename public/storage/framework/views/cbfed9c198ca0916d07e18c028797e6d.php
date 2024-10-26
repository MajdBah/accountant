<?php echo e(Form::open(array('url'=>'vender','method'=>'post'))); ?>

<div class="modal-body">

    <h5 class="sub-title"><?php echo e(__('Basic Info')); ?></h5>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('name',null,array('class'=>'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('contact',__('Contact'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('contact',null,array('class'=>'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('email',__('Email'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('email',null,array('class'=>'form-control','required'=>'required'))); ?>

                </div>
            </div>
        </div>
        <?php echo Form::hidden('role', 'company', null, ['class' => 'form-control select2', 'required' => 'required']); ?>

        <div class="col-lg-4 col-md-4 form-group mt-4">
                <label for="password_switch"><?php echo e(__('Login is enable')); ?></label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="password_switch" class="form-check-input input-primary pointer" value="on" id="password_switch">
                    <label class="form-check-label" for="password_switch"></label>
                </div>
            </div>
        <div class="col-lg-4 col-md-4 col-sm-6 ps_div d-none">
            <div class="form-group">
                <?php echo e(Form::label('password', __('Password'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::password('password', ['class' => 'form-control', 'minlength' => '6'])); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('tax_number',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <h5 class="sub-title"><?php echo e(__('BIlling Address')); ?></h5>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_name',__('Name'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_name',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_phone',__('Phone'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_phone',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('billing_address',__('Address'),array('class'=>'form-label'))); ?>

                <div class="input-group">
                    <?php echo e(Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_city',__('City'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_city',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_state',__('State'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_state',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
       
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_country',__('Country'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_country',null,array('class'=>'form-control'))); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <?php echo e(Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label'))); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('billing_zip',null,array('class'=>'form-control','placeholder'=>__('')))); ?>

                </div>
            </div>
        </div>
        
    </div>

    <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
        <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="<?php echo e(__('Shipping Same As Billing')); ?>" class="btn btn-primary">
        </div>
        <h5 class="sub-title"><?php echo e(__('Shipping Address')); ?></h5>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_name',__('Name'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_name',null,array('class'=>'form-control'))); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_phone',__('Phone'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_phone',null,array('class'=>'form-control'))); ?>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_address',__('Address'),array('class'=>'form-label'))); ?>

                    <div class="input-group">
                        <?php echo e(Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3))); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_city',__('City'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_city',null,array('class'=>'form-control'))); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_state',__('State'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_state',null,array('class'=>'form-control'))); ?>

                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_country',__('Country'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_country',null,array('class'=>'form-control'))); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <?php echo e(Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label'))); ?>

                    <div class="form-icon-user">
                        <?php echo e(Form::text('shipping_zip',null,array('class'=>'form-control','placeholder'=>__('')))); ?>

                    </div>
                </div>
            </div>
            
        </div>
    <?php endif; ?>

</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
<?php $__env->stopPush(); ?><?php /**PATH /home/sites/13a/1/1d6570cbc2/accountants/resources/views/vender/create.blade.php ENDPATH**/ ?>