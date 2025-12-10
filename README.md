# FrostGear – Modern Ski Equipment E-Commerce Platform

FrostGear is a clean, modern, and performance-focused e-commerce website for premium skiing gear.  
Designed for stability, visual clarity, and real-world usability, the platform provides customers with a smooth shopping experience across all devices.

This project is part of a personal portfolio initiative and demonstrates skills in full-stack development, UI/UX design, PHP development, and Git version control.

---

> **Status:** Cart + Auth fully working. Checkout & orders are the next milestone.

---


## 🚀 Features (Current Milestones)

### 🏠 Public Pages

- **Home Page**
  - Hero section with strong branding and call-to-action.
  - “Explore Our Expertise” feature highlights.
  - About FrostGear teaser section.
  - Dynamic **product carousel** showing latest active products from the database.

- **Shop Page**
  - Grid layout of all active products.
  - Category-based filtering.
  - Sorting options (price, name, latest).
  - “On Sale” badge for discounted products.
  - Old price with strikethrough + highlight for current price.

- **Product Detail Page**
  - Large product image with **SALE** badge (if applicable).
  - Category chip and breadcrumb navigation.
  - Full description and stock information.
  - “You may also like” related products from the same category.
  - **Add to Cart** available only to logged-in users.

- **About Page**
  - Brand story and values.
  - Alpine visuals matching FrostGear’s identity.
  - Timeline and “Our Promise” style content block.

- **Contact Page**
  - Contact form UI (markup in place, backend sending to be added later).
  - Static support information (email, phone, address).
  - Side image card with small overlay text.

---

### 👤 Authentication & Sessions

- **Register**
  - Create an account with `name`, `email`, and `password`.
  - Passwords stored using `password_hash()` (secure one-way hashing).
  - Validation for duplicate emails.

- **Login**
  - Email + password validation using `password_verify()`.
  - On success, stores `user_id` (and name) in `$_SESSION`.
  - Displays a **welcome banner** at the top after login.

- **Logout**
  - Clears user session and returns user to the public site.

- **Header Behavior**
  - Shows `Login` / `Register` when logged out.
  - Shows `Welcome, {name}` banner and `Logout` when logged in.
  - Cart icon with **item count badge** using session cart data.

---

### 🛒 Cart System (Session-Based)

- Add products to cart from the **product page** (only if logged in).
- Cart contents are stored in `$_SESSION['cart']`.
- Each cart item stores:
  - Product ID
  - Name
  - Price
  - Image filename
  - Quantity
- Cart page features:
  - Tabular display of all items.
  - Live line totals (`price × qty`) and **live subtotal/total updates** using JavaScript.
  - Quantity changes:
    - Update line total instantly.
    - Recalculate subtotal automatically.
    - Auto-submit the form after a short delay to sync session data.
  - Remove single item (`×`) with smooth fade-out animation.
  - **Clear Cart** with confirmation and smooth fade-out of all rows.
- Stock-aware:
  - When adding or updating, quantity is capped based on available stock in the `products` table.

---

## 🛠 Tech Stack

| Layer       | Technologies                         |
|------------|---------------------------------------|
| Frontend   | HTML5, CSS3, Vanilla JavaScript       |
| Backend    | PHP (procedural), MySQL               |
| Server     | XAMPP (Apache, PHP, MySQL)            |
| Styling    | Custom CSS, Font Awesome Icons        |
| State      | PHP Sessions (`$_SESSION`)            |
| Versioning | Git & GitHub                          |

---

## 📁 Project Structure

```bash
FrostGear/
└── public/
    ├── index.php          # Home page
    ├── shop.php           # Product listing
    ├── product.php        # Single product detail
    ├── cart.php           # Cart page (session-based)
    ├── checkout.php       # (Planned – not active yet)
    ├── about.php          # About FrostGear
    ├── contact.php        # Contact page
    ├── login.php          # User login
    ├── register.php       # User registration
    ├── logout.php         # Session logout
    ├── assets/
    │   ├── css/
    │   │   └── style.css  # Main styles
    │   ├── js/
    │   │   └── cart.js    # Cart dynamic totals & animations
    │   └── images/
    │       ├── FrostGear.png
    │       ├── products/  # Product images
    │       └── ...        # Other page images
    └── includes/
        ├── header.php     # Global header + nav + cart badge
        ├── footer.php     # Global footer
        └── db.php         # Database connection
