<?php
$username = $_GET['username'] ?? null;
$expiry   = $_GET['expiry'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Connected - FastNetUG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/mainstyles.css" rel="stylesheet">
    <link href="css/thankyou.css" rel="stylesheet">
</head>

<body class="ty-page">

    <div class="ty-wrap">

        <img src="images/thankyou.jpeg" alt="You are now connected - FastNetUG" class="ty-flier">

        <?php if ($username): ?>
            <div class="ty-welcome">
                <i class="fas fa-user-check"></i>
                Welcome, <strong><?= htmlspecialchars($username) ?></strong>
                <?php if ($expiry): ?><span class="ty-welcome-expiry">· expires <?= htmlspecialchars($expiry) ?></span><?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="https://chat.whatsapp.com/BX0W5Lyawl7HcrzaSXEvV2" target="_blank" rel="noopener" class="ty-wa-banner">
            <span class="ty-wa-icon"><i class="fab fa-whatsapp"></i></span>
            <span class="ty-wa-text">
                <span class="ty-wa-title">Click here!</span>
                <span class="ty-wa-sub">To join our WhatsApp group &amp; receive network updates first</span>
            </span>
            <span class="ty-wa-arrow"><i class="fas fa-chevron-right"></i></span>
        </a>

    </div>

</body>

</html>
