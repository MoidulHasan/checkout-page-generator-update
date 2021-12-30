<?php
    // Include config file
    require_once "config.php";

    if (isset($_POST['login'])) {
        
        $user_name = trim($_POST['user_name']);
        $password = trim($_POST['password']);
        $h_password = sha1($password);

        $sql = "SELECT * FROM  `user`
        WHERE  `user_name` ='" . $user_name . "' AND  `user_password` =  '" . $h_password . "'";

        $result = $conn->query($sql);

        if ($result){
            
                if ( $result->num_rows > 0) {
                    
                    $found_user  = mysqli_fetch_array($result);
                    //fill the result to session variable
                    session_start();
                    $_SESSION['loggedin']  = true; 
                    $_SESSION['user_name'] = $found_user['user_name']; 
    
            //this part is the verification if admin or user
                 ?>
                    <script type="text/javascript">
                        //then it will be redirected to index.php
                        alert("<?php echo  $_SESSION['user_name']; ?> Welcome!");
                        window.location = "app/index.php";
                    </script>
                <?php        
                }
                else {
                //IF theres no result
                  ?>
                    <script type="text/javascript">
                        alert("Username or Password Not Registered! Contact Your administrator.");
                        window.location = "index.php";
                    </script>
                <?php
    
                }
    
             } else {
                     # code...
            echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
    } 
    else{
        echo 'Fill-Up Login Form First';
    }
     $conn->close();
    ?>