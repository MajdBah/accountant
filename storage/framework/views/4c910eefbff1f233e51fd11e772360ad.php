<?php
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);
?>
<?php echo e(Form::open(array('url' => 'productservice'))); ?>

<div class="modal-body">
    <div class="row">
        <?php if($enable_chatgpt): ?>
        <div>
            <a href="#" data-size="md" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['product & service'])); ?>"
                data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>"
                data-title="<?php echo e(__('Generate content with AI')); ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-robot"></i> 
                <?php echo e(__('Generate with AI')); ?>

            </a>
        </div>
        <?php endif; ?>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('name', '', ['class' => 'form-control', 'required' => 'required'])); ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('sku', __('SKU'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('sku', '', ['class' => 'form-control', 'required' => 'required'])); ?>

                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('sale_price', __('Sale Price'), ['class' => 'form-label'])); ?><span
                    class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::number('sale_price', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01'])); ?>

                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label'])); ?>

            <select name="sale_chartaccount_id" class="form-control" required="required">
                <?php $__currentLoopData = $incomeChartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chartAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" class="subAccount"><?php echo e($chartAccount); ?></option>
                    <?php $__currentLoopData = $incomeSubAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key == $subAccount['account']): ?>
                            <option value="<?php echo e($subAccount['id']); ?>" class="ms-5"> &nbsp; &nbsp;&nbsp; <?php echo e($subAccount['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('purchase_price', __('Purchase Price'), ['class' => 'form-label'])); ?><span
                    class="text-danger">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::number('purchase_price', '', ['class' => 'form-control', 'required' => 'required', 'step' => '0.01'])); ?>

                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label'])); ?>

            <select name="expense_chartaccount_id" class="form-control" required="required">
                <?php $__currentLoopData = $expenseChartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chartAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" class="subAccount"><?php echo e($chartAccount); ?></option>
                    <?php $__currentLoopData = $expenseSubAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key == $subAccount['account']): ?>
                            <option value="<?php echo e($subAccount['id']); ?>" class="ms-5"> &nbsp; &nbsp;&nbsp; <?php echo e($subAccount['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('tax_id', __('Tax'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('tax_id[]', $tax, null, ['class' => 'form-control select2', 'id' => 'choices-multiple1', 'multiple'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('category_id', __('Category'), ['class' => 'form-label'])); ?><span
                class="text-danger">*</span>
            <?php echo e(Form::select('category_id', $category, null, ['class' => 'form-control select', 'required' => 'required'])); ?>


            <div class=" text-xs">
                <?php echo e(__('Please add constant category. ')); ?><a
                    href="<?php echo e(route('product-category.index')); ?>"><b><?php echo e(__('Add Category')); ?></b></a>
            </div>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('unit_id', __('Unit'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('unit_id', $unit, null, ['class' => 'form-control select', 'required' => 'required'])); ?>

        </div>

        <div class="form-group col-md-6">
            <?php echo e(Form::label('quantity', __('Quantity'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('quantity', null, ['class' => 'form-control', 'required' => 'required'])); ?>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="btn-box">
                    <label class="d-block form-label"><?php echo e(__('Type')); ?></label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="customRadio5" name="type"
                                    value="Product" checked="checked" onclick="hide_show(this)">
                                <label class="custom-control-label form-label"
                                    for="customRadio5"><?php echo e(__('Product')); ?></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" id="customRadio6" name="type"
                                    value="Service" onclick="hide_show(this)">
                                <label class="custom-control-label form-label"
                                    for="customRadio6"><?php echo e(__('Service')); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']); ?>

        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-lg-6 col-md-6 col-sm-6">
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



<?php /**PATH /Users/majd/Desktop/accountants/resources/views/productservice/create.blade.php ENDPATH**/ ?>