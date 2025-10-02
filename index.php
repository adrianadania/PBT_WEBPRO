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
    <form action="result.php" method="post" class="full-layout order-form" novalidate>

      <!-- LEFT : BORANG PELANGGAN -->
      <section class="left">
        <fieldset>
          <legend>Customer Rental Form</legend>
          <div class="grid2">
            <label>Name
              <input type="text" name="nama" required>
            </label>
            <label>Phone Number
              <input type="text" name="telefon" pattern="\d{10}" maxlength="10" required>
              <small class="hint">(example: 0123456789)</small>
            </label>
            <label>Email
              <input type="email" name="email" required>
            </label>
            <label>Address
              <input type="text" name="alamat" required>
            </label>
            <label>City
              <input type="text" name="bandar" required>
            </label>
            <label>State
              <input type="text" name="negeri" required>
            </label>
            <label>Poscode
              <input type="text" name="poskod" pattern="\d{5}" maxlength="5" required>
            </label>

            <!-- Rental Date with dd-mm-yyyy format -->
            <label>Rental Date
              <input type="date" name="tarikh" required>
            </label>

            <label>Rental Duration (days)
              <input type="number" name="days" min="1" value="1" required>
            </label>
            <label>Notes
              <input type="text" name="nota" >
            </label>
          </div>
        </fieldset>
      </section>

      <!-- RIGHT : SENARAI PAKEJ -->
      <section class="right">
        <h2>Main Packages <span class="pill">Select 1</span></h2>
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

        <h2>Add-On Packages <span class="pill">Select at least 2</span></h2>
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
        <button type="submit" class="btn-primary">Submit Booking</button>
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
