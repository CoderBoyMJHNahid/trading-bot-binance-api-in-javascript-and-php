<?php 
    include "conn.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM `history` WHERE `history_id` = '$id'";

        $runQuery = mysqli_query($conn,$sql) or die("Could not execute query for delete");

        if ($runQuery) {

            header("Location:{$host}/history.php");

        } else {
            header("Location:{$host}/history.php");
        }
    
    } else {
        header("Location:{$host}/history.php");
    }
    




?>