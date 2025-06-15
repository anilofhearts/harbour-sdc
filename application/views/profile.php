<?php
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
?>
<!-- Bootstrap 5 Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ============================================================== -->
<!-- Page wrapper -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title"></h4>
                <div class="ms-auto text-end">
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
    <!-- Container fluid -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Modal (Change Password) -->
        <!-- ============================================================== -->
        <div class="modal fade" id="passModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 500px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                            $class = array('class'=>'form-control', 'required'=>'required');
                            $path = $data['user']->role_id.'/change_password';
                            echo form_open($path);
                            echo form_hidden('user_id', $data['user']->user_id);
                        ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Current Password:</td>
                                    <td><?= form_password('password', '', $class); ?>
                                        <?= form_error('password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Password:</td>
                                    <td><?= form_password('new_password', '', $class); ?>
                                        <?= form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Confirm Password:</td>
                                    <td><?= form_password('cnf_password', '', $class); ?>
                                        <?= form_error('cnf_password', '<p class="text-danger">', '</p>'); ?>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="text-end">
                                    <td colspan="2">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <!-- End Modal (Change Password) -->

        <!-- ============================================================== -->
        <!-- User Profile and Higher Authorities -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Self Profile -->
            <div class="card col-md-6">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="card-title">User Information</h4>
                            <?php if ($message): ?>
                                <div class="alert <?= $messageClass ?>" role="alert"><?= $message ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#passModal">
                                Change Password
                            </button>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>PEN:</td>
                                <td class="text-success"><?= $data['user']->name ?></td>
                            </tr>
                            <tr>
                                <td>Fullname:</td>
                                <td class="text-success"><?= $data['user']->fullname ?></td>
                            </tr>
                            <tr>
                                <td>Designation:</td>
                                <td class="text-success"><?= $data['user']->designation ?></td>
                            </tr>
                            <tr>
                                <td>Email ID:</td>
                                <td class="text-success"><?= $data['user']->email_id ?></td>
                            </tr>
                            <tr>
                                <td>Phone No.:</td>
                                <td class="text-success"><?= $data['user']->phone_no ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Higher Authorities -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Higher Authorities</h5>
                        <table class="table">
                            <tbody>
                                <?php if (isset($data['ce'])): ?>
                                    <tr>
                                        <td><?= $data['state']->state ?> State:</td>
                                        <td class="text-success"><?= $data['ce']->designation . ' ' . $data['ce']->fullname ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($data['cirUser'])): ?>
                                    <tr>
                                        <td><?= $data['circle']->circle ?> Circle:</td>
                                        <td class="text-success"><?= $data['cirUser']->designation . ' ' . $data['cirUser']->fullname ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($data['divUser'])): ?>
                                    <tr>
                                        <td><?= $data['division']->division ?> Division:</td>
                                        <td class="text-success"><?= $data['divUser']->designation . ' ' . $data['divUser']->fullname ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($data['subdivUser'])): ?>
                                    <tr>
                                        <td><?= $data['subdivision']->subdivision ?> Sub Division:</td>
                                        <td class="text-success"><?= $data['subdivUser']->designation . ' ' . $data['subdivUser']->fullname ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($data['sectionUser'])): ?>
                                    <tr>
                                        <td><?= $data['section']->section ?> Section:</td>
                                        <td class="text-success"><?= $data['sectionUser']->designation . ' ' . $data['sectionUser']->fullname ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid -->
    <!-- ============================================================== -->
</div>
