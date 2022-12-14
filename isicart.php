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
    <div class="container-fuild my-5 py-5 ">
        <img src="storage/icons/empty.png" class="mt-5" width="150px">
        <h2 class="mt-2">Keranjangmu masih kosong nih</h2>
        <p class="mt-2">Yuk, isi keranjangmu dengan kacamata favoritmu!</p>
        <a href="product.php"><button class="btn btn-success mt-2 fw-bold">Mulai Belanja</button></a>
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
            <div class="row border px-4 pt-4 pb-3 py-lg-0" style="align-items: center;">
                <div class="col-4 col-lg-3">
                    <img src='<?= $cart_item[$i]["co_link"] ?>' class="card-img-top">
                </div>
                <div class="col-8 col-lg-4">
                    <div class="card-body">
                        <h4 class="card-title"><?= $cart_item[$i]["br_name"] ?></h4>
                        <p class="card-text fs-5"><?= "SKU-" . $cart_item[$i]["co_id"] ?>
                        <br><?= "Rp " . number_format($cart_item[$i]["kc_price"], 0, "", ",") ?></p>
                    </div>
                </div>
                <div class="d-none d-lg-block col-4">
                    <div class="row justify-content-end justify-content-lg-center me-1 m-lg-0 ms-lg-5">
                        <div class="col-6"><p>Stok : <?= $cart_item[$i]["co_stock"] ?></p></div>
                    </div>
                    <div class="row justify-content-end justify-content-lg-center me-1 m-lg-0 ms-lg-5">
                        <div class="col-8">
                            <div class="d-flex justify-content-end justify-content-lg-center">
                                <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                                    <button type="button" class="btn" onclick="Kurang(this)" value='<?= $co_id ?>' style="border-right:2px gray solid; border-radius:0px;">-</button>
                                    <span id='<?= $co_id ?>' class="px-3" style="font-size:16px;"><?= $cart_item[$i]["ca_qty"] ?></span>
                                    <button type="button" class="btn" onclick="Tambah(this)" value='<?= $co_id ?>' style="border-left: 2px gray solid;border-radius:0px;">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-end text-lg-center justify-content-end">
                        <div class="col-12">
                            <p class="fw-semibold mt-4 ms-lg-5">Subtotal : <?= "Rp " . number_format($cart_item[$i]["ca_subtotal"], 0, "", ",") ?></p>
                        </div>
                    </div>
                </div>
                <div class="d-lg-none row mt-4 justify-content-end">
                    <div class="col-4">
                        <p>Stok : <?= $cart_item[$i]["co_stock"] ?></p>
                    </div>
                    <div class="col-1">
                        
                        <button class="bg-white fw-bold opacity-50" onclick="confirm_delete(this)" data-bs-toggle="modal" data-bs-target="#exampleModal" value='<?= $cart_item[$i]["ca_co_id"] ?>' style="border: none;"><img src="storage/icons/delete.png" width="25px"></button>
                    </div>
                    <div class="col-7">
                        <div class="d-flex justify-content-center">
                            <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                                <button type="button" class="btn" onclick="Kurang(this)" value='<?= $co_id ?>' style="border-right:2px gray solid; border-radius:0px;">-</button>
                                <span id='<?= $co_id ?>' class="px-3" style="font-size:16px;"><?= $cart_item[$i]["ca_qty"] ?></span>
                                <button type="button" class="btn" onclick="Tambah(this)" value='<?= $co_id ?>' style="border-left: 2px gray solid;border-radius:0px;">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <p class="fw-semibold mt-4 ms-lg-5">Subtotal : <?= "Rp " . number_format($cart_item[$i]["ca_subtotal"], 0, "", ",") ?></p>
                    </div>
                </div>
                <div class="d-none d-lg-block col-1">
                    <button class="bg-white fw-bold opacity-50" onclick="confirm_delete(this)" data-bs-toggle="modal" data-bs-target="#exampleModal" value='<?= $cart_item[$i]["ca_co_id"] ?>' style="border: none;"><img src="storage/icons/delete.png" width="25px"></button>
                </div>
            </div>
<?php
        }
?>
        <div class="row justify-content-center justify-content-lg-end pb-4">
            <div class="col-12 col-lg-5">
                <div class="row mt-3">
                    <div class="col-6 text-end">
                        Qty Total : 
                    </div>
                    <div class="col-5 col-lg-3 text-end">
                        <?= number_format($qtytotal, 0, "", ",") ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-end">
                        Harga Total Barang : 
                    </div>
                    <div class="col-5 col-lg-3 text-end">
                    <?= "Rp " . number_format($hargatotal, 0, "", ",") ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-end">
                        Ongkir : 
                    </div>
                    <div class="col-5 col-lg-3 text-end">
                        Rp 20,000
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-end">
                        <h5 class="fw-bold">Harga Total : </h5>
                    </div>
                    <div class="col-5 col-lg-3 text-end">
                        <h5 class="fw-bold"><?= "Rp " . number_format($hargatotal + 20000, 0, "", ",") ?></h5>
                    </div>
                </div>
                <form method="POST">
                    <button class="btn btn-success px-5 mt-3" type="submit" name="beli" onclick="sendEmail()">Beli</button>
                </form>
            </div>
        </div>
<?php
    }
?>