<?php include "includes/header.php";

    if ($_SESSION['user_type'] == 0) {
        header("Location:$host/users.php");
    }

    if (isset($_POST['save'])) {
        $username = $_POST['username'];
        $pwd = $_POST['pwd'];
        $password = md5(sha1($pwd));
        $type = $_POST['type'];

        $sql = "INSERT INTO personal_data (username, pwd,type_user,digit_num) VALUES('{$username}','{$password}','{$type}',4)";

        $runQuery = mysqli_query($conn, $sql) or die("Could not connect to database");

        if ($runQuery) {
            header("Location:$host/users.php");
        } else {
            echo "<h1>Something is wrong</h1>";
        }
        
    }

?>

<header>
    <div class="container">
        <nav class="page_link_wrapper">
            <ul class="d-flex gap-5">
                <li><a href="trade.php">Trading Gains</a></li>
                <li><a href="history.php">History Gains</a></li>
                <li><a href="users.php">All Users</a></li>
            </ul>
        </nav>
    </div>
</header>
<section>

<div class="container">
    <a href="users.php" class="btn btn-secondary">Back</a>
    <div class="row align-items-center justify-content-center">
        <div class="col-md-6">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div class="col-md-12 my-3">
                    <label for="" class="form-label">User Name</label>
                    <input type="text" name="username" placeholder="Enter User Name" class="form-control" />
                </div>
                <div class="col-md-12 my-3">
                    <label for="" class="form-label">Password</label>
                    <input type="password" name="pwd" placeholder="Enter password" class="form-control" />
                </div>
                <div class="col-md-12 my-3">
                    <label for="" class="form-label">User Type</label>
                    <select name="type" class="form-select">
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="col-md-12 my-3">
                    <input type="submit" name="save" value="Add" class="btn btn-success" />
                </div>
            </form>
        </div>
    </div>
    

</div>


</section>
</body>
</html>


