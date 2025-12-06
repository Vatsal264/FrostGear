# FrostGear – Modern Ski Equipment E-Commerce Platform

FrostGear is a clean, modern, and performance-focused e-commerce website for premium skiing gear.  
Designed for stability, visual clarity, and real-world usability, the platform provides customers with a smooth shopping experience across all devices.

This project is part of a personal portfolio initiative and demonstrates skills in full-stack development, UI/UX design, PHP development, and Git version control.

---

## 🚀 Features (Current Progress)

### ✅ Completed
- Modern homepage with:
  - Responsive hero banner
  - Call-to-action promotional section
  - “Explore Our Expertise” informational section
  - About FrostGear brand section
  - Dynamic product carousel (pulling latest items from database)
- Refined, premium UI using custom CSS and design consistency
- Fully connected MySQL database
- Initial GitHub project structure set up and version-controlled

### 🔧 In Progress / Upcoming Features
- Category-based product browsing (Shop page)
- Product detail page (images, description, price)
- Add-to-cart and cart management system
- User authentication (login/registration)
- Admin dashboard for product management
- Order placement & invoice generation
- Responsive layouts for all pages

---

## 🛠 Tech Stack

| Layer | Technologies |
|-------|--------------|
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Backend** | PHP 8+, MySQL |
| **Server** | XAMPP / Apache |
| **Version Control** | Git & GitHub |
| **Design** | Custom UI components, Font Awesome Icons |

---

## 📁 Project Structure
FrostGear/
│
├── public/
│ ├── index.php
│ ├── shop.php
│ ├── product.php
│ ├── assets/
│ │ ├── css/
│ │ │ └── style.css
│ │ ├── images/
│ │ └── js/
│ └── includes/
│ ├── header.php
│ ├── footer.php
│ └── db.php
│
└── README.md
*(Structure will expand as features are added.)*

---

## 🔗 Database Details (Simplified)

### Tables currently implemented:
- **products**
- **categories**

Each product contains:
- Name  
- Description  
- Price  
- Stock level  
- Category mapping  
- Image file reference  
- Active status  

SQL seed files can be found inside the project as development continues.

---
## 📌 Installation & Setup

### 1. Clone the Repository
git clone https://github.com/Vatsal264/FrostGear.git

2. Move Project Into XAMPP
Place the folder inside the htdocs directory:
C:/xampp/htdocs/

4. Start Apache & MySQL (via XAMPP)
Open XAMPP Control Panel → Start Apache and MySQL.

5. Create Database
Create a new MySQL database named:
frostgear_db

Import the SQL tables and seed data provided in the project.

5. Run the Project in Browser
http://localhost/FrostGear/public/index.php

---
