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

        <!-- Modal -->
        <div class="modal fade" id="agreementInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 1000px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agreement Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="font-bold">Agreement No. : </span><span><?= html_escape($agreement[0]->agreement_no) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Date : </span><span><?= html_escape(date('d-m-Y', strtotime($agreement[0]->date_of_agreement))) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Name of Work : </span><span><?= html_escape($agreement[0]->agreement) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Amount : </span><span><?= html_escape($agreement[0]->amount) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Date of Commencement : </span><span><?= html_escape(date('d-m-Y', strtotime($agreement[0]->date_of_commencement))) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Exp Date of Completion : </span><span><?= html_escape(date('d-m-Y', strtotime($agreement[0]->exp_date_of_completion))) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span class="font-bold">Type of Work : </span><span><?= html_escape($agreement[0]->type_of_work) ?></span>
                            </div>
                        </div>

                        <div>
                            <br>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <span class="font-bold">Name of Contractor : </span><span><?= html_escape($agreement[0]->name_of_contractor) ?></span>
                            </div>
                            <div class="col-md-6">
                                <span class="font-bold">Address : </span><span><?= html_escape($agreement[0]->address) ?></span>
                            </div>
                            <div class="col-md-6">
                                <span class="font-bold">Email ID : </span><span><?= html_escape($agreement[0]->contractor_email_id) ?></span>
                            </div>
                            <div class="col-md-6">
                                <span class="font-bold">Phone No. : </span><span><?= html_escape($agreement[0]->contractor_phone_no) ?></span>
                            </div>
                        </div>
                        <div>
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="font-bold">Items : </span>
                                <?php foreach ($items as $item): ?>
                                    <span><?= html_escape($item->item) ?>, </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="font-bold">Locations : </span>
                                <?php foreach ($locations as $location): ?>
                                    <span><?= html_escape($location->location) ?>, </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->


        <div class="row">

            <div class="card col-md-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="card-title">List of Agreements</h4>
                            <?php if($message): ?>
                            <div class="alert <?= html_escape($messageClass) ?>" role="alert"><?= html_escape($message) ?></div>
                            <?php endif;?>
                        </div>

                        <div class="col-lg-2">
                            <?= anchor('agreementForm', 'New Agreement', array('class' => 'btn btn-warning text-white')); ?>
                        </div>
                    </div>

                    <div class="row">
                        <table id="agreement_table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Agreement No</th>
                                  <th scope="col">Agreement</th>
                                  <th scope="col">Contractor Name</th>
                                  <th scope="col">Commencement Date</th>
                                  <th scope="col">Amount</th>
                                  <th scope="col">Department</th>
                                  <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach($agreement as $agreement): ?>
                                <tr>
                                  <th scope="row"><?= ++$i ?></th>
                                  <td><?= html_escape($agreement->agreement_no) ?></td>
                                  <td><?= html_escape($agreement->agreement) ?></td>
                                  <td><?= html_escape($agreement->name_of_contractor) ?></td>
                                  <td><?= html_escape(date('d-m-Y', strtotime($agreement->date_of_commencement))) ?></td>
                                  <td><?= html_escape($agreement->amount) ?></td>
                                  <td><?= html_escape($agreement->department) ?></td>
                                  <td>
                                    <?= anchor("chainage/$agreement->agreement_id", '<i class="mdi mdi-link-variant"></i>', ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Chainage']) ?>
                                    <span class="text-success" data-toggle="modal" data-target="#agreementInfo"><i class="mdi mdi-information-outline" data-toggle="tooltip" data-placement="top" title="Information"></i></span>
                                    <form action="<?= site_url("agreementForm/$agreement->agreement_id"); ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                    </form>
                                    <form action="<?= site_url("section/deleteAgreement/$agreement->agreement_id"); ?>" method="post" style="display:inline;" onsubmit="return doConfirm();">
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
        $('#agreement_table').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            search: true
        });
    });
</script>

<!-- Client-side validation to prevent spaces -->
<script>
    $(document).ready(function(){
        // Prevent spaces in fields
        $('input').on('keypress', function(e) {
            if(e.which === 32) {
                return false;
            }
        });

        // Additional client-side validation can be added here
    });
</script>
