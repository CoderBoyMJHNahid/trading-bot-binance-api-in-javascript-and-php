<?php

    include "conn.php";
    include "functions.php";
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $function = new Functions();

    $val = $data->symbol;

    $result = $function->checkCryptoAvailable($val);

    if (!empty($result)) {
        echo json_encode(["massage" => "Your choose crypto are available","status"=>true]);
    } else {
        echo json_encode(["massage" => "Your choose crypto are not available","status"=>false]);
    }
    



    
