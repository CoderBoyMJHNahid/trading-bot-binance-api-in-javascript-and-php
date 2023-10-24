<?php
 include "includes/header.php";
 include "includes/functions.php";

?>

<header>
    <div class="container">
        <div class="row">
                <?php 
                    $function = new Functions();

                    $check = $function->checkApi();

                    if (empty($check)) {
                        echo "<div class='alert alert-danger'>
                                <p>API Key or Secret Key is incorrect! Please Log Out and check the API Key or Secret Key</p>
                            </div>";
                        }
                
                ?>
            <div class="col-md-4">
                <div class="crypto_actualPrice_wrapper">
    
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="highest_priceTarget">
                            <p>Maximum Resistance Price</p>
                        </div>
                        <div id="highest_priceTarget_value">
                            <p><b>0</b></p>
                        </div>
                    </div>
    
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="lowest_priceTarget">
                            <p>Maximum Support Price</p>
                        </div>
                        <div id="lowest_priceTarget_value">
                            <p><b>0</b></p>
                        </div>
                    </div>
    
                    <div class="info-box d-flex align-items-center justify-content-between">
                        <div class="crypto_name">
                            <p>Crypto</p>
                        </div>
                        <div class="crypto_select_wrapper">
                            <select id="crypto_select" class="form-select">
                                <option value="" disabled selected>Select Crypto</option>
                                <?php 
                                    $sql = "SELECT * FROM crypto WHERE `user` = '{$_SESSION['user_id']}'";
                                    $runQuery = mysqli_query($conn, $sql) or die("runQuery failed");
                                    while ($row = mysqli_fetch_array($runQuery)){
                                        echo "<option value='{$row['crypto_name']}'>{$row['crypto_name']}</option>";
                                    }
                                ?>

                            </select>
                            <?php 
                                $get_budget_sql = "SELECT budget,multiplicator,digit_num,slippage_num FROM personal_data WHERE pid = '{$_SESSION['user_id']}'";
                                $runQuery_get_budget_sql = mysqli_query($conn, $get_budget_sql) or die("Couldn't get budget");

                                $budget = mysqli_fetch_all($runQuery_get_budget_sql,MYSQLI_ASSOC);
                            ?>
                            <input type="hidden" id="budget_input" value="<?php echo $budget[0]['budget'] ?>">
                        </div>
                    </div>
    
                    <div class="info-box d-flex align-items-center justify-content-between border-0">
                        <div class="actual_price">
                            <p>Actual Price</p>
                        </div>
                        <div id="actual_price_value">
                            <p><b> - </b></p>
                        </div>
                    </div>
    
                </div>
            </div>

            <div class="col-md-4">
                <!-- btn wrapper -->
                <div class="header_btn_wrapper">
                    <button class="btn btn-warning my-4" id="set_price_target" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="targetMany()">
                        Set Price Target
                    </button>
                    <button class="btn btn-primary my-4 ms-2" id="set_price_target" data-bs-toggle="modal" data-bs-target="#cryptoModal">
                        Add New Crypto
                    </button>
                        <br/>
                    <button class="btn btn-success" id="budget_price_lock_btn">Start Bot</button>
                    <button class="btn btn-secondary disabled" id="budget_price_unlock_btn">
                        Stop Bot
                    </button>
                    
                    <div id="reset_btn_wrapper" class="my-3"></div>


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
                    <div class="modal_wrapper my-3">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#multiplicatorModal">Set Multiplicator</button>
                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#digit_numModal">Set Digit</button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#slippage_modal">Slippage</button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
    

<section id="data-table">
    <div class="container-fluid">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Price Level</th>
                    <th>State</th>
                    <th>Multiplicator</th>
                    <th>Suggested Bid</th>
                    <th>Bid</th>
                    <th>Crypto Var</th>
                    <th>Target Price</th>
                    <th>% on Actual Price</th>
                    <th>Ricavo</th>
                    <th>Future Sell USDT</th>
                    <th>Crypto received</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
</section>


<!-- Modal for Target Price -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Set Target Price</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <div class="target_price_wrapper_input row" id="target_price_wrapper_input">
            <!-- <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Level 40</label>
                    <input type="number" id="level40" placeholder="Please enter Target Price" class="form-control" value="0" />
                </div> -->
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modal_close_btn">Close</button>
          <button type="button" class="btn btn-success" onclick="getTargetPrice()">Save</button>
        </div>
      </div>
    </div>
</div>

<!-- modal for add new crypto -->
<div class="modal fade" id="cryptoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Crypto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form id="add_new_crypto_form">
                <label for="" class="form-label">Select Crypto Currency</label>
                <select id="new_crypto_select"class="form-select">
                    <option disabled selected value="">Select Crypto</option>
                </select>

                <input type="submit" value="Add New" class="btn btn-primary mt-4">
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

            <p class="my-3">You are using : <?php echo $budget[0]['digit_num'] ?></p>
            <button type="submit" class="btn btn-secondary my-3">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- slippage modal -->
<div class="modal fade" id="slippage_modal" tabindex="-1" aria-labelledby="slippage_modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="slippage_modalLabel">Slippage</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-5">
        <form action="includes/slippage_cor.php" method="POST" class="row">
            <input type="text" name="slippage_num_val" class="form-control my-3" placeholder="Slippage Number" autocomplete="off"/>

            <p class="my-3">You are using : <?php echo $budget[0]['slippage_num'] ?>%</p>
            <button type="submit" class="btn btn-secondary my-3">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="js/action.js"></script>

</body>
</html>