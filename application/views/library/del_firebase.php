<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php $this->load->view("library/firebase_api"); ?>
<script>
  firebase.initializeApp(firebaseConfig);
     var storageRef = firebase.storage().ref();
var desertRef = storageRef.child('<?php echo $file;?>');
// Delete the file
desertRef.delete().then(function() {
  // File deleted successfully
}).catch(function(error) {
  // Uh-oh, an error occurred!
});

</script>