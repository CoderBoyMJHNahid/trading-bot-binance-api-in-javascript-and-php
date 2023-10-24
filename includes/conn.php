<?php 

    $hostname = "localhost";
    $db_user ="root";
    $db_pwd = "";
    $db_name = "binance_bot";

    $conn = mysqli_connect($hostname,$db_user,$db_pwd,$db_name) or die("Couldn't connect to" . mysqli_connect_error());


    $host = "http://localhost/bot";




?>