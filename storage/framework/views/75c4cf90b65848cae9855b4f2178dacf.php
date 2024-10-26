<?php
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);
?>
<?php echo e(Form::open(array('url' => 'transfer'))); ?>

<div class="modal-body">
    <div class="row">
        <?php if($enable_chatgpt): ?>
        <div>
            <a href="#" data-size="md" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['transfer'])); ?>"
                data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>"
                data-title="<?php echo e(__('Generate content with AI')); ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-robot"></i>
                <?php echo e(__('Generate with AI')); ?>

            </a>
        </div>
        <?php endif; ?>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('from_account', __('From Account'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('from_account', $bankAccount,null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('to_account', __('To Account'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('to_account', $bankAccount,null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>

            <div class="form-icon-user">
                <?php echo e(Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01'))); ?>

            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

            <div class="form-icon-user">
                <?php echo e(Form::date('date',date('Y-m-d'),array('class'=>'form-control','required'=>'required'))); ?>


            </div>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('reference', __('Reference'),['class'=>'form-label'])); ?>

            <div class="form-icon-user">
                <?php echo e(Form::text('reference', '', array('class' => 'form-control'))); ?>

            </div>
        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', '', array('class' => 'form-control','rows'=>3))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /Users/majd/Desktop/accountants/resources/views/transfer/create.blade.php ENDPATH**/ ?>