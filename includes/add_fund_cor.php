<?php 
    include "conn.php";
    session_start();

    $deposit = $_POST['deposit_val'];
    $budget = $_POST['budget'];

    if ($deposit !== "" || $deposit !== null || !isset($deposit)) {
        
        $total = $budget + $deposit;
        $sql = "UPDATE personal_data SET budget = '$total' WHERE pid = {$_SESSION['user_id']}";
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