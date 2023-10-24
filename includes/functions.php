<?php 
    require 'vendor/autoload.php';

    use Binance\API;
    
    class Functions{

        private $api_key;
        private $api_secret;
        private $api;

        public function __construct() {

            
            $this->api_key =  $_SESSION['api_key'];
            $this->api_secret =  $_SESSION['api_secret'];


            $this->api = new Binance\API($this->api_key, $this->api_secret,true);
        }

        
        public function checkApi(){
            try {
                // Make a test API request, for example, getting your account information
                $accountInfo = $this->api->account();
                return $accountInfo;

            } catch (Exception $e) {
                // If there's an exception, your API credentials are incorrect
                echo "API Key or Secret Key is incorrect!\n";
                echo "Error: " . $e->getMessage() . "\n";
            }
        }

        public function checkCryptoAvailable($crypto){
            try {
                $ticker = $this->api->price($crypto);
                return $ticker;
            } catch (Exception  $th) {
                return false;
            }
        }

        public function buyUSDT($symbol,$amount,$price){

            try {
                
                // Calculate the quantity of  you can purchase
                $quantity = $amount / $price;

                // Get information about the trading pair
                $exchangeInfo = $this->api->exchangeInfo();
                $filters = $exchangeInfo['symbols'][$symbol]['filters'];

                // Find the filter for lot size
                $lotSizeFilter = array_filter($filters, function($filter) {
                    return $filter['filterType'] == 'LOT_SIZE';
                });
                
                // Get the lot size step
                $lotSizeStep = $lotSizeFilter[1]['stepSize'];

                // Round the quantity to the nearest lot size
                $quantityRounded = round($quantity / $lotSizeStep) * $lotSizeStep;

                // Execute the  purchase order
                $order = $this->api->buy($symbol, $quantityRounded, $price);
                
                return $order;

            } catch (Exception $e) {
                return false;
            }


        }
        
        public function sellUSDT($symbol,$amount,$price){

            try {
                
                // Calculate the quantity of  you can purchase
                $quantity = $amount / $price;

                // Get information about the trading pair
                $exchangeInfo = $this->api->exchangeInfo();
                $filters = $exchangeInfo['symbols'][$symbol]['filters'];

                // Find the filter for lot size
                $lotSizeFilter = array_filter($filters, function($filter) {
                    return $filter['filterType'] == 'LOT_SIZE';
                });
                
                // Get the lot size step
                $lotSizeStep = $lotSizeFilter[1]['stepSize'];

                // Round the quantity to the nearest lot size
                $quantityRounded = round($quantity / $lotSizeStep) * $lotSizeStep;

                // Execute the sell order
                $order = $this->api->sell($symbol, $quantityRounded, $price);
                
                return $order;


            } catch (Exception $e) {
                return false;
            }

        }


    }


?>
