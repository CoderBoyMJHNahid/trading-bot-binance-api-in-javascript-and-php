<?php 
    include "includes/conn.php";

    session_start();

    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        header("Location:$host/trade.php");
    }

    if (isset($_POST['save'])) {

        $username = $_POST['username'];
        $pwd = md5(sha1($_POST['pwd']));
        $api_key = $_POST['api_key']; 
        $api_secret = $_POST['api_secret'];

        if ($username !== "" && $_POST['pwd'] !== "" && $api_key !== "" && $api_secret !== "") {
          $sql = "SELECT * FROM personal_data WHERE username = '$username' AND pwd = '$pwd'";

        $runQuery = mysqli_query($conn, $sql) or die("Could not connect to database");

        if (mysqli_num_rows($runQuery) === 1) {
            $row = mysqli_fetch_all($runQuery,MYSQLI_ASSOC);


            $_SESSION['user_id'] = $row[0]['pid'];
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $row[0]['type_user'];
            $_SESSION['api_key'] = $api_key;
            $_SESSION['api_secret'] = $api_secret;


            header("Location:{$host}/trade.php");
            
        }else{
            header("Location:{$host}/index.php?error");
        }
        } else {
          header("Location:{$host}/index.php?errored");
        }
        

    }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In Page</title>

    <!-- css link -->

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- custom -->
    <link rel="stylesheet" href="css/login.css" />

    <!-- script cdn -->
    <!-- bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

<div class="login-page">
  <div class="form">
    <h2 class="mb-4">Log In</h2>
    <form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
      <input type="text" name="username" placeholder="Username"/>
      <input type="password" name="pwd" placeholder="Password"/>
      <input type="text" name="api_key" placeholder="Enter Your API KEY"/>
      <input type="text" name="api_secret" placeholder="Enter Your API SECRET"/>
      <input type="submit" value="Log In" name="save" class="button"/>
    </form>
    <?php 
      if (isset($_GET['error'])) {
        echo "<div class='alert alert-danger'>Invalid Credentials!!</div>";
      } elseif(isset($_GET['errored'])) {
        echo "<div class='alert alert-danger'>All field are required!!</div>";
      }
      
    ?>
  </div>
</div>


</body>

</html>