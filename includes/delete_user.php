<?php 
    include "conn.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM `personal_data` WHERE `pid` = '$id'";

        $runQuery = mysqli_query($conn,$sql) or die("Could not execute query for delete");

        if ($runQuery) {

            header("Location:{$host}/users.php");

        } else {
            header("Location:{$host}/users.php");
        }
    
    } else {
        header("Location:{$host}/users.php");
    }
    




?>