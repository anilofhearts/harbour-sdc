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
 <div class="card col-md-12">
            <div class="card-body align-center">


                <?php
                $in_weight = $data['trip'][0]->in_weight;
                $out_weight = $data['trip'][0]->out_weight;
                $loss = $data['trip'][0]->onsite_loss;
                $gross_wt=$in_weight - $out_weight;
                $ded=$gross_wt*($loss/100);
    $final_ded=($ded==0) ? '0' :round($ded,-1);
                $net = $gross_wt - $final_ded;
                $net_new = ($in_weight - $out_weight) ;
                ?>

                <div id="printernew" style="width:100%; height:700px;">
                <h4 style="text-align:center;">
                    GOVERNMENT OF KERALA<br>
                  <?=strtoupper($data['agreement'][0]->agreement) ?><br>
                     WEIGHTMENT CARD<br>


                    </h4>
                    <hr>
                    <table style="width:100%; font-weigh:400;">

                    <tr><td>Vehicle No</td><td><?=strtoupper($data['trip'][0]->vehicle_no) ?></td>
                        <td>Card No</td>
                       <td><?=$data['trip'][0]->card_no ?></td>
                        </tr>

                        <tr><td>Cat Of Stones</td><td><?=$data['trip'][0]->item?></td>
                        <td>Trip No</td>
                        <td><?=$data['trip'][0]->trip_no?></td>
                        </tr>
                        <tr>
                        <td>Contractor Name</td><td  colspan="3" style="text-align:left;"><?=strtoupper($data['trip'][0]->name_of_contractor) ?></td>
                        </tr>
                     <tr><td>#ID</td><td><?=$data['agreement'][0]->short_code.$data['trip'][0]->trip_id?></td>
                        <td>No Of Stones</td>
                        <td>______________</td>
                        </tr>
                        <tr><td>Date Time In</td><td><?=date('d-m-Y h:i A', strtotime($data['trip'][0]->in_datetime))?></td>
                        <td>Gross Weight</td>
                        <td><b><?=$in_weight?>&nbsp;Kg</b></td>
                        </tr>
                        <tr>
                        <td>Date Time Out</td>
                        <td><?=date('d-m-Y h:i A', strtotime($data['trip'][0]->out_datetime))?></td>
                             <td>Deduction</td>
                              <?php ?>
                        <td><b><?=$final_ded?>&nbsp;Kg </b></td>
                        </tr>
                         <tr><td>DUMPED AT </td><td><?=$data['trip'][0]->location ?></td>
                        <td>Tare Weight</td>
                    <td><b><?=$out_weight?>&nbsp;Kg</b></td>
                        </tr>
                        <tr>
                            <td>Chainage</td>
                            <td><?=$data['trip'][0]->onsite_chainage ?>&nbsp;m</td>
                            <td>Net Weight </td><td style="font-size:150%;"><b><?=$net?>&nbsp;Kg</b></td>
                        </tr>
                        <tr>
                            <tr>
                        <td colspan="2"> <img src="<?php echo $data['trip'][0]->in_image; ?>" width="240" height="160" ></td>
                        <td colspan="2">

                            <div style="  position: relative;text-align: center;color: black;">
                                <div style=" position: absolute;  top: 8px;right: 16px; font-size:10px; text-align:left;">
                                 <?php



                                      $x =  $data['trip'][0]->lat_long;
                                      $x = explode(",",$x);
                                      if(isset($x[1]))
                                      {
                                          echo "Lat :".$x[0]."<br> Long:".$x[1]."<br>";
                                          echo "Date: ".$data['trip'][0]->onsite_datetime;
                                      }




                                      ?>
                                </div>
                            <img src="<?php echo $data['trip'][0]->onsite_image; ?>" width="240" height="160" >
                            </div>
                                </td>



                        <td colspan="2"></td>

                        </tr>


                        <tr style="margin-top:2%;">
                            <td colspan="4"><br> <span>Measurements Accepted</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RECORDED ON PAGE NO.......  OF MB.......... :</span></td>


                            <td colspan="3" > </td></tr>
                        <td colspan="3" style="text-align:center;">Contractor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Asst. Engineer</td>

                    </table>
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
/*
     window.onload = function () {
     window.print();
    }
    var printWindow = window.open('', '', 'height=400,width=1000');
   var divContents = $("#printernew").html() +
                        "<script>" +
                         "window.onload = function() {" +
                         "     window.print();" +
                         "};" +
                         "<" + "/script>";
    printWindow.document.write(divContents);
    printWindow.document.close();
    */
      window.onload = function () {

PrintElem("#printernew");


    };


        function PrintElem(elem) {
    Popup($(elem).html());
}

function Popup(data) {
    var mywindow = window.open('', 'new div', 'height=1000,width=1000');
    mywindow.document.write('<html><head><title></title>');
      mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.print();
   //mywindow.close();

    return false;
}
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
