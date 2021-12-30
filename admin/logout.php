<?php
    //Start session
    session_start();

    // Unset all the session variables
    unset($_SESSION['loggedin']);
    unset($_SESSION['user_name']);
?>
<script type="text/javascript">
    window.location = "index.php";
</script>