<?php include "includes/header.php" ?>

<header>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="crypto_actualPrice_wrapper">
    
    
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="crypto_name">
                            <p>Crypto</p>
                        </div>
                        <div class="crypto_select_wrapper">
                            <select id="crypto_select_history" class="form-select">
                                <option value="" disabled selected>Select Crypto</option>
                                <?php 
                                    $sql = "SELECT * FROM crypto WHERE `user` = '{$_SESSION['user_id']}'";
                                    $runQuery = mysqli_query($conn, $sql) or die("runQuery failed");
                                    while ($row = mysqli_fetch_array($runQuery)){
                                        echo "<option value='{$row['crypto_name']}'>{$row['crypto_name']}</option>";
                                    }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="averagePurchasePrice ">
                            <p>Average Purchase Price</p>
                        </div>
                        <div class="averagePurchasePrice_val">
                            <p>
                                <b id="averagePurchasePrice_val">Select the Crypto</b>
                            </p>
                        </div>
                    </div>
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="cryptoTot">
                            <p>Crypto Tot Value</p>
                        </div>
                        <div class="cryptoTotValue">
                        <p>
                            <b id="cryptoTotValue">Please Select the Crypto</b>
                        </p>
                        </div>
                    </div>
    
    
                </div>
            </div>
            <div class="col-md-4">
                <div class="budget_crypto_wrapper">
    
                    <div class="info-box d-flex align-items-center justify-content-between">
                        
                        <div class="budget_wrapper">
                            <?php 
                                $get_budget_sql = "SELECT budget,multiplicator,digit_num FROM personal_data WHERE pid = '{$_SESSION['user_id']}'";
                                $runQuery_get_budget_sql = mysqli_query($conn, $get_budget_sql) or die("Couldn't get budget");

                                $budget = mysqli_fetch_all($runQuery_get_budget_sql,MYSQLI_ASSOC);
                            ?>
                            <p>Budget : <b>$<?php echo $budget[0]['budget'] ?></b></p>
                        </div>
                        <div class="budget_btn">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#depositModal">Add Funds</button>
                            <button class="btn btn-sm btn-secondary"  data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw</button>
                        </div>
                    </div>

                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="remainning_budget_wrapper">
                            <p>Remaining Budget</p>
                        </div>
                        <div class="remainning_budget_value">
                            <?php 
                                    $buy_sum_sql = "SELECT SUM(usdt_value) as buy_usdt_value FROM `history` WHERE operation = 'buy' AND `user` = '{$_SESSION['user_id']}'";

                                    $sell_sum_sql = "SELECT SUM(usdt_value) as sell_usdt_value FROM `history` WHERE operation = 'sell' AND `user` = '{$_SESSION['user_id']}'";
                                
                                    $runQuery_buy_sum = mysqli_query($conn, $buy_sum_sql) or die("Error in buy mysqli query");
                                
                                    $buy_sum = mysqli_fetch_all($runQuery_buy_sum, MYSQLI_ASSOC);
                                
                                    $runQuery_sell_sum = mysqli_query($conn, $sell_sum_sql) or die("Error in sell mysqli query");
                                
                                    $sell_sum = mysqli_fetch_all($runQuery_sell_sum, MYSQLI_ASSOC);

                                    
                                $total_calculate = ($budget[0]['budget'] - $buy_sum[0]['buy_usdt_value']) + $sell_sum[0]['sell_usdt_value'];

                            
                                    $numberCal =  sprintf("%.{$budget[0]['digit_num']}f", floor($total_calculate*10000*($total_calculate>0?1:-1))/10000*($total_calculate>0?1:-1));

                                echo "<p><b>$$numberCal</b></p>";

                            ?>
                            
                        </div>
                    </div>

                    <div class="info-box d-flex align-items-center justify-content-between border-0">
                        
                        <div class="prossessed_crypto_wrapper">
                            <p>Possessed Crypto Tot</p>
                        </div>
                        <div class="prossessed_crypto_value">
                            <p><b id="history_crypto_value">Please Select the Crypto</b></p>
                        </div>
                    </div>
    
                </div>
            </div>

            <div class="col-md-4">
                <div class="page_link_wrapper">
                    <ul>
                        <li><a href="trade.php">Trading Gains</a></li>
                        <li><a href="history.php">History Gains</a></li>
                        <li><a href="users.php">All Users</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </div>

                <div class="button_wrapper">
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#multiplicatorModal">Set Multiplicator</button>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#digit_numModal">Set Digit</button>
                </div>

            </div>
        </div>
    </div>
</header>

<section id="history-table">
    <div class="container">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Operation</th>
                    <th>USDT</th>
                    <th>Crypto</th>
                    <th>PossessedCrypto</th>
                    <th>Buy/Sell price</th>
                    <th>Actual Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="history-table-body"></tbody>
        </table>
    </div>
</section>


<!-- modal wrapper -->

<!-- deposit modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="depositModalLabel">ADD FUND</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-5">
        <form action="includes/add_fund_cor.php" method="POST" class="row">
            <input type="number" name="deposit_val" class="form-control my-3" placeholder="ADD FUND" required/>
            <input type="hidden" name="budget" value="<?php echo $budget[0]['budget'] ?>">
            <button type="submit" class="btn btn-success my-3">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- withdraw modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="withdrawModalLabel">Withdraw</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-5">
        <form action="includes/withdraw_cor.php" method="POST" class="row">
            <input type="number" name="withdraw_val" class="form-control my-3" placeholder="Withdraw"/>
            <input type="hidden" name="budget" value="<?php echo $budget[0]['budget'] ?>">
            <button type="submit" class="btn btn-secondary my-3">Withdraw</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Multiplicator Modal -->
<div class="modal fade" id="multiplicatorModal" tabindex="-1" aria-labelledby="multiplicatorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="multiplicatorModalLabel">Multiplicator</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-5">
        <form action="includes/multiplicator_cor.php" method="POST" class="row">
            <input type="text" name="multiplicator_val" class="form-control my-3" placeholder="Multiplicator"/>
            <input type="hidden" name="form_history"/>
            <p class="my-3">You are using : <?php echo $budget[0]['multiplicator'] ?></p>
            <button type="submit" class="btn btn-secondary my-3">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- digit modal -->
<div class="modal fade" id="digit_numModal" tabindex="-1" aria-labelledby="digit_numModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="digit_numModalLabel">Digit Number</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-5">
        <form action="includes/digit_cor.php" method="POST" class="row">
            <input type="number" name="digit_num_val" class="form-control my-3" placeholder="Digit Number"/>
            <input type="hidden" name="form_history"/>
            <p class="my-3">You are using : <?php echo $budget[0]['digit_num'] ?></p>
            <button type="submit" class="btn btn-secondary my-3">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="js/history.js"></script>

</body>
</html>




