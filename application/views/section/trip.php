<?php
$this->load->view("library/firebase_api");
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');

    //STATISTICAL DATA
/*
    $ttlTrips = $stats['ttlTrips'];
    $estWeight = $stats['estimated_quantity'];
    $dumpSite = round($stats['ttlNetWeight']);
    $phyProg = round($stats['ttlNetWeight']/$stats['estimated_quantity']*100);
    $dailyTrips = $stats['dailyTrips'];
    $dailyVehicles = $stats['dailyVehicles'];
    $ttlGross = $stats['dailyGrossWeight'] ? $stats['dailyGrossWeight'][0]->gross_weight : 0 ;
    $itemWeight = $stats['dailyItemWeight'] ? $stats['dailyItemWeight'][0]->item_weight : 0 ;
    $netWeight = $stats['dailyNetWeight'] ? round($stats['dailyNetWeight'][0]->net_weight, 2) : 0 ;
    $loss = $itemWeight > 0 ? round(($itemWeight - $netWeight) * 100 / $itemWeight, 2) : 0 ;
    $dailyProg = round(($netWeight * 100 / $estWeight), 2) ;
*/
 ?>
<!-- First, include the Webcam.js JavaScript Library -->
<script type="text/javascript" src="<?php echo base_url() ?>public/webcam/webcam.min.js"></script>
<!--<script type="text/javascript" src="<?=base_url()?>public/html2canvas/html2canvas-master/dist/html2canvas.js"></script>-->
<!--<script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.full.min.js"></script>-->
<script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.min.js"></script>


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
            <h4 class="page-title">Trip Information</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Trip</li>
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

    <!-- Modal -->
    <div class="modal fade" id="vehicleForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1000px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open('section/addUpdateVehicle')?>
                    <?=form_hidden('dashboard', 1);?>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Vehicle No') ?>
                            <?php echo form_input(array('name' => 'vehicle_no', 'value'=> '' , 'placeholder' => 'Enter Vehicle No', 'required'=>'required', 'class'=>'form-control')); ?>
                            <?php echo form_error('vehicle_no', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group col-lg-2">
                            <?php echo form_label('Vehicle Type') ?>
                            <?php echo form_dropdown(array('name'=>'vehicle_type', 'options'=>$typeOfVehicle, 'class'=>'form-control')) ?>
                            <?php echo form_error('vehicle_type', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-2">
                            <?php echo form_label('Insurance Upto') ?>
                            <?php echo form_input(array('type'=>'date', 'name' => 'insurance_end_date', 'value'=> '' , 'required'=>'required', 'class'=>'form-control')); ?>
                            <?php echo form_error('insurance_end_date', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-5">
                            <?php echo form_label('RC Owner') ?>
                            <div class="row">
                            <?php echo form_input(array('name' => 'rc_owner', 'value'=> '' , 'placeholder' => 'Enter RC Owner', 'required'=>'required', 'class'=>'form-control')); ?>

                            </div>
                            <?php echo form_error('rc_owner', '<p class="text-danger">', '</p>'); ?>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php echo form_submit(array('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success'));?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <div class="row">

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">Add a Trip</h4>
                    </div>
                    <?php if($message): ?>
                    <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                    <?php endif;?>


                    <div class="row">

                        <div class="form-group col-lg-2.3">
                          <label><span>Vehicle No</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-success" data-toggle="modal" data-target="#vehicleForm"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add Vehicle"></i></span></label>

                       <!-- Form Trip Start -->



                            <form id="tripForm">
                        <?=form_hidden('agreement_id', $agreement[0]->agreement_id)?>
                        <input type="hidden" name="trip_type" id="trip_type" value="new">
                        <input type="hidden" name="trip_id" id="trip_id" value="">

                          <select name="trip_vehicle_id" id="trip_vehicle_id" onchange="getval(this.value)" class="form-control select2 custom-select" required="required">
                            <option value="">-- Select Vehicle --</option>
                              <?php foreach($vehicle as $vehicle): ?>
                                <option value="<?=$vehicle->vehicle_id?>" ><?=$vehicle->vehicle_no?></option>
                              <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Location') ?>
                            <select name="agreement_location_id" id="location" class="form-control" onchange="getchainage()">

                                <?php foreach($location as $loc): ?>
                                <option>-- Select Location --</option>
                                    <option value="<?=$loc->agreement_location_id?>"><?=$loc->location?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                          <?php echo form_label('Item Name')?>
                          <select name="agreement_item_id" id="item" class="form-control" onchange="getchainage()">
                                <option>-- Select Item --</option>
                                <?php foreach($item as $itm): ?>

                                    <option value="<?=$itm->agreement_item_id?>"><?=$itm->item?></option>
                                <?php endforeach; ?>
                            </select>
                          <?php echo form_error('agreement_item_id', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-2">
                            <?php echo form_label('Chainage') ?>

                            <select name="onsite_chainage" id="onsite_chainage" class="form-control">
                                <option value="">-</option>

                                <?php foreach ($chainage as $chn) { ?>

                                    <option value="<?=$chn->chainage?>"><?=$chn->chainage?></option>
                                <?php } ?>
                            </select>
                            <?php //echo form_input(array('name' => 'onsite_chainage', 'value'=>($editTrip) ? $editTrip[0]->onsite_chainage : '', 'id'=>'onsite_chainage', 'disabled'=>'disabled', 'placeholder' => 'Chainage', 'class'=>'form-control'), set_value('onsite_chainage')); ?>
                            <?php echo form_error('onsite_chainage', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group col-lg-1">
                            <?php echo form_label('Deduction(%)') ?>
                            <?php echo form_input(array('name' => 'onsite_loss', 'value'=>'', 'id'=>'onsite_loss', 'disabled'=>'disabled', 'placeholder' => 'Deduction', 'class'=>'form-control'), set_value('onsite_loss')); ?>
                            <?php echo form_error('onsite_loss', '<p class="text-danger">', '</p>'); ?>
                        </div>

                        <div class="form-group col-lg-1">
                            <?php echo form_label('. ') ?>

                            <button type="button" id='saver' class="btn btn-primary" onclick="submitit()">Save</button>
                        </div>
                        <!-- <div class="form-group col-lg-3">
                            <?php echo form_label('Quarry') ?>
                            <select name="trip_quarry_id" id="trip_quarry_id" class="form-control">
                                <?php foreach($quarries as $quarry): ?>
                                    <option value="<?=$quarry->vehicle_quarry_id?>"><?=$quarry->location?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> -->
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Contractor\'s Name') ?>
                            <?php echo form_input(array('name' => 'contractor_name', 'value'=>$agreement[0]->name_of_contractor, 'disabled'=>'', 'class'=>'form-control')); ?>
                        </div>
                        <div class="form-group col-lg-2">
                            <?php echo form_label('Trip No'); ?>
                            <?php echo form_input(array('name' => 'trip_no', 'id'=>'trip_no', 'value'=>'', 'readonly'=>'readonly', 'class'=>'form-control')); ?>
                        </div>
                        <div class="form-group col-lg-2">
                            <?php echo form_label('Card No') ?>
                            <?php echo form_input(array('name' => 'card_no', 'id'=>'card_no', 'value'=>'', 'readonly'=>'readonly', 'class'=>'form-control')); ?>
                        </div>
                        <?php $cur = date('d-m-Y H:i:s'); ?>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Date & Time') ?>
                            <?php echo form_input(array('name' => 'datetime', 'id'=>'datetime', 'value'=>date('d-m-Y H:i:s'), 'readonly'=>'', 'class'=>'form-control'), set_value('datetime')); ?>
                        </div>
                        <!--
                        <div class="form-group col-lg-3">
                            <label id="wtlabel">Gross Weight</label>
                            <div class="row">
                                <span id="weight" class="form-control col-md-8 weight">Reading...</span>
                                <input type="hidden" id="weight_com" name="weight" />
                                <?php echo form_error('in_weight', '<p class="text-danger">', '</p>'); ?>
                            </div>
                        </div>

                          <iframe id="videoframe" style="width:500px;height:264px;"></iframe>
                        -->
                    </div>

                    <div class="row">

                        <div class="streambox col-md-6" id="streambox" style="text-align: center;"> Live Stream <br>

                        <img id="snap"  class="img-thumbnail" crossOrigin=Anonymous/>
                        <div class="progress" style="height:20px;">
                          <div class="progress-bar" id="progress-bar" role="progressbar" aria-valuenow="70"
                          aria-valuemin="0" aria-valuemax="100" style="width:0%;">

                          </div>
                        </div>
                        <script>
                           function bigImg()
                            {
                                $.ajax({
        url: "http://127.0.0.1:8001/snapshot.jpg",
        type: 'get',
        dataType: 'html',
        async: false,
        crossDomain: 'true',
        success: function(data, status) {
if(data !='')
    {
document.getElementById("snap").setAttribute(
        'src',"http://127.0.0.1:8001/snapshot.jpg");
    }else{

    }


        }
});


                            }

                        </script>

                            <!--<div id="my_camera"></div>
                            <div hidden="hidden" id="results"></div>-->
                            <canvas id="canvas" width="320" height="240"></canvas>
                            <input id="mydata" type="hidden" name="mydata" value=""/>
                        </div>

                        <div class="col-md-6 align-middle" style="text-align: center;"> <label id="wtlabel" style="font-size: 24px;">Gross Weight</label>
                            <h1><span id="weight_1" class="badge badge-pill badge-success weight">0</span></h1>
                            <input  type="hidden" id="weight_com2" name="weight2" />
                            <input  id="weight" name="weight" readonly='readonly'/>

                            <button type="button" id='lock_wt' class="btn btn-primary" onclick="getValue()">Lock Wt</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="row">


                    </div>
                </div>
            </div>
        </div>
    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- DAILY REPORT -->
    <!-- ============================================================== -->
        <div class="col-md-3">

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center"><?=date('d-m-Y')?></h4>
                    <div class="row">
                        <h4 class="card-title">Day Total</h4>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-danger">No. of Trips</td>
                                <td><?=$data['trips_today']?></td>
                            </tr>
                            <?php $itnet=0; if(isset($data['item_today'])) {foreach($data['item_today'] as $it):?>
                            <tr>
                                <td class="text-danger"><?=$it->item?></td>
                                <td><?=round($it->net_weight/1000,3)?> T</td>
                                <?php $itnet = $itnet+$it->net_weight; ?>
                            </tr>
                            <?php endforeach; }?>
                            <tr class="font-bold">
                                <td class="text-danger">Total</td>
                                <td><?=round($itnet/1000,3)?> T</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <h4 class="card-title">Cumulative</h4>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-danger">No. of Trips</td>
                                <td><?=$data['trips_all']?></td>
                            </tr>
                            <?php $icnet=0; if(isset($data['item_cum'])) {
                                foreach($data['item_cum'] as $ic):?>
                            <tr>
                                <td class="text-danger"><?=$ic->item?></td>
                                <td><?=round($ic->net_weight/1000,-1)?> T</td>
                                <?php $icnet = $icnet+$ic->net_weight; ?>
                            </tr>
                            <?php endforeach; }?>
                            <tr class="font-bold">
                                <td class="text-danger">Total</td>
                                <td><?=round($icnet/1000,-1)?> T</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="text-success">
                                <td>Physical Progress</td>
                                <td class="font-bold"> <?=round((($icnet/1000)/($data['est_ttl']))*100,-1) ?> %</td>
                            </tr>
                            <tr class="text-success">
                                <td>Financial Progress</td>
                                <td class="font-bold"><?=round(($data['ttl_exp']->ttl_exp)/$data['est_ttl_cost']->ttl_cost*100,2)?> %</td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>
    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->

    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">
                <div class="row">
                    <h4 class="card-title">Recent Trips</h4>

                </div>
                <div class="row">

                    <table id="trip_table" class="table table-bordered table-dark">
                        <thead>
                            <tr>
                              <th scope="col">Card No</th>
                              <th scope="col">Date</th>
                              <th scope="col">Trip No</th>
                              <th scope="col">Vehicle No</th>
                              <th scope="col">Location</th>
                              <th scope="col">Item</th>
                              <th scope="col">Gross Weight</th>
                              <th scope="col">Deduction</th>
                              <th scope="col">Tare Weight</th>
                              <th scope="col">Net Weight</th>
                              <th scope="col">Card</th>
                              <!-- <th scope="col">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($trip){foreach($trip as $trip): $color='';
                                if($trip->onsite_datetime == ''){$color = 'bg-success';}
                                if($trip->onsite_datetime != '' && $trip->out_datetime==''){$color = 'bg-info';}?>
                            <tr class="<?=$color?>">
                              <td><?=$trip->card_no?></td>
                              <td><?=date('d-m-Y', strtotime($trip->in_datetime))?></td>
                              <td><?=$trip->trip_no?></td>
                              <td><?=$trip->vehicle_no?></td>
                              <td><?=$trip->location?></td>
                              <td><?=$trip->item?></td>
                              <td><?=$trip->in_weight?></td>
                                <?php $ded=($trip->in_weight- $trip->out_weight)* ($trip->onsite_loss/100);?>

                              <td><?=$final_ded=($ded==0) ? '0' :round($ded - ($ded % 10-10),-1)?></td>

                              <td><?=$trip->out_weight?></td>
                              <td><?=($trip->in_weight-$trip->out_weight)-$final_ded ?></td>
                              <td>
                                <?php echo anchor("tripCard/$trip->trip_id", '<i class="mdi mdi-truck"></i>', ['data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Trip Card']); ?>
                                <?php echo anchor("weightmentCard/$trip->trip_id", '<i class="mdi mdi-weight-kilogram"></i>', ['data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Weightmet Card']); ?>
                              </td>
                              <!-- Update Button -->
                                <form action="<?= site_url("editTrip/$trip->trip_id"); ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                    </form>
                                 <!-- Delete Button -->
                                 <form action="<?= site_url("deleteTrip/$trip->trip_id"); ?>" method="post" style="display:inline;" onsubmit="return doConfirm();">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>   
                                                           </td>
                            </tr>
                            <?php endforeach;} ?>
                        </tbody>
                    </table>
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



<script language="JavaScript">



$(document).ready(function(){
    var screen =  setInterval(bigImg,3000);
 //document.getElementById("weight").readOnly=true;

setInterval(lockWeight,5000);
 $('#saver').prop("disabled",true);
   $('#lock_wt').prop("disabled",true);
     document.getElementById("weight").readOnly=true;
});

function stop_capture() {
  clearInterval(screen);

}

function lockWeight(){

         jQuery.support.cors = true;
  //  $('#saver').prop("disabled",true);
     document.getElementById("weight").readOnly=true;
          $.ajax({url: "http://127.0.0.1:8000/", success:
                  function(result){

if (parseInt(result) > 0){
            /*var wt=result.split(':');
           var wt2=wt[1].split('\r');
              console.log(wt2);
           //  alert(result);
          //     $("#weight_com").val(result);
            // $("#weight").html(result);
           $("#weight_com").val(wt2[0]);*/
 // $('#saver').prop("disabled",false);
    $('#lock_wt').prop("disabled",false);
             $("#weight_1").html(result);

    $("#weight_com2").val(result);

}
              else if(parseInt(result)==0)
                  {

                      $('#lock_wt').prop("disabled",false);
               //   $('#saver').prop("disabled",true);
               //    $("#weight_com").val("0");
             $("#weight_1").html("0");
              }
  }});

}
    function getValue()
{

   var inputVal = document.getElementById("weight_com2").value;
    document.getElementById("weight").readOnly=true;

    $("#weight").val(inputVal);
  $('#saver').prop("disabled",false);
}

//***********************************//
// For select 2
//***********************************//
$(".select2").select2();

function getval(x)
{

    $.ajax({
        url:'getTrip',
        method: 'post',
        data: {vehicle_id:x},
        cache:false,
           dataType: "json",
          success:function(result){
                console.log(result);

            if(result.length >= 1){     //TRIP ONGOING
                //alert('x');
                $('#location').find('option').remove();
                $('#item').find('option').remove();
                $('#onsite_chainage').find('option').remove();
                // $('#trip_quarry_id').find('option').remove();
                $('#location').append('<option value="'+result[0]['agreement_location_id']+'">'+result[0]['location']+'</option>');
                $('#item').append('<option value="'+result[0]['agreement_item_id']+'">'+result[0]['item']+'</option>');
                $('#onsite_chainage').append('<option value="'+result[0]['onsite_chainage']+'">'+result[0]['onsite_chainage']+'</option>');
                // $('#trip_quarry_id').append('<option value="'+result[0]['trip_quarry_id']+'">'+result[0]['quarry_location']+'</option>');

                document.getElementById('trip_id').value = result[0].trip_id;
                document.getElementById('trip_no').value = result[0].trip_no;
                document.getElementById('datetime').value = result[0].in_datetime;
                document.getElementById('card_no').value = result[0].card_no;
document.getElementById("weight").readOnly=true;
                  $('#lock_wt').prop("disabled",false);
                if(!result[0].onsite_datetime) {    // TRIP IN. NOT ONSITE
                    document.getElementById('trip_type').value = 'onsite';
                    document.getElementById('onsite_loss').value = '0';
                    //document.getElementById('onsite_chainage').value = result[0].onsite_chainage;
                    $("#location").attr("disabled", "disabled");
                    $("#item").attr("disabled", "disabled");
                    $("#onsite_loss").removeAttr("disabled", "disabled");
                    $("#onsite_chainage").attr("disabled", "disabled");
                    document.getElementById('onsite_loss').value = '0';
                    document.getElementById('weight').innerText = result[0].in_weight;
                    document.getElementById("weight").readOnly=true;
                     $('#lock_wt').prop("disabled",false);
                    // $("#trip_quarry_id").attr("disabled", "disabled");
                    <?php //$trip_type='out';?>
                } else{                         // TRIP ONSITE. FOR OUT.
                    document.getElementById('onsite_loss').value = result[0].onsite_loss
                    ;
                    document.getElementById('onsite_chainage').value = result[0].onsite_chainage
                    ;
                    document.getElementById('trip_type').value = 'out';
                    $("#location").attr("disabled", "disabled");
                    $("#item").attr("disabled", "disabled");
                    $("#onsite_loss").attr("disabled", "disabled");
                    $("#onsite_chainage").attr("disabled", "disabled");
                    $("#weight").removeAttr("readonly", "readonly");
                    // $("#trip_quarry_id").attr("disabled", "disabled");
                    document.getElementById('wtlabel').innerText = 'Tare Weight';
                     $('#lock_wt').prop("disabled",false);
                    <?php //$trip_type='in';?>
                }
            } else{                                 // TRIP FOR IN.
                //alert('y');
                $('#location').removeAttr("disabled", "disabled");
                $('#location').find('option').remove();
                 $('#lock_wt').prop("disabled",false);
                <?php $list1 =''; foreach($location as $loc1){
                                $list1 .= "<option value=$loc1->agreement_location_id >$loc1->location</option>";
                              }

                echo "var x = \" $list1 \";"; ?>
                $('#location').html(x);

                $('#item').removeAttr("disabled", "disabled");
                $('#item').find('option').remove();
                <?php $list2 =''; foreach($item as $itm1){
                                $list2 .= "<option value=$itm1->agreement_item_id >$itm1->item</option>";
                              }

                echo "var y = \" $list2 \";"; ?>
                $('#item').html(y);

                $('#onsite_chainage').removeAttr("disabled", "disabled");
                $('#onsite_chainage').find('option').remove();
                <?php $list3 ='';
                if(isset($chainage))
                {foreach($chainage as $chn){
                                $list3 .= "<option value='$chn->chainage' >$chn->chainage</option>";
                              }
                }
                echo "var z = \" $list3 \";"; ?>
                $('#onsite_chainage').html(z);
                /*
                $('#trip_quarry_id').removeAttr("disabled", "disabled");
                $('#trip_quarry_id').find('option').remove();
                <?php $list4 ='';
                if(isset($quarry))
                {foreach($quarry as $qry){
                    $list4 .= "<option value='$qry->quarry_id' selected='3' >$qry->quarry_location</option>";
                              }
                }
                echo "var q = \" $list4 \";"; ?>
                $('#trip_quarry_id').html(q);
                */
                document.getElementById('trip_type').value = 'new';
                document.getElementById('trip_id').value = '';
                document.getElementById('datetime').value = <?php echo "\" $cur \";"; ?>;
                document.getElementById('trip_no').value = <?php echo "\" $next_trip \";"; ?>;
                document.getElementById('card_no').value = <?php echo "\" $next_card \";"; ?>;
                document.getElementById('wtlabel').innerText = 'Gross Weight';
                document.getElementById('onsite_loss').value = '';
                $("#onsite_loss").attr("disabled", "disabled");
                $("#weight").removeAttr("readonly", "readonly");

            }
                /*
              $('#sel_division').find('option').not(':first').remove();
              $('#sel_subdivision').find('option').not(':first').remove();
              $('#sel_section').find('option').not(':first').remove();

               for (var i = 0, len = result.length; i < len; ++i) {

                    $('#sel_division').append('<option value="'+result[i]['division_id']+'">'+result[i]['division']+'</option>');

               }
               */
        }
      });
}

function getchainage() {

    var location_id = document.getElementById('location').value;
    var item_id = document.getElementById('item').value;
    $.ajax({
        url:'getChainage',
        method: 'post',
        data: {chainage_agr_loc_id:location_id, chainage_item_id:item_id},
        cache:false,
        dataType: "json",
          success:function(result){
                //console.log(data);
                //console.log(result);
                $('#onsite_chainage').find('option').remove();

                for (var i = 0, len = result.length; i < len; ++i) {

                    $('#onsite_chainage').append('<option value="'+result[i]['chainage']+'">'+result[i]['chainage']+'</option>');

               }
            }
        });
}

$(document).ready( function () {
    $('#trip_table').DataTable({
    responsive: true,
    paging: true,
    search: true,
    "order": [[ 0, "desc" ]]
})
});

    function submitit()
{
    stop_capture();

    var trip = document.getElementById("trip_vehicle_id").value;
    var trip_id = document.getElementById("trip_id").value;

   // var weight=document.getElementById("weight").value;
   var data = $("#tripForm").serialize();

    if(trip == '')
    {
        alert("Invalid Entry");
    }else{
        if(trip_id.length > 1)
        {
            $.post( "addUpdateTrip", data)
             .done(function( suc ) {
                window.location.href= suc;
            });

        }else{
            <?php $x = rand(1,1000000000);?>



  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
    var storage = firebase.storage();



var remoteimageurl = "http://127.0.0.1:8001/snapshot.jpg";
var filename ="site%2F"+<?php echo $x;?>+".jpg";

fetch(remoteimageurl).then(res => {
  return res.blob();
}).then(blob => {
    //uploading blob to firebase storage
  firebase.storage().ref().child(filename).put(blob).then(function(snapshot) {
      var percentage = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        uploader = document.getElementById("progress-bar");
        uploader.style.width = percentage.toFixed(2)+'%';
        uploader.innerHTML = percentage.toFixed(2)+'%';
    return snapshot.ref.getDownloadURL()
 }).then(url => {
   console.log("Firebase storage image uploaded : ", url);
          var data = $("#tripForm").serialize();
data = data+"&in_image="+encodeURIComponent(url);
      console.log(url);
      console.log(data);

$.post( "addUpdateTrip", data)
  .done(function( suc ) {
  window.location.href= suc;
  //console.log('Server response:', suc);
  });
  })
}).catch(error => {
  console.error(error);
});





              }



                }
}
     function getBase64Image(img) {
  var canvas = document.createElement("canvas");
  canvas.width = img.width;
  canvas.height = img.height;
  var ctx = canvas.getContext("2d");
  ctx.drawImage(img, 0, 0);
  var dataURL = canvas.toDataURL();
       // Split the base64 string in data and contentType
var block = dataURL.split(";");
// Get the content type of the image
var contentType = block[0].split(":")[1];// In this case "image/gif"
// get the real base64 content of the file
var realData = block[1].split(",")[1];

  return realData;
                      //console.log(dataURL);
}
</script>
<script src="<?=base_url()?>public/latestweight/files/main.js"></script>
