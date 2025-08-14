<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}



$countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
    'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
    'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
    'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica',
    'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador','England', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini (fmr. Swaziland)', 'Ethiopia', 'Fiji',
    'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
    'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan',
    'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
    'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru', 'Nepal',
    'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine State',
    'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
    'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
    'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka',
    'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago',
    'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
    'Yemen', 'Zambia', 'Zimbabwe','Wales','Scotland', 'Northern Ireland', 'Republic of Ireland'
];

$bookmakers = [
    '1x bet' => 'images/1xbet-logo.png',
    '22bet' => 'images/22xbet-logo.jfif',
    'bet9ja' => 'images/bet9ja-logo.png',
    'betking' => 'images/betking-logo.jfif',
    'football.com' => 'images/football.com-logo.jfif',
    'paripesa' => 'images/paripesa-logo.jfif',
    'sportybet' => 'images/sportybet-logo.jfif'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_booking'])) {
        $code = trim($_POST['code'] ?? '');
        $bookmaker = trim($_POST['bookmaker'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $no_matches = (int)($_POST['no_matches'] ?? 0);
        $type = trim($_POST['type'] ?? '');
        $match_start_date = $_POST['match_start_date'] ?? '';
        $match_end_date = $_POST['match_end_date'] ?? '';

        if ($code === '' || $bookmaker === '' || $country === '' || $no_matches < 1 || $type === '' || $match_start_date === '' || $match_end_date === '') {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required.'];
        } else {
            $stmt = $dbh->prepare("INSERT INTO bookings (code, bookmaker, country, no_matches, type, match_start_date, match_end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$code, $bookmaker, $country, $no_matches, $type, $match_start_date, $match_end_date]);
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Booking code added successfully.'];
        }
    }

    if (isset($_POST['edit_booking'])) {
        $id = (int)$_POST['booking_id'];
        $code = trim($_POST['code']);
        $bookmaker = trim($_POST['bookmaker']);
        $country = trim($_POST['country']);
        $no_matches = (int)$_POST['no_matches'];
        $type = trim($_POST['type']);
        $match_start_date = $_POST['match_start_date'];
        $match_end_date = $_POST['match_end_date'];

        $stmt = $dbh->prepare("UPDATE bookings SET code=?, bookmaker=?, country=?, no_matches=?, type=?, match_start_date=?, match_end_date=? WHERE id=?");
        $stmt->execute([$code, $bookmaker, $country, $no_matches, $type, $match_start_date, $match_end_date, $id]);
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Booking updated.'];
    }

    if (isset($_POST['delete_booking'])) {
        $id = (int)$_POST['booking_id'];
        $dbh->prepare("DELETE FROM bookings WHERE id=?")->execute([$id]);
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Booking deleted.'];
    }
    header("Location: add-booking");
    exit;
}

$bookings = $dbh->query("SELECT * FROM bookings ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Booking Codes - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
  <style>
    .bookmaker-option {
      display: flex;
      align-items: center;
    }
    .bookmaker-option img {
      width: 25px;
      height: 25px;
      object-fit: contain;
      margin-right: 8px;
    }
  </style>
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
    <div class="row">
      <div class="col-md-5">
        <h2 class="mb-4">Add Booking Code</h2>
        <div class="card shadow-sm p-4">
          <form method="POST">
            <input type="hidden" name="add_booking" value="1">

            <div class="mb-3">
              <label class="form-label">Booking Code</label>
              <input type="text" name="code" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Bookmaker</label>
              <select name="bookmaker" class="form-select" required>
                <option value="">-- Select Bookmaker --</option>
                <?php foreach ($bookmakers as $name => $logo): ?>
                  <option value="<?= htmlspecialchars($name) ?>">
                    <?= htmlspecialchars($name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
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

            <div class="mb-3">
              <label class="form-label">Type</label>
              <select name="type" class="form-select" required>
                <option value="free">Free</option>
                <option value="vip">VIP</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">No. of Matches</label>
              <input type="number" name="no_matches" class="form-control" min="1" required>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Match Start Date</label>
              <input type="date" name="match_start_date" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Match End Date</label>
              <input type="date" name="match_end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Booking</button>
          </form>
        </div>
      </div>

      <div class="col-md-7">
        <h2 class="mb-4">Booking Records</h2>
        <div class="card shadow-sm p-4">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Bookmaker</th>
                  <th>Country</th>
                  <th>Matches</th>
                  <th>Type</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($bookings as $booking): ?>
                  <tr>
                    <td><?= htmlspecialchars($booking['code']) ?></td>
                    <td>
                      <img src="../<?= $bookmakers[$booking['bookmaker']] ?? 'default.png' ?>" alt="logo" width="25">
                      <?= htmlspecialchars($booking['bookmaker']) ?>
                    </td>
                    <td><?= htmlspecialchars($booking['country']) ?></td>
                    <td><?= $booking['no_matches'] ?></td>
                    <td><?= htmlspecialchars($booking['type']) ?></td>
                    <td><?= htmlspecialchars($booking['match_start_date']) ?></td>
                    <td><?= htmlspecialchars($booking['match_end_date']) ?></td>
                    <td class="text-nowrap">
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <button type="button" class="btn btn-sm btn-link p-0 me-2 text-warning"
                                onclick='editBooking(<?= json_encode($booking) ?>)'
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Booking">
                          <i class="bi bi-pencil-square fs-5"></i>
                        </button>
                      </form>
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <button type="submit" name="delete_booking"
                                class="btn btn-sm btn-link p-0 text-danger"
                                onclick="return confirm('Delete this booking?')"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Booking">
                          <i class="bi bi-trash fs-5"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
 <form method="POST" class="modal-content">
      <input type="hidden" name="edit_booking" value="1">
      <input type="hidden" name="booking_id" id="edit_booking_id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Booking Code</label>
          <input type="text" name="code" id="edit_code" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Bookmaker</label>
          <select name="bookmaker" id="edit_bookmaker" class="form-select" required>
            <?php foreach ($bookmakers as $name => $logo): ?>
              <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Country</label>
          <select name="country" id="edit_country" class="form-select" required>
            <?php foreach ($countries as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">No. of Matches</label>
          <input type="number" name="no_matches" id="edit_no_matches" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" id="edit_type" class="form-select" required>
            <option value="free">Free</option>
            <option value="vip">VIP</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Match Start Date</label>
          <input type="datetime-local" name="match_start_date" id="edit_match_start_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Match End Date</label>
          <input type="datetime-local" name="match_end_date" id="edit_match_end_date" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<p>&nbsp;</p>
<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function editBooking(data) {
    document.getElementById('edit_booking_id').value = data.id;
    document.getElementById('edit_code').value = data.code;
    document.getElementById('edit_bookmaker').value = data.bookmaker;
    document.getElementById('edit_country').value = data.country;
    document.getElementById('edit_no_matches').value = data.no_matches;
    document.getElementById('edit_type').value = data.type;
    document.getElementById('edit_match_start_date').value = data.match_start_date.replace(' ', 'T');
    document.getElementById('edit_match_end_date').value = data.match_end_date.replace(' ', 'T');
    new bootstrap.Modal(document.getElementById('editModal')).show();
  }
</script>
</body>
</html>
