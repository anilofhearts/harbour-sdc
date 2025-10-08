<?php 
    $message = $this->session->flashdata('message');
    $messageClass = $this->session->flashdata('messageClass');
 ?>

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
            <h4 class="page-title"><?php if($editAgre){ echo 'Update';} else{echo 'Add';} ?> Agreement Form</h4>
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
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

              <?php echo form_open('section/addUpdateAgreement');?>
              <?=form_hidden('agreement_id',  ($editAgre) ? $editAgre[0]->agreement_id : '');?>

              <div class="card-body alert alert-success">
                <div class="row">
                    <h4 class="alert-heading">Agreement Details</h4>
                </div>
                <?php //echo '<pre>'; print_r($data); echo '</pre>'; echo $data['item']['0']->item;?>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Agreement No') ?>
                        <input type="text" name="agreement_no" value="<?php if($editAgre) {echo $editAgre[0]->agreement_no;} else{set_value('agreement_no');} ?>" placeholder="Enter Agreement No" class="form-control" required <?php if(!$editAgre){}else{echo 'readonly';} ?>>
                        <?php echo form_error('agreement_no', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Date of Agreement') ?>
                        <?php echo form_input(array('type'=>'date', 'name' => 'date_of_agreement', 'value'=> ($editAgre) ? $editAgre[0]->date_of_agreement : set_value('date_of_agreement'), 'required'=>'required', 'class'=>'form-control')); ?>
                        <?php echo form_error('date_of_agreement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Name of Work') ?>
                        <?php echo form_input(array('name' => 'agreement', 'value'=> ($editAgre) ? $editAgre[0]->agreement : set_value('agreement'),  'placeholder' => 'Enter Name Agreement', 'required'=>'required', 'class'=>'form-control'), set_value('agreement')); ?>
                        <?php echo form_error('agreement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Amount') ?>
                        <?php echo form_input(array('name' => 'amount', 'value'=> ($editAgre) ? $editAgre[0]->amount : set_value('amount'),  'placeholder' => 'Enter Amount', 'required'=>'required', 'class'=>'form-control')); ?>
                        <?php echo form_error('amount', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                      <?php echo form_label('Date of Commencement')?>
                      <?php echo form_input (array('type'=>'date', 'name'=>'date_of_commencement', 'value'=> ($editAgre) ? $editAgre[0]->date_of_commencement : set_value('date_of_commencement'),  'class'=>'form-control')); ?>
                      <?php echo form_error('date_of_commencement', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                      <?php echo form_label('Exp. Date of Completion')?>
                      <?php echo form_input (array('type'=>'date', 'name'=>'exp_date_of_completion', 'value'=> ($editAgre) ? $editAgre[0]->exp_date_of_completion : set_value('exp_date_of_completion'),  'class'=>'form-control')); ?>
                      <?php echo form_error('exp_date_of_completion', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Type of Work') ?>
                          <select name="type_of_work" id="sel_work" class="form-control">
                            <option>-- Select Type of Work --</option>
                            <?php foreach ($type_of_work as $type_of_work):?>
                              <option value="<?=$type_of_work->work_type?>" <?php if ($editAgre && $type_of_work->work_type == $editAgre[0]->type_of_work) {
                                echo 'selected="'.$type_of_work->work_type.'"';
                              }
                              ?> ><?=$type_of_work->work_type?></option>
                            <?php endforeach; ?>
                          </select>
                        <?php echo form_error('type_of_work', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <?php echo form_label('Short Code') ?>
                        <?php echo form_input(array('name' => 'short_code', 'value'=> ($editAgre) ? $editAgre[0]->short_code : set_value('short_code'),  'placeholder' => 'Short Code for Card No.', 'required'=>'required', 'class'=>'form-control'), set_value('short_code')); ?>
                        <?php echo form_error('short_code', '<p class="text-danger">', '</p>'); ?>
                    </div>
                </div>
              </div>
            <div class="row">
              <div class="card col-md-6">
                <div class="card-body alert alert-danger">
                  <h4 class="card-title">Item Details</h4>
                  <div class="row mb-2">
                    <div class="col-12">
                      <input type="button" value="Add Row" onclick="addItemRow()" class="btn btn-success btn-sm mr-2" />
                      <input type="button" value="Delete Row" onclick="deleteItemRow()" class="btn btn-danger btn-sm" />
                    </div>
                  </div>
                  <div class="row">
                    <table id="itemTable" width="100%" border="0">
                          <tr class="text-center">
                            <th></th>
                            <th width="25%">Item</th>
                            <th width="20%">Agreement Quantity</th>
                            <th width="15%">Unit</th>
                            <th width="20%">Agreement Rate</th>
                            <th width="20%">Agreement Amount</th>
                          </tr>
                      <?php $grTtl = 0; if ($data['item']) {

                        foreach($data['item'] as $item): // print_r($item);
                        $tl = 0;
                        if($item->item == "Tetrapod"){
                          $item->estimated_quantity = $item->estimated_quantity / 2;
                          $item->estimated_rate = number_format($item->estimated_rate * 2, 2);
                        }

                        $tl = $item->estimated_quantity * $item->estimated_rate ;
                        $grTtl = $grTtl + $tl ;
                        ?>
                          <tr>
                            <input type="hidden" name="agreement_item_id[]" value="<?=$item->agreement_item_id?>">
                            <td><input type="checkbox" name="chk" class="form-control"/></td>
                            <td><select name="item[]" id="sel_item" class="form-control" onchange="setUnit(this)">
                                <option value="in">-- Select Item --</option>
                                <?php foreach($data['item'] as $opItem ){ ?>
                                  <option value="<?=$opItem->item?>" <?php if($item->item==$opItem->item){echo "selected=".$opItem->item;}?>><?=$opItem->item?></option>
                                <?php } ?>
                              </select>
                              <!-- <input type="text" name="item[]" value="<?=$item->item?>" class="form-control"/> -->
                              </td>
                            <td><input type="text" name="estimated_quantity[]" id="qty" value="<?=$item->estimated_quantity?>" class="form-control text-right" onchange="calcAmt(this)"/></td>

                            <td><input type="text" name="unit[]" id="unit" value="<?=$item->unit?>" class="form-control" readonly=""></td>

                            <td><input type="text" name="estimated_rate[]" id="amt" value="<?=$item->estimated_rate?>" class="form-control text-right" onchange="calcAmt(this)"/></td>
                            <td><input type="text" id="ttlAmt" value="<?=$tl?>" class="form-control text-right" readonly=""> </td>
                          </tr>
                        <?php endforeach; } else { ?>
                          <tr>
                            <td><input type="checkbox" name="chk" class="form-control"/></td>
                            <td>
                              <select name="item[]" id="sel_item" class="form-control" onchange="setUnit(this)">
                                <option value="in">-- Select Item --</option>
                              </select>
                            </td>

                            <td><input type="text" name="estimated_quantity[]" value="0" id="qty" placeholder="Estimated Qty" class="form-control text-right" onchange="calcAmt(this)" />
                            </td>
                            <td><input type="text" name="unit[]" value="unit" id="unit" class="form-control" readonly=""></td>
                            <td><input type="text" name="estimated_rate[]" value="0" id="amt" placeholder="Est. Amount" class="form-control text-right" onchange="calcAmt(this)"/>
                            </td>
                            <td><input type="text" name="ttlAmt" id="ttlAmt" value="0" class="form-control text-right" readonly=""> </td>
                          </tr>
                        <?php  } ?>
                    </table>
                    <table width="100%" border="0">
                      <tr>
                        <th colspan="5" class="text-right">Total Agreed Amount</th>
                        <th width="20%">
                          <input type="text" name="total" id="total" value="<?=$grTtl?>" class="form-control text-right" readonly="">
                        </th>
                      </tr>
                    </table>

                  </div>
                </div>
              </div>
              <div class="card col-md-6">
                <div class="card-body alert alert-danger">
                  <h4 class="card-title">Location Details</h4>
                  <div class="row mb-2">
                    <div class="col-12">
                      <input type="button" value="Add Row" onclick="addLocRow()" class="btn btn-success btn-sm mr-2" />
                      <input type="button" value="Delete Row" onclick="deleteLocRow()" class="btn btn-danger btn-sm" />
                    </div>
                  </div>
                  <div class="row">
                    <table id="locTable">

                      <?php if ($data['loc']) {
                            foreach($data['loc'] as $loc): //print_r($loc);?>
                        <tr>
                          <input type="hidden" name="agreement_location_id[]" value="<?=$loc->agreement_location_id?>" class="form-control" readonly="readonly" >
                          <td><input type="checkbox" name="chk" class="form-control"/></td>
                          <td><input type="text" name="location[]" value="<?=$loc->location?>" class="form-control"/></td>
                        </tr>
                        <?php endforeach; } else { ?>
                          <tr>
                            <th></th>
                            <th>Location</th>
                          </tr>
                          <tr>
                            <td><input type="checkbox" name="chk" class="form-control"/></td>
                            <td><input type="text" name="location[]" placeholder="Enter Location" class="form-control"/></td>
                          </tr>
                        <?php }?>
                        
                    </table>
                    <div class="form-group">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body alert alert-success">
                <div class="row">
                    <h4 class="card-title">Contractor Details</h4>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                      <?php echo form_label('Name of Contractor')?>
                      <?php echo form_input (array('name'=>'name_of_contractor', 'value'=> ($editAgre) ? $editAgre[0]->name_of_contractor : set_value('name_of_contractor'), 'placeholder'=>'Enter Name of Contractor', 'required'=>'required', 'class'=>'form-control')); ?>
                      <?php echo form_error('name_of_contractor', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-6">
                        <?php echo form_label('Address') ?>
                        <?php echo form_input(array('name' => 'address', 'value'=> ($editAgre) ? $editAgre[0]->address : set_value('address'), 'placeholder' => 'Enter Address', 'class'=>'form-control')); ?>
                        <?php echo form_error('address', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-6">
                      <?php echo form_label('Contractor\'s Email ID')?>
                      <?php echo form_input (array('name'=>'contractor_email_id', 'value'=> ($editAgre) ? $editAgre[0]->contractor_email_id : set_value('contractor_email_id'), 'placeholder'=>'Enter Contractor\'s Email ID', 'required'=>'required', 'class'=>'form-control')); ?>
                      <?php echo form_error('contractor_email_id', '<p class="text-danger">', '</p>'); ?>
                    </div>
                    <div class="form-group col-lg-6">
                      <?php echo form_label('Contractor\'s Phone No.')?>
                      <?php echo form_input (array('name'=>'contractor_phone_no', 'value'=> ($editAgre) ? $editAgre[0]->contractor_phone_no : set_value('contractor_phone_no'), 'placeholder'=>'Enter Contractor\'s Phone No.', 'required'=>'required', 'class'=>'form-control')); ?>
                      <?php echo form_error('contractor_phone_no', '<p class="text-danger">', '</p>'); ?>
                    </div>
                </div>
            </div>

            <div class="card-body alert alert-danger">
                <div class="row">
                    <h4 class="alert-heading">Executing Office</h4>
                </div>
                <div class="row">
                    <div class="form-group col-lg-2">
                      <?php echo form_label('Circle')?>
                      <select id="sel_circle" class="form-control">
                        <option>-- Select Circle --</option>
                        <?php foreach ($circle as $circle):?>
                          <option value="<?=$circle->circle_id?>"><?=$circle->circle?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-lg-3">
                      <?php echo form_label('Division')?>
                      <select id='sel_division' class="form-control">
                        <option>-- Select Division --</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-3">
                      <?php echo form_label('Subdivision')?>
                      <select id='sel_subdivision' class="form-control">
                        <option>-- Select Subdivision --</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-2">
                      <?php echo form_label('Section')?>
                      <select name="section_id" id='sel_section' class="form-control" required="required">
                        <option value="">-- Select Section --</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-2">
                      <?php echo form_label('Department')?>
                      <?php $dept = ($editAgre) ? $editAgre[0]->department : set_value('department') ; ?>
                      <?=form_dropdown('department', $data['dept'], $dept, ['class'=>'form-control', 'required'=>'required']);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php echo anchor('section/agreement', 'Back', array('class'=>'btn btn-secondary text-white mr-2')); ?>
                        <?php echo form_submit('submit', 'Save Agreement', ['class'=>'btn btn-primary']); ?>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>

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
 
  $(document).ready(function(){

    window.ttlAgr = parseFloat(document.getElementById('total').value);
    
  });

  //Circle change
    $('#sel_circle').change(function(){
      var circle_id = $(this).val();
      console.log(circle_id);
      // Ajax request
      $.ajax({
        url:'<?=base_url()?>/getDivision',
        method: 'post',
        data: {circle_id:circle_id},
        cache:false,
           dataType: "json",
          success:function(result){

              $('#sel_division').find('option').not(':first').remove();
              $('#sel_subdivision').find('option').not(':first').remove();
              $('#sel_section').find('option').not(':first').remove();

               for (var i = 0, len = result.length; i < len; ++i) {

                    $('#sel_division').append('<option value="'+result[i]['division_id']+'">'+result[i]['division']+'</option>');

               }
       /*  $('#sel_division').find('option').not(':first').remove();
      $('#sel_subdivision').find('option').not(':first').remove();
          $('#sel_section').find('option').not(':first').remove();

          // Add options
          $.each(response, function(index, data){
            $('#sel_division').append('<option value="'+data['division_id']+'">'+data['division']+'</option>');
          });*/
        }
      });
    });

  //Division change
    $('#sel_division').change(function(){
      var division_id = $(this).val();
      //console.log(division_id);
      // Ajax request
      $.ajax({
        url:'<?=base_url()?>/getSubdivision',
        method: 'post',
        data: {division_id:division_id},
        cache:false,
           dataType: "json",
          success:function(result){

              $('#sel_subdivision').find('option').not(':first').remove();
              $('#sel_section').find('option').not(':first').remove();

               for (var i = 0, len = result.length; i < len; ++i) {

                    $('#sel_subdivision').append('<option value="'+result[i]['subdivision_id']+'">'+result[i]['subdivision']+'</option>');

               }
        }
      });
    });

  //Subdivision change
    $('#sel_subdivision').change(function(){
      var subdivision_id = $(this).val();
      //console.log(subdivision_id);
      // Ajax request
      $.ajax({
        url:'<?=base_url()?>/getSection',
        method: 'post',
        data: {subdivision_id:subdivision_id},
        cache:false,
           dataType: "json",
          success:function(result){

              $('#sel_section').find('option').not(':first').remove();

               for (var i = 0, len = result.length; i < len; ++i) {

                    $('#sel_section').append('<option value="'+result[i]['section_id']+'">'+result[i]['section']+'</option>');

               }
        }
      });
    });

  //Work change
    $('#sel_work').change(function(){
      var work_type = $(this).val();
      //console.log(work_type);
      // Ajax request
      $.ajax({
        url:'<?=base_url()?>/getItem',
        method: 'post',
        data: {work_type:work_type},
        cache:false,
           dataType: "json",
          success:function(result){

              $('#sel_item').find('option').not(':first').remove();
              //$('#itemTable').find('tr').not(':first').remove();
               for (var i = 0, len = result.length; i < len; ++i) {

                    $('#sel_item').append('<option value="'+result[i]['item_name']+'">'+result[i]['item_name']+'</option>');
                    //$('#itemTable').append('<tr><td><input type="checkbox" name="chk" class="form-control"></td><td><input type="text" name="item[]" class="form-control" value="'+result[i]['item_name']+'"></td><td><input type="text" name="estimated_quantity[]" class="form-control" placeholder="Est Quantity"></td><td><input type="text" name="unit[]" class="form-control" value="'+result[i]['unit']+'" placeholder="unit"></td></tr>');
               }
        }
      });
    });

  // SCRIPT FOR ITEM TABLE
    function addItemRow() {

      var table = document.getElementById('itemTable');

      //var table = document.getElementById($tableid);

      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);

      var colCount = table.rows[1].cells.length;

      for(var i=0; i<colCount; i++) {

        var newcell = row.insertCell(i);

        newcell.innerHTML = table.rows[1].cells[i].innerHTML;
        //alert(newcell.childNodes);
        switch(newcell.childNodes[0].type) {
          case "text":
              newcell.childNodes[0].value = "0";
              break;
          case "checkbox":
              newcell.childNodes[0].checked = false;
              break;
          case "select-one":
              newcell.childNodes[0].selectedIndex = 0;
              break;
        }
      }
    }

    function deleteItemRow() {
      try {
      var table = document.getElementById('itemTable');
      //var table = document.getElementById($tableid);
      var rowCount = table.rows.length;

      for(var i=0; i<rowCount; i++) {
        var row = table.rows[i];
        var chkbox = row.cells[0].childNodes[0];
        if(null != chkbox && true == chkbox.checked) {
          if(rowCount <= 1) {
            alert("Cannot delete all the rows.");
            break;
          }
          table.deleteRow(i);
          rowCount--;
          i--;
        }


      }
      }catch(e) {
        alert(e);
      }
    }

  // FETCH UNTI OF ITEM
    function setUnit(x) {
      var work_type = document.getElementById("sel_work").value;

      $.ajax({
        url:'getItem',
        method: 'post',
        data: {work_type:work_type, item_name: x.value},
        // console.log(data),
        cache:false,
           dataType: "json",
          success:function(result){
            // $(x).closest('td').siblings().find('#unit').val(Math.random());
            $(x).closest('td').siblings().find('#unit').val(result[0]['unit']);
            // $(x).closest('#unit').val = result[0]['unit'];
            console.log(result);
        }
      });

    }

    // CALCULATE AGREEMENT AMOUNT
    function calcAmt(y) {
      $preTtl = parseFloat($(y).closest('td').siblings().find('#ttlAmt').val());

      if(y.id == "qty"){
        $qty = parseFloat(y.value);
        $amt = parseFloat($(y).closest('td').siblings().find('#amt').val());
      } else{
        $qty = parseFloat($(y).closest('td').siblings().find('#qty').val());
        $amt = parseFloat(y.value);
      }

      $id = y.id;
      $(y).closest('td').siblings().find('#ttlAmt').val($qty);
      console.log($id);
      console.log(y.id);


      $newTtl = $qty * $amt ;
      $inc = $newTtl - $preTtl ;
      window.ttlAgr = window.ttlAgr + $inc ;

      $(y).closest('td').siblings().find('#ttlAmt').val($newTtl);
      document.getElementById('total').value = window.ttlAgr ;


    }

  // SCRIPT FOR LOCATION TABLE
    function addLocRow() {

      var table = document.getElementById('locTable');

      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);

      var colCount = table.rows[1].cells.length;

      for(var i=0; i<colCount; i++) {

        var newcell = row.insertCell(i);

        newcell.innerHTML = table.rows[1].cells[i].innerHTML;
        //alert(newcell.childNodes);
        switch(newcell.childNodes[0].type) {
          case "text":
              newcell.childNodes[0].value = "";
              break;
          case "checkbox":
              newcell.childNodes[0].checked = false;
              break;
          case "select-one":
              newcell.childNodes[0].selectedIndex = 0;
              break;
        }
      }
    }

    function deleteLocRow() {
      try {
      var table = document.getElementById('locTable');
      var rowCount = table.rows.length;

      for(var i=0; i<rowCount; i++) {
        var row = table.rows[i];
        var chkbox = row.cells[0].childNodes[0];
        if(null != chkbox && true == chkbox.checked) {
          if(rowCount <= 1) {
            alert("Cannot delete all the rows.");
            break;
          }
          table.deleteRow(i);
          rowCount--;
          i--;
        }


      }
      }catch(e) {
        alert(e);
      }
    }
    
</script>