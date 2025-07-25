<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch league record
$stmt = $dbh->prepare("SELECT * FROM leagues WHERE id = ?");
$stmt->execute([$id]);
$league = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$league) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'League record not found.'];
    header("Location: league-record");
    exit;
}

// Country list
$countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',
    'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
    'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin',
    'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
    'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon',
    'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
    'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba',
    'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica',
    'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'England', 'Equatorial Guinea',
    'Eritrea', 'Estonia', 'Eswatini (fmr. Swaziland)', 'Ethiopia', 'Fiji',
    'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',
    'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
    'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran',
    'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan',
    'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos',
    'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
    'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives',
    'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco',
    'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru', 'Nepal',
    'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea',
    'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine State',
    'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland',
    'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
    'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino',
    'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
    'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands',
    'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka',
    'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan',
    'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago',
    'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine',
    'United Arab Emirates', 'United Kingdom', 'United States of America',
    'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
    'Yemen', 'Zambia', 'Zimbabwe', 'Wales', 'Scotland', 'Northern Ireland', 'Republic of Ireland'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $country = trim($_POST['country'] ?? '');

    if ($name === '' || $country === '') {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Please fill in all fields.'];
        header("Location: edit-league.php?id=$id");
        exit;
    }

    $stmt = $dbh->prepare("UPDATE leagues SET name = ?, country = ? WHERE id = ?");
    $success = $stmt->execute([$name, $country, $id]);

    if ($success) {
        $action = "Updated league (ID: $id) on: $current_date";
        log_activity($dbh, $_SESSION['user_id'], $action, 'leagues', $id, $_SERVER['REMOTE_ADDR']);
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'League updated successfully.'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update league.'];
    }

    header("Location: league-record");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit League - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenuMobile">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2>Edit League</h2>
    <form action="" method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="name" class="form-label">League Name</label>
        <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($league['name']) ?>">
      </div>

      <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <select name="country" id="country" class="form-select" required>
          <option value="">-- Select Country --</option>
          <?php foreach ($countries as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $league['country'] === $c ? 'selected' : '' ?>>
              <?= htmlspecialchars($c) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update League</button>
      </div>
    </form>
  </div>
</main>

<footer class="footer">
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
