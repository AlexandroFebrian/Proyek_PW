<?php
  // $base = $_SERVER['REQUEST_URI'];
  // $base = ""; 

  session_start(); 
  $arr = []; 
  $baru = array(
    'idproduk'=>1, 
    'namaproduk'=>'jeruk',
    'qty'=>3,
    'harga'=>3000
  );
  array_push($arr,$baru);
  $baru = array(
    'idproduk'=>2, 
    'namaproduk'=>'apel',
    'qty'=>4,
    'harga'=>4000
  );
  array_push($arr,$baru);

  $_SESSION['cart'] = json_encode($arr); 
?>

<h3>Selected Items:</h3>
<ul>
  <?php 
  $subtotal = 0; 
  $cart = json_decode($_SESSION['cart']); 
  for($i = 0; $i < count($cart); $i++) {
    echo "<li>".$cart[$i]->namaproduk." - ".$cart[$i]->harga." x ".$cart[$i]->qty."</li>"; 
    $subtotal = $subtotal + ($cart[$i]->harga * $cart[$i]->qty); 
  }
  ?>
</ul>

<h4>Total: Rp <?php echo $subtotal; ?></h4>

<form action="checkout-process.php" method="POST">
  <input type="submit" value="Confirm" name="btnpay">
</form>
