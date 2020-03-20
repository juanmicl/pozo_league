<?php

/**
 * Creates a basic transaction with minimal parameters.<br />
 * See CreateTransaction for more advanced features.
 * @param amount The amount of the transaction (floating point to 8 decimals).
 * @param currency1 The source currency (ie. USD), this is used to calculate the exchange rate for you.
 * @param currency2 The cryptocurrency of the transaction. currency1 and currency2 can be the same if you don't want any exchange rate conversion.
 * @param buyer_email Set the buyer's email so they can automatically claim refunds if there is an issue with their payment.
 * @param address Optionally set the payout address of the transaction. If address is empty then it will follow your payout settings for that coin.
 * @param ipn_url Optionally set an IPN handler to receive notices about this transaction. If ipn_url is empty then it will use the default IPN URL in your account.
 */
if (!function_exists('create_tx'))
{
	function create_tx($amount, $currency1, $currency2, $buyer_email, $address='', $ipn_url='')
	{
		try {
			$tx = $this->cps->CreateTransactionSimple($amount, $currency1, $currency2, $buyer_email, $address, $ipn_url);
			return $tx;
		} catch (Exception $e) {
			echo 'Error: ' . $e->getMessage();
			exit();
		}
	}
}