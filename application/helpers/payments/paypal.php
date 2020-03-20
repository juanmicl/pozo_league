<?php
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/developer/applications/
$clientId = config_item('paypal_clientId');
$clientSecret = config_item('paypal_clientSecret');

$apiContext = getApiContext($clientId, $clientSecret);
$baseUrl = getBaseUrl();

function createPayment($data) {
  global $apiContext;
  global $baseUrl;
  // ### Payer
  // A resource representing a Payer that funds a payment
  // For paypal account payments, set payment method
  // to 'paypal'.
  $payer = new Payer();
  $payer->setPaymentMethod("paypal");
  // ### Amount
  // Lets you specify a payment amount.
  // You can also specify additional details
  // such as shipping, tax.
  $amount = new Amount();
  $amount->setCurrency($data['currency'])->setTotal($data['price']);
  // ### Payee
  // Specify a payee with that user's email or merchant id
  // Merchant Id can be found at https://www.paypal.com/businessprofile/settings/
  $payee = new Payee();
  $payee->setEmail($data['email']);
  // ### Transaction
  // A transaction defines the contract of a
  // payment - what is the payment for and who
  // is fulfilling it.
  $transaction = new Transaction();
  $transaction->setAmount($amount)
      ->setDescription($data['id'])
      ->setPayee($payee)
      ->setInvoiceNumber(uniqid());
  // ### Redirect urls
  // Set the urls that the buyer must be redirected to after
  // payment approval/ cancellation.
  $redirectUrls = new RedirectUrls();
  $redirectUrls
        ->setReturnUrl($baseUrl."/order/".$data['id']."?success=true")
        ->setCancelUrl($baseUrl."/order/".$data['id']."?success=false");
  // ### Payment
  // A Payment Resource; create one using
  // the above types and intent set to 'sale'
  $payment = new Payment();
  $payment->setIntent("sale")
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setTransactions(array($transaction));
  // ### Create Payment
  // Create a payment by calling the 'create' method
  // passing it a valid apiContext.
  // (See bootstrap.php for more on `ApiContext`)
  // The return object contains the state and the
  // url to which the buyer must be redirected to
  // for payment approval
  try {
      $payment->create($apiContext);
  } catch (Exception $ex) {
      exit(1);
  }
  // ### Get redirect url
  // The API response provides the url that you must redirect
  // the buyer to. Retrieve the url from the $payment->getApprovalLink()
  // method
  return $payment->getApprovalLink();
}

function checkPayment() {
  global $apiContext;
  // ### Approval Status
  // Determine if the user approved the payment or not
  if ($_GET['success'] == 'true') {
  try {
    // Get the payment Object by passing paymentId
    // payment id was previously stored in session in
    // CreatePaymentUsingPayPal.php
    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);
    // ### Payment Execute
    // PaymentExecution object includes information necessary
    // to execute a PayPal account payment.
    // The payer_id is added to the request query parameters
    // when the user is redirected from paypal back to your site
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);
    try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $apiContext);
        //echo $payment->getId();
        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            return false;
        }
    } catch (Exception $ex) {
        return false;
    }
    return true;
  } catch (Exception $ex) {
    return false;
  }
  } else {
    // Pedido cancelado
    return false;
  }
}

function getApiContext($clientId, $clientSecret)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );
    return $apiContext;
}

function getBaseUrl()
{
    $protocol = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
        $protocol .= 's';
    }
    $host = $_SERVER['HTTP_HOST'];
    $request = $_SERVER['PHP_SELF'];
    return dirname($protocol . '://' . $host . $request);
}
?>
