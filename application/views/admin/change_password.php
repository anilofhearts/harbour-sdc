<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Change Password</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url('welcome')?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="mdi mdi-lock"></i> Change Admin Password</h4>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                        <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                        <?php endif;?>

                        <?php echo form_open('admin_change_password_post', array('id' => 'changePasswordForm')); ?>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Enter your current password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-eye" id="toggleCurrentPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <?php echo form_error('current_password', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Enter new password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-eye" id="toggleNewPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long and include:
                                <ul class="mb-0 mt-1">
                                    <li>At least one uppercase letter (A-Z)</li>
                                    <li>At least one lowercase letter (a-z)</li>
                                    <li>At least one number (0-9)</li>
                                    <li>At least one special character (@$!%*?&)</li>
                                </ul>
                            </small>
                            <?php echo form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm your new password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <?php echo form_error('confirm_password', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Change Password
                            </button>
                            <a href="<?=base_url('welcome')?>" class="btn btn-secondary ml-2">
                                <i class="mdi mdi-arrow-left"></i> Cancel
                            </a>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#toggleCurrentPassword').click(function() {
        const passwordField = $('#current_password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('mdi-eye mdi-eye-off');
    });

    $('#toggleNewPassword').click(function() {
        const passwordField = $('#new_password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('mdi-eye mdi-eye-off');
    });

    $('#toggleConfirmPassword').click(function() {
        const passwordField = $('#confirm_password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('mdi-eye mdi-eye-off');
    });

    // Form validation
    $('#changePasswordForm').submit(function(e) {
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#confirm_password').val();

        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New password and confirm password do not match!');
            return false;
        }

        // Password complexity check
        if (newPassword.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }

        if (!/[A-Z]/.test(newPassword)) {
            e.preventDefault();
            alert('Password must include at least one uppercase letter!');
            return false;
        }

        if (!/[a-z]/.test(newPassword)) {
            e.preventDefault();
            alert('Password must include at least one lowercase letter!');
            return false;
        }

        if (!/[0-9]/.test(newPassword)) {
            e.preventDefault();
            alert('Password must include at least one number!');
            return false;
        }

        if (!/[@$!%*?&]/.test(newPassword)) {
            e.preventDefault();
            alert('Password must include at least one special character (@$!%*?&)!');
            return false;
        }

        return confirm('Are you sure you want to change your password? You will be logged out and need to login again.');
    });
});
</script>