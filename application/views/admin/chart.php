 <?php
   defined('BASEPATH') OR exit('No direct script access allowed');

            ?>


    <script type="text/javascript" src="<?php echo base_url(); ?>public/dist/js/loader.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/libs/bootbox.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {packages:["orgchart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        // For each orgchart box, provide the name, manager, and tooltip to show.
        data.addRows([

          [{'v':'admin', 'f':'Administrator <div class="form-inline"><div class="col-sm-4 text-success pointer" style="margin-left:30%;" onclick="add_circle(\'circle\',\'Admin\',\'admin\')" ><i class="fa fa-plus"></i></div></div>'},
           '', '']
            <?php

               if(isset($circle))
       {
           foreach($circle as $circle_data)
           {
               echo " ,[{'v':'$circle_data->circle_id', 'f':'$circle_data->circle <div class=\"form-inline\"><div class=\"col-sm-4 pointer text-danger\" onclick=\"remove_branch(\'circle\',\'$circle_data->circle_id\')\"><i class=\"fa fa-ban\"></i></div><div class=\"col-sm-4 pointer text-default\" data-toggle=\"modal\" data-target=\"#edit_student\" onclick=\"view_data(\'circle\',\'$circle_data->circle_id\',\'$circle_data->circle\')\"><i class=\"fa fa-list-alt\"></i></div><div class=\"col-sm-4 text-success pointer\" onclick=\"add_circle(\'division\',\'$circle_data->circle\',\'$circle_data->circle_id\')\" ><i class=\"fa fa-plus\"></i></div></div>'},
           'admin', '']";

            $division = $this->manager->get_details("division",array("circle_id"=>$circle_data->circle_id));
               if(isset($division))
               {
                   foreach($division as $division_data)
                   {
                       echo " ,[{'v':'$division_data->division_id', 'f':'$division_data->division <div class=\"form-inline\"><div class=\"col-sm-4 pointer text-danger\" onclick=\"remove_branch(\'division\',\'$division_data->division_id\')\"><i class=\"fa fa-ban\"></i></div><div class=\"col-sm-4 pointer text-default\" data-toggle=\"modal\" data-target=\"#edit_student\" onclick=\"view_data(\'division\',\'$division_data->division_id\',\'$division_data->division\')\" ><i class=\"fa fa-list-alt\"></i></div><div class=\"col-sm-4 text-success pointer\" style=\"margin-left:0%;\" onclick=\"add_circle(\'subdivision\',\'$division_data->division\',\'$division_data->division_id\')\" ><i class=\"fa fa-plus\"></i></div></div>'},
           '$circle_data->circle_id', '']";

               $subdivision = $this->manager->get_details("subdivision",array("division_id"=>$division_data->division_id));
                       if(isset($subdivision))
                       {
                           foreach($subdivision as $sub_data)
                           {
       echo " ,[{'v':'$sub_data->subdivision_id', 'f':'$sub_data->subdivision <div class=\"form-inline\"><div class=\"col-sm-4 pointer text-danger\" onclick=\"remove_branch(\'subdivision\',\'$sub_data->subdivision_id\')\"><i class=\"fa fa-ban\"></i></div><div class=\"col-sm-4 pointer text-default\"><i class=\"fa fa-list-alt\"></i></div><div class=\"col-sm-4 text-success pointer\" onclick=\"add_circle(\'section\',\'$sub_data->subdivision\',\'$sub_data->subdivision_id\')\" ><i class=\"fa fa-plus\"></i></div></div>'},
           '$division_data->division_id', '']";

        $section = $this->manager->get_details("section",array("subdivision_id"=>$sub_data->subdivision_id));
                if(isset($section))
                {
                    foreach($section as $sec_data)
                    {
                          echo " ,[{'v':'$sec_data->section_id', 'f':'$sec_data->section <div class=\"form-inline\"><div class=\"col-sm-4 pointer text-danger\" onclick=\"remove_branch(\'section\',\'$sec_data->section_id\')\" ><i class=\"fa fa-ban\"></i></div></div>'},
           '$sub_data->subdivision_id', '']";

                    }
                }
                           }
                       }

                   }
               }



           }
       }
            ?>

        ]);

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));



        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {'allowHtml':true,'allowCollapse':true,'size':'large'});
 for (var i = 0; i < data.getNumberOfRows(); i++) {
   // chart.collapse(i, true);
  }
      }
        function add_circle(table,y,id)
        {
         bootbox.prompt({
    title: "Add a New <b style='text-transform:capitalize; color:blue;'>"+table+" Under "+y+"</b>",
    centerVertical: true,
    callback: function(result){
        if(result != null)
            {
    $.ajax({
  type: "POST",
  url: "add_node",
  data: { table: table, id: id ,name: result,node: y},
  success: function (res) {

    //console.log(res);
      location.reload();
}
});
            }
    }
});
        }
function remove_branch(table,id)
        {
             bootbox.prompt({
    title: "Remove this branch Type \"delete\"",
    centerVertical: true,
    callback: function(result){
        if(result == 'delete')
            {
    $.ajax({
  type: "POST",
  url: "remove_node",
  data: { table: table, id: id },
  success: function (res) {
    console.log(res);
    location.reload();
}
});
            }
}
             });
        }
function view_data(table,x,name)
        {

      $.ajax({url:"view_branch?id="+x+"&table="+table+"&name="+name,success:function(result){
          $("#edit_student").html(result);
      }});
        }
   </script>
  <style>
.google-visualization-orgchart-node {

    border: none ;
      }
      .pointer
      {
          cursor: pointer;


      }
      body
      {
          background: #212529;
      }td
      {
          padding: 6px !important;

      }

</style>
    <div class="page-wrapper" style="  background: #212529; height:100%; overflow-x:auto;">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
             <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Hierarchy</h4>

                    </div>
                </div>
            </div>
  <div class="modal" data-backdrop="static" id='edit_student' tabindex='1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style="top: 3%;"></div>             <!-- /.row -->
        </div>
    <div id="chart_div" ></div>
        <br>
        <br>
        <br>

