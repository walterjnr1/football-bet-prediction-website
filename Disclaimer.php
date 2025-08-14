<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Disclaimer - <?php echo $app_name; ?></title>
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
        <h1>Disclaimer</h1>
        <p>By using <?php echo $app_name; ?>, you acknowledge and agree to the disclaimers stated below.</p>
    </section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <section class="terms-section">
        <h2>Important Legal Disclaimer</h2>
        <p>
            <?php echo $app_name; ?> provides football predictions, statistics, and betting tips for informational and entertainment purposes only.
            We are not a bookmaker, betting operator, or gambling service provider, and we do not encourage illegal betting in any form.
        </p>

        <h3>1. No Guarantee of Winning</h3>
        <ul>
            <li>Our predictions are based on data analysis, statistics, and expert opinions, but sports events are inherently unpredictable.</li>
            <li>Past performance is not indicative of future results. Even the most accurate prediction can be wrong.</li>
            <li>Any financial gain or loss incurred from using our predictions is entirely at the user’s own risk.</li>
        </ul>

        <h3>2. Age Restriction</h3>
        <ul>
            <li>Services on <?php echo $app_name; ?> are intended for users who are 18 years or older (or legal gambling age in their jurisdiction).</li>
            <li>By accessing our services, you confirm that you meet the legal age requirements for betting in your country or region.</li>
        </ul>

        <h3>3. Local Law Compliance</h3>
        <ul>
            <li>It is your sole responsibility to ensure that the use of our website and services is legal in your jurisdiction.</li>
            <li>We are not responsible for any violation of laws by users who access our content from regions where gambling or betting is prohibited.</li>
        </ul>

        <h3>4. Financial Risk Warning</h3>
        <ul>
            <li>Sports betting involves risk and can result in financial loss. You should only stake amounts you can afford to lose.</li>
            <li>We strongly advise responsible betting. If you feel you may have a gambling problem, seek help from professional support organizations.</li>
        </ul>

        <h3>5. Third-Party Links & Services</h3>
        <ul>
            <li>Our website may contain links to third-party websites, bookmakers, or affiliates. These are provided for convenience and informational purposes only.</li>
            <li>We are not responsible for the content, privacy policies, or practices of third-party websites.</li>
        </ul>

        <h3>6. Accuracy of Information</h3>
        <ul>
            <li>While we strive to ensure all predictions, odds, and statistics are accurate, we make no guarantees regarding the completeness or reliability of the information provided.</li>
            <li>Errors or omissions may occur, and we reserve the right to correct them at any time without notice.</li>
        </ul>

        <h3>7. Limitation of Liability</h3>
        <ul>
            <li><?php echo $app_name; ?>, its owners, employees, and partners shall not be held liable for any loss, injury, or damage resulting directly or indirectly from the use of our services.</li>
            <li>All decisions to bet or gamble are taken solely by the user and at their own risk.</li>
        </ul>

        <h3>8. Intellectual Property</h3>
        <ul>
            <li>All content on <?php echo $app_name; ?>, including predictions, graphics, and text, is owned by us and protected by copyright laws.</li>
            <li>Unauthorized reproduction or redistribution is strictly prohibited.</li>
        </ul>

        <h3>9. Changes to This Disclaimer</h3>
        <ul>
            <li>We reserve the right to update or modify this disclaimer at any time without prior notice.</li>
            <li>It is your responsibility to check this page periodically for updates.</li>
        </ul>

        <p><strong>Final Note:</strong> By using <?php echo $app_name; ?>, you agree that you understand and accept all of the above disclaimers. If you do not agree, you must stop using our services immediately.</p>

        <p>&nbsp;</p>
    </section>

    <?php include('partials/whatsapp.php'); ?>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
</body>
</html>
