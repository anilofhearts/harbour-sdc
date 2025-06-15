<!-- CSRF token -->
<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');

    //STATISTICAL DATA
    $today = time();
    $role_id=$data['role'];
    $agreement_id = $data['agreement'][0]->agreement_id;
    $difference = $today - strtotime($data['agreement'][0]->date_of_commencement);
    $ttlTrips = $data['stats']['ttlTrips'];
    $estWeight = $data['stats']['estimated_quantity'];
    $dumpSite = round($data['stats']['ttlNetWeight']);
    $phyProg =  round((round($data['stats']['ttlNetWeight'])/1000 /$data['stats']['estimated_quantity'])*100,2);
    $dailyTrips = $data['stats']['dailyTrips'];
    $dailyVehicles = $data['stats']['dailyVehicles'];
    $ttlGross = $data['stats']['dailyGrossWeight'] ? $data['stats']['dailyGrossWeight'][0]->gross_weight : 0 ;
    $itemWeight = $data['stats']['dailyItemWeight'] ? $data['stats']['dailyItemWeight'][0]->item_weight : 0 ;
    $netWeight = $data['stats']['dailyNetWeight'] ? round($data['stats']['dailyNetWeight'][0]->net_weight, 2) : 0 ;
    $loss = $itemWeight > 0 ? round(($itemWeight - $netWeight) * 100 / $itemWeight, 2) : 0 ;
    $dailyProg = $estWeight ? round(($netWeight * 100 / $estWeight), 2) : 0 ;

    // CHAINAGE DATA
    if(isset($data['chainage'])) {
        $locations = array_unique(array_column($data['chainage'], 'location'));
    }
    //$chainages = array_unique(array_column($data['chainage'], 'chainage'));
    //$no_items = count(array_unique(array_column($data['chainage'], 'item')));

    $it[0] = 'bg-warning'; $it[1] = 'bg-primary'; $it[2] = 'bg-cyan'; $it[3] = 'bg-success'; $it[4] = 'bg-warning'; $it[5] = 'bg-primary'; $it[6] = 'bg-cyan'; $it[7] = 'bg-success';
    // echo '<pre>'; print_r($data['chainage']); echo '</pre>'; 
    $qtyTtl = [0,0,0,0,0,0,0,0];
    $dumpedTtl = [0,0,0,0,0,0,0,0];
    $progTtl = [0,0,0,0,0,0,0,0];
 ?>
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
            <h4 class="page-title">DASHBOARD OF <?=strtoupper($data['agreement'][0]->agreement)?></h4>
            <div class="ml-auto text-right">
                <?php if($role_id != 'section'){ ?>
                    <?=anchor("/$role_id/generate_report/$agreement_id", 'Generate Report', ['class'=>'btn btn-primary']);?>
                <?php } ?>
                <!-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav> -->
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
    <!-- Sales Cards  -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-truck"></i><br><?=floor($difference / 86400)+1; ?></h1>
                    <h6 class="text-white">No of Days</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-cyan text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-truck"></i><br><?= $ttlTrips ?></h1>
                    <h6 class="text-white">Total Trips</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-weight"></i><br><?= round($estWeight,2) ?> T</h1>
                    <h6 class="text-white">Estimated Weight</h6>
                </div>
            </div>
        </div>
         <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-weight-kilogram"></i><br><?= round($dumpSite/1000,2)?>&nbsp;T
                    <h6 class="text-white">Dumped at Site</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-danger text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-ticket-percent"></i><br><?= $phyProg?> %</h1>
                    <h6 class="text-white">Physical Progress</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-2 col-lg-2">
            <div class="card card-hover">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">&#x20B9;<br><?= $data['finprog']?> %</h1>
                    <h6 class="text-white">Financial Progress</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->
    <div class="row">

    <?php if(isset($locations)) {
     foreach($locations as $location) {

        $this->loc = $location;
          $cng_loc = array_filter($data['chainage'], function($arr) {
              foreach ($arr as $arr1) {

                if($arr->location !== $this->loc){
                  return false;
                }

                $new = array(
                    'item' => $arr->item,
                    'location' => $arr->location,
                    );
                return $new;
              }
          });

          $i = 0;
          $cng_loc = array_values($cng_loc);
          $cng_item = array_unique(array_column($cng_loc, 'item'));
          $cng_unq = array_unique(array_column($cng_loc, 'chainage'));
          $no_items = count(array_unique(array_column($cng_loc, 'item')));
          // echo "<pre>"; print_r($data['chainage']); echo "</pre>";
        ?>
        <div class="card col-md-12">
            <div class="card-body">
                <div class="row">
                    <h4 class="card-title"><?=$location?></h4>

                </div>
                <div class="row">
                    <table id="trip_table" class="table table-bordered">
                        <thead>
                            <tr>

                              <th scope="col" rowspan="2" class="text-white bg-secondary">Chainage</th>
                              <?php $c=0; foreach($cng_item as $item1) { ?>
                                <th scope="col" colspan="3" class="text-center text-white <?php echo $it[$c++]?>"><?=$item1?></th>
                              <?php } ?>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="text-center">

                                <th class="text-white bg-secondary"></th>
                              <?php $c=0; foreach($cng_item as $item2) { ?>
                                <th scope="col" class="text-white <?php echo $it[$c]?>">Est Qty</th>
                                <th scope="col" class="text-white <?php echo $it[$c]?>">Dumped at Site</th>
                                <th scope="col" class="text-white <?php echo $it[$c]?>">Prog %</th>
                              <?php $c++; } ?>
                            </tr>
                        </thead>
                        <tbody>
                          <?php $sl=1;  foreach($cng_unq as $cng) { $c=0;?>
                            <tr class="text-right">

                              <td class="text-white bg-secondary"><?=$cng?></td>
                            <?php foreach($cng_item as $item3) { 
                                $qty =  $cng_loc[$i]->chainage_quantity;
                                $dumped = round($cng_loc[$i]->dumped/1000, 2);

                                $prog = ($cng_loc[$i]->chainage_quantity==0) ? '0' : round(($dumped/$qty)*100, 2);
                                ?>
                              <td class="text-white <?php echo $it[$c]?>"><?=$qty?></td>
                              <td class="text-white <?php echo $it[$c]?>"><?=$dumped?></td>
                              <td class="text-white <?php echo $it[$c]?>"><?=$prog?> %</td>
                            <?php $sl++; $i++; 
                                $qtyTtl[$c]= $qtyTtl[$c]+$qty;
                                $dumpedTtl[$c]=$dumpedTtl[$c]+$dumped;
                                $progTtl[$c]=$progTtl[$c]+$prog;
                                $c++;
                            }?>

                            </tr>
                          <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr class="font-bold font-italic text-right">
                                <td>Total</td>
                                <!-- <td></td> -->
                                <?php 
                                for ($i=0; $i < count($cng_item); $i++) { ?>
                                    <td><?=number_format($qtyTtl[$i], 2) ?></td>
                                    <td><?=$dumpedTtl[$i] ?></td>
                                    <td><?=($cng_loc[$i]->chainage_quantity==0) ? '0' :round(($dumpedTtl[$i]/$qtyTtl[$i])*100,2) ?> %</td>
                                <?php }
                                    $qtyTtl = [0,0,0,0,0,0,0,0];
                                    $dumpedTtl = [0,0,0,0,0,0,0,];
                                    $progTtl = [0,0,0,0,0,0,0,0]; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } } ?>
    </div>

    <!-- ============================================================== -->
    <!-- TABLE REPORT -->
    <!-- ============================================================== -->
    </div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
