<?php
    require_once "../config.php";
    $id = $_GET['id'];

    $sql = "SELECT * from checkout_file where file_id='$id'";
    $result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
    $filedata = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $filedata = $row;
    }

    echo json_encode($filedata);
?>