<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Live Scores-<?php echo $app_name; ?></title>
    <?php include('partials/head.php') ?>
</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php') ?>
    </div>

    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>

    <nav class="navbar">
        <?php include('partials/navbar.php') ?>
    </nav>

    <section class="hero">
        <p>&nbsp;</p>
        <h1>Live Football Scores</h1>
        <p>Follow live updates from leagues and matches around the world.</p>
        <p>&nbsp;</p>
    </section>

    <section class="livescore-section" style="padding: 20px;">
        <div style="width:100%; max-width:1100px; margin:0 auto;">
            <iframe src="https://www.scorebat.com/embed/livescore/"
                    frameborder="0"
                    width="100%"
                    height="600"
                    allowfullscreen
                    allow="autoplay; fullscreen"
                    style="border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.07); background:#fff;">
            </iframe>
        </div>
        <noscript>
            <p>Live scores require JavaScript enabled. Visit 
                <a href="https://www.scorebat.com/livescore/" target="_blank">ScoreBat LiveScore</a> 
                for more.
            </p>
        </noscript>
    </section>

    <?php include('partials/whatsapp.php'); ?>
    
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
</body>
</html>
