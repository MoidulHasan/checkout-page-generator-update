<?php
    require_once "../config.php";
    $id = $_GET['id'];

    $coupons = array();
    $sqlCoupons = "SELECT * from coupons where checkout_file_id ='$id'";
    $resultCoupons = mysqli_query($conn, $sqlCoupons) or die (mysqli_error($conn));

    $i = 1;
    while ($rowCoupons = mysqli_fetch_assoc($resultCoupons)) {

        array_push($coupons, $rowCoupons);
    }
    echo json_encode($coupons);

?>