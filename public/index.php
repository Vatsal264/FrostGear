<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Fetch latest active products for the homepage carousel
$products = [];

$sql = "
    SELECT id, name, main_image, price
    FROM products
    WHERE id IN (1, 2, 3, 12, 13, 8, 10, 15)
";


$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FrostGear - Conquer the Slopes with Confidence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- ================= HERO SECTION ================= -->
<section class="fg-hero">
    <div class="fg-hero__inner">
        <h1>Conquer the Slopes</h1>
        <p>
            Premium skis, boots, and winter gear engineered for stability, comfort,
            and performance in real alpine conditions.
        </p>
        <a href="shop.php" class="fg-btn fg-btn--gold">Shop Now</a>
    </div>

    <div class="fg-hero__art"></div>
</section>

<!-- ================= PRODUCTS SECTION ================= -->
<section class="all-products">
    <h2>All Products</h2>

    <div class="carousel-wrapper">
        <button class="carousel-btn left" id="prevBtn">&#8249;</button>
        <div class="carousel-container">
            <div class="carousel-track">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
                            <div class="img-wrapper">
                                <img src="assets/images/products/<?php echo $product['main_image']; ?>" alt="">
                            </div>
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price">Rs.<?php echo number_format($product['price']); ?></p>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <button class="carousel-btn right" id="nextBtn">&#8250;</button>
    </div>

    <div class="show-more">
        <a href="shop.php" class="show-btn">Show more items</a>
    </div>
</section>

<!-- ================= CTA HERO SECTION ================= -->
<section class="fg-cta-hero">
   <div class="fg-cta-hero__bg">
     <img src="assets/images/Call-to-action.avif" alt="Skiers on dark slope background">
   </div>

   <div class="fg-cta-hero__content">
     <span class="fg-cta-hero__label">Limited Time Only</span>
     <h2 class="fg-cta-hero__title">
       Enjoy 20% Off on a Wide<br>
       Selection of Ski Gear & Accessories
     </h2>
     <a href="shop.php" class="fg-btn fg-btn--gold fg-cta-hero__btn">Shop Now</a>
   </div>
</section>

<!-- ================= ABOUT US SECTION ================= -->
<section class="fg-about">
  <div class="fg-about__inner">

    <div class="fg-about__media">
      <img src="assets/images/about-us.avif" alt="FrostGear team on alpine slope">
    </div>

    <div class="fg-about__content">
      <span class="fg-about__eyebrow">Built for the mountain</span>

      <h2 class="fg-about__title">About FrostGear</h2>

      <p class="fg-about__text">
        FrostGear designs skis, boots, and accessories engineered for precise control and all-day comfort.
        Every piece is field-tested in alpine conditions and refined with feedback from riders at every level.
      </p>

      <ul class="fg-about__values">
        <li><strong>Performance Tuned</strong> — Responsive designs for stability and control.</li>
        <li><strong>Proven Durable</strong> — Materials tested in sub-zero conditions.</li>
        <li><strong>Honest Fit</strong> — Comfort without compromising handling.</li>
      </ul>

      <div class="fg-about__cta">
        <a href="shop.php" class="fg-btn fg-btn--gold">Explore the Gear</a>
      </div>
    </div>

  </div>
</section>

<!-- ================= EXPLORE OUR EXPERTISE ================= -->
<section class="fg-expertise">
  <div class="fg-expertise__inner">

    <header class="fg-expertise__header">
      <h2>Explore Our Expertise</h2>
      <p>Every FrostGear product reflects cutting-edge design, real-world testing, and rider-focused innovation.</p>
    </header>

    <div class="fg-exp-cards">
      <article class="fg-exp-card">
        <div class="fg-exp-card__icon fg-fa">
          <i class="fa-solid fa-pen-ruler"></i>
        </div>
        <h3>Precision Engineering</h3>
        <p>Balanced flex patterns and tuned sidecuts deliver confident edge hold on ice and powder.</p>
      </article>

      <article class="fg-exp-card">
        <div class="fg-exp-card__icon fg-fa">
          <i class="fa-regular fa-snowflake"></i>
        </div>
        <h3>All-Weather Testing</h3>
        <p>Field-tested in sub-zero temps, high winds, and variable snow to ensure reliable performance.</p>
      </article>

      <article class="fg-exp-card">
        <div class="fg-exp-card__icon fg-fa">
          <i class="fa-solid fa-person-skiing"></i>
        </div>
        <h3>Fit & Comfort Science</h3>
        <p>Ergonomic boot lasts and breathable liners keep rides comfortable without sacrificing response.</p>
      </article>
    </div>

    <div class="fg-exp-stats">
      <div>
        <span class="stat">500+</span>
        <span class="label">Products Tested</span>
      </div>
      <div>
        <span class="stat">25</span>
        <span class="label">Years Experience</span>
      </div>
      <div>
        <span class="stat">40+</span>
        <span class="label">Pro Partnerships</span>
      </div>
    </div>

  </div>
</section>


<?php include __DIR__ . '/includes/footer.php'; ?>
