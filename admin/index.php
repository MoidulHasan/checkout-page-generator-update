<?php
    session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
    {
        header("location: app/index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>API Page Generator</title>
</head>

<body class="bg-custom overflow-auto">
    <main>
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="p-3 w-md-50 h-100 d-flex justify-content-center">
                <div class="card p-2 p-md-5 m-md-5 shadow w-sm-50 ">
                    <h1 class="card-title text-center text-primary">CHECKOUT PAGE GENERATOR</h1>
                    <h2 class="card-title text-center">LOGIN</h2>
                    <form action="login.php" method="POST">
                        <div class="form-group py-2">
                            <label for="InputUserName">User Name</label>
                            <input type="text" name="user_name" class="form-control" id="InputUserName"
                                placeholder="Enter User Name" required>
                        </div>
                        <div class="form-group py-2">
                            <label for="InputPassword">Password</label>
                            <input type="password" name="password" class="form-control" id="InputPassword"
                                placeholder="Password" required>
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-center mt-3 ">
                            <button type="submit" name="login" value="submit" class="btn btn-primary px-5">Login</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
</body>

</html>