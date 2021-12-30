<?php
    require_once "../config.php";
    $id = $_GET['id'];
    $deleteSQL = "DELETE FROM coupons WHERE coupon_id ='$id'";
    if (mysqli_query($conn, $deleteSQL)) {
        return 1;
    }
    else {
        return 0;
    }
?>
