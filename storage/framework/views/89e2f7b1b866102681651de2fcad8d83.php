<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Settings')); ?>

<?php $__env->stopSection(); ?>
<?php
use App\Models\Utility;
$logo = \App\Models\Utility::get_file('uploads/logo/');
$logo_light = \App\Models\Utility::getValByName('company_logo_light');
$logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
$company_favicon = \App\Models\Utility::getValByName('company_favicon');
$lang = App\Models\Utility::getValByName('default_language');
$EmailTemplates = App\Models\EmailTemplate::all();
$meta_image = \App\Models\Utility::get_file('uploads/metaevent/');

$file_type = config('files_types');
$setting = App\Models\Utility::settings();
$color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
$local_storage_validation = $setting['local_storage_validation'];
$local_storage_validations = explode(',', $local_storage_validation);

$s3_storage_validation = $setting['s3_storage_validation'];
$s3_storage_validations = explode(',', $s3_storage_validation);

$wasabi_storage_validation = $setting['wasabi_storage_validation'];
$wasabi_storage_validations = explode(',', $wasabi_storage_validation);

$chatGPT = \App\Models\Utility::settings('enable_chatgpt');
$enable_chatgpt = !empty($chatGPT);

$google_recaptcha_version=['v2-checkbox' => __('v2'),'v3' => __('v3')];


?>

<?php $__env->startPush('script-page'); ?>
<script>
    $(document).on("click", '.send_email', function(e) {

        e.preventDefault();
        var title = $(this).attr('data-title');

        var size = 'md';
        var url = $(this).attr('data-url');
        if (typeof url != 'undefined') {
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $("#commonModal").modal('show');

            $.post(url, {
                _token: '<?php echo e(csrf_token()); ?>',
                mail_driver: $("#mail_driver").val(),
                mail_host: $("#mail_host").val(),
                mail_port: $("#mail_port").val(),
                mail_username: $("#mail_username").val(),
                mail_password: $("#mail_password").val(),
                mail_encryption: $("#mail_encryption").val(),
                mail_from_address: $("#mail_from_address").val(),
                mail_from_name: $("#mail_from_name").val(),
            }, function(data) {
                $('#commonModal .body').html(data);
            });
        }
    });


    $(document).on('submit', '#test_email', function(e) {
        e.preventDefault();
        $("#email_sending").show();
        var post = $(this).serialize();
        var url = $(this).attr('action');
        $.ajax({
            type: "post",
            url: url,
            data: post,
            cache: false,
            beforeSend: function() {
                $('#test_email .btn-create').attr('disabled', 'disabled');
            },
            success: function(data) {
                if (data.is_success) {
                    show_toastr('success', data.message, 'success');
                } else {
                    show_toastr('Error', data.message, 'error');
                }
                $("#email_sending").hide();
                $('#commonModal').modal('hide');
            },
            complete: function() {
                $('#test_email .btn-create').removeAttr('disabled');
            },
        });
    });
</script>

<script type="text/javascript">
    function enablecookie() {
        const element = $('#enable_cookie').is(':checked');
        $('.cookieDiv').addClass('disabledCookie');
        if (element == true) {
            $('.cookieDiv').removeClass('disabledCookie');
            $("#cookie_logging").attr('checked', true);
        } else {
            $('.cookieDiv').addClass('disabledCookie');
            $("#cookie_logging").attr('checked', false);
        }
    }
</script>

<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];
        c.addEventListener("click", function(event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }

    function check_theme(color_val) {
        $('input[value="' + color_val + '"]').prop('checked', true);
        $('a[data-value]').removeClass('active_color');
        $('a[data-value="' + color_val + '"]').addClass('active_color');
    }

    if ($('#cust-darklayout').length > 0) {
        var custthemedark = document.querySelector("#cust-darklayout");
        custthemedark.addEventListener("click", function() {
            if (custthemedark.checked) {
                document.querySelector("#style").setAttribute("href", "<?php echo e(asset('assets/css/style-dark.css')); ?>");
                $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $logo_light); ?>');

            } else {
                document.querySelector("#style").setAttribute("href", "<?php echo e(asset('assets/css/style.css')); ?>");
                $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $logo_dark); ?>');

            }
        });
    }
    if ($('#cust-theme-bg').length > 0) {
        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });
    }
</script>

<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    })
    $(".list-group-item").click(function() {
        $('.list-group-item').filter(function() {
            return this.href == id;
        }).parent().removeClass('text-primary');
    });

    function check_theme(color_val) {

        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }

    $(document).on('change', '[name=storage_setting]', function() {
        if ($(this).val() == 's3') {
            $('.s3-setting').removeClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').addClass('d-none');
        } else if ($(this).val() == 'wasabi') {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').removeClass('d-none');
            $('.local-setting').addClass('d-none');
        } else {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').removeClass('d-none');
        }
    });
</script>


<script type="text/javascript">
    $(".email-template-checkbox").click(function() {

        var chbox = $(this);
        $.ajax({
            url: chbox.attr('data-url'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: chbox.val()
            },
            type: 'post',
            success: function(response) {
                if (response.is_success) {
                    show_toastr('success', response.success, 'success');
                    // toastr('Success', response.success, 'success');
                    if (chbox.val() == 1) {
                        $('#' + chbox.attr('id')).val(0);
                    } else {
                        $('#' + chbox.attr('id')).val(1);
                    }
                } else {
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function(response) {
                response = response.responseJSON;
                if (response.is_success) {
                    show_toastr('Error', response.error, 'error');
                } else {
                    show_toastr('Error', response, 'error');
                }
            }
        })
    });
</script>
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>

<script>
    $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function() {
        var template = $("select[name='invoice_template']").val();
        var color = $("input[name='invoice_color']:checked").val();
        $('#invoice_frame').attr('src', '<?php echo e(url('/invoices/preview')); ?>/' + template + '/' + color);
    });

    $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function() {
        var template = $("select[name='proposal_template']").val();
        var color = $("input[name='proposal_color']:checked").val();
        $('#proposal_frame').attr('src', '<?php echo e(url('/proposal/preview')); ?>/' + template + '/' + color);
    });

    $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function() {
        var template = $("select[name='bill_template']").val();
        var color = $("input[name='bill_color']:checked").val();
        $('#bill_frame').attr('src', '<?php echo e(url('/bill/preview')); ?>/' + template + '/' + color);
    });

    $(document).on("change", "select[name='retainer_template'], input[name='retainer_color']", function() {
        var template = $("select[name='retainer_template']").val();
        var color = $("input[name='retainer_color']:checked").val();
        $('#retainer_frame').attr('src', '<?php echo e(url('/retainer/preview')); ?>/' + template + '/' + color);
    });
</script>

<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300,
    })
    $(".list-group-item").click(function() {
        $('.list-group-item').filter(function() {
            return this.href == id;
        }).parent().removeClass('text-primary');
    });

    function check_theme(color_val) {
        // alert('fgft');
        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }
</script>





<script type="text/javascript">
    $(document).ready(function() {
        var checkBox = document.getElementById('tax_number');
        // Check if the element is selected/checked
        if (checkBox.checked) {
            $('#tax_checkbox_id').removeClass('d-none');
        } else {
            $('#tax_checkbox_id').addClass('d-none');
        }
        $(document).on('change', '#tax_number', function() {

            if ($(this).is(':checked') == true) {
                $('#tax_checkbox_id').removeClass('d-none');
            } else {
                $('#tax_checkbox_id').addClass('d-none');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__('Settings')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style>
    .list-group-item.active {
        border: none !important;
    }
</style>
<div class="row mt-3">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1" class="list-group-item list-group-item-action border-0"><?php echo e(__('Brand Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-2" class="list-group-item list-group-item-action border-0"><?php echo e(__('System Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-3" class="list-group-item list-group-item-action border-0"><?php echo e(__('Company Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-4" class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-5" class="list-group-item list-group-item-action border-0"><?php echo e(__('Proposal Print Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-12" class="list-group-item list-group-item-action border-0"><?php echo e(__('Retainer Print Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-6" class="list-group-item list-group-item-action border-0"><?php echo e(__('Invoice Print Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-7" class="list-group-item list-group-item-action border-0"><?php echo e(__('Bill Print Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-8" class="list-group-item list-group-item-action border-0"><?php echo e(__('Payment Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-9" class="list-group-item list-group-item-action border-0"><?php echo e(__('Twilio Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-10" class="list-group-item list-group-item-action border-0"><?php echo e(__('ReCaptcha Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-11" class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Notification Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-13" class="list-group-item list-group-item-action border-0"><?php echo e(__('Storage Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-14" class="list-group-item list-group-item-action border-0"><?php echo e(__('SEO Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-15" class="list-group-item list-group-item-action border-0"><?php echo e(__('Webhook Settings')); ?>

                            <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-16" class="list-group-item list-group-item-action border-0"><?php echo e(__('Cookie Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-17" class="list-group-item list-group-item-action border-0"><?php echo e(__('Cache Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-18" class="list-group-item list-group-item-action border-0"><?php echo e(__('Chat GPT Key Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-9">

                <!--Business Setting-->
                <div id="useradd-1" class="card">

                    <?php echo e(Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-header">
                        <h5><?php echo e(__('Brand Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your brand details')); ?></small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Logo dark')); ?></h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4">
                                                <a href="<?php echo e($logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?' . time()); ?>" target="_blank">
                                                    <img id="blah" alt="your image" src="<?php echo e($logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?' . time()); ?>" width="150px" class="big-logo">
                                                </a>

                                                <!-- <img src="<?php echo e($logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png')); ?>"
                                                                                                             class="big-logo"> -->
                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="company_logo">
                                                    <div class=" bg-primary company_logo_update m-auto"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file" name="company_logo_dark" id="company_logo" class="form-control file" data-filename="company_logo_update" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">


                                                    <!-- <input type="file" name="company_logo_dark" id="company_logo" class="form-control file" data-filename="company_logo_update"> -->
                                                </label>
                                            </div>
                                            <?php $__errorArgs = ['company_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Logo Light')); ?></h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4">
                                                <a href="<?php echo e($logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?' . time()); ?>" target="_blank">
                                                    <img id="blah1" alt="your image" src="<?php echo e($logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?' . time()); ?>" width="150px" class="big-logo img_setting">
                                                </a>


                                                <!-- <img src="<?php echo e($logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png')); ?>"
                                                                                                             class="big-logo img_setting"> -->
                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="company_logo_light">
                                                    <div class=" bg-primary dark_logo_update m-auto"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file" name="company_logo_light" id="company_logo_light" class="form-control file" data-filename="dark_logo_update" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                    <!-- <input type="file" class="form-control file" name="company_logo_light" id="company_logo_light"
                                                                                                                   data-filename="dark_logo_update"> -->
                                                </label>
                                            </div>
                                            <?php $__errorArgs = ['company_logo_light'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Favicon')); ?></h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4">
                                                <a href="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time()); ?>" target="_blank">
                                                    <img id="blah2" alt="your image" src="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time()); ?>" width="50px" class="big-logo img_setting">
                                                </a>

                                                <!-- <img src="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png')); ?>" width="50px"
                                                                                                             class="big-logo img_setting"> -->
                                            </div>
                                            <div class="choose-files mt-4">
                                                <label for="company_favicon">
                                                    <div class="bg-primary company_favicon_update m-auto"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file" name="company_favicon" id="company_favicon" class="form-control file" data-filename="company_favicon_update" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">


                                                    <!-- <input type="file" class="form-control file"  id="company_favicon" name="company_favicon"
                                                                                                                   data-filename="company_favicon_update"> -->
                                                </label>
                                            </div>
                                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('title_text', __('Title Text'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Text')])); ?>

                                        <?php $__errorArgs = ['title_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-title_text" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('footer_text', __('Footer Text'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')])); ?>

                                        <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-footer_text" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('default_language', __('Default Language'), ['class' => 'form-label'])); ?>

                                        <div class="changeLanguage">

                                            <select name="default_language" id="default_language" class="form-control select">
                                                <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php if($lang==$code): ?> selected <?php endif; ?> value="<?php echo e($code); ?>">
                                                    <?php echo e(Str::upper($language)); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <?php $__errorArgs = ['default_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-default_language" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-4 my-auto">
                                            <div class="form-group">
                                                <label class="text-dark mb-1 mt-3" for="SITE_RTL"><?php echo e(__('Enable RTL')); ?></label>
                                                <div class="">
                                                    <input type="checkbox" name="SITE_RTL" id="SITE_RTL" data-toggle="switchbutton" <?php echo e($settings['SITE_RTL'] == 'on' ? 'checked="checked"' : ''); ?> data-onstyle="primary">
                                                    <label class="form-check-labe" for="SITE_RTL"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 my-auto">
                                            <div class="form-group ">
                                                <label class="text-dark mb-1 mt-3" for="display_landing_page"><?php echo e(__('Enable Landing Page')); ?></label>
                                                <div class="">
                                                    <input type="checkbox" name="display_landing_page" class="form-check-input gdpr_fulltime gdpr_type" id="display_landing_page" data-toggle="switchbutton" <?php echo e(Utility::getValByName('display_landing_page') == 'on' ? 'checked' : ''); ?> data-onstyle="primary">
                                                    <label class="form-check-labe" for="display_landing_page"></label>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <h4 class="small-title"><?php echo e(__('Theme Customizer')); ?></h4>
                            <div class="setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="credit-card" class="me-2"></i><?php echo e(__('Primary color settings')); ?>

                                        </h6>

                                        <hr class="my-2" />
                                        <div class="color-wrp">
                                            <div class="theme-color themes-color">
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-1' ? 'active_color' : ''); ?>" data-value="theme-1"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-1" <?php echo e($color == 'theme-1' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-2' ? 'active_color' : ''); ?>" data-value="theme-2"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-2" <?php echo e($color == 'theme-2' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-3' ? 'active_color' : ''); ?>" data-value="theme-3"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-3" <?php echo e($color == 'theme-3' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-4' ? 'active_color' : ''); ?>" data-value="theme-4"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-4" <?php echo e($color == 'theme-4' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-5' ? 'active_color' : ''); ?>" data-value="theme-5"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-5" <?php echo e($color == 'theme-5' ? 'checked' : ''); ?>>
                                                <br>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-6' ? 'active_color' : ''); ?>" data-value="theme-6"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-6" <?php echo e($color == 'theme-6' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-7' ? 'active_color' : ''); ?>" data-value="theme-7"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-7" <?php echo e($color == 'theme-7' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-8' ? 'active_color' : ''); ?>" data-value="theme-8"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-8" <?php echo e($color == 'theme-8' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-9' ? 'active_color' : ''); ?>" data-value="theme-9"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-9" <?php echo e($color == 'theme-9' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-10' ? 'active_color' : ''); ?>" data-value="theme-10"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-10" <?php echo e($color == 'theme-10' ? 'checked' : ''); ?>>
                                            </div>
                                            <div class="color-picker-wrp">
                                                <input type="color" value="<?php echo e($color ? $color : ''); ?>" class="colorPicker <?php echo e(isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'active_color' : ''); ?> image-input" name="custom_color" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Select Your Own Brand Color')); ?>" id="color-picker">
                                                <input type="hidden" name="custom-color" id="colorCode">
                                                <input type='hidden' name="color_flag" value=<?php echo e(isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'true' : 'false'); ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="layout" class="me-2"></i><?php echo e(__('Sidebar settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg" <?php echo e(!empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : ''); ?> />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg"><?php echo e(__('Transparent layout')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="sun" class="me-2"></i><?php echo e(__('Layout settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout" <?php echo e(!empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : ''); ?> />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-darklayout"><?php echo e(__('Dark Layout')); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                                </div>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
                <!--System Setting-->
                <div id="useradd-2" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('System Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your system details')); ?></small>
                    </div>

                    <?php echo e(Form::model($settings, ['route' => 'system.settings', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('site_currency', __('Currency *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('site_currency', null, ['class' => 'form-control font-style'])); ?>

                                <small> <?php echo e(__('Note: Add currency code as per three-letter ISO code.')); ?><br>
                                    <a href="https://stripe.com/docs/currencies" target="_blank"><?php echo e(__('you can find out here..')); ?></a></small> <br>
                                <?php $__errorArgs = ['site_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-site_currency" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('site_currency_symbol', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['site_currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-site_currency_symbol" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label" for="example3cols3Input"><?php echo e(__('Currency Symbol Position')); ?></label>
                                <div class="row mx-3">
                                    <div class="form-check col-md-6">
                                        <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="pre" <?php if(@$settings['site_currency_symbol_position']=='pre' ): ?> checked <?php endif; ?> id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault" checked>
                                            <?php echo e(__('Pre')); ?>

                                        </label>
                                    </div>
                                    <div class="form-check col-md-6">
                                        <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="post" <?php if(@$settings['site_currency_symbol_position']=='post' ): ?> checked <?php endif; ?> id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            <?php echo e(__('Post')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="site_date_format" class="form-label"><?php echo e(__('Date Format')); ?></label>
                                <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                    <option value="M j, Y" <?php if(@$settings['site_date_format']=='M j, Y' ): ?> selected="selected" <?php endif; ?>>Jan 1,2015</option>
                                    <option value="d-m-Y" <?php if(@$settings['site_date_format']=='d-m-Y' ): ?> selected="selected" <?php endif; ?>>dd-mm-yyyy</option>
                                    <option value="m-d-Y" <?php if(@$settings['site_date_format']=='m-d-Y' ): ?> selected="selected" <?php endif; ?>>mm-dd-yyyy</option>
                                    <option value="Y-m-d" <?php if(@$settings['site_date_format']=='Y-m-d' ): ?> selected="selected" <?php endif; ?>>yyyy-mm-dd</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_time_format" class="form-label"><?php echo e(__('Time Format')); ?></label>
                                <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                    <option value="g:i A" <?php if(@$settings['site_time_format']=='g:i A' ): ?> selected="selected" <?php endif; ?>>10:30 PM</option>
                                    <option value="g:i a" <?php if(@$settings['site_time_format']=='g:i a' ): ?> selected="selected" <?php endif; ?>>10:30 pm</option>
                                    <option value="H:i" <?php if(@$settings['site_time_format']=='H:i' ): ?> selected="selected" <?php endif; ?>>22:30</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('invoice_prefix', __('Invoice Prefix'), ['class' => 'form-label'])); ?>


                                <?php echo e(Form::text('invoice_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['invoice_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-invoice_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('invoice_starting_number', __('Invoice Starting Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('invoice_starting_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['invoice_starting_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-invoice_starting_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('proposal_prefix', __('Proposal Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('proposal_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['proposal_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-proposal_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('proposal_starting_number', __('Proposal Starting Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('proposal_starting_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['proposal_starting_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-proposal_starting_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('bill_prefix', __('Bill Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('bill_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['bill_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-bill_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('retainer_starting_number', __('Retainer Starting Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('retainer_starting_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['retainer_starting_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-proposal_starting_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('retainer_prefix', __('Retainer Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('retainer_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['retainer_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-bill_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('bill_starting_number', __('Bill Starting Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('bill_starting_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['bill_starting_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-bill_starting_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('customer_prefix', __('Customer Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('customer_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['customer_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-customer_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('vender_prefix', __('Vender Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('vender_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['vender_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-vender_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('footer_title', __('Invoice/Bill Footer Title'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('footer_title', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['footer_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-footer_title" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('decimal_number', __('Decimal Number Format'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('decimal_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['decimal_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-decimal_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('journal_prefix', __('Journal Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('journal_prefix', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['journal_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-journal_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('shipping_display', __('Display Shipping in Proposal / Invoice / Bill'), ['class' => 'form-label'])); ?>

                                <div class=" form-switch form-switch-left">
                                    <input type="checkbox" class="form-check-input" name="shipping_display" id="email_tempalte_13" <?php echo e($settings['shipping_display'] == 'on' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="email_tempalte_13"></label>
                                </div>

                                <?php $__errorArgs = ['shipping_display'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-shipping_display" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>





                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('footer_notes', __('Invoice/Bill Footer Notes'), ['class' => 'form-label'])); ?>

                                <textarea class="summernote" name="footer_notes"><?php echo $settings['footer_notes']; ?></textarea>
                                <?php $__errorArgs = ['footer_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-footer_notes" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>


                </div>

                <!--Company Setting-->
                <div id="useradd-3" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Company Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company details')); ?></small>
                    </div>
                    <?php echo e(Form::model($settings, ['route' => 'company.settings', 'method' => 'post'])); ?>

                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_name *', __('Company Name *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_name', null, ['class' => 'form-control font-style'])); ?>

                                <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_name" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_address', __('Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_address', null, ['class' => 'form-control font-style'])); ?>

                                <?php $__errorArgs = ['company_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_address" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_city', __('City'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_city', null, ['class' => 'form-control font-style'])); ?>

                                <?php $__errorArgs = ['company_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_city" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_state', __('State'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_state', null, ['class' => 'form-control font-style'])); ?>

                                <?php $__errorArgs = ['company_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_state" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_zipcode', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['company_zipcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_zipcode" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group  col-md-6">
                                <?php echo e(Form::label('company_country', __('Country'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_country', null, ['class' => 'form-control font-style'])); ?>

                                <?php $__errorArgs = ['company_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_country" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_telephone', __('Telephone'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_telephone', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['company_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_telephone" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('registration_number', __('Company Registration Number *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('registration_number', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['registration_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-registration_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <?php echo e(Form::label('tax_number', __('Tax Number'), ['class' => 'form-chech-label'])); ?>

                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                            <input type="checkbox" class="form-check-input" name="tax_number" id="tax_number" <?php echo e($settings['tax_number'] == 'on' ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="vat_gst_number_switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6" id="tax_checkbox_id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline form-group mb-3">
                                                <input type="radio" id="customRadio8" name="tax_type" value="VAT" class="form-check-input" <?php echo e($settings['tax_type'] == 'VAT' ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="customRadio8"><?php echo e(__('VAT Number')); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline form-group mb-3">
                                                <input type="radio" id="customRadio7" name="tax_type" value="GST" class="form-check-input" <?php echo e($settings['tax_type'] == 'GST' ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="customRadio7"><?php echo e(__('GST Number')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo e(Form::text('vat_number', null, ['class' => 'form-control', 'placeholder' => __('Enter VAT / GST Number')])); ?>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>


                </div>

                <!--Email Setting-->
                <div id="useradd-4" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Email Settings')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::model($settings, ['route' => ['email.settings'], 'method' => 'post'])); ?>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_driver', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')])); ?>

                                    <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_host', __('Mail Host'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_host', null, ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')])); ?>

                                    <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_port', __('Mail Port'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_port', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')])); ?>

                                    <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_port" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_username', __('Mail Username'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_username', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')])); ?>

                                    <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_username" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_password', __('Mail Password'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_password', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')])); ?>

                                    <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_password" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_encryption', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')])); ?>

                                    <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_encryption" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_from_address', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')])); ?>

                                    <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_from_address" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('mail_from_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')])); ?>

                                    <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-mail_from_name" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="card-footer d-flex justify-content-end">
                                <div class="form-group me-2">
                                    <a href="javascript:void(0)" class="btn btn-primary send_email" data-title="<?php echo e(__('Send Test Mail')); ?>" data-url="<?php echo e(route('test.mail')); ?>">
                                        <?php echo e(__('Send Test Mail')); ?>

                                    </a>
                                </div>
                                <div class="form-group text-end">
                                    <input class="btn btn-primary" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>

                <!--Proposal Print Setting-->
                <div id="useradd-5" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Proposal Print Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company proposal details')); ?></small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <!-- <h5></h5> -->
                                    <form id="setting-form" method="post" action="<?php echo e(route('proposal.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('Proposal Print Template')); ?></label>
                                            <select class="form-control" name="proposal_template">
                                                <?php $__currentLoopData = App\Models\Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e(isset($settings['proposal_template']) && $settings['proposal_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('QR Display?')); ?></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch custom-switch-v1 mt-2">
                                                    <input type="hidden" name="proposal_qr_display" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" id="customswitchv1-1 proposal_qr_display" name="proposal_qr_display" <?php echo e(isset($settings['proposal_qr_display']) && $settings['proposal_qr_display'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = App\Models\Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="proposal_color" type="radio" value="<?php echo e($color); ?>" class="colorinput-input" <?php echo e(isset($settings['proposal_color']) && $settings['proposal_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color" style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Proposal Logo')); ?></label>
                                            <div class="choose-files mt-5 ">
                                                <label for="proposal_logo">
                                                    <div class=" bg-primary proposal_logo_update"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <img id="blah4" class="mt-3" src="" width="70%" />
                                                    <input type="file" class="form-control file" name="proposal_logo" id="proposal_logo" data-filename="proposal_logo_update" onchange="document.getElementById('blah4').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="proposal_logo" id="proposal_logo" data-filename="proposal_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if(isset($settings['proposal_template']) && isset($settings['proposal_color'])): ?>
                                <iframe id="proposal_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('proposal.preview', [$settings['proposal_template'], $settings['proposal_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="proposal_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('proposal.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Retainer Print Setting-->
                <div id="useradd-12" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Retainer Print Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company retainer details')); ?></small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <form id="setting-form" method="post" action="<?php echo e(route('retainer.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('Retainer Print Template')); ?></label>
                                            <select class="form-control " name="retainer_template">
                                                <?php $__currentLoopData = App\Models\Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e(isset($settings['retainer_template']) && $settings['retainer_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('QR Display?')); ?></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch custom-switch-v1 mt-2">
                                                    <input type="hidden" name="retainer_qr_display" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" id="customswitchv1-1 retainer_qr_display" name="retainer_qr_display" <?php echo e(isset($settings['retainer_qr_display']) && $settings['retainer_qr_display'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = App\Models\Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="retainer_color" type="radio" value="<?php echo e($color); ?>" class="colorinput-input" <?php echo e(isset($settings['retainer_color']) && $settings['retainer_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color" style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Retainer Logo')); ?></label>
                                            <div class="choose-files mt-5 ">
                                                <label for="retainer_logo">
                                                    <div class=" bg-primary retainer_logo_update"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <img id="blah5" class="mt-3" src="" width="70%" />
                                                    <input type="file" class="form-control file" name="retainer_logo" id="retainer_logo" data-filename="retainer_logo_update" onchange="document.getElementById('blah5').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="retainer_logo" id="retainer_logo" data-filename="retainer_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if(isset($settings['retainer_template']) && isset($settings['retainer_color'])): ?>
                                <iframe id="retainer_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('retainer.preview', [$settings['retainer_template'], $settings['retainer_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="retainer_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('retainer.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Invoice Setting-->
                <div id="useradd-6" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Invoice Print Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company invoice details')); ?></small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <!-- <h5></h5> -->
                                    <form id="setting-form" method="post" action="<?php echo e(route('invoice.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('Invoice Template')); ?></label>
                                            <select class="form-control" name="invoice_template">
                                                <?php $__currentLoopData = Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e(isset($settings['invoice_template']) && $settings['invoice_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('QR Display?')); ?></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch custom-switch-v1 mt-2">
                                                    <input type="hidden" name="invoice_qr_display" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" id="customswitchv1-1 invoice_qr_display" name="invoice_qr_display" <?php echo e(isset($settings['invoice_qr_display']) && $settings['invoice_qr_display'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="invoice_color" type="radio" value="<?php echo e($color); ?>" class="colorinput-input" <?php echo e(isset($settings['invoice_color']) && $settings['invoice_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color" style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Invoice Logo')); ?></label>
                                            <div class="choose-files mt-5 ">
                                                <label for="invoice_logo">
                                                    <div class=" bg-primary invoice_logo_update"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <img id="blah6" class="mt-3" src="" width="70%" />
                                                    <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo" data-filename="invoice_logo_update" onchange="document.getElementById('blah6').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo" data-filename="invoice_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if(isset($settings['invoice_template']) && isset($settings['invoice_color'])): ?>
                                <iframe id="invoice_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('invoice.preview', [$settings['invoice_template'], $settings['invoice_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="invoice_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('invoice.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                </div>

                <!--bill Setting-->
                <div id="useradd-7" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Bill Print Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company bill details')); ?></small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <!-- <h5></h5> -->
                                    <form id="setting-form" method="post" action="<?php echo e(route('bill.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address" class="form-label"><?php echo e(__('Bill Template')); ?></label>
                                            <select class="form-control" name="bill_template">
                                                <?php $__currentLoopData = App\Models\Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>" <?php echo e(isset($settings['bill_template']) && $settings['bill_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-form-label"><?php echo e(__('QR Display?')); ?></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch custom-switch-v1 mt-2">
                                                    <input type="hidden" name="bill_qr_display" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" id="customswitchv1-1 bill_qr_display" name="bill_qr_display" <?php echo e(isset($settings['bill_qr_display']) && $settings['bill_qr_display'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="bill_color" type="radio" value="<?php echo e($color); ?>" class="colorinput-input" <?php echo e(isset($settings['bill_color']) && $settings['bill_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color" style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Bill Logo')); ?></label>
                                            <div class="choose-files mt-5 ">
                                                <label for="bill_logo">
                                                    <div class=" bg-primary bill_logo_update"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <img id="blah7" class="mt-3" src="" width="70%" />
                                                    <input type="file" class="form-control file" name="bill_logo" id="bill_logo" data-filename="bill_logo_update" onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="bill_logo" id="bill_logo" data-filename="bill_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if(isset($settings['bill_template']) && isset($settings['bill_color'])): ?>
                                <iframe id="bill_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('bill.preview', [$settings['bill_template'], $settings['bill_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="bill_frame" class="w-100 h-100" frameborder="0" src="<?php echo e(route('bill.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                </div>

                <!--Payment Setting-->
                <div class="card" id="useradd-8">
                    <div class="card-header">
                        <h5><?php echo e(__('Payment Settings')); ?></h5>
                        <small class="text-secondary font-weight-bold">
                            <?php echo e(__(' These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.')); ?>

                        </small>
                    </div>
                    <?php echo e(Form::model($settings, ['route' => 'company.payment.settings', 'method' => 'POST'])); ?>

                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="faq justify-content-center">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                <!-- Bank Transfer -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBank" aria-expanded="false" aria-controls="collapseBank">
                                                            <span class="d-flex align-items-center">

                                                                <?php echo e(__('Bank Transfer')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_bank_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_bank_enabled" id="is_bank_enabled" <?php echo e(isset($company_payment_setting['is_bank_enabled']) && $company_payment_setting['is_bank_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseBank" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="col-form-label"><?php echo e(__('Bank Details')); ?></label>
                                                                        <textarea class="form-control" rows="5" name="bank_detail"><?php echo e(!empty($company_payment_setting['bank_detail']) ? $company_payment_setting['bank_detail'] : ''); ?></textarea>
                                                                        <small><?php echo e(__('Example : Bank : Bank name </br> Account Number : 0000 0000 </br>')); ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Stripe -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Stripe')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_stripe_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_stripe_enabled" id="is_stripe_enabled" <?php echo e(isset($company_payment_setting['is_stripe_enabled']) && $company_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="input-edits">
                                                                        <div class="form-group">
                                                                            <label for="stripe_key" class="col-form-label"><?php echo e(__('Stripe Key')); ?></label>
                                                                            <input class="form-control" placeholder="<?php echo e(__('Enter Stripe Key')); ?>" name="stripe_key" type="text" value="<?php echo e(!isset($company_payment_setting['stripe_key']) || is_null($company_payment_setting['stripe_key']) ? '' : $company_payment_setting['stripe_key']); ?>" id="stripe_key">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="input-edits">
                                                                        <div class="form-group">
                                                                            <label for="stripe_secret" class="col-form-label"><?php echo e(__('Stripe Secret')); ?></label>
                                                                            <input class="form-control " placeholder="Enter Stripe Secret" name="stripe_secret" type="text" value="<?php echo e(!isset($company_payment_setting['stripe_secret']) || is_null($company_payment_setting['stripe_secret']) ? '' : $company_payment_setting['stripe_secret']); ?>" id="stripe_secret">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Paypal -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwo">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Paypal')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paypal_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_paypal_enabled" id="is_paypal_enabled" <?php echo e(isset($company_payment_setting['is_paypal_enabled']) && $company_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label" for="paypal_mode"><?php echo e(__('Paypal Mode')); ?></label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                                        <input type="radio" name="paypal_mode" value="sandbox" class="form-check-input" <?php echo e(isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="paypal_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label"><?php echo e(__('Client ID')); ?></label>
                                                                        <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['paypal_client_id']) || is_null($company_payment_setting['paypal_client_id']) ? '' : $company_payment_setting['paypal_client_id']); ?>" placeholder="<?php echo e(__('Client ID')); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paypal_secret_key']) || is_null($company_payment_setting['paypal_secret_key']) ? '' : $company_payment_setting['paypal_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Paystack -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThree">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Paystack')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="checkbox" class="form-check-input" name="is_paystack_enabled" id="is_paystack_enabled" <?php echo e(isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                        <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paystack_public_key']) || is_null($company_payment_setting['paystack_public_key']) ? '' : $company_payment_setting['paystack_public_key']); ?>" placeholder="<?php echo e(__('Public Key')); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paystack_secret_key']) || is_null($company_payment_setting['paystack_secret_key']) ? '' : $company_payment_setting['paystack_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        <div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flutterwave -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFour">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                            <span class="d-flex align-items-center"><?php echo e(__('Flutterware')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" <?php echo e(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                        <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['flutterwave_public_key']) || is_null($company_payment_setting['flutterwave_public_key']) ? '' : $company_payment_setting['flutterwave_public_key']); ?>" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['flutterwave_secret_key']) || is_null($company_payment_setting['flutterwave_secret_key']) ? '' : $company_payment_setting['flutterwave_secret_key']); ?>" placeholder="Secret Key">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Razorpay -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFive">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Razorpay')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_razorpay_enabled" id="is_razorpay_enabled" <?php echo e(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label"><?php echo e(__('Public Key')); ?></label>

                                                                        <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['razorpay_public_key']) || is_null($company_payment_setting['razorpay_public_key']) ? '' : $company_payment_setting['razorpay_public_key']); ?>" placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['razorpay_secret_key']) || is_null($company_payment_setting['razorpay_secret_key']) ? '' : $company_payment_setting['razorpay_secret_key']); ?>" placeholder="Secret Key">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Paytm -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingSix">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                            <span class="d-flex align-items-center"><?php echo e(__('Paytm')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytm_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_paytm_enabled" id="is_paytm_enabled" <?php echo e(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="paypal-label col-form-label" for="paypal_mode"><?php echo e(__('Paytm Environment')); ?></label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">

                                                                                    <input type="radio" name="paytm_mode" value="local" class="form-check-input" <?php echo e(!isset($company_payment_setting['paytm_mode']) || $company_payment_setting['paytm_mode'] == '' || $company_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : ''); ?>>

                                                                                    <?php echo e(__('Local')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="paytm_mode" value="production" class="form-check-input" <?php echo e(isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : ''); ?>>

                                                                                    <?php echo e(__('Production')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gy-4">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_public_key" class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                        <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['paytm_merchant_id']) || is_null($company_payment_setting['paytm_merchant_id']) ? '' : $company_payment_setting['paytm_merchant_id']); ?>" placeholder="Merchant ID">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_secret_key" class="col-form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                        <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paytm_merchant_key']) || is_null($company_payment_setting['paytm_merchant_key']) ? '' : $company_payment_setting['paytm_merchant_key']); ?>" placeholder="Merchant Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_industry_type" class="col-form-label"><?php echo e(__('Industry Type')); ?></label>
                                                                        <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="<?php echo e(!isset($company_payment_setting['paytm_industry_type']) || is_null($company_payment_setting['paytm_industry_type']) ? '' : $company_payment_setting['paytm_industry_type']); ?>" placeholder="Industry Type">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mercado Pago -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingseven">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                                            <span class="d-flex align-items-center"><?php echo e(__('Mercado Pago')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mercado_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_mercado_enabled" id="is_mercado_enabled" <?php echo e(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="coingate-label col-form-label" for="mercado_mode"><?php echo e(__('Mercado Mode')); ?></label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="mercado_mode" value="sandbox" class="form-check-input" <?php echo e((isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == '') || (isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                                    <?php echo e(__('Sandbox')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="mercado_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                    <?php echo e(__('Live')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mercado_access_token" class="col-form-label"><?php echo e(__('Access Token')); ?></label>
                                                                    <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="<?php echo e(isset($company_payment_setting['mercado_access_token']) ? $company_payment_setting['mercado_access_token'] : ''); ?>" placeholder="<?php echo e(__('Access Token')); ?>" />
                                                                    <?php if($errors->has('mercado_secret_key')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('mercado_access_token')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mollie -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingeight">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Mollie')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mollie_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_mollie_enabled" id="is_mollie_enabled" <?php echo e(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseeight" class="accordion-collapse collapse" aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label"><?php echo e(__('Mollie Api Key')); ?></label>
                                                                        <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['mollie_api_key']) || is_null($company_payment_setting['mollie_api_key']) ? '' : $company_payment_setting['mollie_api_key']); ?>" placeholder="Mollie Api Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_profile_id" class="col-form-label"><?php echo e(__('Mollie Profile ID')); ?></label>
                                                                        <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['mollie_profile_id']) || is_null($company_payment_setting['mollie_profile_id']) ? '' : $company_payment_setting['mollie_profile_id']); ?>" placeholder="Mollie Profile Id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mollie_partner_id" class="col-form-label"><?php echo e(__('Mollie Partner ID')); ?></label>
                                                                        <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['mollie_partner_id']) || is_null($company_payment_setting['mollie_partner_id']) ? '' : $company_payment_setting['mollie_partner_id']); ?>" placeholder="Mollie Partner Id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Skrill -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingnine">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsenine" aria-expanded="false" aria-controls="collapsenine">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Skrill')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_skrill_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_skrill_enabled" id="is_skrill_enabled" <?php echo e(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapsenine" class="accordion-collapse collapse" aria-labelledby="headingnine" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key" class="col-form-label"><?php echo e(__('Skrill Email')); ?></label>
                                                                        <input type="text" name="skrill_email" id="skrill_email" class="form-control" value="<?php echo e(!isset($company_payment_setting['skrill_email']) || is_null($company_payment_setting['skrill_email']) ? '' : $company_payment_setting['skrill_email']); ?>" placeholder="Enter Skrill Email">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- CoinGate -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingten">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseten" aria-expanded="false" aria-controls="collapseten">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('CoinGate')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_coingate_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_coingate_enabled" id="is_coingate_enabled" <?php echo e(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseten" class="accordion-collapse collapse" aria-labelledby="headingten" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-12">
                                                                    <label class="col-form-label" for="coingate_mode"><?php echo e(__('CoinGate Mode')); ?></label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">

                                                                                        <input type="radio" name="coingate_mode" value="sandbox" class="form-check-input" <?php echo e(!isset($company_payment_setting['coingate_mode']) || $company_payment_setting['coingate_mode'] == '' || $company_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="coingate_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="coingate_auth_token" class="col-form-label"><?php echo e(__('CoinGate Auth Token')); ?></label>
                                                                        <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="<?php echo e(!isset($company_payment_setting['coingate_auth_token']) || is_null($company_payment_setting['coingate_auth_token']) ? '' : $company_payment_setting['coingate_auth_token']); ?>" placeholder="CoinGate Auth Token">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PaymentWall -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingeleven">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeleven" aria-expanded="false" aria-controls="collapseeleven">
                                                            <span class="d-flex align-items-center"><?php echo e(__('PaymentWall')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" <?php echo e(isset($company_payment_setting['is_paymentwall_enabled']) && $company_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseeleven" class="accordion-collapse collapse" aria-labelledby="headingeleven" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_public_key" class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                        <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paymentwall_public_key']) || is_null($company_payment_setting['paymentwall_public_key']) ? '' : $company_payment_setting['paymentwall_public_key']); ?>" placeholder="<?php echo e(__('Public Key')); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_secret_key" class="col-form-label"><?php echo e(__('Private Key')); ?></label>
                                                                        <input type="text" name="paymentwall_secret_key" id="paymentwall_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['paymentwall_secret_key']) || is_null($company_payment_setting['paymentwall_secret_key']) ? '' : $company_payment_setting['paymentwall_secret_key']); ?>" placeholder="<?php echo e(__('Private Key')); ?>">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Toyyibpay -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingtwelve">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsetwelve" aria-expanded="false" aria-controls="collapsetwelve">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('Toyyibpay')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_toyyibpay_enabled" id="is_toyyibpay_enabled" <?php echo e(isset($company_payment_setting['is_toyyibpay_enabled']) && $company_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapsetwelve" class="accordion-collapse collapse" aria-labelledby="headingtwelve" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="toyyibpay_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="toyyibpay_secret_key" id="toyyibpay_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['toyyibpay_secret_key']) || is_null($company_payment_setting['toyyibpay_secret_key']) ? '' : $company_payment_setting['toyyibpay_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="category_code" class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                                        <input type="text" name="category_code" id="category_code" class="form-control" value="<?php echo e(!isset($company_payment_setting['category_code']) || is_null($company_payment_setting['category_code']) ? '' : $company_payment_setting['category_code']); ?>" placeholder="<?php echo e(__('Category Code')); ?>">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Payfast -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne13" aria-expanded="false" aria-controls="collapseOne13">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('PayFast')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_payfast_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_payfast_enabled" id="is_payfast_enabled" <?php echo e(isset($company_payment_setting['is_payfast_enabled']) && $company_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne13" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <label class="paypal-label col-form-label" for="payfast_mode"><?php echo e(__('Payfast Mode')); ?></label>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                                        <input type="radio" name="payfast_mode" value="sandbox" class="form-check-input" <?php echo e(isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="payfast_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="paytm_public_key" class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                            <input type="text" name="payfast_merchant_id" id="payfast_merchant_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['payfast_merchant_id']) || is_null($company_payment_setting['payfast_merchant_id']) ? '' : $company_payment_setting['payfast_merchant_id']); ?>" placeholder="Merchant ID">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="paytm_secret_key" class="col-form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                            <input type="text" name="payfast_merchant_key" id="payfast_merchant_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['payfast_merchant_key']) || is_null($company_payment_setting['payfast_merchant_key']) ? '' : $company_payment_setting['payfast_merchant_key']); ?>" placeholder="Merchant Key">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="payfast_signature" class="col-form-label"><?php echo e(__('Salt Passphrase')); ?></label>
                                                                            <input type="text" name="payfast_signature" id="payfast_signature" class="form-control" value="<?php echo e(!isset($company_payment_setting['payfast_signature']) || is_null($company_payment_setting['payfast_signature']) ? '' : $company_payment_setting['payfast_signature']); ?>" placeholder="Salt passphrase">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Iyzipay -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFourteen">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                                            <span class="d-flex align-items-center">
                                                                <?php echo e(__('IyziPay')); ?>

                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_iyzipay_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_iyzipay_enabled" id="is_iyzipay_enabled" <?php echo e(isset($company_payment_setting['is_iyzipay_enabled']) && $company_payment_setting['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>

                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-12">
                                                                    <label class="paypal-label col-form-label" for="paypal_mode"><?php echo e(__('IyziPay Mode')); ?></label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                                        <input type="radio" name="iyzipay_mode" value="sandbox" class="form-check-input" <?php echo e(isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio" name="iyzipay_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : ''); ?>>

                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_private_key" class="col-form-label"><?php echo e(__('Private Key')); ?></label>
                                                                        <input type="text" name="iyzipay_private_key" id="iyzipay_private_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['iyzipay_private_key']) || is_null($company_payment_setting['iyzipay_private_key']) ? '' : $company_payment_setting['iyzipay_private_key']); ?>" placeholder="<?php echo e(__('Private key')); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                        <input type="text" name="iyzipay_secret_key" id="iyzipay_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['iyzipay_secret_key']) || is_null($company_payment_setting['iyzipay_secret_key']) ? '' : $company_payment_setting['iyzipay_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- SSPAY -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse15" aria-expanded="false" aria-controls="collapse15">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Sspay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_sspay_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_sspay_enabled" id="is_sspay_enabled" <?php echo e(isset($company_payment_setting['is_sspay_enabled']) && $company_payment_setting['is_sspay_enabled'] == 'on' ? 'checked' : ''); ?>>

                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse15" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row gy-4">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="sspay_category_code" class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                                            <input type="text" name="sspay_category_code" id="sspay_category_code" class="form-control" value="<?php echo e(!isset($company_payment_setting['sspay_category_code']) || is_null($company_payment_setting['sspay_category_code']) ? '' : $company_payment_setting['sspay_category_code']); ?>" placeholder="<?php echo e(__('Category code')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="sspay_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text" name="sspay_secret_key" id="sspay_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['sspay_secret_key']) || is_null($company_payment_setting['sspay_secret_key']) ? '' : $company_payment_setting['sspay_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Paytab -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse16" aria-expanded="false" aria-controls="collapse16">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Paytab')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                        <input type="hidden" name="is_payfast_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input" name="is_paytab_enabled" id="is_paytab_enabled" <?php echo e(isset($company_payment_setting['is_paytab_enabled']) && $company_payment_setting['is_paytab_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="custom-control-label form-label" for="is_paytab_enabled"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse16" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_profile_id" class="col-form-label"><?php echo e(__('Profile Id')); ?></label>
                                                                            <input type="text" name="paytab_profile_id" id="paytab_profile_id" class="form-control" value="<?php echo e(isset($company_payment_setting['paytab_profile_id']) ? $company_payment_setting['paytab_profile_id'] : ''); ?>" placeholder="<?php echo e(__('Profile Id')); ?>">
                                                                        </div>
                                                                        <?php if($errors->has('paytab_profile_id')): ?>
                                                                        <span class="invalid-feedback d-block">
                                                                            <?php echo e($errors->first('paytab_profile_id')); ?>

                                                                        </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_server_key" class="col-form-label"><?php echo e(__('Server Key')); ?></label>
                                                                            <input type="text" name="paytab_server_key" id="paytab_server_key" class="form-control" value="<?php echo e(isset($company_payment_setting['paytab_server_key']) ? $company_payment_setting['paytab_server_key'] : ''); ?>" placeholder="<?php echo e(__('paytab Secret')); ?>">
                                                                        </div>
                                                                        <?php if($errors->has('paytab_server_key')): ?>
                                                                        <span class="invalid-feedback d-block">
                                                                            <?php echo e($errors->first('paytab_server_key')); ?>

                                                                        </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_region" class="form-label"><?php echo e(__('Region')); ?></label>
                                                                            <input type="text" name="paytab_region" id="paytab_region" class="form-control form-control-label" value="<?php echo e(isset($company_payment_setting['paytab_region']) ? $company_payment_setting['paytab_region'] : ''); ?>" placeholder="<?php echo e(__('Region')); ?>" /><br>
                                                                            <?php if($errors->has('paytab_region')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('paytab_region')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Benefit -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse17" aria-expanded="false" aria-controls="collapse17">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Benefit')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_benefit_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_benefit_enabled" id="is_benefit_enabled" <?php echo e(isset($company_payment_setting['is_benefit_enabled']) && $company_payment_setting['is_benefit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label" for="is_benefit_enabled"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse17" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row gy-4">

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label'])); ?>

                                                                            <?php echo e(Form::text('benefit_api_key', isset($company_payment_setting['benefit_api_key']) ? $company_payment_setting['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')])); ?>

                                                                            <?php $__errorArgs = ['benefit_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                            <span class="invalid-benefit_api_key" role="alert">
                                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                            </span>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label'])); ?>

                                                                            <?php echo e(Form::text('benefit_secret_key', isset($company_payment_setting['benefit_secret_key']) ? $company_payment_setting['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')])); ?>

                                                                            <?php $__errorArgs = ['benefit_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                            <span class="invalid-benefit_secret_key" role="alert">
                                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                            </span>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Cashfree -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse18" aria-expanded="false" aria-controls="collapse18">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Cashfree')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_cashfree_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_cashfree_enabled" id="is_cashfree_enabled" <?php echo e(isset($company_payment_setting['is_cashfree_enabled']) && $company_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label" for="is_cashfree_enabled"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse18" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row gy-4">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label'])); ?>

                                                                            <?php echo e(Form::text('cashfree_api_key', isset($company_payment_setting['cashfree_api_key']) ? $company_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')])); ?>

                                                                            <?php $__errorArgs = ['cashfree_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                            <span class="invalid-cashfree_api_key" role="alert">
                                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                            </span>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label'])); ?>

                                                                            <?php echo e(Form::text('cashfree_secret_key', isset($company_payment_setting['cashfree_secret_key']) ? $company_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')])); ?>

                                                                            <?php $__errorArgs = ['cashfree_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                            <span class="invalid-cashfree_secret_key" role="alert">
                                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                            </span>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- aamarpay -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse19" aria-expanded="false" aria-controls="collapse19">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Aamarpay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_aamarpay_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_aamarpay_enabled" id="is_aamarpay_enabled" <?php echo e(isset($company_payment_setting['is_aamarpay_enabled']) && $company_payment_setting['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse19" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row pt-2">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('aamarpay_store_id', __('Store Id'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('aamarpay_store_id', isset($company_payment_setting['aamarpay_store_id']) ? $company_payment_setting['aamarpay_store_id'] : '', ['class' => 'form-control', 'placeholder' => __('Store Id')])); ?><br>
                                                                            <?php if($errors->has('aamarpay_store_id')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('aamarpay_store_id')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('aamarpay_signature_key', __('Signature Key'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('aamarpay_signature_key', isset($company_payment_setting['aamarpay_signature_key']) ? $company_payment_setting['aamarpay_signature_key'] : '', ['class' => 'form-control', 'placeholder' => __('Signature Key')])); ?><br>
                                                                            <?php if($errors->has('aamarpay_signature_key')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('aamarpay_signature_key')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('aamarpay_description', __('Description'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('aamarpay_description', isset($company_payment_setting['aamarpay_description']) ? $company_payment_setting['aamarpay_description'] : '', ['class' => 'form-control', 'placeholder' => __('Description')])); ?><br>
                                                                            <?php if($errors->has('aamarpay_description')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('aamarpay_description')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PayTR -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFourteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse20" aria-expanded="false" aria-controls="collapse20">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('PayTR')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paytr_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_paytr_enabled" id="is_paytr_enabled" <?php echo e(isset($company_payment_setting['is_paytr_enabled']) && $company_payment_setting['is_paytr_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse20" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row pt-2">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('paytr_merchant_id', isset($company_payment_setting['paytr_merchant_id']) ? $company_payment_setting['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')])); ?><br>
                                                                            <?php if($errors->has('paytr_merchant_id')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('paytr_merchant_id')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('paytr_merchant_key', isset($company_payment_setting['paytr_merchant_key']) ? $company_payment_setting['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')])); ?><br>
                                                                            <?php if($errors->has('paytr_merchant_key')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('paytr_merchant_key')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('paytr_merchant_salt', isset($company_payment_setting['paytr_merchant_salt']) ? $company_payment_setting['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')])); ?><br>
                                                                            <?php if($errors->has('paytr_merchant_salt')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('paytr_merchant_salt')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- YooKassa -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFifteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse21" aria-expanded="false" aria-controls="collapse21">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('YooKassa')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_yookassa_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_yookassa_enabled" id="is_yookassa_enabled" <?php echo e(isset($company_payment_setting['is_yookassa_enabled']) && $company_payment_setting['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse21" class="accordion-collapse collapse" aria-labelledby="headingFifteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row pt-2">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('yookassa_shop_id', __('Shop id'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('yookassa_shop_id', isset($company_payment_setting['yookassa_shop_id']) ? $company_payment_setting['yookassa_shop_id'] : '', ['class' => 'form-control', 'placeholder' => __('Shop id')])); ?><br>
                                                                            <?php if($errors->has('yookassa_shop_id')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('yookassa_shop_id')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <?php echo e(Form::label('yookassa_secret', __('Merchant Key'), ['class' => 'form-label'])); ?>

                                                                            <?php echo e(Form::text('yookassa_secret', isset($company_payment_setting['yookassa_secret']) ? $company_payment_setting['yookassa_secret'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')])); ?><br>
                                                                            <?php if($errors->has('yookassa_secret')): ?>
                                                                            <span class="invalid-feedback d-block">
                                                                                <?php echo e($errors->first('yookassa_secret')); ?>

                                                                            </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Xendit -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingSixteen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse22" aria-expanded="false" aria-controls="collapse22">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Xendit')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_xendit_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_xendit_enabled" id="is_xendit_enabled" <?php echo e(isset($company_payment_setting['is_xendit_enabled']) && $company_payment_setting['is_xendit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label" for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse22" class="accordion-collapse collapse" aria-labelledby="headingSixteen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="xendit_api" class="form-label"><?php echo e(__('API Key')); ?></label>
                                                                            <input type="text" name="xendit_api" id="xendit_api" class="form-control" value="<?php echo e(!isset($company_payment_setting['xendit_api']) || is_null($company_payment_setting['xendit_api']) ? '' : $company_payment_setting['xendit_api']); ?>" placeholder="<?php echo e(__('API Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="xendit_token" class="form-label"><?php echo e(__('Token')); ?></label>
                                                                            <input type="text" name="xendit_token" id="xendit_token" class="form-control" value="<?php echo e(!isset($company_payment_setting['xendit_token']) || is_null($company_payment_setting['xendit_token']) ? '' : $company_payment_setting['xendit_token']); ?>" placeholder="<?php echo e(__('Token')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Midtrans -->
                                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingSeventeen">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse23" aria-expanded="false" aria-controls="collapse23">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Midtrans')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_midtrans_enabled" value="off">
                                                                        <input type="checkbox" class="form-check-input input-primary" name="is_midtrans_enabled" id="is_midtrans_enabled" <?php echo e(isset($company_payment_setting['is_midtrans_enabled']) && $company_payment_setting['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label" for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse23" class="accordion-collapse collapse" aria-labelledby="headingSeventeen" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <label class="paypal-label col-form-label" for="midtrans_mode"><?php echo e(__('Midtrans Mode')); ?></label>
                                                                <br>

                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['midtrans_mode']) && $company_payment_setting['midtrans_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                                    <input type="radio" name="midtrans_mode" value="sandbox" class="form-check-input" <?php echo e(isset($company_payment_setting['midtrans_mode']) && $company_payment_setting['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>

                                                                                    <?php echo e(__('Sandbox')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="midtrans_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['midtrans_mode']) && $company_payment_setting['midtrans_mode'] == 'live' ? 'checked="checked"' : ''); ?>>

                                                                                    <?php echo e(__('Live')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="midtrans_secret" class="form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text" name="midtrans_secret" id="midtrans_secret" class="form-control" value="<?php echo e(!isset($company_payment_setting['midtrans_secret']) || is_null($company_payment_setting['midtrans_secret']) ? '' : $company_payment_setting['midtrans_secret']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-paiementpro">
                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-paiementpro" aria-expanded="true" aria-controls="collapse-paiementpro">
                                                            <span class="d-flex align-items-center"><?php echo e(__('Paiementpro')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('On/Off')); ?>:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paiementpro_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_paiementpro_enabled" id="is_paiementpro_enabled" <?php echo e(isset($company_payment_setting['is_paiementpro_enabled']) && $company_payment_setting['is_paiementpro_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                                    <label class="custom-control-label form-control-label" for="is_paiementpro_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-paiementpro" class="accordion-collapse collapse" aria-labelledby="heading-paiementpro" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paiementpro_merchant_id" class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                        <input type="text" name="paiementpro_merchant_id" id="paiementpro_merchant_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['paiementpro_merchant_id']) || is_null($company_payment_setting['paiementpro_merchant_id']) ? '' : $company_payment_setting['paiementpro_merchant_id']); ?>" placeholder="<?php echo e(__('Merchant ID')); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-nepalste">
                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-nepalste" aria-expanded="true" aria-controls="collapse-nepalste">
                                                            <span class="d-flex align-items-center"><?php echo e(__('Nepalste')); ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"><?php echo e(__('On/Off')); ?>:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_nepalste_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input" name="is_nepalste_enabled" id="is_nepalste_enabled" <?php echo e(isset($company_payment_setting['is_nepalste_enabled']) && $company_payment_setting['is_nepalste_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                                    <label class="custom-control-label form-control-label" for="is_nepalste_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-nepalste" class="accordion-collapse collapse" aria-labelledby="heading-nepalste" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12 pb-4 form-group">
                                                                    <label class="nepalste-label form-label" for="nepalste_mode"><?php echo e(__('Nepalste Mode')); ?></label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-3" style="margin-right: 15px;">
                                                                            <div class="border accordion-header p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label text-dark">
                                                                                        <input type="radio" name="nepalste_mode" value="sandbox" class="form-check-input" <?php echo e(!isset($company_payment_setting['nepalste_mode']) || $company_payment_setting['nepalste_mode'] == '' || $company_payment_setting['nepalste_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3">
                                                                            <div class="border accordion-header p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label text-dark">
                                                                                        <input type="radio" name="nepalste_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['nepalste_mode']) && $company_payment_setting['nepalste_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="nepalste_public_key" class="col-form-label"><?php echo e(__('Nepalste Public Key')); ?></label>
                                                                        <input type="text" name="nepalste_public_key" id="nepalste_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['nepalste_public_key']) || is_null($company_payment_setting['nepalste_public_key']) ? '' : $company_payment_setting['nepalste_public_key']); ?>" placeholder="<?php echo e(__('Nepalste Public Key')); ?>">
                                                                    </div>
                                                                </div>
                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-cinetpay">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-cinetpay" aria-expanded="true" aria-controls="collapse-cinetpay">
                                                    <span class="d-flex align-items-center"><?php echo e(__('Cinetpay')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('On/Off')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_cinetpay_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input" name="is_cinetpay_enabled" id="is_cinetpay_enabled" <?php echo e(isset($company_payment_setting['is_cinetpay_enabled']) && $company_payment_setting['is_cinetpay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label form-control-label" for="is_cinetpay_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse-cinetpay" class="accordion-collapse collapse" aria-labelledby="heading-cinetpay" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cinetpay_api_key" class="col-form-label"><?php echo e(__('Cinetpay Api Key')); ?></label>
                                                                <input type="text" name="cinetpay_api_key" id="cinetpay_api_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['cinetpay_api_key']) || is_null($company_payment_setting['cinetpay_api_key']) ? '' : $company_payment_setting['cinetpay_api_key']); ?>" placeholder="<?php echo e(__('Cinetpay Api Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cinetpay_site_id" class="col-form-label"><?php echo e(__('Cinetpay Site Id')); ?></label>
                                                                <input type="text" name="cinetpay_site_id" id="cinetpay_site_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['cinetpay_site_id']) || is_null($company_payment_setting['cinetpay_site_id']) ? '' : $company_payment_setting['cinetpay_site_id']); ?>" placeholder="<?php echo e(__('Cinetpay Site Id')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-fedapay">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-fedapay" aria-expanded="true" aria-controls="collapse-fedapay">
                                                    <span class="d-flex align-items-center"><?php echo e(__('Fedapay')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('On/Off')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_fedapay_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input" name="is_fedapay_enabled" id="is_fedapay_enabled" <?php echo e(isset($company_payment_setting['is_fedapay_enabled']) && $company_payment_setting['is_fedapay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label form-control-label" for="is_fedapay_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse-fedapay" class="accordion-collapse collapse" aria-labelledby="heading-fedapay" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12 pb-4 form-group">
                                                            <label class="fedapay-label form-label" for="fedapay_mode"><?php echo e(__('Fedapay Mode')); ?></label>
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="col-lg-3" style="margin-right: 15px;">
                                                                    <div class="border accordion-header p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label text-dark">
                                                                                <input type="radio" name="fedapay_mode" value="sandbox" class="form-check-input" <?php echo e(!isset($company_payment_setting['fedapay_mode']) || $company_payment_setting['fedapay_mode'] == '' || $company_payment_setting['fedapay_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Sandbox')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border accordion-header p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label text-dark">
                                                                                <input type="radio" name="fedapay_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['fedapay_mode']) && $company_payment_setting['fedapay_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Live')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fedapay_public_key" class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                <input type="text" name="fedapay_public_key" id="fedapay_public_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['fedapay_public_key']) || is_null($company_payment_setting['fedapay_public_key']) ? '' : $company_payment_setting['fedapay_public_key']); ?>" placeholder="<?php echo e(__('Public Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fedapay_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="fedapay_secret_key" id="fedapay_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['fedapay_secret_key']) || is_null($company_payment_setting['fedapay_secret_key']) ? '' : $company_payment_setting['fedapay_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-2-3">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse18" aria-expanded="true" aria-controls="collapse18">
                                                    <span class="d-flex align-items-center"><?php echo e(__('PayHere')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('On/Off')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_payhere_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input" name="is_payhere_enabled" id="is_payhere_enabled" <?php echo e(isset($company_payment_setting['is_payhere_enabled']) && $company_payment_setting['is_payhere_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label form-control-label" for="is_payhere_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse18" class="accordion-collapse collapse" aria-labelledby="heading-2-3" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12 pb-4">
                                                            <label class="payhere-label col-form-label" for="payhere_mode"><?php echo e(__('PayHere Mode')); ?></label>
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="col-lg-3" style="margin-right: 15px;">
                                                                    <div class="border accordion-header p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label text-dark">
                                                                                <input type="radio" name="payhere_mode" value="sandbox" class="form-check-input" <?php echo e(!isset($company_payment_setting['payhere_mode']) || $company_payment_setting['payhere_mode'] == '' || $company_payment_setting['payhere_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Sandbox')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border accordion-header p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label text-dark">
                                                                                <input type="radio" name="payhere_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['payhere_mode']) && $company_payment_setting['payhere_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Live')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="payhere_merchant_id" class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                <input type="text" name="payhere_merchant_id" id="payhere_merchant_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['payhere_merchant_id']) || is_null($company_payment_setting['payhere_merchant_id']) ? '' : $company_payment_setting['payhere_merchant_id']); ?>" placeholder="<?php echo e(__('Merchant ID')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="payhere_merchant_secret" class="col-form-label"><?php echo e(__('Merchant Secret')); ?></label>
                                                                <input type="text" name="payhere_merchant_secret" id="payhere_merchant_secret" class="form-control" value="<?php echo e(!isset($company_payment_setting['payhere_merchant_secret']) || is_null($company_payment_setting['payhere_merchant_secret']) ? '' : $company_payment_setting['payhere_merchant_secret']); ?>" placeholder="<?php echo e(__('Merchant Secret')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="payhere_app_id" class="col-form-label"><?php echo e(__('App ID')); ?></label>
                                                                <input type="text" name="payhere_app_id" id="payhere_app_id" class="form-control" value="<?php echo e(!isset($company_payment_setting['payhere_app_id']) || is_null($company_payment_setting['payhere_app_id']) ? '' : $company_payment_setting['payhere_app_id']); ?>" placeholder="<?php echo e(__('App ID')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="payhere_app_secret" class="col-form-label"><?php echo e(__('App Secret')); ?></label>
                                                                <input type="text" name="payhere_app_secret" id="payhere_app_secret" class="form-control" value="<?php echo e(!isset($company_payment_setting['payhere_app_secret']) || is_null($company_payment_setting['payhere_app_secret']) ? '' : $company_payment_setting['payhere_app_secret']); ?>" placeholder="<?php echo e(__('App Secret')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item card shadow-none">
                                            <h2 class="accordion-header" id="heading-2-30">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse32" aria-expanded="true" aria-controls="collapse32">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Tap')); ?>

                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('On/Off: ')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_tap_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input input-primary" name="is_tap_enabled" id="is_tap_enabled" <?php echo e(isset($company_payment_setting['is_tap_enabled']) && $company_payment_setting['is_tap_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label" for="customswitchv1-2"></label>
                                                        </div>
                                                    </div>

                                                </button>
                                            </h2>

                                            <div id="collapse32" class="accordion-collapse collapse" aria-labelledby="heading-2-30" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-check form-group">
                                                                <label for="company_tap_secret_key" class="form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="company_tap_secret_key" id="company_tap_secret_key" class="form-control" value="<?php echo e(!isset($company_payment_setting['company_tap_secret_key']) || is_null($company_payment_setting['company_tap_secret_key']) ? '' : $company_payment_setting['company_tap_secret_key']); ?>" placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-2-27">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse33" aria-expanded="true" aria-controls="collapse33">
                                            <span class="d-flex align-items-center">
                                                <?php echo e(__('AuthorizeNet')); ?>

                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?php echo e(__('On/Off:')); ?></span>
                                                <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                    <input type="hidden" name="is_authorizenet_enabled" value="off">
                                                    <input type="checkbox" class="form-check-input" name="is_authorizenet_enabled" id="is_authorizenet_enabled" <?php echo e(isset($company_payment_setting['is_authorizenet_enabled']) && $company_payment_setting['is_authorizenet_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    <label class="custom-control-label form-label" for="is_authorizenet_enabled"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse33" class="accordion-collapse collapse" aria-labelledby="heading-2-27" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="authorizenet_mode" class="col-form-label"><?php echo e(__('AuthorizeNet Mode')); ?></label>
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['authorizenet_mode']) && $company_payment_setting['authorizenet_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                        <input type="radio" name="authorizenet_mode" value="sandbox" class="form-check-input" <?php echo e((isset($company_payment_setting['authorizenet_mode']) && $company_payment_setting['authorizenet_mode'] == '') || (isset($company_payment_setting['authorizenet_mode']) && $company_payment_setting['authorizenet_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>><?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="me-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['authorizenet_mode']) && $company_payment_setting['authorizenet_mode'] == 'live' ? 'active' : ''); ?>">
                                                                        <input type="radio" name="authorizenet_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['authorizenet_mode']) && $company_payment_setting['authorizenet_mode'] == 'live' ? 'checked="checked"' : ''); ?>><?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="authorizenet_merchant_login_id" class="col-form-label"><?php echo e(__('Merchant Login ID')); ?></label>
                                                        <input class="form-control" placeholder="Enter Merchant Login ID" name="authorizenet_merchant_login_id" type="text" value="<?php echo e($company_payment_setting['authorizenet_merchant_login_id'] ?? ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="authorizenet_merchant_transaction_key" class="col-form-label"><?php echo e(__('Merchant Transaction Key')); ?></label>
                                                        <input class="form-control" placeholder="Enter Merchant Transaction Key" name="authorizenet_merchant_transaction_key" type="text" value="<?php echo e($company_payment_setting['authorizenet_merchant_transaction_key'] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-2-30">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse34" aria-expanded="true" aria-controls="collapse34">
                                            <span class="d-flex align-items-center">
                                                <?php echo e(__('Khalti')); ?>

                                            </span>

                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?php echo e(__('On/Off: ')); ?></span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_khalti_enabled" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_khalti_enabled" id="is_khalti_enabled" <?php echo e(isset($company_payment_setting['is_khalti_enabled']) && $company_payment_setting['is_khalti_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    <label class="form-check-label" for="customswitchv1-2"></label>
                                                </div>
                                            </div>

                                        </button>
                                    </h2>

                                    <div id="collapse34" class="accordion-collapse collapse" aria-labelledby="heading-2-30" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="khalti_mode" class="col-form-label"><?php echo e(__('Khalti Mode')); ?></label>
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['khalti_mode']) && $company_payment_setting['khalti_mode'] == 'sandbox' ? 'active' : ''); ?>">
                                                                        <input type="radio" name="khalti_mode" value="sandbox" class="form-check-input" <?php echo e((isset($company_payment_setting['khalti_mode']) && $company_payment_setting['khalti_mode'] == '') || (isset($company_payment_setting['khalti_mode']) && $company_payment_setting['khalti_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>><?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="me-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark <?php echo e(isset($company_payment_setting['khalti_mode']) && $company_payment_setting['khalti_mode'] == 'live' ? 'active' : ''); ?>">
                                                                        <input type="radio" name="khalti_mode" value="live" class="form-check-input" <?php echo e(isset($company_payment_setting['khalti_mode']) && $company_payment_setting['khalti_mode'] == 'live' ? 'checked="checked"' : ''); ?>><?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="khalti_secret_key" class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                        <input class="form-control" placeholder="Enter Secret Key" name="khalti_secret_key" type="text" value="<?php echo e($company_payment_setting['khalti_secret_key'] ?? ''); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="khalti_public_key" class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                        <input class="form-control" placeholder="Enter Public Key" name="khalti_public_key" type="text" value="<?php echo e($company_payment_setting['khalti_public_key'] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="form-group">
                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                    </div>
                </div>
            </div>
            </form>
        </div>
        <!--Twilio Setting-->
        <div id="useradd-9" class="card">
            <div class="card-header">
                <h5><?php echo e(__('Twilio Settings')); ?></h5>
                <small class="text-muted"><?php echo e(__('Edit your company twilio setting details')); ?></small>
            </div>

            <div class="card-body">
                <?php echo e(Form::model($settings, ['route' => 'twilio.settings', 'method' => 'post'])); ?>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo e(Form::label('twilio_sid', __('Twilio SID '), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('twilio_sid', isset($settings['twilio_sid']) ? $settings['twilio_sid'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio SID'), 'required' => 'required'])); ?>

                            <?php $__errorArgs = ['twilio_sid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-twilio_sid" role="alert">
                                <strong class="text-danger"><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo e(Form::label('twilio_token', __('Twilio Token'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('twilio_token', isset($settings['twilio_token']) ? $settings['twilio_token'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required'])); ?>

                            <?php $__errorArgs = ['twilio_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-twilio_token" role="alert">
                                <strong class="text-danger"><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo e(Form::label('twilio_from', __('Twilio From'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('twilio_from', isset($settings['twilio_from']) ? $settings['twilio_from'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio From'), 'required' => 'required'])); ?>

                            <?php $__errorArgs = ['twilio_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-twilio_from" role="alert">
                                <strong class="text-danger"><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>


                    <div class="col-md-12 mt-4 mb-2">
                        <h5 class="small-title"><?php echo e(__('Module Settings')); ?></h5>
                    </div>
                    <div class="col-md-4 mb-2">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Customer')); ?></span>
                                    <?php echo e(Form::checkbox('customer_notification', '1', isset($settings['customer_notification']) && $settings['customer_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'customer_notification'])); ?>

                                    <label class="form-check-label" for="customer_notification"></label>
                                </div>

                            </li>
                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Vendor')); ?></span>
                                    <?php echo e(Form::checkbox('vender_notification', '1', isset($settings['vender_notification']) && $settings['vender_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'vender_notification'])); ?>

                                    <label class="form-check-label" for="vender_notification"></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-2">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Invoice')); ?></span>
                                    <?php echo e(Form::checkbox('invoice_notification', '1', isset($settings['invoice_notification']) && $settings['invoice_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'invoice_notification'])); ?>

                                    <label class="form-check-label" for="invoice_notification"></label>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Revenue')); ?></span>
                                    <?php echo e(Form::checkbox('revenue_notification', '1', isset($settings['revenue_notification']) && $settings['revenue_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'revenue_notification'])); ?>

                                    <label class="form-check-label" for="revenue_notification"></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-2">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Bill')); ?></span>
                                    <?php echo e(Form::checkbox('bill_notification', '1', isset($settings['bill_notification']) && $settings['bill_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'bill_notification'])); ?>

                                    <label class="form-check-label" for="bill_notification"></label>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Proposal')); ?></span>
                                    <?php echo e(Form::checkbox('proposal_notification', '1', isset($settings['proposal_notification']) && $settings['proposal_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'proposal_notification'])); ?>

                                    <label class="form-check-label" for="proposal_notification"></label>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-4 mb-2">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('New Payment')); ?></span>
                                    <?php echo e(Form::checkbox('payment_notification', '1', isset($settings['payment_notification']) && $settings['payment_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'payment_notification'])); ?>

                                    <label class="form-check-label" for="payment_notification"></label>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class=" form-switch form-switch-right">
                                    <span><?php echo e(__('Invoice Reminder')); ?></span>
                                    <?php echo e(Form::checkbox('reminder_notification', '1', isset($settings['reminder_notification']) && $settings['reminder_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'reminder_notification'])); ?>

                                    <label class="form-check-label" for="reminder_notification"></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="form-group">
                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

            </div>

        </div>

        <!--ReCaptcha Setting-->
        <div id="useradd-10" class="card mb-3">
            <?php echo e(Form::model($settings, ['route' => 'recaptcha.settings.store', 'method' => 'post', 'accept-charset' => 'UTF-8'])); ?>

            <?php echo csrf_field(); ?>
            <div class="card-header row d-flex justify-content-between">
                <div class="col-auto">
                    <h5><?php echo e(__('ReCaptcha Settings')); ?></h5>
                    <small class="text-muted">
                        <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/" target="_blank" class="text-blue">
                            (How to Get Google reCaptcha Site and Secret key)
                        </a>
                    </small><br>
                </div>
                <div class="col-auto">
                    <div class="form-switch form-switch-right" style="width: 86.1375px; height: 41.4px;">
                        <input type="checkbox" class="form-check-input" name="recaptcha_module" data-toggle="switchbutton" id="recaptcha_module" value="yes" <?php echo e($settings['recaptcha_module'] == 'yes' ? 'checked' : ''); ?>>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group col switch-width">
                            <?php echo e(Form::label('google_recaptcha_version', __('Google Recaptcha Version'), ['class' => ' col-form-label'])); ?>


                            <?php echo e(Form::select('google_recaptcha_version', $google_recaptcha_version, isset($settings['google_recaptcha_version']) ? $settings['google_recaptcha_version'] : 'v2-checkbox', ['id' => 'google_recaptcha_version', 'class' => 'form-control choices', 'searchEnabled' => 'true'])); ?>

                        </div>
                    </div>
                    <div class="col-lg-6">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo e(Form::label('google_recaptcha_key', __('Google Recaptcha Key'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('google_recaptcha_key', null, ['class' => 'form-control', 'placeholder' => __('Enter Google Recaptcha Key')])); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo e(Form::label('google_recaptcha_secret', __('Google Recaptcha Secret Key'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::text('google_recaptcha_secret', null, ['class' => 'form-control', 'placeholder' => __('Enter Google Recaptcha Secret Key')])); ?>

                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <div class="form-group">
                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                    </div>
                </div>

            </div>
            <?php echo e(Form::close()); ?>

        </div>

        <!--Email Notification Setting-->
        <div id="useradd-11" class="card">

            <?php echo e(Form::model($settings, ['route' => ['status.email.language'], 'method' => 'post'])); ?>

            <?php echo csrf_field(); ?>
            <div class="col-md-12">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <h5><?php echo e(__('Email Notification Settings')); ?></h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- <div class=""> -->
                        <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                            <div class="list-group">
                                <div class="list-group-item form-switch form-switch-right">
                                    <label class="form-label" style="margin-left:5%;"><?php echo e($EmailTemplate->name); ?></label>

                                    <input class="form-check-input" name='<?php echo e($EmailTemplate->id); ?>' id="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>" type="checkbox" <?php if($EmailTemplate->template->is_active == 1): ?> checked="checked" <?php endif; ?>
                                    type="checkbox" value="1"
                                    data-url="<?php echo e(route('status.email.language', [$EmailTemplate->template->id])); ?>" />
                                    <label class="form-check-label" for="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>"></label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- </div> -->
                    </div>
                    <div class="card-footer p-0">
                        <div class="col-sm-12 mt-3 px-2">
                            <div class="text-end">
                                <input class="btn btn-print-invoice  btn-primary " type="submit" value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>



        <!--storage Setting-->
        <div id="useradd-13" class="card mb-3">
            <?php echo e(Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data'])); ?>

            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <h5 class=""><?php echo e(__('Storage Settings')); ?></h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="pe-2">
                        <input type="radio" class="btn-check" name="storage_setting" id="local-outlined" autocomplete="off" <?php echo e($setting['storage_setting'] == 'local' ? 'checked' : ''); ?> value="local" checked>
                        <label class="btn btn-outline-primary" for="local-outlined"><?php echo e(__('Local')); ?></label>
                    </div>
                    <div class="pe-2">
                        <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined" autocomplete="off" <?php echo e($setting['storage_setting'] == 's3' ? 'checked' : ''); ?> value="s3">
                        <label class="btn btn-outline-primary" for="s3-outlined">
                            <?php echo e(__('AWS S3')); ?></label>
                    </div>

                    <div class="pe-2">
                        <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined" autocomplete="off" <?php echo e($setting['storage_setting'] == 'wasabi' ? 'checked' : ''); ?> value="wasabi">
                        <label class="btn btn-outline-primary" for="wasabi-outlined"><?php echo e(__('Wasabi')); ?></label>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="local-setting row <?php echo e($setting['storage_setting'] == 'local' ? ' ' : 'd-none'); ?>">
                        
                        <div class="form-group col-8 switch-width">
                            <?php echo e(Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-label'])); ?>

                            <select name="local_storage_validation[]" class="select2" id="local_storage_validation" multiple>
                                <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if(in_array($f, $local_storage_validations)): ?> selected <?php endif; ?>>
                                    <?php echo e($f); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="local_storage_max_upload_size"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                <input type="number" name="local_storage_max_upload_size" class="form-control" value="<?php echo e(!isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size']); ?>" placeholder="<?php echo e(__('Max upload size')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="s3-setting row <?php echo e($setting['storage_setting'] == 's3' ? ' ' : 'd-none'); ?>">

                        <div class=" row ">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_key"><?php echo e(__('S3 Key')); ?></label>
                                    <input type="text" name="s3_key" class="form-control" value="<?php echo e(!isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key']); ?>" placeholder="<?php echo e(__('S3 Key')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_secret"><?php echo e(__('S3 Secret')); ?></label>
                                    <input type="text" name="s3_secret" class="form-control" value="<?php echo e(!isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret']); ?>" placeholder="<?php echo e(__('S3 Secret')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_region"><?php echo e(__('S3 Region')); ?></label>
                                    <input type="text" name="s3_region" class="form-control" value="<?php echo e(!isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region']); ?>" placeholder="<?php echo e(__('S3 Region')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_bucket"><?php echo e(__('S3 Bucket')); ?></label>
                                    <input type="text" name="s3_bucket" class="form-control" value="<?php echo e(!isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket']); ?>" placeholder="<?php echo e(__('S3 Bucket')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_url"><?php echo e(__('S3 URL')); ?></label>
                                    <input type="text" name="s3_url" class="form-control" value="<?php echo e(!isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url']); ?>" placeholder="<?php echo e(__('S3 URL')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_endpoint"><?php echo e(__('S3 Endpoint')); ?></label>
                                    <input type="text" name="s3_endpoint" class="form-control" value="<?php echo e(!isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint']); ?>" placeholder="<?php echo e(__('S3 Endpoint')); ?>">
                                </div>
                            </div>
                            <div class="form-group col-8 switch-width">
                                <?php echo e(Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label'])); ?>

                                <select name="s3_storage_validation[]" class="select2" id="s3_storage_validation" multiple>
                                    <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if(in_array($f, $s3_storage_validations)): ?> selected <?php endif; ?>>
                                        <?php echo e($f); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="s3_max_upload_size"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                    <input type="number" name="s3_max_upload_size" class="form-control" value="<?php echo e(!isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size']); ?>" placeholder="<?php echo e(__('Max upload size')); ?>">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="wasabi-setting row <?php echo e($setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none'); ?>">
                        <div class=" row ">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_key"><?php echo e(__('Wasabi Key')); ?></label>
                                    <input type="text" name="wasabi_key" class="form-control" value="<?php echo e(!isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key']); ?>" placeholder="<?php echo e(__('Wasabi Key')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_secret"><?php echo e(__('Wasabi Secret')); ?></label>
                                    <input type="text" name="wasabi_secret" class="form-control" value="<?php echo e(!isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret']); ?>" placeholder="<?php echo e(__('Wasabi Secret')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="s3_region"><?php echo e(__('Wasabi Region')); ?></label>
                                    <input type="text" name="wasabi_region" class="form-control" value="<?php echo e(!isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region']); ?>" placeholder="<?php echo e(__('Wasabi Region')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="wasabi_bucket"><?php echo e(__('Wasabi Bucket')); ?></label>
                                    <input type="text" name="wasabi_bucket" class="form-control" value="<?php echo e(!isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket']); ?>" placeholder="<?php echo e(__('Wasabi Bucket')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="wasabi_url"><?php echo e(__('Wasabi URL')); ?></label>
                                    <input type="text" name="wasabi_url" class="form-control" value="<?php echo e(!isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url']); ?>" placeholder="<?php echo e(__('Wasabi URL')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="wasabi_root"><?php echo e(__('Wasabi Root')); ?></label>
                                    <input type="text" name="wasabi_root" class="form-control" value="<?php echo e(!isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root']); ?>" placeholder="<?php echo e(__('Wasabi Root')); ?>">
                                </div>
                            </div>
                            <div class="form-group col-8 switch-width">
                                <?php echo e(Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label'])); ?>


                                <select name="wasabi_storage_validation[]" class="select2" id="wasabi_storage_validation" multiple>
                                    <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if(in_array($f, $wasabi_storage_validations)): ?> selected <?php endif; ?>>
                                        <?php echo e($f); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="wasabi_root"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                    <input type="number" name="wasabi_max_upload_size" class="form-control" value="<?php echo e(!isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size']); ?>" placeholder="<?php echo e(__('Max upload size')); ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                </div>
                <?php echo e(Form::close()); ?>

            </div>


        </div>

        
        <div class="card mb-3" id="useradd-14">
            <?php echo e(Form::open(['url' => route('seo.settings'), 'enctype' => 'multipart/form-data'])); ?>

            <div class="card-header">
                <h5><?php echo e(__('SEO Settings')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if($enable_chatgpt): ?>
                    <div>
                        <a href="javascript:void(0)" data-size="md" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['seo settings'])); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate content with AI')); ?>" class="btn btn-primary btn-sm float-end">
                            <i class="fas fa-robot"></i>
                            <?php echo e(__('Generate with AI')); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <?php echo e(Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label'])); ?>

                            <?php echo e(Form::text('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Keywords'])); ?>

                        </div>

                        <div class="form-group">
                            <?php echo e(Form::label('meta_description', __('Meta Description'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::textarea('meta_description', !empty($settings['meta_description']) ? $settings['meta_description'] : '', ['class' => 'form-control ', 'row' => 3, 'placeholder' => 'Enter Meta Description'])); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <?php echo e(Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label '])); ?>

                        </div>
                        <div class="setting-card">
                            <div class="logo-content">
                                <a href="<?php echo e($meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png')); ?>" target="_blank"> <img id="meta" src="<?php echo e($meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png')); ?>" width="400px" class="img_setting"> </a>
                            </div>
                            <div class="choose-files mt-4">
                                <label for="meta_image">
                                    <div class=" bg-primary logo"> <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                    </div>
                                    <input style="margin-top: -40px;" type="file" class="form-control file" name="meta_image" id="meta_image" data-filename="meta_image" onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <button class="btn-submit btn btn-primary" type="submit">
                    <?php echo e(__('Save Changes')); ?>

                </button>
            </div>
            <?php echo e(Form::close()); ?>

        </div>

        <!--Webhook Setting-->
        <div class="" id="useradd-15">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5><?php echo e(__('Web Hook Settings')); ?></h5>
                    <a href="javascript:void(0)" data-size="md" data-url="<?php echo e(route('webhook.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create New webhook')); ?>" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th> <?php echo e(__('Modules')); ?></th>
                                    <th> <?php echo e(__('url')); ?></th>
                                    <th> <?php echo e(__('Method')); ?></th>
                                    <th width="200px"> <?php echo e('Action'); ?></th>
                                </tr>
                            </thead>
                            <?php
                            $webhooks = App\Models\Webhook::where('created_by', Auth::user()->id)->get();
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $webhooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webhook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="Action">
                                    <td class="sorting_1">
                                        <?php echo e($webhook->module); ?>

                                    </td>
                                    <td class="sorting_3">
                                        <?php echo e($webhook->url); ?>

                                    </td>
                                    <td class="sorting_2">
                                        <?php echo e($webhook->method); ?>

                                    </td>
                                    <td class="">
                                        <div class="action-btn bg-info ms-2">
                                            <a class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('webhook.edit', $webhook->id)); ?>" data-size="md" data-ajax-popup="true" data-title="<?php echo e(__('Edit Webhook')); ?>" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Edit')); ?>" data-bs-placement="top" class="edit-icon" data-original-title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <?php echo Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['webhook.destroy', $webhook->id],
                                            'id' => 'delete-form-' . $webhook->id,
                                            ]); ?>

                                            <a href="#!" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Delete')); ?>" data-bs-placement="top" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e(__('Delete')); ?>">
                                                <i class="ti ti-trash text-white"></i></a>
                                            <?php echo Form::close(); ?>

                                        </div>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card" id="useradd-16">
            <?php echo e(Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post'])); ?>

            <div class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                <h5><?php echo e(__('Cookie Settings')); ?></h5>
                <div class="d-flex align-items-center">
                    <?php echo e(Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3'])); ?>

                    <div class="custom-control custom-switch" onclick="enablecookie()">
                        <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" name="enable_cookie" class="form-check-input input-primary " id="enable_cookie" <?php echo e($settings['enable_cookie'] == 'on' ? ' checked ' : ''); ?>>
                        <label class="custom-control-label mb-1" for="enable_cookie"></label>
                    </div>
                </div>
            </div>
            <div class="card-body cookieDiv <?php echo e($settings['enable_cookie'] == 'off' ? 'disabledCookie ' : ''); ?>">
                <div class="row ">
                    <?php if($enable_chatgpt): ?>
                    <div>
                        <a href="javascript:void(0)" data-size="md" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['cookie'])); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate content with AI')); ?>" class="btn btn-primary btn-sm float-end">
                            <i class="fas fa-robot"></i>
                            <?php echo e(__('Generate with AI')); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                            <input type="checkbox" name="cookie_logging" class="form-check-input input-primary cookie_setting" id="cookie_logging" onclick="enableButton()" <?php echo e($settings['cookie_logging'] == 'on' ? ' checked ' : ''); ?>>
                            <label class="form-check-label" for="cookie_logging"><?php echo e(__('Enable logging')); ?></label>
                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label'])); ?>

                            <?php echo e(Form::text('cookie_title', null, ['class' => 'form-control cookie_setting'])); ?>

                        </div>
                        <div class="form-group ">
                            <?php echo e(Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label'])); ?>

                            <?php echo Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch custom-switch-v1 ">
                            <input type="checkbox" name="necessary_cookies" class="form-check-input input-primary" id="necessary_cookies" checked onclick="return false">
                            <label class="form-check-label" for="necessary_cookies"><?php echo e(__('Strictly necessary cookies')); ?></label>
                        </div>
                        <div class="form-group ">
                            <?php echo e(Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label'])); ?>

                            <?php echo e(Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting'])); ?>

                        </div>
                        <div class="form-group ">
                            <?php echo e(Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label'])); ?>

                            <?php echo Form::textarea('strictly_cookie_description', null, [
                            'class' => 'form-control cookie_setting ',
                            'rows' => '3',
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-12">
                        <h5><?php echo e(__('More Information')); ?></h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ">
                            <?php echo e(Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label'])); ?>

                            <?php echo e(Form::text('more_information_description', null, ['class' => 'form-control cookie_setting'])); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ">
                            <?php echo e(Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label'])); ?>

                            <?php echo e(Form::text('contactus_url', null, ['class' => 'form-control cookie_setting'])); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between">
                <div>
                    <?php if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on'): ?>
                    <label for="file" class="form-label"><?php echo e(__('Download cookie accepted data')); ?></label>
                    <a href="<?php echo e(asset(Storage::url('uploads/sample')) . '/data.csv'); ?>" class="btn btn-primary mr-2 ">
                        <i class="ti ti-download"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-primary">
            </div>
            <?php echo e(Form::close()); ?>

        </div>

        <!--cache Setting-->

        <div id="useradd-17" class="card">
            <div class="card-header">
                <h5><?php echo e(__('Cache Settings')); ?></h5>
                <small class="text-secondary font-weight-bold">
                    <?php echo e(__('This is a page meant for more advanced users, simply ignore it if you don`t understand what cache is.')); ?>

                </small>
            </div>
            <div class="card-body">
                <div class="col-12 form-group">
                    <label for="Current cache size" class="col-form-label bold"><?php echo e(__('Current cache size')); ?></label>
                    <div class="input-group search-form">
                        <input type="text" value="<?php echo e(Utility::GetCacheSize()); ?>" class="form-control" readonly>
                        <span class="input-group-text bg-transparent"><?php echo e(__('MB')); ?></span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?php echo e(url('config-cache')); ?>" class="btn btn-print-invoice btn-primary m-r-10"><?php echo e(__('Clear Cache')); ?></a>
            </div>
        </div>

        <!--Chat GPT Key Setting-->
        <div id="useradd-18" class="card">
            <?php echo e(Form::model($settings, ['route' => 'settings.chatgptkey', 'method' => 'post'])); ?>

            <?php echo csrf_field(); ?>
            <div class="card-header row d-flex justify-content-between">
                <div class="col-auto">
                    <h5><?php echo e(__('Chat GPT Key Settings')); ?></h5>
                    <small><?php echo e(__('Edit your key details')); ?></small>
                </div>
                <div class="col-auto">
                    <div class="form-switch form-switch-right" style="width: 86.1375px; height: 41.4px;">
                        <input type="checkbox" class="form-check-input" name="enable_chatgpt" data-toggle="switchbutton" id="enable_chatgpt" <?php echo e($settings['enable_chatgpt'] == 'on' ? ' checked ' : ''); ?>>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="Current cache size" class="col-form-label bold"><?php echo e(__('Chat GPT Key')); ?></label>
                        <?php echo e(Form::text('chatgpt_key', isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Key Here')])); ?>

                    </div>
                    <div class="form-group col-6">
                        <label for="Current cache size" class="col-form-label bold"><?php echo e(__('Chat GPT Model name')); ?></label>
                        <?php echo e(Form::text('chatgpt_model_name', isset($settings['chatgpt_model_name']) ? $settings['chatgpt_model_name'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Model Name')])); ?>

                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit"><?php echo e(__('Save Changes')); ?></button>
            </div>
            <?php echo e(Form::close()); ?>

        </div>

        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
<link rel="stylesheet" href=" <?php echo e(Module::asset('LandingPage:Resources/assets/css/summernote/summernote-bs4.css')); ?>" />
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
<script src="<?php echo e(Module::asset('LandingPage:Resources/assets/js/plugins/summernote-bs4.js')); ?>" referrerpolicy="origin"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
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

<script>
    $('.colorPicker').on('click', function(e) {
        $('body').removeClass('custom-color');
        if (/^theme-\d+$/) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass('custom-color');
        $('.themes-color-change').removeClass('active_color');
        $(this).addClass('active_color');
        const input = document.getElementById("color-picker");
        setColor();
        input.addEventListener("input", setColor);

        function setColor() {
            $(':root').css('--color-customColor', input.value);
        }

        $(`input[name='color_flag`).val('true');
    });

    $('.themes-color-change').on('click', function() {

        $(`input[name='color_flag`).val('false');

        var color_val = $(this).data('value');
        $('body').removeClass('custom-color');
        if (/^theme-\d+$/) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass(color_val);
        $('.theme-color').prop('checked', false);
        $('.themes-color-change').removeClass('active_color');
        $('.colorPicker').removeClass('active_color');
        $(this).addClass('active_color');
        $(`input[value=${color_val}]`).prop('checked', true);
    });

    $.fn.removeClassRegex = function(regex) {
        return $(this).removeClass(function(index, classes) {
            return classes.split(/\s+/).filter(function(c) {
                return regex.test(c);
            }).join(' ');
        });
    };
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/majd/Desktop/accountants/resources/views/settings/company.blade.php ENDPATH**/ ?>