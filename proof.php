<?php
include('config.php');

// Pagination setup
$limit = 4; // 2 per row, so let's show 4 per page
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Count total tickets
$totalStmt = $dbh->query("SELECT COUNT(*) FROM ticket_proofs");
$totalTickets = $totalStmt->fetchColumn();
$totalPages = ceil($totalTickets / $limit);

// Fetch tickets for current page
$stmt = $dbh->prepare("SELECT id, image FROM ticket_proofs ORDER BY id DESC LIMIT :start, :limit");
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Ticket Proof-<?php echo $app_name; ?></title>
<?php include('partials/head.php'); ?>

</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php'); ?>
    </div>
    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>
    <nav class="navbar">
        <?php include('partials/navbar.php'); ?>
    </nav>

    <section class="hero">
        <p>&nbsp;</p>
        <h1>Winning Ticket Proof</h1>
        <p>Here are some of our recent winning predictions. We share these so you can see our proven track record and have confidence in our services.</p>
    </section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <section class="terms-section">
        <h2>Recent Wins</h2>
        <p>
            At <strong><?php echo $app_name; ?></strong>, we pride ourselves on accuracy and transparency. Below are snapshots of real winning tickets from our satisfied clients.
        </p>

        <div class="ticket-gallery">
            <?php if ($tickets): ?>
                <?php foreach ($tickets as $ticket): ?>
                    <div class="ticket">
                        <?php if (!empty($ticket['image']) && file_exists($ticket['image'])): ?>
                            <img src="<?php echo htmlspecialchars($ticket['image']); ?>" alt="Winning Ticket">
                        <?php else: ?>
                            <img src="images/no-image.png" alt="No Ticket Available">
                        <?php endif; ?>
                        
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No ticket proofs available at the moment.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <h3 style="margin-top: 40px;">Why We Show Ticket Proof</h3>
        <ul>
            <li>✅ To prove our predictions are accurate and profitable.</li>
            <li>✅ To build trust with new customers.</li>
            <li>✅ To motivate existing members to continue using our services.</li>
        </ul>

        <p>
            Remember, past results are an indicator of our expertise, but betting should always be done responsibly.
        </p>

        <h3>Want Your Own Winning Ticket?</h3>
        <p>
            Subscribe to our VIP service today and start receiving premium fixed matches with a high winning probability.
        </p>
        <p>
            <a href="signup" style="display: inline-block; background: #28a745; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Join VIP Now</a>
        </p>

        <p>&nbsp;</p>
    </section>

    <?php include('partials/whatsapp.php'); ?>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
</body>
</html>
