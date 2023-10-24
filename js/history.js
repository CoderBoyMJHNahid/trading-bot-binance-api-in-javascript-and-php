  // history logs javascript parts

  let digit_number = 8;

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

  const digitNumber = (number)=>{

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

  // average purchase price fetch code

  const averagePurchasePrice = async (crypto)=>{
    try {

      const res = await fetch("./includes/average_purchase.php",{
        headers:{"Content-Type": "application/json"},
        method:"POST",
        body: JSON.stringify({crypto})
      })

      const result = await res.json();
    
      return result;


    } catch (error) {
      console.log(error);
    }
  }




  // select crypto to see which crypto history
  const target_select = document.getElementById('crypto_select_history');

  // event fire on change the crypto selector
  target_select.addEventListener("change", async ()=>{
    const value = target_select.value;

    try {

      const actualPrice = await getPrice(value);
        
        const res = await fetch("./includes/history_cor.php",{
            headers:{"Content-Type": "application/json"},
            method: "POST",
            body: JSON.stringify({value})
        });

        const data = await res.json();
        
        if (data.length > 0) {

          const averagePrice = await averagePurchasePrice(value);

          document.getElementById("history_crypto_value").innerHTML= digitNumber(data[data.length - 1].possessed_crypto);

          document.getElementById("cryptoTotValue").innerHTML= digitNumber(data[data.length - 1].possessed_crypto * actualPrice);

          document.getElementById("averagePurchasePrice_val").innerHTML= digitNumber(averagePrice);

          let tableHTML="";

        data.forEach(elem =>{
            const {history_id,history_date,operation,usdt_value,crypto_var,possessed_crypto,buy_sell_price,actual_value} = elem;

          tableHTML +=`
            <tr>
                <td>${history_date}</td>
                <td>${operation}</td>
                <td>${operation === "buy" ? "- " : ""}${digitNumber(usdt_value)}</td>
                <td>${operation === "sell" ? "- " : ""}${digitNumber(crypto_var)}</td>
                <td>${digitNumber(possessed_crypto)}</td>
                <td>${digitNumber(buy_sell_price)}</td>
                <td>${digitNumber(actual_value)}</td>
                <td>
                    <a href="includes/delete_history.php?id=${history_id}" onclick="return confirm('Are you sure to delete this history log?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
          `
        });

        document.getElementById("history-table-body").innerHTML=tableHTML;

        }else{
          document.getElementById("history_crypto_value").innerHTML= "Please Select the Crypto";

          document.getElementById("cryptoTotValue").innerHTML= "Please Select the Crypto";

          document.getElementById("averagePurchasePrice_val").innerHTML= "Select the Crypto";

          document.getElementById("history-table-body").innerHTML="";
        }
        
        


    } catch (error) {
        console.log(error);
    }


  })