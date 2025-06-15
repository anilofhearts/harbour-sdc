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
            <h4 class="page-title"></h4>
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
    <!-- Sales Cards  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->

    <!-- Modal (edit user) -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $class = array('class'=>'form-control'); ?>
                <div class="modal-body">
                    <?= form_open('edit_user'); ?>
                    <?= form_hidden('user_id', html_escape($data['user'][0]->user_id)); ?>
                    <table class="table">
                    <tbody>
                        <tr>
                            <td>Fullname :</td>
                            <td><?= form_input('fullname', html_escape($data['user'][0]->fullname), $class); ?>
                                <?= form_error('fullname', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Designation :</td>
                            <td>
                                <select name="designation_id" class="form-control">
                                    <option>-- Select Designation --</option>
                                    <?php foreach($data['designation'] as $dgn): ?>
                                        <option value="<?= html_escape($dgn->designation_id) ?>" <?php if($dgn->designation_id == $data['user'][0]->designation_id){echo 'selected';} ?>><?= html_escape($dgn->designation) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Email ID :</td>
                            <td><?= form_input('email_id', html_escape($data['user'][0]->email_id), $class); ?>
                                <?= form_error('email_id', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Phone No. :</td>
                            <td><?= form_input('phone_no', html_escape($data['user'][0]->phone_no), $class); ?>
                                <?= form_error('phone_no', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="text-right">
                            <td colspan="2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?= form_submit('submit', 'Save', array('class'=>'btn btn-primary')); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal (edit user) -->
    
    <!-- Modal (Change Pass) -->
    <div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $class = array('class'=>'form-control', 'required'=>'required'); ?>
                <div class="modal-body">
                    <?= form_open('change_password'); ?>
                    <?= form_hidden('user_id', html_escape($data['user'][0]->user_id)); ?>
                    <table class="table">
                    <tbody>
                        <tr>
                            <td>Current Password :</td>
                            <td><?= form_password('password', '', $class); ?>
                                <?= form_error('password', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>New Password :</td>
                            <td><?= form_password('new_password', '', $class); ?>
                                <?= form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirm Password :</td>
                            <td><?= form_password('cnf_password', '', $class); ?>
                                <?= form_error('cnf_password', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="text-right">
                            <td colspan="2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?= form_submit('submit', 'Change Password', array('class'=>'btn btn-warning')); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal (Change Pass) -->

    <div class="row">

        <div class="card col-md-6">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="card-title">User Information</h4>
                        <?php if($message): ?>
                        <div class="alert <?= html_escape($messageClass) ?>" role="alert"><?= html_escape($message) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-2">
                        <?= anchor('#', 'Change Password', array('class'=>'btn btn-warning text-white', "data-toggle"=>"modal", "data-target"=>"#passModal")); ?>
                    </div>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>PEN :</td>
                            <td class="text-success"><?= html_escape($data['user'][0]->name) ?></td>
                        </tr>
                        <tr>
                            <td>Fullname :</td>
                            <td class="text-success"><?= html_escape($data['user'][0]->fullname) ?></td>
                        </tr>
                        <tr>
                            <td>Designation :</td>
                            <td class="text-success"><?= html_escape($data['user'][0]->designation) ?></td>
                        </tr>
                        <tr>
                            <td>Email ID :</td>
                            <td class="text-success"><?= html_escape($data['user'][0]->email_id) ?></td>
                        </tr>
                        <tr>
                            <td>Phone No. :</td>
                            <td class="text-success"><?= html_escape($data['user'][0]->phone_no) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
                                <td>Kerala Chief Engineer :</td>
                                <td class="text-success"><?= html_escape($data['ce'][0]->designation.' '.$data['ce'][0]->fullname) ?></td>
                            </tr>
                            <tr>
                                <td><?= html_escape($data['circle'][0]->circle) ?> Circle :</td>
                                <td class="text-success"><?= html_escape($data['cirUser'][0]->designation.' '.$data['cirUser'][0]->fullname) ?></td>
                            </tr>
                            <tr>
                                <td><?= html_escape($data['division'][0]->division) ?> Division :</td>
                                <td class="text-success"><?= html_escape($data['divUser'][0]->designation.' '.$data['divUser'][0]->fullname) ?></td>
                            </tr>
                            <tr>
                                <td><?= html_escape($data['subdivision'][0]->subdivision) ?> Sub Division :</td>
                                <td class="text-success"><?= html_escape($data['subUser'][0]->designation.' '.$data['subUser'][0]->fullname) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->
    </div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
</div>
