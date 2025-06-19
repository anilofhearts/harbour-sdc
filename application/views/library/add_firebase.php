<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $x = rand(1,1000000000);?>



  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
    var storage = firebase.storage();
     var storageRef = firebase.storage().ref();
  
  var thisRef = storageRef.child("site/"+<?php echo $x;?>+".jpg");
      var upload = thisRef.putString(getBase64Image(document.getElementById("capture")),'base64');

  upload.on(
          "state_changed",
    function progress(snapshot){
    var percentage = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        uploader = document.getElementById("progress-bar");
        uploader.style.width = percentage.toFixed(2)+'%';

},
 function error() {
            alert("error uploading file");
          },
      
       function complete(){
           
           //uploader.parentNode.removeChild(uploader);
  upload.snapshot.ref.getDownloadURL().then(function(downloadURL) {
var data = <?php echo $_POST; ?>;
data.in_image = downloadURL;
console.log(data);
$.post( "addUpdateTrip", data)
  .done(function( suc ) {
    document.write(suc);
    //console.log('Server response:', suc);
  });
    console.log('File available at', downloadURL);
  });
    console.log('Uploaded a blob or file!');

}); 

