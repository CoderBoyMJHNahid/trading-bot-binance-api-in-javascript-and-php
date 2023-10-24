<?php 
    include "conn.php";
    session_start();

    $digit_num = $_POST['digit_num_val'];

    if ($digit_num !== "" || $digit_num !== null || !isset($digit_num)) {
        
        $sql = "UPDATE personal_data SET digit_num = '$digit_num' WHERE pid = {$_SESSION['user_id']}";
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