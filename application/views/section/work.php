<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
?>
<!-- CSRF token -->
<!-- <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" /> -->

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
            <h4 class="page-title">Work Information</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Work</li>
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
                <h5 class="card-title"><?php if($editWork){ echo 'Update';} else{echo 'Add New';} ?> Work Detail</h5>

                <?php if($message): ?>
                <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                <?php endif;?>
                <?php echo form_open('section/addUpdateWork')?>
                <?=form_hidden('work_id', ($editWork) ? $editWork[0]->work_id : '');?>
                <div class="row">

                    <div class="form-group col-lg-2">
                        <?php echo form_label('Work Type') ?>
                        <?php echo form_dropdown(array('name'=>'work_type', 'options'=>$typeOfWork, 'selected'=>($editWork) ? $editWork[0]->work_type : '', 'class'=>'form-control')) ?>
                        <?php echo form_error('work_type', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-2">
                        <?php echo form_label('Item No') ?>
                        <?php echo form_input(array(
                            'name' => 'item_no', 
                            'value'=>($editWork) ? $editWork[0]->item_no : '', 
                            'placeholder' => 'Enter Item No', 
                            'required'=>'required', 
                            'class'=>'form-control',
                            'pattern'=>'^[0-9]+(\\.[0-9]+)?$', // Decimal allowed
                            'title'=>'Item No must be a number (decimals allowed).'
                        )); ?>
                        <?php echo form_error('item_no', '<p class="text-danger">', '</p>'); ?>
                    </div>

                    <div class="form-group col-lg-2">
                        <?php echo form_label('Item Name') ?>
                        <?php echo form_input(array(
                            'name' => 'item_name', 
                            'value'=>($editWork) ? $editWork[0]->item_name : '', 
                            'placeholder' => 'Enter Item Name', 
                            'required'=>'required', 
                            'class'=>'form-control',
                            'pattern'=>'^[A-Za-z0-9\- ]+$', // Alphanumeric, hyphens, spaces allowed
                            'title'=>'Item Name can include letters, numbers, hyphens, and spaces.'
                        )); ?>
                        <?php echo form_error('item_name', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-1">
                        <?php echo form_label('Unit') ?>
                        <?php echo form_input(array(
                            'name' => 'unit', 
                            'value'=>($editWork) ? $editWork[0]->unit : '', 
                            'placeholder' => 'Enter Unit', 
                            'class'=>'form-control',
                            'pattern'=>'^[a-zA-Z0-9]+$', // Alphanumeric only, no spaces
                            'title'=>'Unit must be alphanumeric with no spaces.'
                        )); ?>
                        <?php echo form_error('unit', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-5">
                        <?php echo form_label('Item Description') ?>
                        <div class="row">
                        <?php echo form_input(array(
                            'name' => 'item_description', 
                            'value'=>($editWork) ? $editWork[0]->item_description : '', 
                            'placeholder' => 'Enter Item Description', 
                            'required'=>'required', 
                            'class'=>'form-control col-lg-8'
                        )); ?>
                        <?php echo form_submit(array('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success')); ?>
                        </div>
                        <?php echo form_error('item_description', '<p class="text-danger">', '</p>'); ?>
                        
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
                <h5 class="card-title">List of Works</h5>
                <div class="table-responsive">
                    <table id="work_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th>Work Type</th>
                              <th>Item No</th>
                              <th>Item Name</th>
                              <th>Unit</th>
                              <th>Item Description</th>
                              <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0; foreach($work as $work): ?>
                            <tr>
                                <td><?=++$i?></td>
                                <td><?=html_escape($work->work_type)?></td>
                                <td><?=html_escape($work->item_no)?></td>
                                <td><?=html_escape($work->item_name)?></td>
                                <td><?=html_escape($work->unit)?></td>
                                <td><?=html_escape($work->item_description)?></td>
                                <td>
   <!-- Update Button -->
   <form action="<?= site_url("section/workDetail/$work->work_id"); ?>" method="post" style="display:inline;">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update">
            <i class="mdi mdi-check"></i>
        </button>
    </form>

    <!-- Delete Button -->
    <form action="<?= site_url("section/deleteWork/$work->work_id"); ?>" method="post" style="display:inline;" onsubmit="return doConfirm();">
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
<script>
    /****************************************
     *       Basic Table                   *
     ****************************************/
    $(document).ready( function () {
        $('#work_table').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            search: true
        });
    });
    function doConfirm() {
    return confirm('Are you sure you want to delete this work?');
}
</script>
