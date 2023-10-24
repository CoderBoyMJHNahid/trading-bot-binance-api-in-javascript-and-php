<?php 
    include "conn.php";
    session_start();

    $multiplicator = $_POST['multiplicator_val'];

    if ($multiplicator !== "" || $multiplicator !== null || !isset($multiplicator)) {
        
        $sql = "UPDATE personal_data SET multiplicator = '$multiplicator' WHERE pid = {$_SESSION['user_id']}";
        $runQuery = mysqli_query($conn, $sql) or die("Couldn't execute update query");
        if ($runQuery) {
            
            if (isset($_POST['form_history'])) {
                header("Location:{$host}/history.php");
            } else {
                header("Location:{$host}/trade.php");
            }

        } else {
            
            if (isset($_POST['form_history'])) {
                header("Location:{$host}/history.php");
            } else {
                header("Location:{$host}/trade.php");
            }

        }
    } else {
        
        if (isset($_POST['form_history'])) {
            header("Location:{$host}/history.php");
        } else {
            header("Location:{$host}/trade.php");
        }

    }
    

    


?>