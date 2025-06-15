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
        <div class="card col-md-8">
            <div class="card-body align-center">
                <h4 style="text-align: center;"><?=strtoupper($data['section'][0]->section)?><br>
                    <?=strtoupper($data['agreement'][0]->agreement) ?></h4>
                <h4 style="text-align: center;text-decoration: underline;">TRIP CARD</h4>
                <?php
                $in_weight = $data['trip'][0]->in_weight;
                $out_weight = $data['trip'][0]->out_weight;
                $loss = $data['trip'][0]->onsite_loss;
                $net = ($in_weight - $out_weight) * (1 - $loss/100);
                ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span>CARD NO : </span><span><?=$data['agreement'][0]->short_code.$data['trip'][0]->card_no?></span>
                            </div>
                            <div class="col-md-4">
                                <span>DATE : </span><span><?=date('d-m-Y')?></span></div>
                        </div>
                        <div class="row">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span>VEHICLE NO : </span><span><?=strtoupper($data['trip'][0]->vehicle_no) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span>TRIP NO : </span><span><?=$data['trip'][0]->trip_no?></span>
                            </div>
                        </div>
                        <div class="row">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span>NAME OF CONTRACTOR : </span><span><?=strtoupper($data['trip'][0]->name_of_contractor) ?></span>
                            </div>
                            <div class="col-md-4">
                                <span>CATEGORY OF STONE : </span><span><?=$data['trip'][0]->item?></span>
                            </div>
                        </div>
                        <div class="row">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-8">

                            </div>
                            <div class="col-md-4">
                                <span>GROSS : </span><span><?=$in_weight?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span>DATE AND TIME IN : </span><span><?=date('d-m-Y h:i A', strtotime($data['trip'][0]->in_datetime))?></span></span>
                            </div>
                            <div class="col-md-4">
                                <span>LOSS : </span><span><?=$loss?> %</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">

                            </div>
                            <div class="col-md-4">
                                <span>TARE : </span><span><?=$out_weight?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span>DATE AND TIME OUT :</span><span><?php if(is_null($data['trip'][0]->out_datetime)){echo '';}else{date('d-m-Y h:i A', strtotime($data['trip'][0]->out_datetime));}?></span></span>
                            </div>
                            <div class="col-md-4">
                                <span>NET : </span><span><?=$net?> T</span>
                            </div>
                        </div>
                        <div class="row">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <span class="font-bold">TO BE DUMPED AT : </span><span><?=$data['trip'][0]->location ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="font-bold">CHAINAGE : </span><span><?=$data['trip'][0]->onsite_chainage ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>RECORDED ON PAGE NO..... OF MB..... :</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <img src="<?php echo urldecode($data['trip'][0]->in_image); ?>" width="240" height="160" >
                            
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <span>ASST ENGINEER/ OVERSEER</span>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                <span>ASST ENGINEER</span>
                            </div>
                        </div>
                        <div class="row">
                            <p></p>
                        </div>
                        <div style="text-align: right;">
                            <?=anchor('trip', '<i class="mdi mdi-arrow-left">Trips</i>'); ?>
                        </div>
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

   var printWindow = window.open('', '', 'height=400,width=800');
   var divContents = $("#printarea").html() +
                        "<script>" +
                        "window.onload = function() {" +
                        "     window.print();" +
                        "};" +
                        "<" + "/script>";
   printWindow.document.write(divContents);
   printWindow.document.close();

</script>
