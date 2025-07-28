<?php 
include('config.php');

$itemsPerPage = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Get total number of unique dates
$countQuery = "SELECT COUNT(DISTINCT DATE(created_at)) AS total FROM ticket_proofs";
$stmtCount = $dbh->prepare($countQuery);
$stmtCount->execute();
$totalDates = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalDates / $itemsPerPage);

// Fetch paginated dates
$proofDatesQuery = "
    SELECT DISTINCT DATE(created_at) AS proof_date 
    FROM ticket_proofs 
    ORDER BY proof_date DESC 
    LIMIT :limit OFFSET :offset";
$stmtDates = $dbh->prepare($proofDatesQuery);
$stmtDates->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmtDates->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtDates->execute();
$proofDates = $stmtDates->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ticket Proof - <?php echo $app_name; ?></title>
    <?php include('partials/head.php'); ?>
  
</head>
<body>
<body>
<div class="site-wrap">

  <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close">
        <span class="icon-close2 js-menu-toggle"></span>
      </div>
    </div>
    <div class="site-mobile-menu-body"></div>
  </div>

  <header class="site-navbar py-4" role="banner">
    <div class="container">
      <div class="d-flex align-items-center">
        <div class="site-logo">
          <a href="index">
            <img src="<?php echo $app_logo; ?>" alt="Logo">
          </a>
        </div>
        <div class="ml-auto">
          <nav class="site-navigation position-relative text-right" role="navigation">
            <?php include('partials/navbar.php'); ?>
          </nav>
          <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white"><span class="icon-menu h3 text-white"></span></a>
        </div>
      </div>
    </div>
  </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mx-auto text-center">
                    <h1 class="text-white">WINNING TICKETS</h1>
                    <p>At <?php echo $app_name; ?> we bring you real-time match updates, team news, transfer rumors, and expert insights. Whether you’re checking scores, reading match previews, or analyzing player performance—we’ve got everything a true football fan needs.</p>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>

    <!-- VIP Proof Section -->
    <section class="reviews-section">
        <h2 class="text-center">Winning Tickets of Our VIP Members</h2>
        <div class="container">
            <?php if ($proofDates): ?>
                <?php foreach ($proofDates as $proofDate): ?>
                    <div class="vip-proof-container">
                        <div class="vip-proof-header">Past VIP Results - <?= date('l, F j, Y', strtotime($proofDate)); ?></div>
                        <div class="vip-proof-grid">
                            <?php
                            $proofsQuery = "SELECT image FROM ticket_proofs WHERE DATE(created_at) = ?";
                            $stmtProofs = $dbh->prepare($proofsQuery);
                            $stmtProofs->execute([$proofDate]);
                            $proofs = $stmtProofs->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($proofs as $proof): ?>
                                <div class="vip-proof-item">
                                    <img src="<?= htmlspecialchars($proof['image']); ?>" alt="Ticket Proof" class="vip-image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>No ticket proof records found.</div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">« Prev</a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        if ($end - $start < 4) {
                            if ($start == 1) $end = min($totalPages, $start + 4);
                            else $start = max(1, $end - 4);
                        }
                        for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Next »</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- WhatsApp Button -->
     <?php include('partials/whatsapp.php'); ?>


    <!-- Footer -->
    <?php include('partials/footer.php'); ?>
</div>


</body>
</html>
