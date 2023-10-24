<?php 
    include "conn.php";

    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $crypto = $data->symbol;
    

    $sql = "DELETE FROM `trade` WHERE crypto = '$crypto' AND `user` = '{$_SESSION['user_id']}'";

    $runQuery = mysqli_query($conn, $sql) or die("run query failed");

    if($runQuery){
        echo json_encode(["data"=>"Deleted","status"=>true]);
    }else{
        echo json_encode(["data"=>"could not delete","status"=>false]);
    }

    mysqli_close($conn);
?>