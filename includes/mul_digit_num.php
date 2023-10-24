<?php 
    include "conn.php";

    session_start();

    $sql = "SELECT multiplicator,digit_num,slippage_num FROM personal_data WHERE pid = '{$_SESSION['user_id']}'";

    $runQuery = mysqli_query($conn, $sql) or die("Couldn't connect to database");

    $result = mysqli_fetch_all($runQuery,MYSQLI_ASSOC);

    echo json_encode($result[0]);


?>