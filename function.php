<?php
/* ---------- COMPANY INFO ---------- */
function companyInfo(): array
{
    return [
        'logo'   => 'logo.jpg',
        'name'   => 'DOUBLE A TRANSPORTS RENTAL SERVICES',
        'addr'   => 'Lot 42, Jalan Harmoni, Kajang',
        'phone'  => '03-98765432',
        'email'  => 'doubleatransports@gmail.com',
    ];
}

/* ---------- DATA: PACKAGES ---------- */
function mainPackages(): array
{
    return [
        ['id' => 'sedan', 'name' => 'Car Rental – Sedan',        'price' => 180, 'unit' => '/day', 'img' => 'sedan.png'],
        ['id' => 'suv',   'name' => 'Car Rental – SUV',          'price' => 250, 'unit' => '/day', 'img' => 'suv.png'],
        ['id' => 'van12', 'name' => 'Van Rental – 12 Seater',    'price' => 300, 'unit' => '/day', 'img' => 'van.png'],
        ['id' => 'bus40', 'name' => 'Bus Charter – 40 Seater',   'price' => 600, 'unit' => '/day', 'img' => 'bas.png'],
        ['id' => 'mpv',   'name' => 'Luxury MPV Rental',         'price' => 450, 'unit' => '/day', 'img' => 'mpv.png'],
    ];
}
function addOnPackages(): array
{
    return [
        ['id' => 'driver', 'name' => 'Driver Service',         'price' => 120, 'unit' => '/day', 'img' => 'driver.jpg'],
        ['id' => 'petrol', 'name' => 'Petrol Add-on',          'price' => 100, 'unit' => '/full tank', 'img' => 'petrol.jpg'],
        ['id' => 'gps',    'name' => 'GPS Navigation System',  'price' => 30,  'unit' => '/day', 'img' => 'gps.jpg'],
        ['id' => 'seat',   'name' => 'Child Safety Seat',      'price' => 50,  'unit' => '/day', 'img' => 'seat.jpg'],
    ];
}

/* ---------- HELPERS ---------- */
function money($n)
{
    return 'RM' . number_format((float)$n, 2);
}
function findById(array $list, string $id)
{
    foreach ($list as $x) {
        if ($x['id'] === $id) return $x;
    }
    return null;
}
function receiptNo(): string
{
    return 'T' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
}

/* ---------- VALIDATION + BILL ---------- */
function buildBill(array $post): array
{
    $errors = [];

    // 8+ personal details (adjust labels in index.php to match these names)
    $required = ['nama', 'telefon', 'email', 'alamat', 'bandar', 'negeri', 'poskod', 'tarikh'];
    foreach ($required as $k) {
        if (empty(trim((string)($post[$k] ?? '')))) $errors[] = "Medan '$k' diperlukan.";
    }

    // exactly 1 main
    $mainId = $post['main'] ?? '';
    if (!$mainId) $errors[] = "Sila pilih SATU (1) pakej utama.";
    // at least 2 add-ons
    $addons = $post['addons'] ?? [];
    if (!is_array($addons)) $addons = [];
    if (count($addons) < 2) $errors[] = "Sila pilih sekurang-kurangnya DUA (2) pakej tambahan.";

    // days (≥1)
    $days = max(1, (int)($post['days'] ?? 1));

    if ($errors) return ['ok' => false, 'errors' => $errors];

    $main = findById(mainPackages(), $mainId);
    if (!$main) $errors[] = "Pakej utama tidak sah.";
    $addonItems = [];
    foreach ($addons as $aid) {
        $itm = findById(addOnPackages(), $aid);
        if ($itm) $addonItems[] = $itm;
    }
    if (count($addonItems) < 2) $errors[] = "Pakej tambahan tidak sah.";

    if ($errors) return ['ok' => false, 'errors' => $errors];

    // price calc
    $mainCost  = $main['price'] * $days; // all main are per day
    $addonCost = 0;
    foreach ($addonItems as $a) {
        // petrol is one-off (not per day); others per day
        if ($a['id'] === 'petrol') $addonCost += $a['price'];
        else $addonCost += $a['price'] * $days;
    }
    $subtotal = $mainCost + $addonCost;
    $tax = round($subtotal * 0.06, 2);
    $total = $subtotal + $tax;

    return [
        'ok' => true,
        'cust' => [
            'nama' => trim($post['nama']),
            'telefon' => trim($post['telefon']),
            'email' => trim($post['email']),
            'alamat' => trim($post['alamat']),
            'bandar' => trim($post['bandar']),
            'negeri' => trim($post['negeri']),
            'poskod' => trim($post['poskod']),
            'tarikh' => trim($post['tarikh']),
            'days' => $days,
            'nota' => trim($post['nota'] ?? ''),
        ],
        'main' => $main,
        'addons' => $addonItems,
        'calc' => compact('mainCost', 'addonCost', 'subtotal', 'tax', 'total'),
        'receipt' => receiptNo(),
    ];
}
