<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');

    //echo '<pre>'; print_r($data['chainage']); echo '</pre>'; 
 ?>
<script src="<?= base_url()?>public/assets/libs/select2/dist/js/select2.min.js"></script>

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
    <?php

        $i=0;
        while ($i < count($data['section'])) {
            $today = time();
            $difference = $today - strtotime($data['agreement'][$i]->date_of_commencement);
            $ttlTrips = $data['stats'][$i]['ttlTrips'];
            $estWeight = $data['stats'][$i]['estimated_quantity'];
            $dumpSite = round($data['stats'][$i]['ttlNetWeight']);
            $phyProg = $data['stats'][$i]['estimated_quantity'] ? round($data['stats'][$i]['ttlNetWeight']/$data['stats'][$i]['estimated_quantity']*100): 0;
            $finProg = round(($data['ttl_exp'][$i]->ttl_exp*100)/$data['est_ttl_cost'][$i]->ttl_cost,2);
            
    ?>
    <div class="card col-md-12">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="card-title">SECTION : <?=strtoupper($data['section'][$i]->section);?></h4>
                </div>
                <div class="col-md-8">
                    <h4 class="card-title">AGREEMENT : <?=strtoupper($data['agreement'][$i]->agreement);?></h4>
                </div>
            </div>
            <!-- CARDS -->
            <div class="row">
                <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-truck"></i><br><?php //floor($difference / 86400); ?></h1>
                            <h6 class="text-white">No of Days</h6>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-cyan text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-truck"></i><br><?php  //$ttlTrips ?></h1>
                            <h6 class="text-white">Total Trips</h6>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-weight"></i><br><?php // $estWeight ?> T</h1>
                            <h6 class="text-white">Estimated Weight</h6>
                        </div>
                    </div>
                </div>
                 <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-weight-kilogram"></i><br><?php // $dumpSite ?> T
                            <h6 class="text-white">Dumped at Site</h6>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-danger text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-ticket-percent"></i><br><?php // $phyProg?> %</h1>
                            <h6 class="text-white">Physical Progress</h6>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-md-2 col-lg-2">
                    <div class="card card-hover">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white">&#x20B9;<br><?php // $finProg?> %</h1>
                            <h6 class="text-white">Financial Progress</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    
        $i++;
        }
        
    ?>
    <?php
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    ?>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
