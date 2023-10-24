<?php 
    include "conn.php";
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $val = $data->crypto;
    

    $sql = "SELECT crypto FROM trade WHERE crypto = '$val' AND `user` = '{$_SESSION['user_id']}'";

    $runQuery = mysqli_query($conn, $sql) or die("run query failed");

    if(mysqli_num_rows($runQuery) > 0){
        echo json_encode(["massage"=>"have the crypto","status"=> true]);
    }else{
        echo json_encode(["massage"=>"haven't the crypto","status"=>false]);
    }

    mysqli_close($conn);
?>