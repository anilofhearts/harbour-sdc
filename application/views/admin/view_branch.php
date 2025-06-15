<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($user)) {
    foreach ($user as $data);
}
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?= site_url('update_pass') ?>" method="post">
            <!-- CSRF Protection -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
                   value="<?= $this->security->get_csrf_hash(); ?>">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Info <?php echo $name; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- User Name -->
                <div class="form-group row">
                    <label for="fname" class="col-sm-3 text-right control-label col-form-label">User Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" placeholder="First Name Here" value="<?php echo $data->name; ?>" autocomplete="username">
                        <input type="hidden" name="section_id" value="<?php echo $id; ?>">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group row">
                    <label for="password" class="col-sm-3 text-right control-label col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Update Password Here Or Leave Blank" autocomplete="new-password">
                    </div>
                </div>

                <!-- Contact No -->
                <div class="form-group row">
                    <label for="phone_no" class="col-sm-3 text-right control-label col-form-label">Contact No</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone_no" name="phone_no" 
                               placeholder="Mobile No" value="<?php echo $data->phone_no; ?>">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update changes</button>
            </div>
        </form>
    </div>
</div>
