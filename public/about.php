<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us – FrostGear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<!-- ========== HERO ========== -->
<section class="fg-about-hero-A">
    <img src="assets/images/About-us-2.png" alt="Skiers on mountain">
    <div class="fg-about-hero-A__content">
        <h1>Built For The Mountain</h1>
        <p>Crafting ski gear that brings confidence, control, and comfort to every run.</p>
    </div>
</section>

<!-- ========== OUR STORY (Centered) ========== -->
<section class="fg-about-story-A">
    <div class="inner">
        <h2>Our Story</h2>
        <p>
            FrostGear was born from a simple belief — great ski gear should feel
            predictable, stable, and comfortable from the first turn to the last.
            What began as a small testing project on local slopes became a mission
            to build equipment that riders trust in real alpine conditions.
        </p>
    </div>
</section>

<!-- ========== IMAGE + TEXT SPLIT ========== -->
<section class="fg-about-split-A">
    <div class="split-img">
        <img src="assets/images/About-us-3.png" alt="">
    </div>

    <div class="split-text">
        <h2>Born From Real Mountain Demands</h2>
        <p>
            Instead of designing behind a desk, our process starts on snow.  
            We test prototypes on ice, powder, and variable terrain — constantly adjusting
            flex, shape, and materials until every piece of gear feels natural and responsive.
        </p>
        <p>
            If it doesn’t hold up in unpredictable conditions, it doesn’t earn the FrostGear badge.
        </p>
    </div>
</section>

<!-- ========== VALUES ICON ROW ========== -->
<section class="fg-about-values-A">
    <div class="inner">
        <h2>What Drives Us</h2>

        <div class="values-grid">
            <div class="value-card">
                <i class="fa-solid fa-mountain"></i>
                <h3>Field Tested</h3>
                <p>Every product is refined on real slopes, not just on paper.</p>
            </div>

            <div class="value-card">
                <i class="fa-solid fa-gauge-high"></i>
                <h3>Confidence at Speed</h3>
                <p>We focus on stability and edge control — even in demanding terrain.</p>
            </div>

            <div class="value-card">
                <i class="fa-solid fa-hand-holding-heart"></i>
                <h3>Comfort That Lasts</h3>
                <p>Warmth and fit designed for long sessions, not quick runs.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== TIMELINE ========== -->
<section class="fg-about-timeline-A">
    <div class="inner">
        <h2>Our Journey</h2>

        <div class="timeline">
            <div class="time-block">
                <span class="year">2015</span>
                <p>Started testing homemade prototypes on icy local runs.</p>
            </div>

            <div class="time-block">
                <span class="year">2017</span>
                <p>Launched first small-batch skis designed from rider feedback.</p>
            </div>

            <div class="time-block">
                <span class="year">2020</span>
                <p>Expanded into boots and performance accessories.</p>
            </div>

            <div class="time-block">
                <span class="year">2024</span>
                <p>40+ pro rider partnerships and worldwide testing network.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== PROMISE CTA ========== -->
<section class="fg-about-promise-A">
    <h2>Our Promise</h2>
    <p>
        If it carries the FrostGear name, it's built to handle real mountain conditions.
        We create products that inspire confidence — so you can focus on the ride.
    </p>
    <a href="shop.php" class="fg-btn fg-btn--gold">Explore Our Gear</a>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
