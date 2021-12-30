<?php
  // Include config file
    require_once "../config.php";
    if(isset($_POST['file_name']))
    {
        $text = $_POST["file_name"];
        $sql = "SELECT file_name from checkout_file where file_name='$text'";
        $result = $conn->query($sql);
            if ($result){
                
                    if ( $result->num_rows > 0) {
                        echo '<span class="text-danger" id="File_Name_Status_icon"><i class="fas fa-times-circle"></i></span> <span id="File_Name_Status">Not Abailable</span>';
                    }
                    else{
                        echo '<span class="text-primary" id="File_Name_Status_icon"><i class="fas fa-check-circle"></i></span> <span id="File_Name_Status">Abailable</span>';
                    }
                }
    }
?>