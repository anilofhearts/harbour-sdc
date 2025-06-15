<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
    $vehmessage = $this->session->flashdata('vehmessage');
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
            <h4 class="page-title">Quarry Information</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quarry</li>
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
    <div class="row">

    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

        <div class="card col-lg-5" style="margin-right: 20px">
            <div class="card-body">
                <h5 class="card-title">Update Vehicle & Quarry</h5>

                <?php if($vehmessage): ?>
                <div class="alert <?=$messageClass?>" role="alert"><?=$vehmessage?></div>
                <?php endif;?>
                <?php echo form_open('contractor/updateVehicleQuarry')?>
                <?=form_hidden('quarry_id',  ($editQry) ? $editQry[0]->quarry_id : '');?>
                <div class="row">
                    <div class="form-group col-lg-5">
                        <?php echo form_label('Vehicle No.') ?>

                            <select name="vehicle_id" id="vehicle_id" class="form-control select2 custom-select" required="required">
                                <option value="">-- Select Vehicle --</option>
                                <?php foreach($vehicles as $vehicle): ?>
                                <option value="<?=$vehicle->vehicle_id?>" ><?=$vehicle->vehicle_no?></option>
                                <?php endforeach; ?>
                            </select>
                        
                        <?php echo form_error('vehicle_id', '<p class="text-danger">', '</p>'); ?>
                        
                    </div>

                    <div class="form-group col-lg-6">
                        <?php echo form_label('Quarry') ?>
                        <div class="row">
                            <select name="quarry_id" id="quarry_id" class="form-control select2 custom-select col-lg-8" required="required">
                                <option value="">-- Select Quarry --</option>
                                <?php foreach($quarries as $optQry): ?>
                                <option value="<?=$optQry->quarry_id?>" ><?=$optQry->quarry_location?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_submit(array('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success'));?>
                        </div>
                        <?php echo form_error('quarry_id', '<p class="text-danger">', '</p>'); ?>
                        
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="card col-lg-6">
            <div class="card-body">
                <h5 class="card-title"><?php if($editQry){ echo 'Update';} else{echo 'Add New';} ?> Quarry</h5>

                <?php if($message): ?>
                <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                <?php endif;?>
                <?php echo form_open('contractor/addUpdateQuarry', ['id' => 'quarryForm']) ?>
                <?=form_hidden('quarry_id',  ($editQry) ? $editQry[0]->quarry_id : '');?>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <?php echo form_label('Quarry Location') ?>
                        <?php echo form_input(array('name' => 'quarry_location', 'id' => 'quarryLocation', 'value'=> ($editQry) ? $editQry[0]->quarry_location : '' , 'placeholder' => 'Enter Quarry Location', 'required'=>'required', 'class'=>'form-control'), set_value('quarry_location')); ?>
                        <?php echo form_error('quarry_location', '<p class="text-danger">', '</p>'); ?>
                    </div>

                    <div class="form-group col-lg-6">
                        <?php echo form_label('Description') ?>
                        <div class="row">
                        <?php echo form_input(array('name' => 'description', 'id' => 'description', 'value'=> ($editQry) ? $editQry[0]->description : '' , 'placeholder' => 'Enter Description', 'required'=>'required', 'class'=>'form-control col-lg-8'), set_value('description')); ?>
                        <?php echo form_submit(array('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success'));?>
                        </div>
                        <?php echo form_error('description', '<p class="text-danger">', '</p>'); ?>
                        
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        

    <!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->

        <div class="card col-md-5" style="margin-right: 20px">
            <div class="card-body">
                <h5 class="card-title">Vehicle and Quarry List</h5>
                <div class="table-responsive">
                    <table id="vehicle_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th>Vehicle No.</th>
                              <th>Quarry</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0; foreach($vehicles as $vehicle): ?>
                            <tr>
                                <td><?=++$i?></td>
                                <td id="vehicle_no" class="vno"><?=$vehicle->vehicle_no?></td>
                                <td><?=$vehicle->quarry_location?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card col-md-6">
            <div class="card-body">
                <h5 class="card-title">List of Quarries</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th>Quarry Location</th>
                              <th>Description</th>
                              <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0; foreach($quarries as $quarry): ?>
                            <tr>
                                <td><?=++$i?></td>
                                <td id="vehicle_no" class="vno"><?=$quarry->quarry_location?></td>
                                <td><?=$quarry->description?></td>
                                <td>
                                <!-- Delete Button -->
                                <form action="<?= site_url("contractor/deleteQuarry/$quarry->quarry_id"); ?>" method="post" style="display:inline;" onsubmit="return doConfirm();">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                                                   </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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

<!-- Place JavaScript here -->
<script language="JavaScript">
document.getElementById("quarryForm").onsubmit = function() {
    var quarryLocation = document.getElementById("quarryLocation").value;
    var description = document.getElementById("description").value;
    var valid = true;

    // Check length of Quarry Location
    if (quarryLocation.length > 15) {
        alert("Quarry Location cannot exceed 50 characters.");
        valid = false;
    }

    // Check length of Description
    if (description.length > 60) {
        alert("Description cannot exceed 200 characters.");
        valid = false;
    }

    // Check for special characters (you can customize this as needed)
    var regex = /^[a-zA-Z0-9\s]+$/;
    if (!regex.test(quarryLocation) || !regex.test(description)) {
        alert("Please avoid special characters in Quarry Location and Description.");
        valid = false;
    }

    return valid;
};

/****************************************
 *       Basic Table                   *
 ****************************************/
$(document).ready( function () {
    $('#vehicle_table').DataTable({
    responsive: true,
    ordering: true,
    paging: true,
    search: true
    });
} );
</script>
