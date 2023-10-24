<?php 
    include "conn.php";
    session_start();

    $slippage = $_POST['slippage_num_val'];

    if ($slippage !== "" || $slippage !== null || !isset($slippage)) {
        
        $sql = "UPDATE personal_data SET slippage_num = '$slippage' WHERE pid = {$_SESSION['user_id']}";
        $runQuery = mysqli_query($conn, $sql) or die("Couldn't execute update query");
        if ($runQuery) {

            header("Location:{$host}/trade.php");

        }else{

            header("Location:{$host}/trade.php");

        }
            
    } else {
        
        header("Location:{$host}/trade.php");

    }
    

    


?>