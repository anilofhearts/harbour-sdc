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

            <!-- Agreement Items Section -->
            <div class="card-body alert alert-info mt-3">
                <div class="row">
                    <div class="col-lg-10">
                        <h4 class="alert-heading">Agreement Items</h4>
                        <p class="mb-0">Add items that will be part of this agreement.</p>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-success" onclick="addItemRow()">
                            <i class="bi bi-plus"></i> Add Item
                        </button>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <table id="itemsTable" class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;">Action</th>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['item'])): ?>
                                    <?php foreach ($data['item'] as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <input type="text" name="item[]" value="<?= html_escape($item->item) ?>" class="form-control" required>
                                                <input type="hidden" name="agreement_item_id[]" value="<?= html_escape($item->agreement_item_id) ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="unit[]" value="<?= html_escape($item->unit) ?>" class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="rate[]" value="<?= html_escape($item->rate) ?>" class="form-control" step="0.01" min="0" required>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <input type="text" name="item[]" value="" class="form-control" placeholder="Enter item name" required>
                                            <input type="hidden" name="agreement_item_id[]" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="unit[]" value="" class="form-control" placeholder="Unit" required>
                                        </td>
                                        <td>
                                            <input type="number" name="rate[]" value="0.00" class="form-control" step="0.01" min="0" required>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Agreement Locations Section -->
            <div class="card-body alert alert-warning mt-3">
                <div class="row">
                    <div class="col-lg-10">
                        <h4 class="alert-heading">Agreement Locations</h4>
                        <p class="mb-0">Add locations where this agreement will be executed.</p>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-success" onclick="addLocationRow()">
                            <i class="bi bi-plus"></i> Add Location
                        </button>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <table id="locationsTable" class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;">Action</th>
                                    <th>Location Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['loc'])): ?>
                                    <?php foreach ($data['loc'] as $index => $location): ?>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeLocationRow(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <input type="text" name="location[]" value="<?= html_escape($location->location) ?>" class="form-control" required>
                                                <input type="hidden" name="agreement_location_id[]" value="<?= html_escape($location->agreement_location_id) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLocationRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <input type="text" name="location[]" value="" class="form-control" placeholder="Enter location name" required>
                                            <input type="hidden" name="agreement_location_id[]" value="">
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <?= anchor('section/agreement', 'Back', array('class' => 'btn btn-secondary text-white')); ?>
                        <?= form_submit('submit', 'Save Agreement', ['class' => 'btn btn-primary']); ?>
                    </div>
                </div>
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
        // Check if total element exists before accessing it
        if (document.getElementById('total')) {
            window.ttlAgr = parseFloat(document.getElementById('total').value);
        }

        // Prevent spaces in fields
        $('input[name="agreement_no"], input[name="short_code"], input[name="amount"], input[name="agreement"]').on('keypress', function(e) {
            if(e.which === 32) {
                return false;
            }
        });

        // Additional client-side validation can be added here
    });

    // Function to add new item row
    function addItemRow() {
        var table = document.getElementById('itemsTable');
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        
        cell1.innerHTML = '<button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(this)"><i class="bi bi-trash"></i></button>';
        cell2.innerHTML = '<input type="text" name="item[]" value="" class="form-control" placeholder="Enter item name" required><input type="hidden" name="agreement_item_id[]" value="">';
        cell3.innerHTML = '<input type="text" name="unit[]" value="" class="form-control" placeholder="Unit" required>';
        cell4.innerHTML = '<input type="number" name="rate[]" value="0.00" class="form-control" step="0.01" min="0" required>';
    }

    // Function to remove item row
    function removeItemRow(button) {
        var table = document.getElementById('itemsTable');
        if (table.rows.length > 2) { // Keep at least one row
            button.closest('tr').remove();
        } else {
            alert('At least one item is required.');
        }
    }

    // Function to add new location row
    function addLocationRow() {
        var table = document.getElementById('locationsTable');
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        
        cell1.innerHTML = '<button type="button" class="btn btn-sm btn-danger" onclick="removeLocationRow(this)"><i class="bi bi-trash"></i></button>';
        cell2.innerHTML = '<input type="text" name="location[]" value="" class="form-control" placeholder="Enter location name" required><input type="hidden" name="agreement_location_id[]" value="">';
    }

    // Function to remove location row
    function removeLocationRow(button) {
        var table = document.getElementById('locationsTable');
        if (table.rows.length > 2) { // Keep at least one row
            button.closest('tr').remove();
        } else {
            alert('At least one location is required.');
        }
    }

    // Form validation before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        var itemInputs = document.querySelectorAll('input[name="item[]"]');
        var locationInputs = document.querySelectorAll('input[name="location[]"]');
        
        // Check if at least one item is filled
        var hasValidItem = false;
        itemInputs.forEach(function(input) {
            if (input.value.trim() !== '') {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            alert('Please add at least one item.');
            e.preventDefault();
            return;
        }
        
        // Check if at least one location is filled
        var hasValidLocation = false;
        locationInputs.forEach(function(input) {
            if (input.value.trim() !== '') {
                hasValidLocation = true;
            }
        });
        
        if (!hasValidLocation) {
            alert('Please add at least one location.');
            e.preventDefault();
            return;
        }
    });
</script>
