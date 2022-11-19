<?php
session_start(); 
require_once(dirname(__FILE__) . '/Veritrans.php');

//Set Your server key
Veritrans_Config::$serverKey = "SB-Mid-server-vKCiBWxuxq4MuWn5dbexzL2u";

// Uncomment for production environment
// Veritrans_Config::$isProduction = true;

// Enable sanitization
Veritrans_Config::$isSanitized = true;

// Enable 3D-Secure
Veritrans_Config::$is3ds = true;

  $subtotal = 0; 
  $cart = json_decode($_SESSION['cart']); 
  for($i = 0; $i < count($cart); $i++) {
    $subtotal = $subtotal + ($cart[$i]->harga * $cart[$i]->qty); 
  }

// Required
$transaction_details = array(
  'order_id' => rand(),
  'gross_amount' => $subtotal, // no decimal allowed for creditcard
);

$arrmidtrans = []; 
$cart = json_decode($_SESSION['cart']); 
for($i = 0; $i < count($cart); $i++) {
  $arrmidtrans[$i] = []; 
  $arrmidtrans[$i]['id'] = $cart[$i]->idproduk;
  $arrmidtrans[$i]['price'] = $cart[$i]->harga;
  $arrmidtrans[$i]['quantity'] = $cart[$i]->qty;
  $arrmidtrans[$i]['name'] = $cart[$i]->namaproduk;
}


$item_details = $arrmidtrans; //array ($item1_details, $item2_details);

// Optional
$billing_address = array(
  'first_name'    => "Andri",
  'last_name'     => "Litani",
  'address'       => "Mangga 20",
  'city'          => "Jakarta",
  'postal_code'   => "16602",
  'phone'         => "081122334455",
  'country_code'  => 'IDN'
);

// Optional
$shipping_address = array(
  'first_name'    => "Obet",
  'last_name'     => "Supriadi",
  'address'       => "Manggis 90",
  'city'          => "Jakarta",
  'postal_code'   => "16601",
  'phone'         => "08113366345",
  'country_code'  => 'IDN'
);

// Optional
$customer_details = array(
  'first_name'    => "Andri",
  'last_name'     => "Litani",
  'email'         => "andri@litani.com",
  'phone'         => "081122334455",
  'billing_address'  => $billing_address,
  'shipping_address' => $shipping_address
);

// Optional, remove this to display all available payment methods
// $enable_payments = array('credit_card','cimb_clicks','mandiri_clickpay','echannel');
// $enable_payments = array(); 

// Fill transaction details
$transaction = array(
  'transaction_details' => $transaction_details,
  'customer_details' => $customer_details,
  'item_details' => $item_details,
  // 'enabled_payments' => $enable_payments,
);

$snapToken = Veritrans_Snap::getSnapToken($transaction);
echo "snapToken = ".$snapToken;
?>

<!DOCTYPE html>
<html>
  <body>
    <button id="pay-button">Pay!</button>
    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre> 

<!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-TKV1zRASAGX4eBcv"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('<?=$snapToken?>', {
          // Optional
          onSuccess: function(result){
            console.log("success bayar "); 
            alert('success bayar midtrans'); 
            // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            document.getElementById('result-json').innerHTML = "masuk sukses";
            /* You may add your own js here, this is just example */ 
            // $.post("ajax.php",
            //   { jenis: 'midtranspayment' },
            //   function(result) {
            //     alert(result); 
            //     window.location = "thanks.php"; 
            //   }
            // );
          },
          // Optional
          onPending: function(result){
            document.getElementById('result-json').innerHTML = "masuk pending"; 
            // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            /* You may add your own js here, this is just example */ 
          },
          // Optional
          onError: function(result){
            document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            /* You may add your own js here, this is just example */ 
          }
        });
      };
    </script>
  </body>
</html>
