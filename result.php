<?php
require_once 'function.php';
$co = companyInfo();
$bill = buildBill($_POST);
?>
<!DOCTYPE html>
<html lang="ms">

<head>
  <meta charset="UTF-8">
  <title>Resit Tempahan</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- ===== HEADER ===== -->
  <header class="site-header">
    <img src="<?= htmlspecialchars($co['logo']) ?>" alt="Logo" class="logo">
    <div class="header-text">
      <h1><?= htmlspecialchars($co['name']) ?></h1>
    </div>
  </header>

  <main class="container">
    <?php if (!$bill['ok']): ?>
      <div class="alert error">
        <h3>Ralat Pengesahan</h3>
        <ul><?php foreach ($bill['errors'] as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
        <a class="btn-primary" href="index.php">← Kembali</a>
      </div>
    <?php else: ?>
      <?php $c = $bill['cust'];
      $calc = $bill['calc']; ?>
      <section class="receipt">
        <div class="receipt-head">
          <div>
            <h2>Resit Tempahan</h2>
            <div>No Resit: <strong><?= $bill['receipt'] ?></strong></div>
            <div>Tarikh Cetak: <?= date('d/m/Y H:i') ?></div>
          </div>
        </div>

        <h3>Maklumat Pelanggan</h3>
        <div class="grid2 small">
          <div><strong>Nama:</strong> <?= htmlspecialchars($c['nama']) ?></div>
          <div><strong>Telefon:</strong> <?= htmlspecialchars($c['telefon']) ?></div>
          <div><strong>Email:</strong> <?= htmlspecialchars($c['email']) ?></div>
          <div><strong>Alamat:</strong> <?= htmlspecialchars($c['alamat']) ?>, <?= htmlspecialchars($c['bandar']) ?>, <?= htmlspecialchars($c['negeri']) ?> <?= htmlspecialchars($c['poskod']) ?></div>
          <div><strong>Tarikh Sewa:</strong> <?= htmlspecialchars($c['tarikh']) ?></div>
          <div><strong>Tempoh:</strong> <?= $c['days'] ?> hari</div>
          <?php if ($c['nota']) { ?><div style="grid-column:1/-1;"><strong>Nota:</strong> <?= htmlspecialchars($c['nota']) ?></div><?php } ?>
        </div>

        <h3>Ringkasan Pakej</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Item</th>
              <th>Harga Unit</th>
              <th>Kuantiti</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= $bill['main']['name'] ?> (utama)</td>
              <td><?= money($bill['main']['price']) ?>/day</td>
              <td><?= $c['days'] ?> hari</td>
              <td><?= money($calc['mainCost']) ?></td>
            </tr>
            <?php foreach ($bill['addons'] as $a): ?>
              <tr>
                <td><?= $a['name'] ?> (add-on)</td>
                <td><?= money($a['price']) ?><?= $a['unit'] ?></td>
                <td>
                  <?php if ($a['id'] === 'petrol') {
                    echo '1';
                  } else {
                    echo $c['days'] . ' hari';
                  } ?>
                </td>
                <td>
                  <?php
                  $line = ($a['id'] === 'petrol') ? $a['price'] : $a['price'] * $c['days'];
                  echo money($line);
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="right">Subjumlah</th>
              <th><?= money($calc['subtotal']) ?></th>
            </tr>
            <tr>
              <th colspan="3" class="right">Cukai (6%)</th>
              <th><?= money($calc['tax']) ?></th>
            </tr>
            <tr>
              <th colspan="3" class="right">Jumlah Perlu Dibayar</th>
              <th><?= money($calc['total']) ?></th>
            </tr>
          </tfoot>
        </table>

        <div class="actions">
          <button onclick="window.print()" class="btn-ghost">Cetak Resit</button>
          <a href="index.php" class="btn-primary">Kembali</a>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <footer class="site-footer">
    © <?= date('Y') ?> <?= htmlspecialchars($co['name']) ?>. Semua Hak Cipta Terpelihara.
  </footer>
</body>

</html>