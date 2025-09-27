<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Tempahan Pakej Transportation</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<main>

<form action="result.php" method="post" class="order-form">

  <!-- Maklumat Pelanggan (8 info) -->
  <fieldset>
    <legend>Maklumat Pelanggan</legend>
    <label>Nama: <input type="text" name="nama" required></label>
    <label>No Telefon: <input type="text" name="telefon" maxlength="10" required></label>
    <label>Email: <input type="email" name="email" placeholder="example@gmail.com" required></label>
    <label>Alamat: <textarea name="alamat" maxlength="100" required></textarea></label>
    <label>Tarikh Sewa: <input type="date" name="tarikh" min="<?=date('Y-m-d')?>" required></label>
    <label>Masa Ambil: <input type="time" name="masa" required></label>
    <label>No IC / Passport: <input type="text" name="ic" maxlength="12" pattern="\d{12}" required></label>
    <label>Catatan Tambahan: <input type="text" name="nota" maxlength="100"></label>
  </fieldset>

  <!-- 5 Pakej Utama -->
  <h2>Pakej Utama (Pilih 1)</h2>
  <div class="package-grid">
    <?php
    $utama = [
      ["Car Rental – Sedan (RM180/day)","sedan.png",180],
      ["Car Rental – SUV (RM250/day)","suv.png",250],
      ["Van Rental – 12 Seater (RM300/day)","van.png",300],
      ["Bus Charter – 40 Seater (RM600/day)","bas.png",600],
      ["Luxury MPV Rental (RM450/day)","mpv.png",450]
    ];
    foreach($utama as $u){
      echo "<label class='package'>
              <img src='{$u[1]}' alt='{$u[0]}'>
              <input type='checkbox' name='pakej[]' value='{$u[0]}|{$u[2]}|main'>
              <span>{$u[0]}</span>
            </label>";
    }
    ?>
  </div>

  <!-- Add-On Packages (tanpa gambar) -->
  <h2>Pakej Tambahan (Pilih sekurang-kurangnya 2)</h2>
  <div class="package-grid">
    <?php
    $addon = [
      ["Driver Service (RM120/day)",120],
      ["Petrol Add-on (RM100/full tank)",100],
      ["GPS Navigation System (RM30/day)",30],
      ["Child Safety Seat (RM50/day)",50]
    ];
    foreach($addon as $a){
      echo "<label class='package'>
              <input type='checkbox' name='pakej[]' value='{$a[0]}|{$a[1]}|sub'>
              <span>{$a[0]}</span>
            </label>";
    }
    ?>
  </div>

  <button type="submit">Hantar Tempahan</button>
</form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
