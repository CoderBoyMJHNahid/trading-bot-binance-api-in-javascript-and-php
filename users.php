<?php include "includes/header.php" ?>

<header>
    <div class="container">
        <nav class="menu_bar">
            <ul class="d-flex gap-5">
                <li><a href="trade.php">Trading Gains</a></li>
                <li><a href="history.php">History Gains</a></li>
                <li><a href="users.php">All Users</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </div>
</header>
<section>

<div class="container">
    
    
    <?php 
        if ($_SESSION['user_type'] == 1) {
            echo "<a href='add-user.php' class='btn btn-primary'>Add New User</a>";
        }
    ?>

    <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <?php 
                        if ($_SESSION['user_type'] == 1) {
                            echo "<th>Action</th>";
                        }
                     ?>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT pid,username FROM personal_data ORDER BY pid";
                    $runQuery = mysqli_query($conn, $sql) or die("Couldn't run query personal data");
                    if (mysqli_num_rows($runQuery) > 0) {
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($runQuery)) {
                            $i++;
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['username'] ?></td>
                                <?php 
                                    if ($_SESSION['user_type'] == 1) {
                                ?>
                                <td>
                                    <a href="includes/delete_user.php?id=<?php echo $row['pid']?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this User?')">Delete</a>
                                </td>
                                <?php } ?>
                                
                            </tr>
                    <?php
                         }
                    } ?>
            </tbody>
        </table>

</div>


</section>
</body>
</html>


