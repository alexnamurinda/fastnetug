<?php
// $activePage: 'home' | 'coverage' | 'about' | 'contact' | 'reviews'
// $basePath: '' for root, '../' for pages/
$basePath = $basePath ?? '';
$activePage = $activePage ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= $basePath ?>index.php">
            <img src="<?= $basePath ?>images/FastNetUGbg.png" alt="FastNetUG Logo" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="<?= $basePath ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'coverage' ? 'active' : '' ?>" href="<?= $basePath ?>pages/coverage.php">Coverage Areas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'about' ? 'active' : '' ?>" href="<?= $basePath ?>pages/about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'contact' ? 'active' : '' ?>" href="<?= $basePath ?>pages/contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'reviews' ? 'active' : '' ?>" href="<?= $basePath ?>pages/reviews.php">Reviews</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
