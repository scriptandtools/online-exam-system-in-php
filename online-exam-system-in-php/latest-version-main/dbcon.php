
<?php
// create db connection
$conn=new mysqli("localhost","root","","exams");
if($conn->connect_error) {
    die("connection failed");
   }

?>