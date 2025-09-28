<?php require_once 'function.php';
$co = companyInfo(); ?>
<!DOCTYPE html>
<html lang="ms">

<head>
  <meta charset="UTF-8">
  <title>Tempahan Pakej Transportation</title>
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

  <!-- ===== CONTENT: FORM GRID ===== -->
  <main>
    <!-- Tambahkan class full-layout & order-form pada form -->
    <form action="result.php" method="post" class="full-layout order-form" novalidate>

      <!-- LEFT : BORANG PELANGGAN -->
      <section class="left">
        <fieldset>
          <legend>Maklumat Pelanggan</legend>
          <div class="grid2">
            <label>Nama
              <input type="text" name="nama" required>
            </label>
            <label>No Telefon
              <input type="text" name="telefon" pattern="\d{10}" maxlength="10" placeholder="10 digit" required>
              <small class="hint">Mesti 10 digit (contoh: 0123456789)</small>
            </label>
            <label>Email
              <input type="email" name="email" required>
            </label>
            <label>Alamat
              <input type="text" name="alamat" required>
            </label>
            <label>Bandar
              <input type="text" name="bandar" required>
            </label>
            <label>Negeri
              <input type="text" name="negeri" required>
            </label>
            <label>Poskod
              <input type="text" name="poskod" pattern="\d{5}" maxlength="5" required>
            </label>
            <label>Tarikh Sewa
              <input type="date" name="tarikh" min="<?= date('Y-m-d') ?>" required>
            </label>
            <label>Tempoh Sewa (hari)
              <input type="number" name="days" min="1" value="1" required>
            </label>
            <label>Nota (opsyenal)
              <input type="text" name="nota" placeholder="Keperluan khas…">
            </label>
          </div>
        </fieldset>
      </section>

      <!-- RIGHT : SENARAI PAKEJ -->
      <section class="right">
        <h2>Pakej Utama <span class="pill">Pilih 1</span></h2>
        <div class="cards">
          <?php foreach (mainPackages() as $p): ?>
            <label class="card">
              <img src="<?= htmlspecialchars($p['img']) ?>" alt="">
              <div class="card-body">
                <div class="title"><?= htmlspecialchars($p['name']) ?></div>
                <div class="price"><?= money($p['price']) ?><?= $p['unit'] ?></div>
                <input type="radio" name="main" value="<?= $p['id'] ?>" required>
              </div>
            </label>
          <?php endforeach; ?>
        </div>

        <h2>Pakej Tambahan <span class="pill">Pilih sekurang-kurangnya 2</span></h2>
        <div class="cards">
          <?php foreach (addOnPackages() as $p): ?>
            <label class="card">
              <img src="<?= htmlspecialchars($p['img']) ?>" alt="">
              <div class="card-body">
                <div class="title"><?= htmlspecialchars($p['name']) ?></div>
                <div class="price"><?= money($p['price']) ?><?= $p['unit'] ?></div>
                <input type="checkbox" name="addons[]" value="<?= $p['id'] ?>">
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- BUTTONS DI BAWAH KESELURUHAN GRID -->
      <div class="button-container">
        <button type="submit" class="btn-primary">Hantar Tempahan</button>
        <button type="reset" class="btn-ghost">Reset</button>
      </div>

    </form>
  </main>

  <footer class="site-footer">
    © <?= date('Y') ?> <?= htmlspecialchars($co['name']) ?>. Semua Hak Cipta Terpelihara.
  </footer>

  <script>
    document.querySelector('.order-form').addEventListener('submit', function(e) {
      const addons = [...document.querySelectorAll('input[name="addons[]"]:checked')];
      if (addons.length < 2) {
        alert('Sila pilih sekurang-kurangnya DUA (2) pakej tambahan.');
        e.preventDefault();
      }
      const tel = document.querySelector('input[name="telefon"]');
      if (tel && !/^\d{10}$/.test(tel.value)) {
        alert('No telefon mesti 10 digit.');
        e.preventDefault();
      }
    });
  </script>
</body>

</html>