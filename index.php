<?php 
require_once 'function.php';
$co = companyInfo();

// ==========================
// FORM VALIDATION HANDLING
// ==========================
$errors = [];
$data = [
  'nama' => '', 'telefon' => '', 'email' => '', 'alamat' => '',
  'bandar' => '', 'negeri' => '', 'poskod' => '', 'tarikh' => '',
  'days' => '', 'nota' => '', 'main' => '', 'addons' => []
];

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($data as $key => $val) {
    if (isset($_POST[$key])) $data[$key] = trim($_POST[$key]);
  }
  if (isset($_POST['addons'])) $data['addons'] = $_POST['addons'];

  // Validation
  if ($data['nama'] === '') $errors[] = "Nama wajib diisi.";
  if (!preg_match('/^\d{10}$/', $data['telefon'])) $errors[] = "No telefon mesti 10 digit.";
  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak sah.";
  if ($data['alamat'] === '' || $data['bandar'] === '' || $data['negeri'] === '' || $data['poskod'] === '') $errors[] = "Alamat lengkap diperlukan.";
  if (!preg_match('/^\d{5}$/', $data['poskod'])) $errors[] = "Poskod mesti 5 digit.";
  if ($data['tarikh'] === '') $errors[] = "Sila pilih tarikh sewaan.";
  if ($data['main'] === '') $errors[] = "Pakej utama wajib dipilih.";
  if (count($data['addons']) < 2) $errors[] = "Sila pilih sekurang-kurangnya dua pakej tambahan.";

  // Jika tiada error → simpan data & redirect
  if (empty($errors)) {
    $_SESSION['form_data'] = $data;
    header("Location: result.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <title>Tempahan Pakej Transportation</title>
  <link rel="stylesheet" href="style.css">
  <script>
    window.onload = function() {
      <?php if (!empty($errors)): ?>
        alert("<?= implode('\n', array_map('addslashes', $errors)) ?>");
      <?php endif; ?>
    };
  </script>
</head>
<body>

<header class="site-header">
  <img src="<?= htmlspecialchars($co['logo']) ?>" alt="Logo" class="logo">
  <div class="header-text">
    <h1><?= htmlspecialchars($co['name']) ?></h1>
  </div>
</header>

<main>
  <form action="" method="post" class="order-form" novalidate>
    <!-- LEFT: CUSTOMER FORM -->
    <section class="left">
      <fieldset>
        <legend>Customer Rental Form</legend>
        <div class="grid2">
          <label>Name
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required style="width:200px">
          </label>
          <label>Phone Number
            <input type="text" name="telefon" maxlength="10" pattern="\d{10}" value="<?= htmlspecialchars($data['telefon']) ?>" required style="width:150px">
          </label>
          <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required style="width:220px">
          </label>
          <label>Address
            <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>" required style="width:350px">
          </label>
          <label>City
            <input type="text" name="bandar" value="<?= htmlspecialchars($data['bandar']) ?>" required style="width:150px">
          </label>
          <label>State
            <input type="text" name="negeri" value="<?= htmlspecialchars($data['negeri']) ?>" required style="width:150px">
          </label>
          <label>Poscode
            <input type="text" name="poskod" maxlength="5" value="<?= htmlspecialchars($data['poskod']) ?>" required style="width:80px">
          </label>
          <label>Rental Date
            <input type="date" name="tarikh" value="<?= htmlspecialchars($data['tarikh']) ?>" required style="width:160px">
          </label>
          <label>Rental Duration (days)
            <input type="number" name="days" min="1" value="<?= htmlspecialchars($data['days'] ?: '1') ?>" required style="width:80px">
          </label>
          <label>Notes
            <input type="text" name="nota" value="<?= htmlspecialchars($data['nota']) ?>" style="width:300px">
          </label>
        </div>
      </fieldset>
    </section>

    <!-- RIGHT: PACKAGE LIST -->
    <section class="right">
      <h2>Main Packages <span class="pill">Select 1</span></h2>
      <div class="cards">
        <?php foreach (mainPackages() as $p): ?>
          <label class="card">
            <img src="<?= htmlspecialchars($p['img']) ?>" alt="">
            <div class="card-body">
              <div class="title"><?= htmlspecialchars($p['name']) ?></div>
              <div class="price"><?= money($p['price']) ?><?= $p['unit'] ?></div>
              <input type="radio" name="main" value="<?= $p['id'] ?>" <?= ($data['main'] == $p['id']) ? 'checked' : '' ?>>
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
              <input type="checkbox" name="addons[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $data['addons']) ? 'checked' : '' ?>>
            </div>
          </label>
        <?php endforeach; ?>
      </div>
    </section>

    <div class="button-container">
      <button type="submit" class="btn-primary">Submit Booking</button>
      <button type="reset" class="btn-ghost">Reset</button>
    </div>
  </form>
</main>

<footer class="site-footer">
  © <?= date('Y') ?> <?= htmlspecialchars($co['name']) ?>. Semua Hak Cipta Terpelihara.
</footer>

</body>
</html>
