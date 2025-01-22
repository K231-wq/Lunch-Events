<?php
    session_start();
    if(isset($_SESSION['userInfo'])){
        unset($_SESSION['userInfo']);
        session_destroy();
        sleep(2);
        header("Location: ../Login/SignIn.php");
    }

?>