<?php
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
?>

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
                <h4 class="page-title">User Management</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
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
        <!-- Modal for Changing Password -->
        <!-- ============================================================== -->
        <div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 500px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=form_open('subdivision/change_password');?>
                        <?=form_hidden('user_id', html_escape($data['user'][0]->user_id)); ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Current Password:</td>
                                    <td><?=form_password('password', '', ['class' => 'form-control', 'required' => 'required']); ?>
                                        <?=form_error('password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Password:</td>
                                    <td><?=form_password('new_password', '', ['class' => 'form-control', 'required' => 'required']); ?>
                                        <?=form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Confirm Password:</td>
                                    <td><?=form_password('cnf_password', '', ['class' => 'form-control', 'required' => 'required']); ?>
                                        <?=form_error('cnf_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="text-right">
                                    <td colspan="2">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <?=form_submit('submit', 'Change Password', ['class' => 'btn btn-warning']);?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal (Change Pass) -->
        <!-- ============================================================== -->
        <!-- Modal for Resetting Password -->
        <!-- ============================================================== -->
        <div class="modal fade" id="resetPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 500px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=form_open('subdivision/reset_password');?>
                        <input type="hidden" name="user_id" id="reset_user_id" value="">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>New Password:</td>
                                    <td><?=form_password('new_password', '', ['class' => 'form-control', 'required' => 'required']); ?>
                                        <?=form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Confirm Password:</td>
                                    <td><?=form_password('cnf_password', '', ['class' => 'form-control', 'required' => 'required']); ?>
                                        <?=form_error('cnf_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="text-right">
                                    <td colspan="2">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <?=form_submit('submit', 'Reset Password', ['class' => 'btn btn-warning']);?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal (Reset Pass) -->
        <!-- ============================================================== -->
        <!-- Modal for Adding/Editing User -->
        <!-- ============================================================== -->
        <div class="modal fade" id="addEditUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 500px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=form_open('subdivision/add_edit_user');?>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Section:</td>
                                    <td>
                                        <select name="section_id" id="section_id" class="form-control" required="required">
                                            <option>-- Select Section --</option>
                                            <?php foreach ($data['sections'] as $sec): ?>
                                                <option value="<?=html_escape($sec->section_id)?>"><?=html_escape($sec->section)?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PEN:</td>
                                    <td><?=form_input('name', '', ['id' => 'name', 'class' => 'form-control', 'required' => 'required']);?>
                                        <?=form_error('name', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Designation:</td>
                                    <td>
                                        <select name="designation_id" id="designation_id" class="form-control" required="required">
                                            <option>-- Select Designation --</option>
                                            <?php foreach ($data['designation'] as $dgn): ?>
                                                <option value="<?=html_escape($dgn->designation_id)?>"><?=html_escape($dgn->designation)?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fullname:</td>
                                    <td><?=form_input('fullname', '', ['id' => 'fullname', 'class' => 'form-control', 'required' => 'required']);?>
                                        <?=form_error('fullname', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email ID:</td>
                                    <td><?=form_input('email_id', '', ['id' => 'email_id', 'class' => 'form-control']);?>
                                        <?=form_error('email_id', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phone No.:</td>
                                    <td><?=form_input('phone_no', '', ['id' => 'phone_no', 'class' => 'form-control']);?>
                                        <?=form_error('phone_no', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="text-right">
                                    <td colspan="2">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <?=form_submit('submit', 'Save', ['class' => 'btn btn-primary']);?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal (add/edit user) -->
        <!-- ============================================================== -->
        <!-- User Information & Higher Authorities -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- SELF PROFILE -->
            <div class="card col-md-6">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="card-title">User Information</h4>
                            <?php if($message): ?>
                            <div class="alert <?=html_escape($messageClass)?>" role="alert"><?=html_escape($message)?></div>
                            <?php endif;?>
                        </div>
                        <div class="col-lg-2">
                            <?=anchor('#', 'Change Password', ['class'=>'btn btn-warning text-white', "data-toggle"=>"modal", "data-target"=>"#passModal"]); ?>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>PEN:</td>
                                <td class="text-success"><?=html_escape($data['user'][0]->name)?></td>
                            </tr>
                            <tr>
                                <td>Fullname:</td>
                                <td class="text-success"><?=html_escape($data['user'][0]->fullname)?></td>
                            </tr>
                            <tr>
                                <td>Designation:</td>
                                <td class="text-success"><?=html_escape($data['user'][0]->designation)?></td>
                            </tr>
                            <tr>
                                <td>Email ID:</td>
                                <td class="text-success"><?=html_escape($data['user'][0]->email_id)?></td>
                            </tr>
                            <tr>
                                <td>Phone No.:</td>
                                <td class="text-success"><?=html_escape($data['user'][0]->phone_no)?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- HIGHER AUTHORITIES -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <h5 class="card-title">Higher Authorities</h5>
                            </div>
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Kerala Chief Engineer:</td>
                                    <td class="text-success"><?=html_escape($data['ce'][0]->designation.' '.$data['ce'][0]->fullname)?></td>
                                </tr>
                                <tr>
                                    <td><?=html_escape($data['circle'][0]->circle)?> Circle:</td>
                                    <td class="text-success"><?=html_escape($data['cirUser'][0]->designation.' '.$data['cirUser'][0]->fullname)?></td>
                                </tr>
                                <tr>
                                    <td><?=html_escape($data['division'][0]->division)?> Division:</td>
                                    <td class="text-success"><?=html_escape($data['divUser'][0]->designation.' '.$data['divUser'][0]->fullname)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Section Users List -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="card col-md-9">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title">Section Users</h4>
                        </div>
                        <div class="col-lg-2">
                            <?=anchor('#', 'Add User', ['class'=>'btn btn-warning text-white', "data-toggle"=>"modal", "data-target"=>"#addEditUser"]); ?>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="font-bold">Section</th>
                                <th>PEN</th>
                                <th>Designation</th>
                                <th>Fullname</th>
                                <th>Email ID</th>
                                <th>Phone No.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['users'] as $usr): 
                                $id = html_escape($usr['user_id']);
                                $sid = html_escape($usr['section_id']);
                                $did = html_escape($usr['designation_id']);
                            ?>
                            <tr>
                                <td><?=html_escape($usr['section']);?></td>
                                <td><?=html_escape($usr['name']);?></td>
                                <td><?=html_escape($usr['designation']);?></td>
                                <td><?=html_escape($usr['fullname']);?></td>
                                <td><?=html_escape($usr['email_id']);?></td>
                                <td><?=html_escape($usr['phone_no']);?></td>
                                <td>
                                    <?=anchor('#', 'Edit', ['class'=>'btn btn-outline-primary', "data-toggle"=>"modal", "data-target"=>"#addEditUser", "onclick"=>"fillEditForm($id, '$sid', '$usr[name]', '$did', '$usr[fullname]', '$usr[email_id]', '$usr[phone_no]')"]); ?>
                                    <?=anchor('#', 'Reset Password', ['class'=>'btn btn-outline-warning', "data-toggle"=>"modal", "data-target"=>"#resetPass", "onclick"=>"fillResetForm($id)"]); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>

<script type="text/javascript">
    function fillEditForm(id, sid, name, did, fullname, email_id, phone_no) {
        document.getElementById('user_id').value = id;
        document.getElementById('section_id').value = sid;
        document.getElementById('name').value = name;
        document.getElementById('designation_id').value = did;
        document.getElementById('fullname').value = fullname;
        document.getElementById('email_id').value = email_id;
        document.getElementById('phone_no').value = phone_no;
    }

    function fillResetForm(id) {
        document.getElementById('reset_user_id').value = id;
    }
</script>
