<?php
    require_once("connection.php");

    include('PHP Mail/OAuthTokenProvider.php');
	include('PHP Mail/Exception.php');
	include('PHP Mail/PHPMailer.php');
	include('PHP Mail/OAuth.php');
	include('PHP Mail/SMTP.php');
	include('PHP Mail/POP3.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_SESSION["email"])) {
        unset($_SESSION["email"]);
        $cart_item = [];
        $transaksi = [];
    
        $ht_id = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(ht_id) FROM htrans"))[0];
        $query = mysqli_query($conn, "SELECT * FROM htrans JOIN dtrans ON dt_ht_id = ht_id JOIN users ON ht_us_id = us_id JOIN color ON dt_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ht_id = '$ht_id'");
        while ($row = mysqli_fetch_array($query)) {
            $transaksi[] = $row;
        }

        if (count($transaksi) != 0) {
            // Setup Mail
            $mail = new PHPMailer();
            $mail -> isSMTP();
            $mail -> Host = "smtp.gmail.com";
            $mail -> Port = 587;
        
            // Setup SMTP
            $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail -> SMTPSecure = "tls";
            $mail -> SMTPAuth = true;
            $mail -> Username = "ivan.s21@mhs.istts.ac.id";
            $mail -> Password = "lmiwhsgbkbewhgqg";
        
            // Set Recipient
            $mail -> setFrom("optikprimadona@official.co.id", "Optik Primadona");
            $mail -> addAddress($transaksi[0]["us_email"], $transaksi[0]["us_name"]);
            $mail -> Subject = "Email Coba";
            if ($transaksi[0]["ht_status"] == 1) { 
                $mail -> Subject = "Lampiran Invoice";
            } elseif ($transaksi[0]["ht_status"] == 2) {
                $mail -> Subject = "Menunggu Pembayaran";
            } else {
                $mail -> Subject = "Pesanan Dibatalkan";
            }
            
        
            $body = '<div style="margin: 5px; padding: 50px; border: 1px solid gray; border-radius: 20px;">
            <h5 style="font-size: 16px; margin: 0px;">';
            if ($transaksi[0]["ht_status"] == 1) {
                $body .= '<b style="color: green;">Selesai</b>';
            } elseif ($transaksi[0]["ht_status"] == 2) {
                $body .= '<b style="color: orange;">Menunggu Pembayaran</b>';
            } else {
                $body .= '<b style="color: red;">Dibatalkan</b>';
            }
            $body .= '</h5>
                <hr>
                <div style="width: 100%; display: flex; flex-wrap: wrap;">
                    ';
            if ($transaksi[0]["ht_status"] == 1) {
                $body .= '
                <div style="width: 50%;">
                <p>No. Invoice</p>
            </div>
            <div style="width: 50%; text-align: right;">
                <p style="font-weight: bold; color: green;">' . $transaksi[0]["ht_invoice"] . '</p>
                    </div>
                ';
            } else {
                $body .= '
                <div style="width: 50%;">
                <p>Order ID</p>
            </div>
            <div style="width: 50%; text-align: right;">
                <p style="font-weight: bold; color: green;">' . strrev(str_replace("OP", "", $transaksi[0]["ht_invoice"])) . '</p>
                    </div>
                ';
            }
            $body .= '
            </div>
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%;">
            <p>Tanggal Pembelian</p>
        </div>
        <div style="width: 50%; text-align: right;">
            <p>' . date_format(date_create($transaksi[0]["ht_date"]),"d F Y, h:i A") . '</p>
                </div>
                </div>
            <hr>
            <h5>Detail Produk</h5>
            ';
            $total_qty = 0;
            for ($i = 0; $i < count($transaksi); $i++) {
                $total_qty += $transaksi[$i]["dt_qty"];
                $body .= '
                <div style="width: 100%; display: flex; flex-wrap: wrap; border: 1px solid gray; align-items: center; border-radius: 5px;">
                    <div style="width: 15%;">
                        <img src="https://optikprimadona.my.id/' . $transaksi[$i]["co_link"] . '" style="width: 100%;">
                    </div>
                    <div style="width: 30%; text-align: left; margin-left: 30px;">
                        <h4 style="margin: 0px;">' . $transaksi[$i]["br_name"] . '</h5>
                        <p style="margin: 0px;"> SKU-' . $transaksi[$i]["co_id"] . '</p>
                        <p style="margin: 0px; font-size: 12px;">' . $transaksi[$i]["dt_qty"] . ' barang x Rp ' . number_format($transaksi[$i]["kc_price"], 0, "", ",") . '</p>
                    </div>
                    <div style="width: 50%;">
                        <p>Total Harga</p>
                        <h5>Rp ' . number_format($transaksi[$i]["dt_subtotal"], 0, "", ",") . '</h5>
                    </div>
                </div>
            ';
            }
            $body .= '
            <hr>
            <h5>Rincian Pembayaran</h5>
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%;">
                    <p>Total Harga (' . $total_qty . ' barang)</p>
                </div>
                <div style="width: 50%; text-align: right;">
                    <p>Rp ' . number_format($transaksi[0]["ht_total"], 0, "", ",") . '</p>
                </div>
            </div>
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%;">
                    <p>Total Ongkos Kirim</p>
                </div>
                <div style="width: 50%; text-align: right;">
                    <p>Rp 20,000</p>
                </div>
            </div>
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 100%;">
                    <hr>
                </div>
            </div>
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%;">
                    <h3>Total Belanja</h3>
                </div>
                <div style="width: 50%; text-align: right;">
                    <h3>Rp ' . number_format($transaksi[0]["ht_total"] + 20000, 0, "", ",") . '</h3>
                </div>
            </div>
            </div>';
            $mail -> Body = $body;
            $mail -> IsHTML(true);
            $mail-> WordWrap = 50;
            $mail -> Send();
        }
    }
?>