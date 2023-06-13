<?php
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "myAdmin0372021";
    $db = "product_inventory";

    $conn = mysqli_connect($servername, $username, $password, $db);

    if($conn->connect_error) {
      die("Connection failed: ".$conn->connect_error);
    }

    return $conn;

    // echo "Connected Successfully";
  }
?>