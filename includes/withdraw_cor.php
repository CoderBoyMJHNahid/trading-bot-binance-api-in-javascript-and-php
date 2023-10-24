<?php 
    include "conn.php";

    session_start();

    $withdraw = $_POST['withdraw_val'];
    $budget = $_POST['budget'];

    if ($withdraw !== "" || $withdraw !== null || !isset($withdraw)) {
        
        $lasted_val = $budget - $withdraw;
        $sql = "UPDATE personal_data SET budget = '$lasted_val' WHERE pid = '{$_SESSION['user_id']}'";
        $runQuery = mysqli_query($conn, $sql) or die("Couldn't execute update query");
        if ($runQuery) {
            header("Location:{$host}/history.php");
        } else {
            header("Location:{$host}/history.php");
        }
    } else {
        header("Location:{$host}/history.php");
    }
    

    


?>