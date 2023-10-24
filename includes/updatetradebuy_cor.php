<?php 
    include "conn.php";
    include "functions.php";

    session_start();

    $function = new Functions();

                                
    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $dateTime = Date("m-d-Y");

    $order = $function->buyUSDT($data->crypto,$data->bid,$data->actualPrice);


    if (!empty($order)) {

        $usdtValue = "";
        if ($order['cummulativeQuoteQty'] == 0) {
            
            $usdtValue = $data->actualPrice * $order['origQty'];

        } else {
            $usdtValue = $order['cummulativeQuoteQty'];
        }
        
        
        $sql = "UPDATE `trade` SET `state_status`='$data->state_status',`multiplicator`='$data->multiplicator',`suggested_bid`='$data->s_bid',`bid`='{$usdtValue}',`crypto_var`='{$order['origQty']}',`on_actual_price`='0',`ricavo`='0',`future_sell_usdt`='{$usdtValue}',`crypto_received`='0' WHERE `pricelevel`='$data->pricelevel' AND `crypto`='$data->crypto' AND `user` = '{$_SESSION['user_id']}'";

        $runQuery = mysqli_query($conn, $sql) or die("run query failed");

        if($runQuery){

            $get_lasted_possessed_crypto = "SELECT possessed_crypto FROM `history` WHERE crypto = '$data->crypto' AND `user` = '{$_SESSION['user_id']}' ORDER BY history_id LIMIT 1";
            $runQuery_possessed_crypto = mysqli_query($conn, $get_lasted_possessed_crypto) or die("runQuery_possessed_crypto Failed!!!");
            $result = mysqli_fetch_all($runQuery_possessed_crypto,MYSQLI_ASSOC);

            $add_possessed_crypto = 0;

            !empty($result) ? $add_possessed_crypto = $result[0]['possessed_crypto'] : $add_possessed_crypto = 0;

            $total_possessed_crypto = $order['origQty'] + $add_possessed_crypto; 
            
            
            $actual_val = $data->actualPrice * $total_possessed_crypto;


            $sql2 = "INSERT INTO `history`(`history_date`, `operation`, `usdt_value`, `crypto_var`, `possessed_crypto`, `buy_sell_price`, `actual_value`, `crypto`,`user`) VALUES ('$dateTime','$data->action','{$usdtValue}','{$order['origQty']}','$total_possessed_crypto','$data->actualPrice', '$actual_val', '$data->crypto','{$_SESSION['user_id']}')";

            $run_query_sql2 = mysqli_query($conn,$sql2) or die("run_query_sql2 failed");

            if($run_query_sql2){
                echo json_encode(["massage"=>"trade and history log Updated","status"=> true]);
            }else{
                echo json_encode(["massage"=>"trade and history log Updated not Updated","status"=>false]);
            }

        
        }else{
            echo json_encode(["massage"=>"trade not Updated","status"=>false]);
        }

    } else {
        echo json_encode(["massage"=>"Problem with api","status"=>false,"data"=>$order]);
    }
    
    
    mysqli_close($conn);
?>