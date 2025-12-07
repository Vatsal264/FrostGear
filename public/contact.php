<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us – FrostGear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<!-- ========== CONTACT HERO / INTRO ========== -->
<section class="fg-contact-hero">
    <div class="fg-contact-hero__inner">
        <nav class="fg-breadcrumb">
            <a href="index.php">Home</a>
            <span>&raquo;</span>
            <span>Contact</span>
        </nav>

        <h1>Contact FrostGear</h1>
        <p>
            Questions about gear, sizing, or orders? Send us a message and we’ll get back to you as soon as possible.
        </p>
    </div>
</section>

<!-- ========== MAIN CONTACT SECTION (FORM + IMAGE) ========== -->
<main class="fg-contact-main">
    <div class="fg-contact-main__inner">

        <!-- LEFT: FORM -->
        <section class="fg-contact-form">
            <h2>Send Us a Message</h2>
            <p class="fg-contact-form__intro">
                Fill out the form and our team will respond via email.  
                For urgent inquiries, use the contact details below.
            </p>

            <!-- FORM UI ONLY (not functional yet) -->
            <form action="#" method="post" class="fg-form">
                <div class="fg-form__row">
                    <div class="fg-form__field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your name" required>
                    </div>
                </div>

                <div class="fg-form__row">
                    <div class="fg-form__field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="fg-form__row">
                    <div class="fg-form__field">
                        <label for="topic">Topic</label>
                        <select id="topic" name="topic">
                            <option value="general">General Question</option>
                            <option value="orders">Orders &amp; Shipping</option>
                            <option value="product">Product / Sizing Help</option>
                            <option value="support">Technical Support</option>
                        </select>
                    </div>
                </div>

                <div class="fg-form__row">
                    <div class="fg-form__field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5"
                                  placeholder="How can we help?"></textarea>
                    </div>
                </div>

                <button type="submit" class="fg-btn fg-btn--gold fg-contact-submit" disabled>
                    Send Message (Coming Soon)
                </button>

                <p class="fg-contact-note">
                    Message submission will be enabled in the next update.
                </p>
            </form>

            <!-- CONTACT INFO (clean version) -->
            <div class="fg-contact-info">
                <div class="info-card">
                    <h3>Get in Touch</h3>
                    <p>You can reach our team directly using the details below:</p>

                    <p><strong>Email:</strong> 
                        <a href="mailto:support@frostgear.com">support@frostgear.com</a>
                    </p>

                    <p><strong>Phone:</strong> 
                        <a href="tel:+910000000000">+91 00000 00000</a>
                    </p>

                    <p><strong>Address:</strong><br>
                        FrostGear HQ, Alpine Avenue,<br>
                        Glacier Park Road, India
                    </p>
                </div>
            </div>
        </section>

        <!-- RIGHT: IMAGE PANEL -->
        <section class="fg-contact-visual">
            <div class="fg-contact-visual__card">
                <img src="assets/images/Contact-us.png"
                     alt="FrostGear support in alpine environment">
                <div class="fg-contact-visual__overlay">
                    <h3>Here to Help</h3>
                    <p>Advice on gear, fit, and performance from people who actually ride.</p>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
