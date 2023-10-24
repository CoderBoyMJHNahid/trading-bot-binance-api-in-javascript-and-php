<?php

    include "conn.php";
    session_start();

    $jsonData = file_get_contents("php://input");

    $data = json_decode($jsonData);

    $val = $data->crypto;
    

    $buy_sum_sql = "SELECT SUM(usdt_value) as buy_usdt_value FROM `history` WHERE operation = 'buy' AND crypto = '{$val}' AND `user` = '{$_SESSION['user_id']}'";

    $sell_sum_sql = "SELECT SUM(usdt_value) as sell_usdt_value FROM `history` WHERE operation = 'sell' AND crypto = '{$val}' AND `user` = '{$_SESSION['user_id']}'";

    $runQuery_buy_sum = mysqli_query($conn, $buy_sum_sql) or die("Error in buy mysqli query");

    $buy_sum = mysqli_fetch_all($runQuery_buy_sum, MYSQLI_ASSOC);

    $runQuery_sell_sum = mysqli_query($conn, $sell_sum_sql) or die("Error in sell mysqli query");

    $sell_sum = mysqli_fetch_all($runQuery_sell_sum, MYSQLI_ASSOC);

    $total_usdt = $buy_sum[0]['buy_usdt_value'] - $sell_sum[0]['sell_usdt_value'];


    // crypto operation sum 
    $buy_crypto_sql = "SELECT SUM(crypto_var) as buy_crypto_value FROM `history` WHERE operation = 'buy' AND crypto = '{$val}' AND `user` = '{$_SESSION['user_id']}'";

    $sell_crypto_sql = "SELECT SUM(crypto_var) as sell_crypto_value FROM `history` WHERE operation = 'sell' AND crypto = '{$val}' AND `user` = '{$_SESSION['user_id']}'";

    $runQuery_buy_crypto = mysqli_query($conn, $buy_crypto_sql) or die("Error in buy mysqli query");

    $buy_crypto = mysqli_fetch_all($runQuery_buy_crypto, MYSQLI_ASSOC);

    $runQuery_sell_crypto = mysqli_query($conn, $sell_crypto_sql) or die("Error in sell mysqli query");

    $sell_crypto = mysqli_fetch_all($runQuery_sell_crypto, MYSQLI_ASSOC);

    $total_crypto = $buy_crypto[0]['buy_crypto_value'] - $sell_crypto[0]['sell_crypto_value'];

    
    $average = $total_usdt / $total_crypto;
    
    echo json_encode($average);