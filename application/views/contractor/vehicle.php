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
            <h4 class="page-title">Vehicle Information</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vehicle</li>
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

        <div class="card col-md-12">
            <div class="card-body">
                <h5 class="card-title"><?php if($editVeh){ echo 'Update';} else{echo 'Add New';} ?> Vehicle</h5>

                <?php if($message): ?>
                <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                <?php endif;?>
                <?php echo form_open('contractor/addUpdateVehicle')?>
                <?=form_hidden('vehicle_id',  ($editVeh) ? $editVeh[0]->vehicle_id : '');?>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Vehicle No') ?>
                        <?php echo form_input(array('name' => 'vehicle_no', 'value'=> ($editVeh) ? $editVeh[0]->vehicle_no : '' , 'placeholder' => 'Enter Vehicle No', 'required'=>'required', 'class'=>'form-control'), set_value('vehicle_no')); ?>
                        <?php echo form_error('vehicle_no', '<p class="text-danger">', '</p>'); ?>
                    </div>

                    <div class="form-group col-lg-2">
                        <?php echo form_label('Quarry') ?>
                        <?php echo form_dropdown(array('name'=>'vehicle_type', 'options'=>$typeOfVehicle, 'selected'=> ($editVeh) ? $editVeh[0]->vehicle_type : 'Truck' , 'class'=>'form-control'), set_value('vehicle_type')) ?>
                        <?php echo form_error('vehicle_type', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-2">
                        <?php echo form_label('Insurance Upto') ?>
                        <?php echo form_input(array('type'=>'date', 'name' => 'insurance_end_date', 'value'=> ($editVeh) ? $editVeh[0]->insurance_end_date : '' , 'required'=>'required', 'class'=>'form-control'), set_value('insurance_end_date')); ?>
                        <?php echo form_error('insurance_end_date', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-5">
                        <?php echo form_label('Agent Name') ?>
                        <div class="row">
                        <?php echo form_input(array('name' => 'rc_owner', 'value'=> ($editVeh) ? $editVeh[0]->rc_owner : '' , 'placeholder' => 'Enter Agent Name', 'required'=>'required', 'class'=>'form-control col-lg-8'), set_value('rc_owner')); ?>
                        <?php echo form_submit(array('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success'));?>
                        </div>
                        <?php echo form_error('rc_owner', '<p class="text-danger">', '</p>'); ?>
                        
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    <!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->

        <div class="card col-md-12">
            <div class="card-body">
                <h5 class="card-title">List of Vehicles</h5>
                <div class="table-responsive">
                    <table id="vehicle_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th>Vehicle No</th>
                              <th>Vehicle Type</th>
                              <th>Insurance Upto</th>
                              <th>Agent Name</th>
                              <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0; foreach($vehicle as $vehicle): ?>
                            <tr>
                                <td><?=++$i?></td>
                                <td id="vehicle_no" class="vno"><?=$vehicle->vehicle_no?></td>
                                <td><?=$vehicle->vehicle_type?></td>
                                <td><?=date('d-m-Y', strtotime($vehicle->insurance_end_date))?></td>
                                <td><?=$vehicle->rc_owner?></td>
                                <td>
                                    <!-- Update Button -->
                                    <form action="<?= site_url("contractor/vehicle/$vehicle->vehicle_id"); ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="<?= site_url("contractor/deleteVehicle/$vehicle->vehicle_id"); ?>" method="post" style="display:inline;" onsubmit="return doConfirm();">
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
<script language="JavaScript">
/****************************************
 *       Basic Table                   *
 ****************************************/
$(document).ready( function () {
    $('#vehicle_table').DataTable({
    responsive: true,
    ordering: true,
    paging: true,
    search: true
})
} );
</script>
