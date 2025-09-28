<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
?>

<!-- Flash Messages -->
<?php if ($message): ?>
<div class="alert <?= html_escape($messageClass) ?> alert-dismissible fade show" role="alert">
    <?= html_escape($message) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

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
                <h4 class="page-title">Chainage Management</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('section') ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chainage</li>
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
    <!-- Agreement Details Card -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-file-text"></i> Agreement Details
                </h4>
            </div>
            <div class="card-body">
                <?= form_open('', ['id' => 'locationForm']) ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Agreement No.:</label>
                            <p class="form-control-plaintext"><?= html_escape($data['agreement'][0]->agreement_no); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Agreement:</label>
                            <p class="form-control-plaintext"><?= html_escape($data['agreement'][0]->agreement); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <?= form_label('Select Location', 'agreement_location_id', ['class' => 'form-label fw-bold']) ?>
                            <select name="agreement_location_id" id="agreement_location_id" class="form-select" required>
                                <option value="">-- Select Location --</option>
                                <?php foreach ($data['locations'] as $location): ?>
                                    <option value="<?= html_escape($location->agreement_location_id) ?>" 
                                            <?= ($data['agreement_location_id'] == $location->agreement_location_id) ? 'selected' : '' ?>>
                                        <?= html_escape($location->location) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <?= form_submit(['name' => 'submit', 'value' => 'Add Chainage', 'class' => 'btn btn-primary']) ?>
                                <?= anchor('agreement', 'Back', ['class' => 'btn btn-secondary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Add Chainage Form (only show when location is selected) -->
    <?php if ($data['agreement_location_id'] > 0): ?>
    <div class="col-12 mt-4" id="formblock">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Add Chainage For <?= html_escape(array_column($data['locations'], 'location', 'agreement_location_id')[$data['agreement_location_id']]); ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-info" onclick="addRow('chainageTable')">
                            <i class="bi bi-plus"></i> Add Row
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteRow('chainageTable')">
                            <i class="bi bi-trash"></i> Delete Row
                        </button>
                    </div>
                </div>

                <?= form_open('add_chainage', ['id' => 'chainageForm']) ?>
                <?= form_hidden('agreement_id', $data['agreement'][0]->agreement_id) ?>
                <?= form_hidden('chainage_agr_loc_id', $data['agreement_location_id']) ?>

                <div class="table-responsive">
                    <table id="chainageTable" class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" style="width: 50px;">
                                    <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                                </th>
                                <th scope="col">Chainage</th>
                                <?php foreach ($data['items'] as $ih): ?>
                                    <th scope="col"><?= html_escape($ih->item) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cng_loc = array();
                            if (isset($data['list']) && !empty($data['list'])) {
                                foreach ($data['list'] as $item) {
                                    if ($item->agreement_location_id == $data['agreement_location_id']) {
                                        $cng_loc[] = $item;
                                    }
                                }
                            }
                            ?>
                            
                            <?php if (!empty($cng_loc)): ?>
                                <?php $i = 0; $cng_unq = array_unique(array_column($cng_loc, 'chainage')); ?>
                                <?php foreach ($cng_unq as $cng): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ckk" class="row-checkbox">
                                        </td>
                                        <td>
                                            <input type="text" name="chainage[]" value="<?= html_escape($cng) ?>" class="form-control" required>
                                        </td>
                                        <?php foreach ($data['items'] as $item): ?>
                                        <td>
                                            <?= form_hidden('chainage_item_id[]', $item->agreement_item_id); ?>
                                            <input type="number" name="chainage_quantity[]" 
                                                   value="<?= html_escape($cng_loc[$i]->chainage_quantity); $i++; ?>" 
                                                   class="form-control" step="0.01" min="0" required>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ckk" class="row-checkbox">
                                    </td>
                                    <td>
                                        <input type="text" name="chainage[]" value="0 - 20" class="form-control" required>
                                    </td>
                                    <?php foreach ($data['items'] as $item1): ?>
                                    <td>
                                        <?= form_hidden('chainage_item_id[]', $item1->agreement_item_id); ?>
                                        <input type="number" name="chainage_quantity[]" value="0.00" 
                                               class="form-control" step="0.01" min="0" required>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Save Chainage
                        </button>
                        <a href="<?= base_url('agreement') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chainage Details Table -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-list-ul"></i> Chainage Details
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data-table">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Location</th>
                                <th scope="col">Chainage</th>
                                <?php foreach ($data['items'] as $item2): ?>
                                    <th scope="col"><?= html_escape($item2->item) ?></th>
                                <?php endforeach; ?>
                                <th scope="col">Total Qty of All Stones in Specified Chainage (T)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; $s = 0; ?>
                            <?php if ($data['list']): ?>
                                <?php while ($i < count($data['list'])): $ttl = 0; ?>
                                    <tr>
                                        <td><?= ++$s ?></td>
                                        <td><?= html_escape($data['list'][$i]->location) ?></td>
                                        <td><?= html_escape($data['list'][$i]->chainage) ?></td>
                                        <?php foreach ($data['items'] as $item3): ?>
                                            <?php 
                                                $it = $data['list'][$i]->item;
                                                $qty = $data['list'][$i]->chainage_quantity;
                                                if ($it == "Tetrapod") {
                                                    $qty = $qty / 2;
                                                } else {
                                                    $ttl += $qty;
                                                }
                                            ?>
                                            <td><?= html_escape($qty) ?></td>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                        <td><strong><?= html_escape($ttl) ?></strong></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= count($data['items']) + 4 ?>" class="text-center text-muted">
                                        <i class="bi bi-info-circle"></i> No chainage details found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> * Total Qty excludes Tetrapod
                </small>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->

<script type="text/javascript">
    // Function to add a new row to the chainage table
    function addRow(tableId) {
        var table = document.getElementById(tableId);
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var colCount = table.rows[1].cells.length;

        for (var i = 0; i < colCount; i++) {
            var newcell = row.insertCell(i);
            newcell.innerHTML = table.rows[1].cells[i].innerHTML;

            // Clear values for new row
            var inputs = newcell.getElementsByTagName('input');
            for (var j = 0; j < inputs.length; j++) {
                switch (inputs[j].type) {
                    case "text":
                        inputs[j].value = "";
                        break;
                    case "number":
                        inputs[j].value = "0.00";
                        break;
                    case "checkbox":
                        inputs[j].checked = false;
                        break;
                    case "select-one":
                        inputs[j].selectedIndex = 0;
                        break;
                }
            }
        }
    }

    // Function to delete selected rows
    function deleteRow(tableId) {
        try {
            var table = document.getElementById(tableId);
            var rowCount = table.rows.length;
            var deleted = false;

            for (var i = rowCount - 1; i >= 1; i--) { // Start from bottom to avoid index issues
                var row = table.rows[i];
                var chkbox = row.cells[0].querySelector('input[type="checkbox"]');
                
                if (chkbox && chkbox.checked) {
                    if (rowCount <= 2) { // Keep at least one data row
                        alert("Cannot delete all rows. At least one row must remain.");
                        break;
                    }
                    table.deleteRow(i);
                    deleted = true;
                    rowCount--;
                }
            }
            
            if (!deleted) {
                alert("Please select rows to delete.");
            }
        } catch (e) {
            alert("Error deleting rows: " + e.message);
        }
    }

    // Function to toggle all checkboxes
    function toggleAllCheckboxes() {
        var selectAll = document.getElementById('selectAll');
        var checkboxes = document.querySelectorAll('.row-checkbox');
        
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAll.checked;
        });
    }

    // Form validation
    document.getElementById('chainageForm').addEventListener('submit', function(e) {
        var chainageInputs = document.querySelectorAll('input[name="chainage[]"]');
        var quantityInputs = document.querySelectorAll('input[name="chainage_quantity[]"]');
        
        for (var i = 0; i < chainageInputs.length; i++) {
            if (!chainageInputs[i].value.trim()) {
                alert('Please fill in all chainage values.');
                e.preventDefault();
                return;
            }
        }
        
        for (var i = 0; i < quantityInputs.length; i++) {
            if (!quantityInputs[i].value || parseFloat(quantityInputs[i].value) < 0) {
                alert('Please enter valid quantity values.');
                e.preventDefault();
                return;
            }
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            // Use Bootstrap 5 dismiss if available, otherwise use Bootstrap 4
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } else {
                // Fallback for Bootstrap 4
                alert.style.display = 'none';
            }
        });
    }, 5000);
</script>
