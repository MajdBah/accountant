<?php
    $settings = Utility::settings();
    $lang = isset($currEmailTempLang->lang) ? $currEmailTempLang->lang : 'en';
    if ($lang == null) {
        $lang = 'en';
    }
    $LangName = \App\Models\Language::where('code', $lang)->first();

?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e($emailTemplate->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Email Template')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
<link rel="stylesheet" href=" <?php echo e(Module::asset('LandingPage:Resources/assets/css/summernote/summernote-bs4.css')); ?>" />
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
<script src="<?php echo e(Module::asset('LandingPage:Resources/assets/js/plugins/summernote-bs4.js')); ?>" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            $('.summernote-simple').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 200,
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php
    $chatGPT = \App\Models\Utility::settings('enable_chatgpt');
    $enable_chatgpt = !empty($chatGPT);
?>
<?php $__env->startSection('action-btn'); ?>
    <!-- <div class="all-button-box row d-flex justify-content-end">
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                    <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true"  data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                        data-title="<?php echo e(__('Create New Email Template')); ?>" data-url="<?php echo e(route('email_template.create')); ?>"><i class="ti ti-plus"></i> <?php echo e(__('Add')); ?> </a>
                </div>
            </div> -->
    <div class="row">
        <!-- <div class="col-lg-6">
                        <h3 class="m-2"><?php echo e(__($emailTemplate->name)); ?></h3>
                    </div> -->
        <!-- <div class="col-lg-6"> -->
        <div class="text-end">
            <div class="d-flex justify-content-end drp-languages">
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                            
                            <span
                                class="drp-text hide-mob text-primary"><?php echo e(ucFirst(isset($LangName->fullName) ? $LangName->fullName : 'English')); ?></span>
                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('manage.email.language', [$emailTemplate->id, $code])); ?>"
                                    class="dropdown-item <?php echo e($currEmailTempLang->lang == $lang ? 'text-primary' : ''); ?>"><?php echo e(Str::upper($lang)); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                            <span
                                class="drp-text hide-mob text-primary"><?php echo e(__('Template: ')); ?><?php echo e($emailTemplate->name); ?></span>
                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                            <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('manage.email.language', [$EmailTemplate->id, Request::segment(3) ? Request::segment(3) : \Auth::user()->lang])); ?>"
                                    class="dropdown-item <?php echo e($emailTemplate->name == $EmailTemplate->name ? 'text-primary' : ''); ?>"><?php echo e($EmailTemplate->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </li>

                </ul>
                <?php if($enable_chatgpt): ?>
                    <div class="mb-0 m-2">
                        <a href="#" data-size="md" class="btn btn-primary" data-ajax-popup-over="true"
                            data-url="<?php echo e(route('generate', ['email template'])); ?>" data-bs-placement="top"
                            data-title="<?php echo e(__('Generate content with AI')); ?>">
                            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- </div> -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                    
                    <?php echo e(Form::model($currEmailTempLang, ['route' => ['email_template.update', $currEmailTempLang->parent_id], 'method' => 'PUT'])); ?>

                    <h5><?php echo e(__('Placeholders')); ?></h5>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header card-body">
                                <!-- <h5></h5> -->
                                <div class="row text-xs">
                                    <?php if($emailTemplate->slug == 'new_bill_payment'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Bill Payment Create')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Name')); ?> : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Bill')); ?> : <span
                                                    class="pull-right text-primary">{payment_bill}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Amount')); ?> : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Date')); ?> : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Method')); ?> : <span
                                                    class="pull-right text-primary">{payment_method}</span></p>


                                        </div>
                                    <?php elseif($emailTemplate->slug == 'customer_invoice_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Customer Invoice Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Name')); ?> : <span
                                                    class="pull-right text-primary">{invoice_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Number')); ?> : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Url')); ?> : <span
                                                    class="pull-right text-primary">{invoice_url}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'bill_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Bill Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Name')); ?> : <span
                                                    class="pull-right text-primary">{bill_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Number')); ?> : <span
                                                    class="pull-right text-primary">{bill_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Url')); ?> : <span
                                                    class="pull-right text-primary">{bill_url}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'new_invoice_payment'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Invoice payment Create')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Name')); ?> : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Amount')); ?> : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Number')); ?> : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Date')); ?> : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment DueAmount')); ?> : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'invoice_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Invoice Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Name')); ?> : <span
                                                    class="pull-right text-primary">{invoice_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Number')); ?> : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Url')); ?> : <span
                                                    class="pull-right text-primary">{invoice_url}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'payment_reminder'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Payment Reminder')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Name')); ?> : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Invoice Number')); ?> : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Due Amount')); ?> : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Date')); ?> : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'proposal_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Proposal Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Proposal Name')); ?> : <span
                                                    class="pull-right text-primary">{proposal_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Proposal Number')); ?> : <span
                                                    class="pull-right text-primary">{proposal_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Proposal Url')); ?> : <span
                                                    class="pull-right text-primary">{proposal_url}</span></p>
                                        </div>
                                    <?php elseif($emailTemplate->slug == 'user_created'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Create User')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Email')); ?> : <span
                                                    class="pull-right text-primary">{email}</span></p>
                                            <p class="col-4"><?php echo e(__('Password')); ?> : <span
                                                    class="pull-right text-primary">{password}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'vendor_bill_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Vendor Bill Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Name')); ?> : <span
                                                    class="pull-right text-primary">{bill_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Number')); ?> : <span
                                                    class="pull-right text-primary">{bill_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Bill Url')); ?> : <span
                                                    class="pull-right text-primary">{bill_url}</span></p>

                                        </div>
                                    <?php elseif($emailTemplate->slug == 'new_contract'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Create User')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract Customer')); ?> : <span
                                                    class="pull-right text-primary">{contract_customer}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract Subject')); ?> : <span
                                                    class="pull-right text-primary">{contract_subject}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract Start_Date')); ?> : <span
                                                    class="pull-right text-primary">{contract_start_date}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract End_Date')); ?> : <span
                                                    class="pull-right text-primary">{contract_end_date}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract Type')); ?> : <span
                                                    class="pull-right text-primary">{contract_type}</span></p>
                                            <p class="col-4"><?php echo e(__('Contract Value')); ?> : <span
                                                    class="pull-right text-primary">{contract_value}</span></p>
                                        </div>
                                    <?php elseif($emailTemplate->slug == 'retainer_sent' || $emailTemplate->slug == 'customer_retainer_sent'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Proposal Send')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Retainer Name')); ?> : <span
                                                    class="pull-right text-primary">{retainer_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Retainer Number')); ?> : <span
                                                    class="pull-right text-primary">{retainer_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Retainer Url')); ?> : <span
                                                    class="pull-right text-primary">{retainer_url}</span></p>
                                        </div>
                                    <?php elseif($emailTemplate->slug == 'new_retainer_payment'): ?>
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3"><?php echo e(__('Invoice payment Create')); ?></h6> -->
                                            <p class="col-4"><?php echo e(__('App Name')); ?> : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4"><?php echo e(__('App Url')); ?> : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Name')); ?> : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Amount')); ?> : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4"><?php echo e(__('Retainer Number')); ?> : <span
                                                    class="pull-right text-primary">{retainer_number}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment Date')); ?> : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4"><?php echo e(__('Payment DueAmount')); ?> : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>

                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-6">
                            <?php echo e(Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark'])); ?>

                            <?php echo e(Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required'])); ?>

                        </div>
                        <div class="form-group col-md-6">
                            <?php echo e(Form::label('from', __('From'), ['class' => 'col-form-label text-dark'])); ?>

                            <?php echo e(Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required'])); ?>

                        </div>
                        <div class="form-group col-12">
                            <?php echo e(Form::label('content', __('Email Message'), ['class' => 'form-label text-dark'])); ?>

                            <?php echo e(Form::textarea('content', $currEmailTempLang->content, ['class' => 'summernote-simple', 'required' => 'required'])); ?>

                        </div>
                    </div>


                    <hr>
                    <div class="col-md-12 text-end">
                        <?php echo e(Form::hidden('lang', null)); ?>

                        <input type="submit" value="<?php echo e(__('Save')); ?>"
                            class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>

                    <?php echo e(Form::close()); ?>




                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/13a/1/1d6570cbc2/accountants/resources/views/email_templates/show.blade.php ENDPATH**/ ?>