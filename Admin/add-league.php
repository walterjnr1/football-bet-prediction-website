<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',    'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
    'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin',    'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
    'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon',    'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
    'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba',    'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica',
    'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador','England', 'Equatorial Guinea',    'Eritrea', 'Estonia', 'Eswatini (fmr. Swaziland)', 'Ethiopia', 'Fiji',
    'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',    'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
    'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran',    'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan',
    'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos',    'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
    'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives',    'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco',    'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru', 'Nepal',
    'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea',    'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine State',
    'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland',    'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
    'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino',    'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
    'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands',    'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka',
    'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan',    'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago',
    'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine',    'United Arab Emirates', 'United States of America',    'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
    'Yemen', 'Zambia', 'Zimbabwe','Wales','Scotland', 'Northern Ireland', 'Republic of Ireland'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_league'])) {
    $name = trim($_POST['name'] ?? '');
    $country = trim($_POST['country'] ?? '');


    if ($name === '' || $country === '') {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'League name and country are required.'];
        header("Location: add-league");
        exit;
    }

    // Check if league already exists
    $stmt = $dbh->prepare("SELECT * FROM leagues WHERE name = ? AND country = ? ");
    $stmt->execute([$name, $country]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'This league already exists.'];
        header("Location: add-league");
        exit;
    }

    // Insert new league
    $stmt = $dbh->prepare("INSERT INTO leagues (name, country) VALUES (?, ?)");
    $stmt->execute([$name, $country]);
    $league_id = $dbh->lastInsertId();

    $current_date = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $action = "Added new league: $name ($country) on $current_date";
    log_activity($dbh, $_SESSION['user_id'], $action, 'leagues', $league_id, $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'League added successfully.'];
    header("Location: add-league");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add League - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
  <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Add New League</h2>
    <div class="card shadow-sm p-4">
      <form id="leagueForm" method="POST">
        <input type="hidden" name="add_league" value="1">

        <div class="mb-3">
          <label class="form-label">League Name</label>
          <input type="text" name="name" class="form-control" placeholder="e.g., Premier League" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Country</label>
          <select name="country" class="form-select" required>
            <option value="">-- Select Country --</option>
            <?php foreach ($countries as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="button" class="btn btn-primary mt-3" id="confirmAddLeague">Add League</button>
      </form>
    </div>
  </div>
</main>

</div>
</div>
</div>

<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>

<script>
document.getElementById('confirmAddLeague').addEventListener('click', function () {
    document.getElementById('leagueForm').submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
