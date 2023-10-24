<?php 
    include "conn.php";

    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $crypto = $data->currency;
    

    $sql = "SELECT * FROM trade WHERE crypto = '$crypto' AND `user` = '{$_SESSION['user_id']}' ORDER BY trade_id  DESC";

    $runQuery = mysqli_query($conn, $sql) or die("run query failed");

    if($runQuery){
        $row = mysqli_fetch_all($runQuery,MYSQLI_ASSOC);
        echo json_encode($row);
    }else{
        echo json_encode(["data"=>"didn't find","status"=>false]);
    }

    mysqli_close($conn);
?>