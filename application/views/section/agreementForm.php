<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
?>
<!-- CSRF token -->
<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
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
            <h4 class="page-title"><?= $editAgre ? 'Update' : 'Add'; ?> Agreement Form</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
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
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

              <?= form_open('section/addUpdateAgreement'); ?>
              <?= form_hidden('agreement_id', ($editAgre) ? html_escape($editAgre[0]->agreement_id) : ''); ?>

              <div class="card-body alert alert-success">
                <div class="row">
                    <h4 class="alert-heading">Agreement Details</h4>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <?= form_label('Agreement No') ?>
                        <?= form_input(array(
                            'name' => 'agreement_no', 
                            'value' => $editAgre ? html_escape($editAgre[0]->agreement_no) : set_value('agreement_no'), 
                            'placeholder' => 'Enter Agreement No', 
                            'class' => 'form-control', 
                            'required' => 'required', 
                       //     'readonly' => $editAgre ? 'readonly' : null
                           )
                        ); ?>
                        <?= form_error('agreement_no', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Date of Agreement') ?>
                        <?= form_input(array(
                            'type' => 'date', 
                            'name' => 'date_of_agreement', 
                            'value' => $editAgre ? html_escape($editAgre[0]->date_of_agreement) : set_value('date_of_agreement'), 
                            'required' => 'required', 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('date_of_agreement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Name of Work') ?>
                        <?= form_input(array(
                            'name' => 'agreement', 
                            'value' => $editAgre ? html_escape($editAgre[0]->agreement) : set_value('agreement'), 
                            'placeholder' => 'Enter Name of Work', 
                            'required' => 'required', 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('agreement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Amount') ?>
                        <?= form_input(array(
                            'name' => 'amount', 
                            'value' => $editAgre ? html_escape($editAgre[0]->amount) : set_value('amount'), 
                            'placeholder' => 'Enter Amount', 
                            'required' => 'required', 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('amount', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Date of Commencement') ?>
                        <?= form_input(array(
                            'type' => 'date', 
                            'name' => 'date_of_commencement', 
                            'value' => $editAgre ? html_escape($editAgre[0]->date_of_commencement) : set_value('date_of_commencement'), 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('date_of_commencement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Exp. Date of Completion') ?>
                        <?= form_input(array(
                            'type' => 'date', 
                            'name' => 'exp_date_of_completion', 
                            'value' => $editAgre ? html_escape($editAgre[0]->exp_date_of_completion) : set_value('exp_date_of_completion'), 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('exp_date_of_completion', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Type of Work') ?>
                        <select name="type_of_work" id="sel_work" class="form-control">
                            <option>-- Select Type of Work --</option>
                            <?php foreach ($type_of_work as $work): ?>
                                <option value="<?= html_escape($work->work_type) ?>" <?= $editAgre && $work->work_type == $editAgre[0]->type_of_work ? 'selected' : '' ?>>
                                    <?= html_escape($work->work_type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('type_of_work', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?= form_label('Short Code') ?>
                        <?= form_input(array(
                            'name' => 'short_code', 
                            'value' => $editAgre ? html_escape($editAgre[0]->short_code) : set_value('short_code'), 
                            'placeholder' => 'Short Code for Card No.', 
                            'required' => 'required', 
                            'class' => 'form-control')
                        ); ?>
                        <?= form_error('short_code', '<p class="text-danger">', '</p>'); ?>
                    </div>
                </div>
              </div>
              
            <!-- Other parts of the form go here -->

            <div class="row">
                <?= anchor('section/agreement', 'Back', array('class' => 'btn btn-secondary text-white')); ?>
                <?= form_submit('submit', 'Save Agreement', ['class' => 'btn btn-primary']); ?>
            </div>
            
            <?= form_close(); ?>

            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

<script type="text/javascript">
    $(document).ready(function(){
        window.ttlAgr = parseFloat(document.getElementById('total').value);

        // Prevent spaces in fields
        $('input[name="agreement_no"], input[name="short_code"], input[name="amount"], input[name="agreement"]').on('keypress', function(e) {
            if(e.which === 32) {
                return false;
            }
        });

        // Additional client-side validation can be added here
    });

    // Scripts for dynamic row operations, AJAX requests, etc.
</script>
