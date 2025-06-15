<?php
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
    //echo '<pre>'; print_r($data['chainage']); echo '</pre>';
 ?>
<script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.min.js"></script>
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
            <h4 class="page-title">Dashboard</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->

    <?php foreach($bricks as $brick){
        echo $brick;
    } ?>

        <?php // echo "<pre>"; print_r($subdivisions);  echo "</pre>"; ?>
        

    <!-- ============================================================== -->
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->
    </div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
