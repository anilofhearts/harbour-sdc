<?php $this->load->view("library/firebase_api");
if(is_numeric($_POST['trip_id']) == 1)
{
   echo "<script>
   var data = ".json_encode($_POST).";

$.post('addUpdateTrip', data)
  .done(function( suc ) {
    document.write(suc);
  });
 </script>";
}
?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">

<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <div class="row">

    <!-- ============================================================== -->
    <!-- FORM BLOCK -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- TABLE BLOCK -->
    <!-- ============================================================== -->

        <div class="card col-md-12">
            <div class="card-body">
                <h4>Preview Of Photo</h4>
                <table>

                <th>
<img crossorigin="anonymous" id='capture'  class="img-thumbnail" src="http://127.0.0.1:8001/snapshot.jpg"/>
               <button class='btn-info btn btn-md' onClick="refreshPage()">Retake Photo</button>
               </th>
               <th style='text-align:center;' class='center'>
                <button type=button class='btn-md btn-danger btn' onClick='submitit()' >Finalise and submit </button></th>
                </table>

<div class="progress" style="height:20px;">
  <div class="progress-bar" id="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:0%;">

  </div>
</div>

            </div>

        </div>
        <script>
function refreshPage(){
 $.ajax({
        url: "http://127.0.0.1:8001/snapshot.jpg",
        type: 'get',
        dataType: 'html',
        async: false,
        crossDomain: 'true',
        success: function(data, status) {
          //  console.log(data);
         document.getElementById("capture").setAttribute(
        'src',"http://127.0.0.1:8001/snapshot.jpg");
        }
});

}
function submitit()
{
<?php $this->load->view("library/add_firebase"); ?>
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

