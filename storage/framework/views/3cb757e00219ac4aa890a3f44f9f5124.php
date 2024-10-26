<?php echo e(Form::open(['url' => 'bank-account'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('chart_account_id', __('Account'), ['class' => 'form-label'])); ?>

            <select name="chart_account_id" class="form-control" required="required">
                <?php $__currentLoopData = $chartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chartAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" class="subAccount"><?php echo e($chartAccount); ?></option>
                    <?php $__currentLoopData = $subAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key == $subAccount['account']): ?>
                            <option value="<?php echo e($subAccount['id']); ?>" class="ms-5"> &nbsp; &nbsp;&nbsp;
                                <?php echo e($subAccount['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('holder_name', __('Bank Holder Name'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <span><i class="ti ti-address-card"></i></span>
                <?php echo e(Form::text('holder_name', '', ['class' => 'form-control', 'required' => 'required'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('bank_name', __('Bank Name'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <span><i class="ti ti-university"></i></span>
                <?php echo e(Form::text('bank_name', '', ['class' => 'form-control', 'required' => 'required'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('account_number', __('Account Number'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <span><i class="ti ti-notes-medical"></i></span>
                <?php echo e(Form::text('account_number', '', ['class' => 'form-control', 'required' => 'required'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('opening_balance', __('Opening Balance'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <span><i class="ti ti-dollar-sign"></i></span>
                <?php echo e(Form::number('opening_balance', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('contact_number', __('Contact Number'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <span><i class="ti ti-mobile-alt"></i></span>
                <?php echo e(Form::text('contact_number', '', ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('bank_address', __('Bank Address'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::textarea('bank_address', '', ['class' => 'form-control', 'rows' => 2])); ?>

        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/sites/13a/1/1d6570cbc2/accountants/resources/views/bankAccount/create.blade.php ENDPATH**/ ?>