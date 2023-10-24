// initialize hostname
  const hostname = "http://localhost/bot";

  // initialize the bot status and digit number
  let botStatus = false;

  let digit_number = 8;


  // Initialize an empty object to store the targetPrice values
  const targetPriceValues = {};


// functionality for the start and stop bot

  const budget_input = document.getElementById("budget_input");

  // target also lock and unlock btn
  const lock_btn = document.getElementById("budget_price_lock_btn");
  const unlock_btn = document.getElementById("budget_price_unlock_btn");
  const set_price_btn = document.getElementById("set_price_target");

  const crypto_select = document.getElementById("crypto_select");

  //  price locked by lock button click
  lock_btn.addEventListener("click", () =>{
    
    if (isTableMake()) {
      unlock_btn.classList.remove("disabled");
      lock_btn.classList.add("disabled");
      set_price_btn.classList.add("disabled");

      checkCrypto();

      botStatus = true;

    } else {
      alert("Please select crypto and set the target price!!");
    }
  })

  // remove lock by unlock button click
  unlock_btn.addEventListener("click", () =>{

    unlock_btn.classList.add("disabled");
    lock_btn.classList.remove("disabled");
    set_price_btn.classList.remove("disabled");

    botStatus = false;

  })

  //  notifications with sound effects

  const playNotification =()=>{
      let audio = new Audio("./assets/sound.mp3");
      audio.play().catch(e => console.log("You browser does not support to play notification sound effects. Please allow the notification sound effect to your browser"));
  }

  // fetch multiplicator data
  const fetchMultiplicatorDigit = async ()=>{
    try {

      const res = await fetch("./includes/mul_digit_num.php");

      const result = await res.json();
      
      digit_number = result.digit_num;
      
      return result;

    } catch (error) {
        console.log(error);
    }
  }

  fetchMultiplicatorDigit();

  // make digits for numbers by user choice 
  const digitNumber = (number) => {

    const parts = number.toString().split('.');

    let final_number;
  
    // Check if there is a decimal part
    if (parts.length === 2) {
      final_number = parts[0] + "." + parts[1].slice(0, digit_number);
    } else {
      final_number = parts[0];
    }
  
    return final_number;

  }


  // define target price with specified function

  const targetMany = ()=>{
    let filedNumber = prompt("How many target price you want to add?");

    filedNumber = Number(filedNumber);

    if (filedNumber > 0) {
      
      const htmlModal = document.getElementById("target_price_wrapper_input");
      let targetPriceHTMl = '';

      for (let index = 1; index <= filedNumber; index++) {

        targetPriceHTMl += `<div class="col-md-6 mb-3">
                              <label for="" class="form-label">Level ${index}</label>
                              <input type="number" id="level${index}" placeholder="Please enter Target Price" class="form-control" value="0" />
                            </div>
                          `;
        
      }

      htmlModal.innerHTML = targetPriceHTMl;

      }

    
  }

  // getting all target price value and adding them to an object
  const getTargetPrice = () =>{
    
    // target price inputs
    const targetPriceElements = document.querySelectorAll('.target_price_wrapper_input input[type="number"]');

      // Loop through each targetPrice element and add its value to the object
      targetPriceElements.forEach(function(targetPrice) {
          targetPriceValues[targetPrice.id] =  parseFloat(targetPrice.value); 
          targetPrice.value = 0;
      });

    // update the maximum Resistance Price
    const higherPrice = document.getElementById("highest_priceTarget_value");
      higherPrice.innerHTML = `<p><b>${targetPriceValues.level40}</b></p>`;

      // update the Maximum Support Price
    const lowPrice = document.getElementById("lowest_priceTarget_value");
      lowPrice.innerHTML = `<p><b>${targetPriceValues.level1}</b></p>`;

    document.getElementById("modal_close_btn").click();
  }

  // check crypto are available for order by api
  const checkAvailableCrypto = async (symbol)=>{

    try {

      const response = await fetch("./includes/checkCryptoAvailable.php",{
        headers:{"Content-Type": "application/json"},
        method:"POST",
        body:JSON.stringify({symbol})
      })

      const data = await response.json();
  
      if (data.status === false) {
        alert(data.massage);
      }
    
    } catch (error) {
      console.log(error);
    }


  }


  // check able function the crypto currency are available in database or not
  const checkCrypto = async ()=>{

    const crypto = crypto_select.value;

    try {

      const res = await fetch("./includes/check_crypto.php",{
        headers:{"Content-Type": "application/json"},
        method: "POST",
        body: JSON.stringify({crypto})
      });

      const result = await res.json();

      if (result.status === false) {

        const s_bid = budget_input.value / Object.keys(targetPriceValues).length;

        for (const key in targetPriceValues) {
          await fetch("./includes/addtrade_cor.php",{
           headers:{"Content-Type": "application"},
           method:"POST",
           body: JSON.stringify({level:key,target_price:targetPriceValues[key],crypto:crypto,s_bid:s_bid})
            }
          )
        }

        makeTable(crypto);

      }

    } catch (error) {
        console.log(error);
    }

  }
  
  // check with condition for make table
  const isTableMake = ()=>{

    const cryptocurrency = crypto_select.value;

    const tableBody = document.getElementById("table-body");

    if (tableBody.innerHTML === "") {
          if (Object.keys(targetPriceValues).length === 0 && targetPriceValues.constructor === Object || cryptocurrency === "") {
          
            return false;
      
          }else{
            return true;
          }
    }else{
      return true;
    }

  
  }


  // make table by currant crypto
  const makeTable = async (currency) =>{

    try {

        const res = await fetch("./includes/trade_cor.php",{
          headers:{"Content-Type" : "application/json"},
          method: "POST",
          body: JSON.stringify({currency})
        });
        const data = await res.json();

        if (data.length > 0) {

          let tableHTML="";

        data.forEach(elem =>{

          tableHTML +=`
          <tr>
              <td>${elem.pricelevel}</td>
              <td class="${elem.state_status}">${elem.state_status}</td>
              <td>${elem.multiplicator}</td>
              <td>$${elem.suggested_bid}</td>
              <td>$${elem.bid}</td>
              <td>${digitNumber(elem.crypto_var)}</td>
              <td>${digitNumber(elem.target_price)}</td>
              <td>${digitNumber(elem.on_actual_price)}%</td>
              <td>$${digitNumber(elem.ricavo)}</td>
              <td>$${digitNumber(elem.future_sell_usdt)}</td>
              <td>${digitNumber(elem.crypto_received)}</td>
            </tr>
          `
        });


        document.getElementById("table-body").innerHTML=tableHTML;
        
        // update the maximum Resistance Price
        const higherPrice = document.getElementById("highest_priceTarget_value");
        higherPrice.innerHTML = `<p><b>${digitNumber(data[0].target_price)}</b></p>`;

        // update the Maximum Support Price
        const lowPrice = document.getElementById("lowest_priceTarget_value");
        lowPrice.innerHTML = `<p><b>${digitNumber(data[data.length - 1].target_price)}</b></p>`;

        }else{
          document.getElementById("table-body").innerHTML="";
        
        // update the maximum Resistance Price
        const higherPrice = document.getElementById("highest_priceTarget_value");
        higherPrice.innerHTML = `<p><b>0</b></p>`;

        // update the Maximum Support Price
        const lowPrice = document.getElementById("lowest_priceTarget_value");
        lowPrice.innerHTML = `<p><b>0</b></p>`;

        }

    } catch (error) {
      console.log(error);
    }

  }


  // new crypto currency fetch function
  const crypto_fetch = async()=>{
    try {

      const res = await fetch("https://api.binance.com/api/v3/ticker/price");
      const data = await res.json();
      const select_option = document.getElementById("new_crypto_select");

      data.forEach(elem =>{
        const option = document.createElement('option');
        option.value = elem.symbol;
        option.textContent = elem.symbol;
        select_option.appendChild(option);
      })

    } catch (error) {
      console.log(error);
    }
  }

  crypto_fetch();


  // add new crypto options into data by form submit 
  const crypto_form = document.getElementById("add_new_crypto_form");

  crypto_form.addEventListener("submit",async (e) => {
    e.preventDefault();

    const value = document.getElementById("new_crypto_select").value;
    
    if (value !== "") {
      try {

        const data_crypto = JSON.stringify({value});

        const res = await fetch("./includes/crypto_cor.php",{
          headers:{"Content-Type": "application/json"},
          method: "POST",
          body:data_crypto
        });
        const result = await res.json();
        
        if(result.status){
          alert("You have added a new crypto currency");
            window.location = hostname;
        }else{
          alert("Something went wrong. Please try again!!")
        }
        
      } catch (error) {
          console.log(error);
      }
    } else {
      alert("Please select a new crypto currency");
    }
    
  })


    // get actual price 
    const getPrice = async (symbol) => {
      try {
  
        const res = await fetch(`https://api.binance.com/api/v3/ticker/price?symbol=${symbol}`);
  
        const data = await res.json();
        
        const amount = digitNumber(data.price);
        
        return amount;

      } catch (error) {
        console.log(error);
      }
    }

    
    // check which level is full and update those level gain and actual price
      const updateCryptoRange = async (currency,actualPrice)=>{

        try {
          
            //  fetch all data from database
            const res = await fetch("./includes/trade_cor.php",{
              headers:{"Content-Type" : "application/json"},
              method: "POST",
              body: JSON.stringify({currency})
            });
            const data = await res.json();
            
            if (data.length > 0) {
              // if have then loop for check state and set bid function
            for (let i = 0; i < data.length; i++) {
                
              if (data[i].state_status === "full" ) {

                const bid  = data[i].bid;
                // set on actual price existing excel formula
                
                const onActualPrice = (100 * actualPrice / data[i].target_price / 100) - 1;
                const newOnActualPrice = onActualPrice * 100;

                // set ricavo existing excel formula
                const newGains = (bid * newOnActualPrice) / 100;
                
                const gain = newGains / 2;

                // set gains ratio 50/50 usdt
                const newFutureSellUsdt = Number(bid) + gain;
                
                // set crypto receive existing excel formula
                const newCryptoReceived = (newGains /2) / data[i].target_price;
                
                // store all data in an object for make json format
                const updateData ={
                  pricelevel : data[i].pricelevel,
                  on_actual_price: newOnActualPrice,
                  ricavo: newGains,
                  future_sell_usdt: newFutureSellUsdt,
                  crypto_received: newCryptoReceived,
                  crypto: currency,
                  actualPrice: actualPrice,
                }

                const response = await fetch("./includes/update_trade_cor.php",{
                  headers:{"Content-Type": "application/json"},
                  method:"POST",
                  body: JSON.stringify(updateData)
                })
                
                const result = await response.json();

                makeTable(currency);

              }

            }

          }

        } catch (error) {
          console.log(error);
        }


      }

    // check target price state and set bid function
    const startBot = async (status)=>{

          if (status) {

            const multi = await fetchMultiplicatorDigit();
            const currency = crypto_select.value;
            // const actualPrice = "0.2137";
            const actualPrice = await getPrice(currency);

            // calculate slippage
            const calPercent = (actualPrice * multi.slippage_num) / 100;
            
        
            const lowRange = Number(actualPrice) - calPercent;
          
            const highRange = Number(actualPrice) + calPercent;
            
            
            
          
              try {
                  //  fetch all data from database
                const res = await fetch("./includes/trade_cor.php",{
                  headers:{"Content-Type" : "application/json"},
                  method: "POST",
                  body: JSON.stringify({currency})
                });
                const data = await res.json();
                // check that there have any data
                if (data.length > 0) {
                  
                    // if have then loop for check state and set bid function
                  for (let i = 0; i < data.length; i++) {
                      
                    //check actual price hit any level
                      if(digitNumber(highRange) >= digitNumber(data[i].target_price) && digitNumber(lowRange) <= digitNumber(data[i].target_price)){
                        console.log("Let's go");
                        // if hit then check state 
                          if (data[i].state_status === "open" ) {

                              // set new multiplier
                              let multiple = Number(data[i].multiplicator);
                              const newMultiple = multiple + 1;

                              // set bid with existing excel formula
                              const powerCalculator = Math.pow(multi.multiplicator,newMultiple)
                              const newBid = data[i].suggested_bid * powerCalculator;

                              // change state
                              const newState = "full";
                              

                              // store all data in an object for make json format
                              const updateData ={
                                pricelevel : data[i].pricelevel,
                                state_status: newState,
                                multiplicator : newMultiple,
                                s_bid: newBid,
                                bid: newBid,
                                crypto: currency,
                                actualPrice: actualPrice,
                                action:"buy"
                              }

                              // pass the data for save in database
                              const response = await fetch("./includes/updatetradebuy_cor.php",{
                                headers:{"Content-Type": "application/json"},
                                method:"POST",
                                body: JSON.stringify(updateData)
                              })

                              const result = await response.json();
                              console.log("🚀 ~ file: action.js:468 ~ startBot ~ result:", result)
                              
                              playNotification();

                              makeTable(currency);

                          } else if(data[i].state_status === "full") {

                            if (data[i + 1].state_status === "full") {

                              const bid  = data[i + 1].bid;
                              // set on actual price existing excel formula
                              
                              const onActualPrice = (100 * actualPrice / data[i + 1].target_price / 100) - 1;
                              const newOnActualPrice = onActualPrice * 100;

                              // set ricavo existing excel formula
                              const newGains = (bid * newOnActualPrice) / 100;
                              
                              const gain = newGains / 2;

                              // set gains ratio 50/50 usdt
                              const futureSellUsdt = Number(bid) + gain;

                              const preBid = data[i + 1].suggested_bid;

                              // change state
                              const newState = "open";
                              

                              // store all data in an object for make json format
                              const updateData ={
                                pricelevel : data[i + 1].pricelevel,
                                state_status: newState,
                                s_bid: preBid,
                                future_sell_usdt: futureSellUsdt,
                                crypto: currency,
                                actualPrice: actualPrice,
                                action:"sell"
                              }
                            
                              //pass the data for save in database
                              const response = await fetch("./includes/updatetradesell_cor.php",{
                                headers:{"Content-Type": "application/json"},
                                method:"POST",
                                body: JSON.stringify(updateData)
                              })

                              const result = await response.json();
                              console.log("🚀 ~ file: action.js:583 ~ startBot ~ result:", result)

                              playNotification();
                              
                              makeTable(currency);

                              }
                          }

                      }

                  }

                }

                updateCryptoRange(currency,actualPrice);
                
              } catch (error) {
                console.log(error);
              }

          }

    }
    const reset_price = async (symbol)=>{
      
      const response = await fetch("./includes/delete_target_price.php",{
        headers:{"Content-Type": "application/json"},
        method: "POST",
        body: JSON.stringify({symbol})
      })
      const data = await response.json();
      if (data.status) {
        makeTable(symbol);
      } else {
        alert("Something went wrong!!");
      }

    }
    const delete_reset_price = () =>{
      const symbol = crypto_select.value

      if (confirm('Are you sure to reset the target price?')) {
        reset_price(symbol);
      }

    };

  // change actual price by selected currency

  crypto_select.addEventListener("change",async (e) => {
    
    const symbol = e.target.value;
    const amount = await getPrice(symbol);
    document.getElementById("actual_price_value").innerHTML=`<p><b>${amount}</b></p>`;
    
    // add a new alert element for showing the crypto are available or not

    checkAvailableCrypto(symbol);

    // create a new button to delete target price
    document.getElementById("reset_btn_wrapper").innerHTML=`<button class="btn btn-danger" id="reset_btn" onclick="delete_reset_price()">Reset All Target Price for ${symbol}</button>`;

    makeTable(symbol);

    startBot(botStatus);
    
    setInterval(async () => {
      const symbol = e.target.value;
      const amount = await getPrice(symbol);
      document.getElementById("actual_price_value").innerHTML=`<p><b>${amount}</b></p>`;
      startBot(botStatus);
    },200);
    
  })









