<?php
require_once 'function.php';
$co = companyInfo();
$bill = buildBill($_POST);
?>
<!DOCTYPE html>
<html lang="ms">

<head>
  <meta charset="UTF-8">
  <title>Booking Receipt</title>
  <link rel="stylesheet" href="style.css">
  <script>
    window.onload = function() {
      <?php if (!$bill['ok']): ?>
        alert("<?= implode('\n', array_map('addslashes', $bill['errors'])) ?>");
      <?php endif; ?>
    };
  </script>
</head>

<body>
  <!-- ===== HEADER ===== -->
  <header class="site-header">
    <img src="<?= htmlspecialchars($co['logo']) ?>" alt="Logo" class="logo">
    <div class="header-text">
      <h1><?= htmlspecialchars($co['name']) ?></h1>
      <p><?= htmlspecialchars($co['addr']) ?> | 📞 <?= htmlspecialchars($co['phone']) ?> | ✉️ <?= htmlspecialchars($co['email']) ?></p>
    </div>
  </header>

  <main class="container" style="width:100%;padding:20px;">
    <?php if (!$bill['ok']): ?>
      <div class="alert error">
        <h3>❌ Ralat Dalam Tempahan</h3>
        <ul>
          <?php foreach ($bill['errors'] as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
        <a class="btn-primary" href="index.php">← Kembali ke Borang Tempahan</a>
      </div>

    <?php else: ?>
      <?php
      $c = $bill['cust'];
      $calc = $bill['calc'];
      ?>
      <section class="receipt" style="width:100%;">
        <div class="receipt-head">
          <h2>Booking Receipt</h2>
          <p>Receipt No: <strong><?= $bill['receipt'] ?></strong></p>
          <p>Print Date: <?= date('d/m/Y H:i') ?></p>
        </div>

        <h3>Customer Information</h3>
        <div class="grid2 small">
          <div><strong>Name:</strong> <?= htmlspecialchars($c['nama']) ?></div>
          <div><strong>Phone:</strong> <?= htmlspecialchars($c['telefon']) ?></div>
          <div><strong>Email:</strong> <?= htmlspecialchars($c['email']) ?></div>
          <div style="grid-column:1/-1;"><strong>Address:</strong> <?= htmlspecialchars($c['alamat']) ?>, <?= htmlspecialchars($c['bandar']) ?>, <?= htmlspecialchars($c['negeri']) ?> <?= htmlspecialchars($c['poskod']) ?></div>
          <div><strong>Rental Date:</strong> <?= htmlspecialchars($c['tarikh']) ?></div>
          <div><strong>Duration:</strong> <?= htmlspecialchars($c['days']) ?> days</div>
          <?php if ($c['nota']): ?>
            <div style="grid-column:1/-1;"><strong>Notes:</strong> <?= htmlspecialchars($c['nota']) ?></div>
          <?php endif; ?>
        </div>

        <h3>Package Summary</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Item</th>
              <th>Unit Price</th>
              <th>Quantity</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= htmlspecialchars($bill['main']['name']) ?> (Main)</td>
              <td><?= money($bill['main']['price']) ?><?= $bill['main']['unit'] ?></td>
              <td><?= $c['days'] ?> days</td>
              <td><?= money($calc['mainCost']) ?></td>
            </tr>
            <?php foreach ($bill['addons'] as $a): ?>
              <tr>
                <td><?= htmlspecialchars($a['name']) ?> (Add-on)</td>
                <td><?= money($a['price']) ?><?= htmlspecialchars($a['unit']) ?></td>
                <td><?= ($a['id'] === 'petrol') ? '1' : $c['days'] . ' days' ?></td>
                <td>
                  <?= money(($a['id'] === 'petrol') ? $a['price'] : $a['price'] * $c['days']) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr><th colspan="3" class="right">Subtotal</th><th><?= money($calc['subtotal']) ?></th></tr>
            <tr><th colspan="3" class="right">Tax (6%)</th><th><?= money($calc['tax']) ?></th></tr>
            <tr><th colspan="3" class="right">Total</th><th><?= money($calc['total']) ?></th></tr>
          </tfoot>
        </table>

        <div class="actions" style="margin-top:20px;">
          <button onclick="window.print()" class="btn-ghost">🖨 Print Receipt</button>
          <a href="index.php" class="btn-primary">← New Booking</a>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <footer class="site-footer">
    © <?= date('Y') ?> <?= htmlspecialchars($co['name']) ?>. Semua Hak Cipta Terpelihara.
  </footer>
</body>

</html>
