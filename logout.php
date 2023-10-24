<?php 
    include "includes/conn.php";

    session_start();
    session_unset();
    session_destroy();
    

    header("Location:$host");


?>