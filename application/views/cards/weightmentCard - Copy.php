<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">

    <!-- MUST BE REMOVED ON DEPLOYMENT (AUTO REFRESH) 
    <meta http-equiv="refresh" content="10"/>       -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>public/assets/images/favicon.png">
    <title>Harbour Management</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/assets/libs/select2/dist/css/select2.min.css">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/dist/css/style.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/latestweight/files/main.css" rel="stylesheet">
    
    <!-- Datatable CSS -->
    <link href="<?php echo base_url(); ?>public/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!--   <script src="<?php echo base_url(); ?>public/assets/libs/jquery/dist/jquery.min.js"></script>-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <script src="<?php echo base_url(); ?>public/assets/libs/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap tether Core JavaScript -->

    <script src="<?php echo base_url(); ?>public/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
</script>




    <!-- Bootstrap tether Core JavaScript -->



    <style type="text/css">
        .headertitle {
            background: black;
            color: white;
            text-align: center;
            font-size: large;
            font-weight: bold;
        }
    </style>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">

<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <div class="row" id="printarea">

    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->
        <?php //echo '<pre>'; print_r($data) ; echo '</pre>'; ?>
        <div class="card col-md-12">
            <div class="card-body align-center">
                <h4 style="text-align: center;"><?=strtoupper($data['section'][0]->section)?><br>
                    <?=strtoupper($data['agreement'][0]->agreement) ?></h4>
                <h4 style="text-align: center;text-decoration: underline;">WEIGHTMENT CARD</h4>
                <?php
                $in_weight = $data['trip'][0]->in_weight;
                $out_weight = $data['trip'][0]->out_weight;
                $loss = $data['trip'][0]->onsite_loss;
                $net = ($in_weight - $out_weight) * (1 - $loss/100);
                ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>CARD NO : </span><span><?=$data['agreement'][0]->short_code.$data['trip'][0]->card_no?></span>
                            </div>
                            <div class="col-md-6">
                                <span>DATE : </span><span><?=date('d-m-Y')?></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>VEHICLE NO : </span><span><?=strtoupper($data['trip'][0]->vehicle_no) ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>TRIP NO : </span><span><?=$data['trip'][0]->trip_no?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>NAME OF CONTRACTOR : </span><span><?=strtoupper($data['trip'][0]->name_of_contractor) ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>CATEGORY OF STONE : </span><span><?=$data['trip'][0]->item?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <span>GROSS : </span><span><?=$in_weight?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>DATE AND TIME IN : </span><span><?=date('d-m-Y h:i A', strtotime($data['trip'][0]->in_datetime))?></span></span>
                            </div>
                            <div class="col-md-6">
                                <span>LOSS : </span><span><?=$loss?> %</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <span>TARE : </span><span><?=$out_weight?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>DATE AND TIME OUT :</span><span><?=date('d-m-Y h:i A', strtotime($data['trip'][0]->out_datetime))?></span></span>
                            </div>
                            <div class="col-md-6">
                                <span>NET : </span><span><?=$net?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="font-bold">DUMPED AT : </span><span><?=$data['trip'][0]->location ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="font-bold">CHAINAGE : </span><span><?=$data['trip'][0]->onsite_chainage ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>RECORDED ON PAGE NO............. OF MB.............. :</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <img src="<?php echo $data['trip'][0]->in_image; ?>" class="img-thumbnail img-responsive" >
                                </div>
                            <div class="col-md-6">
                                <div class="custom">
                            <img src="<?php echo $data['trip'][0]->onsite_image; ?>" class="img-responsive img-thumbnail"  >

                                  <div class="top-right">


                                      <?php



                                      $x =  $data['trip'][0]->lat_long;
                                      $x = explode(",",$x);
                                      if(isset($x[1]))
                                      {
                                          echo "Lat :".$x[0]." Long:".$x[1]."<br>";
                                      }
                                      echo "Date: ".$data['trip'][0]->onsite_datetime;



                                      ?></div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>ASST ENGINEER</span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <?=anchor('trip', '<i class="mdi mdi-arrow-left">Trips</i>'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                All Rights Reserved by itdock.in Designed and Developed by <a href="https://itdock.in">ITDOCK Technologies PVT LTD</a>.
            </div>
            
        </div>
    </div>

        
<!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

 <script type="text/javascript">

    window.onload = function () {
        window.print();
    }
   // var printWindow = window.open('', '', 'height=400,width=800');
   // var divContents = $("#printarea").html() +
   //                      "<script>" +
   //                      "window.onload = function() {" +
   //                      "     window.print();" +
   //                      "};" +
   //                      "<" + "/script>";
   // printWindow.document.write(divContents);
   // printWindow.document.close();
</script>

            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <!-- <footer class="footer text-center">
                All Rights Reserved by itdock.in Designed and Developed by <a href="https://itdock.in">ITDOCK Technologies PVT LTD</a>.
            </footer> -->
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    
    <!-- Datatable js -->
    


    <!-- <script src="<?php echo base_url(); ?>public/assets/libs/popper.js/dist/umd/popper.min.js"></script> -->
    <script src="<?php echo base_url(); ?>public/assets/extra-libs/DataTables/datatables.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->


    <script src="<?php echo base_url(); ?>public/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>public/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url(); ?>public/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url(); ?>public/dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <!-- <script src="<?php echo base_url(); ?>public/dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/excanvas.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/jquery.flot.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/jquery.flot.time.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo base_url(); ?>public/dist/js/pages/chart/chart-page-init.js"></script> -->
</body>

</html>
