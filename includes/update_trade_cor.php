<?php 
    include "conn.php";
    
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $sql = "UPDATE `trade` SET `on_actual_price`='$data->on_actual_price',`ricavo`='$data->ricavo',`future_sell_usdt`='$data->future_sell_usdt',`crypto_received`='$data->crypto_received' WHERE `pricelevel`='$data->pricelevel' AND `crypto`='$data->crypto' AND `user` = '{$_SESSION['user_id']}'";

        $runQuery = mysqli_query($conn, $sql) or die("run query failed");

        if($runQuery){

            echo json_encode(["massage"=>"trade and Updated","status"=> true]);
        
        }else{
            echo json_encode(["massage"=>"trade not Updated","status"=>false]);
        }
    
    mysqli_close($conn);
?>