<?php
    require_once("connection.php");

    $cart_item = [];

    if (isset($_SESSION["auth_user_id"])) {
        $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "'");
        while ($row = mysqli_fetch_array($query)) {
            $cart_item[] = $row;
        }
    }
?>

<?php
    if (count($cart_item) == 0) {
?>
    <div class="container-fuild">
        <img src="storage/icons/empty.png" class="mt-5" width="150px">
        <h2 class="mt-2">Keranjangmu masih kosong nih</h2>
        <p class="mt-2">Yuk, isi keranjangmu dengan kacamata favoritmu!</p>
        <button class="btn btn-success mt-2 fw-bold" formaction="product.php">Mulai Belanja</button>
    </div>
<?php
    } else {
        $qtytotal = 0;
        $hargatotal = 0;
        for ($i = 0; $i < count($cart_item); $i++) {
            $qtytotal += $cart_item[$i]["ca_qty"];
            $hargatotal += $cart_item[$i]["ca_subtotal"];
            $co_id = $cart_item[$i]["ca_co_id"];
?>
            <div class="row justify-content-center border rounded-4 p-5">
                <div class="col-6 col-lg-4">
                    <img src='<?= $cart_item[$i]["co_link"] ?>' class="card-img-top">
                </div>
                <div class="col-12 col-lg-4 mt-5">
                    <div class="card-body">
                        <h4 class="card-title"><?= $cart_item[$i]["br_name"] ?></h4>
                        <p class="card-text fs-5"><?= "SKU-" . $cart_item[$i]["co_id"] ?>
                        <br><?= "Rp " . number_format($cart_item[$i]["kc_price"], 0, "", ",") ?></p>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mt-5">
                    <p>Stok : <?= $cart_item[$i]["kc_stock"] ?></p>
                    <div class="d-flex justify-content-center">
                        <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                            <button type="button" class="btn" onclick="Kurang(this)" value='<?= $co_id ?>' style="border-right:2px gray solid; border-radius:0px;">-</button>
                            <span id='<?= $co_id ?>' class="px-3" style="font-size:16px;"><?= $cart_item[$i]["ca_qty"] ?></span>
                            <!-- <input type="hidden" name="kuantiti" value="0" id="kuantitiHidden<?=$ctr?>"> -->
                            <button type="button" class="btn" onclick="Tambah(this)" value='<?= $co_id ?>' style="border-left: 2px gray solid;border-radius:0px;">+</button>
                        </div>
                        <!-- <input class="form-control text-center w-25" type="number" value='<?= $cart_item[$i]["ca_qty"] ?>' min="1" max="<?= $cart_item[$i]["kc_stock"] ?>" step="1" name="qty"> -->
                        <button class="btn btn-danger fw-bold w-50 ms-3" type="submit" name="remove" value='<?= $cart_item[$i]["ca_us_id"] . "~" . $cart_item[$i]["ca_co_id"] ?>'>Remove</button>
                    </div>
                    <h5 class="mt-4">Total : <?= "Rp " . number_format($cart_item[$i]["ca_subtotal"], 0, "", ",") ?></h5>
                </div>
            </div>
<?php
        }
?>
        <div class="row justify-content-center border rounded-4 pb-5">
            <div class="d-none d-lg-block col-lg-8"></div>
            <div class="col-12 col-lg-4">
                <h5 class="mt-3">Qty Total : <?= number_format($qtytotal, 0, "", ",") ?></h5>
                <h5 class="mt-3">Harga Barang Total : <?= "Rp " . number_format($hargatotal, 0, "", ",") ?></h5>
                <h5 class="mt-3">Ongkir : Rp 50,000</h5>
                <h5 class="mt-3">Harga Total : <?= "Rp " . number_format($hargatotal + 50000, 0, "", ",") ?></h5>
                <button class="btn btn-success px-5 mt-3" type="submit" name="beli">Beli</button>
            </div>
        </div>
<?php
    }
?>