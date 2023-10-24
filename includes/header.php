<?php include "conn.php";

    session_start();

    if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
        header("Location:$host/index.php");
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading bot Binance</title>

    <!-- css link -->

        <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <!-- custom -->
    <link rel="stylesheet" href="css/style.css" />


    <!-- script cdn -->
        <!-- bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
    