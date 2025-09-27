<?php
require 'function.php';

// ===== Ambil Data =====
$nama    = $_POST['nama'] ?? '';
$telefon = $_POST['telefon'] ?? '';
$email   = $_POST['email'] ?? '';
$alamat  = $_POST['alamat'] ?? '';
$tarikh  = $_POST['tarikh'] ?? '';
$masa    = $_POST['masa'] ?? '';
$ic      = $_POST['ic'] ?? '';
$nota    = $_POST['nota'] ?? '';
$pakej   = $_POST['pakej'] ?? [];

// ===== Validation =====
$main = $sub = 0;
foreach ($pakej as $p) {
    list(,, $type) = explode('|', $p);
    if ($type === 'main') $main++;
    if ($type === 'sub')  $sub++;
}
if (!($main === 1 && $sub >= 2)) {
    die("<p style='color:red;text-align:center;'>Peraturan: 1 Pakej Utama + sekurang-kurangnya 2 Add-On.<br>
         <a href='index.php'>Kembali</a></p>");
}

$total   = kiraJumlah($pakej);
$resitNo = 'TRS' . time();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Resit Tempahan</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main class="receipt">
  <h2>Resit Tempahan</h2>
  <p><strong>No Resit:</strong> <?= $resitNo ?></p>
  <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
  <p><strong>No Telefon:</strong> <?= htmlspecialchars($telefon) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
  <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat) ?></p>
  <p><strong>Tarikh & Masa:</strong> <?= htmlspecialchars($tarikh) ?> @ <?= htmlspecialchars($masa) ?></p>
  <p><strong>IC / Passport:</strong> <?= htmlspecialchars($ic) ?></p>
  <?php if ($nota): ?>
    <p><strong>Catatan:</strong> <?= htmlspecialchars($nota) ?></p>
  <?php endif; ?>

  <h3>Pakej Dipilih:</h3>
  <ul>
    <?php
    foreach ($pakej as $p) {
        list($n, $h) = explode('|', $p);
        echo "<li>$n – RM$h</li>";
    }
    ?>
  </ul>

  <h3>Jumlah Bayaran: RM<?= number_format($total, 2) ?></h3>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
