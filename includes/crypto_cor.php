<?php 
    include "conn.php";
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $val = $data->value;
    

    $sql = "INSERT INTO crypto (crypto_name,`user`) VALUES ('{$val}','{$_SESSION['user_id']}') ";

    $runQuery = mysqli_query($conn, $sql) or die("run query failed");

    if($runQuery){
        echo json_encode(["massage"=>"Data saved","status"=> true]);
    }else{
        echo json_encode(["massage"=>"Couldn't save","status"=>false]);
    }

    mysqli_close($conn);
?>