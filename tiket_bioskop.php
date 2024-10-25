<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pemesanan Tiket Bioskop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            padding: 10px;
            border-bottom: 3px solid black;
            display: inline-block;
        }
        .container {
            background-color: #8DCCCF;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .ticket-input {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .input-group {
            flex: 1;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #FBC037;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #F0CD6A;
        }
        .result {
            background-color: #DEECEF;
            padding: 20px;
            border-radius: 20px;
            margin-top: 20px;
        }
        .total-section {
            border-top: 2px solid ;
            margin-top: 10px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistem Pemesanan Tiket Bioskop</h1>
    </div>

    <div class="container">
        <form method="post" action="">
            <div class="ticket-input">
                <div class="input-group">
                    <label for="jumlah_dewasa">Tiket Dewasa (Rp50.000):</label>
                    <input type="number" name="jumlah_dewasa" id="jumlah_dewasa" min="0" value="0" required>
                </div>
                <div class="input-group">
                    <label for="jumlah_anak">Tiket Anak-anak (Rp30.000):</label>
                    <input type="number" name="jumlah_anak" id="jumlah_anak" min="0" value="0" required>
                </div>
            </div>
            
            <div class="ticket-input">
                <div class="input-group">
                    <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
                    <input type="date" name="tanggal_pemesanan" id="tanggal_pemesanan" required>
                </div>
            </div>
            
            <input type="submit" value="Hitung Total Harga">
            <h3>NOTE :</h3>
            <p><i>*Total harga lebih dari Rp.150.000 mendapatkan diskon 10%</p>
            <p>*Weekend dikenai biaya tambahan Rp.10.000 pertiket</i></p>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jumlah_dewasa = intval($_POST["jumlah_dewasa"]);
        $jumlah_anak = intval($_POST["jumlah_anak"]);
        $tanggal_pemesanan = $_POST["tanggal_pemesanan"];
        
        // Validasi minimal satu tiket
        if ($jumlah_dewasa + $jumlah_anak == 0) {
            echo "<div class='result'><p style='color: red;'>Mohon pesan minimal satu tiket!</p></div>";
            exit;
        }

        // Cek hari weekend atau weekday
        $hari = date('N', strtotime($tanggal_pemesanan)); // 1 (Senin) sampai 7 (Minggu)
        $is_weekend = ($hari >= 6); // Sabtu dan Minggu

        // Hitung harga tiket dewasa
        $harga_dewasa = $jumlah_dewasa * 50000;
        $harga_anak = $jumlah_anak * 30000;
        
        // Tambahan biaya weekend
        $tambahan_weekend = 0;
        if ($is_weekend) {
            $tambahan_weekend = ($jumlah_dewasa + $jumlah_anak) * 10000;
        }

        $total_harga = $harga_dewasa + $harga_anak + $tambahan_weekend;
        
        // Hitung diskon
        $diskon = 0;
        if ($total_harga > 150000) {
            $diskon = $total_harga * 0.1;
        }

        $total_akhir = $total_harga - $diskon;

        // Format tanggal ke Indonesia
        setlocale(LC_TIME, 'id_ID');
        $nama_hari = strftime('%A', strtotime($tanggal_pemesanan));
        $tanggal_format = date('d/m/Y', strtotime($tanggal_pemesanan));

        // Tampilkan hasil
        echo "<div class='result'>";
        echo "<h2>Rincian Pemesanan</h2>";
        echo "<p>Tanggal Pemesanan: $nama_hari, $tanggal_format</p>";
        
        echo "<h3>Detail Tiket:</h3>";
        if ($jumlah_dewasa > 0) {
            echo "<p>Tiket Dewasa: $jumlah_dewasa x Rp" . number_format(50000, 0, ',', '.') . 
                 " = Rp" . number_format($harga_dewasa, 0, ',', '.') . "</p>";
        }
        if ($jumlah_anak > 0) {
            echo "<p>Tiket Anak-anak: $jumlah_anak x Rp" . number_format(30000, 0, ',', '.') . 
                 " = Rp" . number_format($harga_anak, 0, ',', '.') . "</p>";
        }
        
        if ($is_weekend) {
            echo "<p>Tambahan Weekend (Rp.10.000 / Tiket): Rp" . number_format($tambahan_weekend, 0, ',', '.') . "</p>";
        }

        echo "<div class='total-section'>";
        echo "<p>Total Harga: Rp" . number_format($total_harga, 0, ',', '.') . "</p>";
        
        if ($diskon > 0) {
            echo "<p>Diskon (10%): Rp" . number_format($diskon, 0, ',', '.') . "</p>";
        }
        
        echo "<h3>Total Pembayaran: Rp" . number_format($total_akhir, 0, ',', '.') . "</h3>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</body>
</html>

