<?php
    require_once "../config.php";
    $id = $_GET['file_id'];

    $sql = "SELECT * from checkout_file where file_id='$id'";
    $result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
    //echo $id;
    $filename = '../../';
    while ($row = mysqli_fetch_assoc($result)) {
        $filename .= $row['file_name'];
    }

    $filename .='.php';
    unlink($filename);

    // sql to delete a record
    $sql = "DELETE FROM checkout_file WHERE file_id='$id'";

    if (mysqli_query($conn, $sql)) {
        $deleteSQL = "DELETE FROM coupons WHERE checkout_file_id ='$id'";
        if (mysqli_query($conn, $deleteSQL)) {
          echo "Deleted";
        }
        else {
          echo $id."Not deleted";
        }
        //return 1;
      } else {
        echo $id."Not deleted";
      }
?>