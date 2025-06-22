<?php 
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
<script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.full.min.js"></script>
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
            <h4 class="page-title">Report Generation</h4>
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">Filter Fields</h4>
                    </div>
                    <?php //if(isset($data)){print_r($data);};  ?>
                    <?php if($message): ?>
                    <div class="alert <?=$messageClass?>" role="alert"><?=$message?></div>
                    <?php endif;?>
                    

                    <div class="row col-lg-8">

                        <div class="form-group col-lg-3">
                            
                          <label>Vehicle No</label>
                        <?php $url = $role.'/generate_report/'.$data['trip.agreement_id'];?>  
                        <?php echo form_open($url); ?>

                          <select name="trip_vehicle_id" class="form-control select2 custom-select">
                            <option value="">-- Select Vehicle --</option>
                              <?php foreach($vehicle as $vehicle): ?>
                                <option value="<?=$vehicle->vehicle_id?>" ><?=$vehicle->vehicle_no?></option>
                              <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Location') ?>
                            <select name="agreement_location_id" id="location" class="form-control" onchange="getchainage()">
                                <option value="">-- Select Location --</option>
                                <?php foreach($location as $loc): ?>
                                    <option value="<?=$loc->agreement_location_id?>"><?=$loc->location?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <?php echo form_label('Item Name')?>
                          <select name="agreement_item_id" id="item" class="form-control" onchange="getchainage()">
                                <option value="">-- Select Item --</option>
                                <?php foreach($item as $itm): ?>
                                    <option value="<?=$itm->agreement_item_id?>"><?=$itm->item?></option>
                                <?php endforeach; ?>
                            </select>
                          <?php echo form_error('agreement_item_id', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Chainage') ?>

                            <select name="onsite_chainage" id="onsite_chainage" class="form-control">
                                <option value="">-</option>
                                <?php foreach ($chainage as $chn) { ?>
                                    <option value="<?=$chn->chainage?>"><?=$chn->chainage?></option>
                                <?php } ?>
                            </select>
                            <?php echo form_error('onsite_chainage', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('Card No.') ?>
                            <?=form_input('card_no', '', array('class'=>'form-control')); ?>
                            <?php echo form_error('card_no', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('From') ?>
                            <input type="date" name="date_from" class="form-control">
                            <?php echo form_error('date_from', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        <div class="form-group col-lg-3">
                            <?php echo form_label('To') ?>
                            <input type="date" name="date_to" class="form-control">
                            <?php echo form_error('date_to', '<p class="text-danger">', '</p>'); ?>
                        </div>
                        
                        <div class="form-group col-lg-2">
                            <?php echo form_label('. ') ?>
                            <?php echo form_submit(array('name'=>'submit', 'value'=>'Search', 'class'=>'btn btn-primary form-control'));?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

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
                    <h4 class="card-title">Report</h4>

                </div>
                <div class="row">
                    <?php //echo "trip-"; print_r($trip);?>
                    <table id="trip_table" class="table table-bordered">
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
                              <th scope="col">Pics</th>
                              <th scope="col">Cards</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $ttlTrips=0; $netWeight=0; $ttlNetWeight=0; ?>

                            <?php if($trip){foreach($trip as $trip): $color='';
                                if($trip->onsite_datetime == ''){$color = 'bg-success text-white';}
                                if($trip->onsite_datetime != '' && $trip->out_datetime==''){$color = 'bg-info text-white';}?>
                            <?php $ded=($trip->in_weight- $trip->out_weight)* ($trip->onsite_loss/100);
                          // $final_ded=($ded==0) ? '0' :round($ded - ($ded % 10-10),-1);
                          $final_ded=($ded==0) ? '0' :round($ded,-1); ?>

                            <?php $netWeight = $trip->in_weight-$trip->out_weight-$final_ded; ?>
                            <tr class="<?=$color?>">
                              <td><?=$trip->card_no?></td>
                              <td><?=date('d-m-Y', strtotime($trip->in_datetime))?></td>
                              <td><?=$trip->trip_no?></td>
                              <td><?=$trip->vehicle_no?></td>
                              <td><?=$trip->location?></td>
                              <td><?=$trip->item?></td>
                              <td><?=$trip->in_weight?></td>
                              <td><?=$final_ded?></td>
                              <td><?=$trip->out_weight?></td>
                              <td><?=$netWeight?></td>
                              <td><?=anchor("$role/trip_single/$trip->trip_id", 'Show', array('class'=>'btn btn-link btn-sm', 'target'=>'_blank'));?></td>
                              <td>
                                <?php echo anchor("tripCard/$trip->trip_id", '<i class="mdi mdi-truck"></i>', ['data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Trip Card']); ?>
                                <?php echo anchor("weightmentCard/$trip->trip_id", '<i class="mdi mdi-weight-kilogram"></i>', ['data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Weightmet Card']); ?>
                              </td>
                            </tr>
                            <?php $ttlTrips++; $ttlNetWeight = $ttlNetWeight + $netWeight;?>
                            <?php endforeach;} ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-danger text-center font-weight-bold">
                                <td colspan="9"> Total trips : <?=$ttlTrips?> </td>
                                <td><?=$ttlNetWeight/1000?>&nbsp;T</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
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

//***********************************//
// For select 2
//***********************************//
$(".select2").select2();

function getchainage() {

    var location_id = document.getElementById('location').value;
    var item_id = document.getElementById('item').value;
    $.ajax({
        url: '<?=base_url()?>index.php/getChainage',
        method: 'post',
        data: {chainage_agr_loc_id:location_id, chainage_item_id:item_id},
        cache:false,
        dataType: "json",
         success:function(result){
               // console.log(data);
                //console.log(result);
               $('#onsite_chainage').find('option').remove();
               for (var i = 0, len = result.length; i < len; ++i) {

                  $('#onsite_chainage').append('<option value="'+result[i]['chainage']+'">'+result[i]['chainage']+'</option>');

              }
            }
        });
}

/****************************************
 *       Basic Table                   *
 ****************************************/
$(document).ready( function () {
    $('#trip_table').DataTable({
    responsive: true,
    paging: true,
    search: true,
    "order": [[ 0, "desc" ]]
})
});

</script>
