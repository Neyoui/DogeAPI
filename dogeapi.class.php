<?php

/*
 * @ Class: DogeAPI
 * @ Description: A PHP Class for dogeapi.com
 * @ Last change: 06.04.2014
 *
 * @ Version: 1.0.0
 * @ Copyright: 2014 www.mr-anderson.org
 * @ Author: Markus Tressel <support@mr-anderson.org>
 *
 * @ Requirements: PHP 5.3 and greater, dogeapi.com V2
 * @ Note: No cURL extension is needed!
 *
 * @ Info: If you need some help, just contact me @ support@mr-anderson.org
 * @ Info: Please note that you have to whitelist your server ip at http://dogeapi.com/settings/
 *
 * @ Updates: You can fined updates under the following website: http://dogecoin.mr-anderson.org/
 * @ Feedback & Bugreport: If you want to give me feedback or you find a Bug, than just contact me via mail.
 *
 * @ Donate: Feel free to donate something.
 * @ Donate Address: DSHm1KireZW22EaMTXYB2dXKvo2BVf1iSk (Dogecoin)
 * @ Donate Address: 16qGX5wggVB3XbvYGcM7E4k7YVVTr4mx9X (Bitcoin)
 * @ Donate Address: N5WbYVk4LyE4qxoWYjzZPTqKcCFDJKVaxs (Namecoin)
 *
 */

/*
 * @ Documentation
 *
 * @ Installation:
 *
 *      1) Create a new DogeAPI Object.
 *          $DogeAPI = new DogeAPI("your_api_key");
 *
 * @ Useable functions:
 *
 *      $DogeAPI->get_balance();
 *          Description: Returns the DOGE balance of your entire account to 8 decimal places.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <numeric>
 *          Example response: 0
 *          Example response: 7.64974147
 *
 *      $DogeAPI->get_new_address("Label");
 *          Description: Returns a new payment address for your account. You can pass an optional alphanumeric {ADDRESS_LABEL} as a label for the address.
 *          Require Args: none
 *          Optional Args: address_label
 *          Response: <dogecoin_address>
 *          Example response: DSHm1KireZW22EaMTXYB2dXKvo2BVf1iSk
 *
 *      $DogeAPI->get_my_addresses();
 *          Description: Returns all payment addresses/address_ids for your account.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <array>
 *          Example response: Array ( [0] => DN9NVwU7Z8bBon9n9k94haTwf546HB25ut [1] => D9K1c9B6D7LQG75hNwEEp6VEZR3XD3SqoS [2] => DE63eKsUjv4YqbjCp9XQj7FGDQ45cHvTgz )
 *
 *      $DogeAPI->get_difficulty();
 *          Description: Returns the current difficulty.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <numeric>
 *          Example response: 1033.68
 *
 *      $DogeAPI->get_current_block();
 *          Description: Returns the current block.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <numeric>
 *          Example response: 170981
 *
 *      $DogeAPI->get_current_price(amount, currency);
 *          Description: Returns the current price in USD or BTC. The convert_to(defaults to USD) and amount_doge(defaults to 1) parameters are optional.
 *          Require Args: none
 *          Optional Args: amount, currency
 *          Response: <numeric>
 *          Example response: 0.00046134376
 *
 *      $DogeAPI->get_network_hashrate();
 *          Description: Returns the current network hashrate.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <numeric>
 *          Example response: 72329521595
 *
 *      $DogeAPI->get_info();
 *          Description: Returns current information, including price in USD/BTC, block count, difficulty, 5 minute price change, network hashrate, and API version.
 *          Require Args: none
 *          Optional Args: none
 *          Response: <array>
 *          Example response: Array ( [difficulty] => 1588.77 [network_hashrate] => 72599438905 [current_block] => 171002 [doge_usd] => 0.0004620649 [doge_btc] => 1.01E-6 [5min_btc_change] => 0 [5min_usd_change] => 0 [api_version] => 2 )
 *
 */

class DogeAPI {

    /* settings - do not touch them! */
    protected $SETTINGS_API_HOST    = "https://www.dogeapi.com/";
    protected $SETTINGS_API_PATH    = "wow/v2/";

    /* vars */
    private $APIKEY;


    /* construcor */
    public function __construct($APIKEY = NULL) {

        // SETUP APIKEY
        if($APIKEY == NULL) {
            echo 'You need to setup an API KEY!<br /><pre>$DogeAPI = new DogeAPI("your_api_key");</pre>';
            exit();
        } else { $this->APIKEY = $APIKEY; }

        // VERIFY APIKEY
        if($this->verify_apikey() != true) {
            echo "Your API KEY is invalid!";
            exit();
        }

    }

    /* server_request */
    private function server_request($request, $need_key = true) {

        if($need_key == true) {
            $request = $this->SETTINGS_API_HOST.$this->SETTINGS_API_PATH."?api_key=".$this->APIKEY."&a=".$request;
        } else {
            $request = $this->SETTINGS_API_HOST.$this->SETTINGS_API_PATH."?a=".$request;
        }

        $response = file_get_contents($request);

        if(!empty($response)) {
            return json_decode($response, true);
        } else {
            return $response;
        }

    }

    /* verify_apikey */
    private function verify_apikey() {

        $response = $this->server_request("get_balance");

        if(empty($response)) {
            return false;
        } else {
            return true;
        }

    }

    /* debug */
    private function debug($response) {
        print_r($response);
    }


    /* API */
    public function get_balance() {

        $response = $this->server_request("get_balance");
        return $response['data']['balance'];

    }

    public function get_new_address($address_label = NULL) {

        $request = "get_new_address";

        if($address_label != NULL) {
            $request .= "&address_label=".$address_label;
        }

        $response = $this->server_request($request);

        return $response['data']['address'];

    }

    public function get_my_addresses() {

        $response = $this->server_request("get_my_addresses");
        return $response['data']['addresses'];

    }


    /* requests which doesn't need an api key */
    public function get_difficulty() {

        $response = $this->server_request("get_difficulty", false);
        return $response['data']['difficulty'];

    }

    public function get_current_block() {

        $response = $this->server_request("get_current_block", false);
        return $response['data']['current_block'];

    }

    public function get_current_price($amount = 1 ,$currency = "USD") {

        $request = "get_current_price&convert_to=".$currency."&amount_doge=".$amount;

        $response = $this->server_request($request, false);
        return $response['data']['amount'];

    }

    public function get_network_hashrate() {

        $response = $this->server_request("get_network_hashrate", false);
        return $response['data']['network_hashrate'];

    }

    public function get_info() {

        $response = $this->server_request("get_info", false);
        return $response['data']['info'];

    }

}

?>
