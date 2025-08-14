<?php
include('config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
//how to handle the case where id is not provided or invalid
if ($id <= 0) {
    header("Location: index"); // Redirect to homepage if id is invalid
    exit();
}

// Fetch news articles from the database using id
$stmt = $dbh->prepare("SELECT id, title, content, image, published_at FROM news WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row_news = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $row_news['title']; ?>-<?php echo $app_name; ?></title>
<?php include('partials/head.php')  ?>
</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php')  ?>
    </div>
    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>
    <nav class="navbar">
     <?php include('partials/navbar.php')  ?>
    </nav>

   <section class="hero">
    <p>Stay informed with the latest updates from <?php echo $app_name; ?> News Desk — covering this developing story as it unfolds.</p>
    <h2>&nbsp;</h2>
    <h2>&nbsp;</h2>
</section>

        <h2>&nbsp;</h2>
        <h2>&nbsp;</h2>

<section class="terms-section">
    <h2><?php echo $row_news['title']; ?></h2>

    <?php if (!empty($row_news['image'])): ?>
    <p style="text-align: center;">
        <img src="<?php echo $row_news['image']; ?>" alt="News Image" class="news-image" style="width: 500px; height: auto;">
    </p>
<?php endif; ?>


    <!-- Centered container with justified text -->
             <p style="text-align: center;">
        <?php echo $row_news['content']; ?>
        </p>
    <p>&nbsp;</p>

    <div style="text-align: center;">
        <a href="javascript:history.back()" class="back-button">← Back</a>
    </div>
</section>


     <?php include('partials/whatsapp.php'); ?>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
</body>
</html>
