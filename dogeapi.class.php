<?php

/*
 * @ Class: DogeAPI
 * @ Description: A PHP Class for dogeapi.com
 * @ Last change: 06.04.2014
 *
 * @ Version: 1.0.0
 * @ Copyright: 2014 www.mr-anderson.org
 * @ License: http://creativecommons.org/licenses/by-sa/4.0/
 * @ Author: Markus Tressel <support@mr-anderson.org>
 *
 * @ Requirements: PHP 5.3 and greater, dogeapi.com V2
 * @ Note: No cURL extension is needed!
 *
 * @ Info: If you need some help, just contact me @ support@mr-anderson.org
 * @ Info: Please note that you have to whitelist your server ip at http://dogeapi.com/settings/
 *
 * @ Updates: You can find updates under the following website: https://github.com/Neyoui/DogeAPI/
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
 *      $DogeAPI->withdraw(amount, pin, payment_address);
 *          Description: Withdraws {AMOUNT_DOGE} doge to a {PAYMENT_ADDRESS} you specify. Requires your {PIN}.
 *          Attention!: For now this must be more than 5 doge, and you must have enough extra in your wallet to pay all network fees (another 1-3 doge). DogeAPI takes a 0.5% fee when withdrawing.
 *          Require Args: amount, pin, payment_address
 *          Optional Args: none
 *          Response: <string>
 *          Example response: success
 *          Example response: failed
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
 *      $DogeAPI->get_address_received(address);
 *          Description: Returns the current amount received to all addresses with {PAYMENT_ADDRESS}.
 *          Require Args: address
 *          Optional Args: none
 *          Response: <numeric>
 *          Example response: 0
 *          Example response: 7.64974147
 *
 *      $DogeAPI->get_address_by_label(label);
 *          Description: Returns the payment address for the given {ADDRESS_LABEL}. If there are more addresses with the same label, all of them would be returned.
 *          Require Args: label
 *          Optional Args: none
 *          Response: <array>
 *          Example response: Array ( [0] => DE63eKsUjv4YqbjCp9XQj7FGDQ45cHvTgz )
 *          Example response: Array ( [0] => DE63eKsUjv4YqbjCp9XQj7FGDQ45cHvTgz [1] => DJKF7PQXB1JzMvCpCtDvvaMoipVbFBEDig )
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
 *      $DogeAPI->create_user(user_id);
 *          Description: Creates a new user identified by an alphanumeric, case-insensitive {USER_ID} and returns their payment address. Each user has only one payment address.
 *          Require Args: user_id
 *          Optional Args: none
 *          Response: <dogecoin_address>
 *          Example response: DSHm1KireZW22EaMTXYB2dXKvo2BVf1iSk
 *          Example response: failed (if someting went wrong)
 *
 *      $DogeAPI->get_user_address(user_id);
 *          Description:Returns the balance of the user with a given {USER_ID}.
 *          Require Args: user_id
 *          Optional Args: none
 *          Response: <dogecoin_address>
 *          Example response: DSHm1KireZW22EaMTXYB2dXKvo2BVf1iSk
 *
 *      $DogeAPI->withdraw_from_user(user_id, pin, amount, payment_address);
 *          Description: Withdraws {AMOUNT_DOGE} from {USER_ID} to {PAYMENT_ADDRESS}. Requires your {PIN}
 *          Require Args: user_id, pin, amount, payment_address
 *          Optional Args: none
 *          Response: <string>
 *          Example response: success
 *          Example response: failed
 *
 *      $DogeAPI->move_to_user(to_user_id, from_user_id, amount);
 *          Description: Moves {AMOUNT_DOGE} to user with ID {TO_USER_ID} from user with ID {FROM_USER_ID}. There is no network fee for this transaction, just the DogeAPI fee.
 *          Require Args: to_user_id, from_user_id, amount
 *          Optional Args: none
 *          Response: <string>
 *          Example response: success
 *          Example response: failed
 *
 *      $DogeAPI->get_users();
 *          Description: Returns a list of users asssociated with your account with their balances.
 *          Require Args: tnone
 *          Optional Args: none
 *          Response: <array>
 *          Example response: Array ( [0] => Array ( [user_id] => TestDevUser [payment_address] => DDEBH2J1dW6yUSboKjtu7dfcAsLbGaX3UQ [user_balance] => 0.00000000 ) [1] => Array ( [user_id] => TestDevUser2 [payment_address] => D9YT1BA1g8EwzubJfkxu8uPQ4ppcamPZzc [user_balance] => 0.00000000 ) )
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

    public function withdraw($amount, $pin, $payment_address) {

        $response = $this->server_request("withdraw&amount_doge=".$amount."&pin=".$pin."&payment_address=".$payment_address."");

        if(empty($response)) {
            return "failed";
        } else {
            return "success";
        }

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

    public function get_address_received($address) {

        $response = $this->server_request("get_address_received&payment_address=".$address);
        return $response['data']['received'];

    }

    public function get_address_by_label($label) {

        $response = $this->server_request("get_address_by_label&address_label=".$label);
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

    /* API V2 */
    public function create_user($user_id) {

        $response = $this->server_request("create_user&user_id=".$user_id);

        if(empty($response)) {
            return "failed";
        } else {
            return $response['data']['address'];
        }

    }

    public function get_user_address($user_id) {

        $response = $this->server_request("get_user_address&user_id=".$user_id);
        return $response['data']['address'];

    }

    public function get_user_balance($user_id) {

        $response = $this->server_request("get_user_balance&user_id=".$user_id);
        return $response['data']['balance'];

    }

    public function withdraw_from_user($user_id, $pin, $amount, $payment_address) {

        $response = $this->server_request("withdraw_from_user&user_id=".$user_id."&pin=".$pin."&amount_doge=".$amount."&payment_address=".$payment_address);

        if(empty($response)) {
            return "failed";
        } else {
            return "success";
        }

    }

    public function move_to_user($to_user_id, $from_user_id, $amount) {

        $response = $this->server_request("move_to_user&to_user_id=".$to_user_id."&from_user_id=".$from_user_id."&amount_doge=".$amount);

        if(empty($response)) {
            return "failed";
        } else {
            return "success";
        }

    }

    public function get_users() {

        $response = $this->server_request("get_users");
        return $response['data']['users'];

    }

    public function get_transactions() {

    }

}

?>
