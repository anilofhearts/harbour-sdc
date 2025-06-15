<?php
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');

    $role = $data['role'];
    $name = $data['name'];

    // echo "<pre>";print_r($data); echo "</pre>";
 ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

    <!-- Modal (Reset Pass) -->
    <div class="modal fade" id="resetPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set/Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <?php $class = array('class'=>'form-control', 'required'=>'required');?>
                <div class="modal-body">
                    <?=form_open('reset_password');?>
                    <?=form_hidden('role', $role);?>
                    <?=form_hidden('name', $name); ?>
                    <input type="hidden" name="user_id" id="reset_user_id" value="">
                    <table class="table">
                    <tbody>
                        <tr>
                            <td>New Password : </td>
                            <td><?=form_password('new_password', '', $class); ?>
                                <?=form_error('new_password', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirm Password : </td>
                            <td><?=form_password('cnf_password', '', $class); ?>
                                <?=form_error('cnf_password', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="text-right">
                            <td colspan="2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?=form_submit('submit', 'Reset Password', array('class'=>'btn btn-warning'));?>
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
    <!-- Modal (add/edit user) -->
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
                    <?php echo form_open('add_edit_user');
                        echo form_hidden('section_id', $data['users'][0]->section_id);
                        echo form_hidden('role', $role);
                        echo form_hidden('name', $name); ?>
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <table class="table">
                    <tbody>
                        <!--<tr>
                            <td>Division : </td>
                            <td><select name="section_id" id="section_id" class="form-control" required="required">
                                <option>-- Select Division --</option>
                                <?php foreach($data['divisions'] as $div): ?>
                                    <option value="<?=$div->division_id?>" selected=""><?=$div->division?></option>
                                <?php endforeach; ?>
                            </select> </td>
                        </tr>-->
                        <tr>
                            <td>PEN : </td>
                            <td><?=form_input('pen', '', array('id'=>'pen', 'class'=>'form-control', 'required'=>"required"));?>
                                <?=form_error('pen', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Designation : </td>
                            <td><select name="designation_id" id="designation_id" class="form-control" required="required">
                                <option>-- Select Designation --</option>
                                <?php foreach($data['designation'] as $dgn){ 
                                    if($role!='section' && ($dgn->designation=='Contractor' || $dgn->designation=='Department')){
                                        continue;
                                    } ?>
                                    <option value="<?=$dgn->designation_id?>" selected=""><?=$dgn->designation?></option>
                                <?php } ?>
                            </select> </td>
                        </tr>
                        <tr>
                            <td>Fullname : </td>
                            <td><?=form_input('fullname', '', array('id'=>'fullname', 'class'=>'form-control', 'required'=>"required"));?>
                                <?=form_error('fullname', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Email ID : </td>
                            <td><?=form_input('email_id', '', array('id'=>'email_id', 'class'=>'form-control',));?>
                                <?=form_error('email_id', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Phone No. : </td>
                            <td><?=form_input('phone_no', '', array('id'=>'phone_no', 'class'=>'form-control',));?>
                                <?=form_error('phone_no', '<p class="text-danger">', '</p>'); ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="text-right">
                            <td colspan="2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?=form_submit('submit', 'Save', array('class'=>'btn btn-primary'));?>
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


    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10">
                        <h4 class="card-title"><?=$name.' '.ucfirst($role)?> Users</h4>
                        <?php if($message): ?>
                            <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                        <?php endif;?>
                    </div>

                    <div class="col-lg-2">
                        <?php echo anchor('#', 'Add User', array('class'=>'btn btn-warning text-white', "data-toggle"=>"modal", "data-target"=>"#addEditUser")); ?>
                        <?php echo anchor($role.'_view', 'Back', array('class'=>'btn btn-secondary text-white')); ?>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="font-bold">#</th>
                            <th>PEN</th>
                            <th>Designation</th>
                            <th>Fullname</th>
                            <th>Email ID</th>
                            <th>Phone No.</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($data['users'] as $usr):
                            $id=$usr->user_id;
                            $sid=$usr->section_id;
                            $did=$usr->designation_id; ?>
                        <tr>
                            <td><?=$i; $i++;?></td>
                            <td><?= $nm=$usr->name; //echo $nm; ?></td>
                            <td><?= $dgn=$usr->designation;?></td>
                            <td><?= $fn=$usr->fullname;?></td>
                            <td><?= $em=$usr->email_id;?></td>
                            <td><?= $ph=$usr->phone_no;?></td>
                            <td><?=anchor('#', 'Edit', array('class'=>'btn btn-sm btn-outline-primary', "data-toggle"=>"modal", "data-target"=>"#addEditUser", "onclick"=>"fillEditForm($id, '$sid', '$nm', '$did', '$fn', '$em', '$ph')")); ?>
                                <?=anchor('#', 'Set/Reset Password', array('class'=>'btn btn-sm btn-outline-warning', "data-toggle"=>"modal", "data-target"=>"#resetPass", "onclick"=>"fillResetForm($id)")); ?>
                                <?=anchor("delete_user/$id/$role/$name", 'Delete', array('class'=>'btn btn-sm btn-outline-danger', 'onClick'=>"return doConfirm();"));?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    function fillEditForm($id, $sid, $nm, $did, $fn, $em, $ph) {
        document.getElementById('user_id').value = $id;
        // document.getElementById('section_id').value = $sid;
        document.getElementById('pen').value = $nm;
        document.getElementById('designation_id').value = $did;
        document.getElementById('fullname').value = $fn;
        document.getElementById('email_id').value = $em;
        document.getElementById('phone_no').value = $ph;
        // alert($name);
    }

    function fillResetForm($id) {
        document.getElementById('reset_user_id').value = $id;
    }
</script>
