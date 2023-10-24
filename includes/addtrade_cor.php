<?php 
    include "conn.php";
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $check_sql = "SELECT * FROM trade WHERE pricelevel = '{$data->level}' AND 'crypto' = '{$data->crypto}' AND `user` = '{$_SESSION['user_id']}'";

    $runCheckQuery = mysqli_query($conn, $check_sql) or die("Check query failed");

    if (mysqli_num_rows($runCheckQuery) === 0) {

        
        $sql = "INSERT INTO trade (pricelevel,state_status,suggested_bid,target_price,crypto,`user`) VALUES ('{$data->level}','open','{$data->s_bid}','{$data->target_price}','{$data->crypto}','{$_SESSION['user_id']}')";

        $runQuery = mysqli_query($conn, $sql) or die("run query failed");

        if($runQuery){
            echo json_encode(["massage"=>"trade added","status"=> true]);
        }else{
            echo json_encode(["massage"=>"trade","status"=>false]);
        }
    }
    
    mysqli_close($conn);
?>