<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');

?>
<!--<script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.full.min.js"></script>-->
<!-- <script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.min.js"></script> -->


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
            <h4 class="page-title">SHOWING TRIP DETAILS</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report</li>
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
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">
                <div class="row">
                    <?php //echo "trip-"; print_r($trip);?>
                    <div class="col-12">
                        <h5>AGREEMENT : </h5>
                    </div>
                    <div class="col-lg-3">
                        <h5>DATE : <?=$trip->in_datetime?></h5>
                    </div>
                    <div class="col-lg-3">
                        <h5>LOCATION : <?=$location->location?></h5>
                    </div>
                    <div class="col-lg-3">
                        <h5>VEHICLE NO : <?=$vehicle->vehicle_no?></h5>
                    </div>
                    <div class="col-lg-3">
                        <h5>TRIP NO : <?=$trip->trip_no?></h5>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="row el-element-overlay">
        <div class="col-lg-4">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="<?=$trip->in_image;?>" alt="IMAGE NOT AVAILABLE" style="height: 250px;" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <li class="el-item"><a class="btn default btn-outline image-popup-vertical-fit el-link" href="<?=$trip->in_image;?>"><i class="mdi mdi-magnify-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="el-card-content">
                        <h4 class="m-b-0">IN IMAGE</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="<?=$trip->onsite_image;?>" alt="IMAGE NOT AVAILABLE" style="height: 250px;" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <li class="el-item"><a class="btn default btn-outline image-popup-vertical-fit el-link" href="<?=$trip->onsite_image;?>"><i class="mdi mdi-magnify-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="el-card-content">
                        <h4 class="m-b-0">ONSITE IMAGE</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="<?=$trip->out_image;?>" alt="IMAGE NOT AVAILABLE" style="height: 250px;" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <li class="el-item"><a class="btn default btn-outline image-popup-vertical-fit el-link" href="<?=$trip->out_image;?>"><i class="mdi mdi-magnify-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="el-card-content">
                        <h4 class="m-b-0">OUT IMAGE</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->
    </div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

<script src="<?=base_url()?>public/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
<script src="<?=base_url()?>public/assets/libs/magnific-popup/meg.init.js"></script>