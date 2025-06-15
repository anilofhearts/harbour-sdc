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
                <h4 class="page-title"></h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
        <!-- ============================================================== -->
        <!-- TABLE BLOCK -->
        <!-- ============================================================== -->

        <div class="row">
            <div class="card col-md-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title">Agreement Details</h4>
                            <?php if ($message): ?>
                            <div class="alert <?= html_escape($messageClass) ?>" role="alert"><?= html_escape($message) ?></div>
                            <?php endif;?>
                        </div>
                    </div>

                    <?= form_open() ?>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label>Agreement No.: </label> <?= html_escape($data['agreement'][0]->agreement_no); ?>
                        </div>

                        <div class="form-group col-lg-3">
                            <label>Agreement : </label> <?= html_escape($data['agreement'][0]->agreement); ?>
                        </div>

                        <div class="form-group col-lg-3">
                            <?= form_label('Select Location') ?>
                            <select name="agreement_location_id" class="form-control">
                                <option>-- Select Location --</option>
                                <?php foreach ($data['locations'] as $location): ?>
                                    <option value="<?= html_escape($location->agreement_location_id) ?>"><?= html_escape($location->location) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <?= form_label('. ') ?>
                            <span>
                                <?= form_submit(['name' => 'submit', 'value' => 'Add Chainage', 'class' => 'btn btn-primary']) ?>
                                <?= anchor('agreement', 'Back', ['class' => 'btn btn-secondary']); ?>
                            </span>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>

            <?php if ($data['agreement_location_id'] > 0): ?>
            <div class="card col-md-12" id="formblock">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title">Add Chainage For <?= html_escape(array_column($data['locations'], 'location', 'agreement_location_id')[$data['agreement_location_id']]); ?> </h4>
                            <?php if ($message): ?>
                            <div class="alert <?= html_escape($messageClass) ?>" role="alert"><?= html_escape($message) ?></div>
                            <?php endif; ?>
                            <span>
                                <input type="button" value="Add Row" onclick="addRow('chainageTable')" class="btn btn-info" />
                                <input type="button" value="Delete Row" onclick="deleteRow('chainageTable')" class="btn btn-danger" />
                            </span>
                        </div>
                    </div>

                    <?= form_open('add_chainage') ?>
                    <?= form_hidden('agreement_id', $data['agreement'][0]->agreement_id) ?>
                    <?= form_hidden('chainage_agr_loc_id', $data['agreement_location_id']) ?>

                    <div class="row">
                        <table id="chainageTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Chainage</th>
                                    <?php foreach ($data['items'] as $ih): ?>
                                        <th scope="col"><?= html_escape($ih->item) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (isset($data['list']) && count($cng_loc) > 0): ?>
                                    <?php $i = 0; $cng_unq = array_unique(array_column($cng_loc, 'chainage')); ?>
                                    <?php foreach ($cng_unq as $cng): ?>
                                        <tr>
                                            <th><?= form_checkbox('ckk', '', FALSE); ?></th>
                                            <td><input type="text" name="chainage[]" value="<?= html_escape($cng) ?>" class="form-control"></td>
                                            <?php foreach ($data['items'] as $item): ?>
                                            <td>
                                                <?= form_hidden('chainage_item_id[]', $item->agreement_item_id); ?>
                                                <input type="text" name="chainage_quantity[]" value="<?= html_escape($cng_loc[$i]->chainage_quantity); $i++; ?>" class="form-control" required="required">
                                            </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <th><?= form_checkbox('ckk', '', FALSE); ?></th>
                                        <td><input type="text" name="chainage[]" value="0 - 20" class="form-control"></td>
                                        <?php foreach ($data['items'] as $item1): ?>
                                        <td>
                                            <?= form_hidden('chainage_item_id[]', $item1->agreement_item_id); ?>
                                            <?= form_input('chainage_quantity[]', '0.00', ['class' => 'form-control', 'required' => 'required']); ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <?= form_submit('submit', 'Save', ['class' => 'btn btn-success']) ?>
                            <a href="" class="btn btn-info">Close</a>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="card col-md-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title">Chainage Details</h4>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped table-bordered">
                            <thead>
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
                                            <td><?= html_escape($ttl) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center"> Chainage details not added</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table> 
                        <p>* Total Qty excludes Tetrapod</p>
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
<script type="text/javascript">

    // SCRIPT FOR CHAINAGE FORM
    function addRow(tableId) {
        var table = document.getElementById(tableId);
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var colCount = table.rows[1].cells.length;

        for (var i = 0; i < colCount; i++) {
            var newcell = row.insertCell(i);
            newcell.innerHTML = table.rows[1].cells[i].innerHTML;

            switch (newcell.childNodes[0].type) {
                case "text":
                    newcell.childNodes[0].value = "";
                    break;
                case "checkbox":
                    newcell.childNodes[0].checked = false;
                    break;
                case "select-one":
                    newcell.childNodes[0].selectedIndex = 0;
                    break;
            }
        }
    }

    function deleteRow(tableId) {
        try {
            var table = document.getElementById(tableId);
            var rowCount = table.rows.length;

            for (var i = 0; i < rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if (chkbox && chkbox.checked) {
                    if (rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }
        } catch (e) {
            alert(e);
        }
    }

    function hideForm() {
        document.getElementById('formblock').style.visibility = 'hidden';
    }
</script>
