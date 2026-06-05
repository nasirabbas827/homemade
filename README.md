# homemade  

A lightweight PHP web application that enables administrators and managers to create and maintain a catalog of homemade products for a smart‑home‑style shop. The system includes separate admin and manager interfaces, a MySQL database schema, and a responsive front‑end.

---

## Overview  

`homemade` provides a simple yet functional platform for:

* Managing shops and shop managers (admin side)  
* Adding, editing, and deleting product categories and items (manager side)  
* Displaying a public storefront with contact support  

All core functionality is built with plain PHP, MySQL, and vanilla CSS, making it easy to deploy on any LAMP stack.

---

## Features  

| Area | Capability |
|------|------------|
| **Admin** | Login / logout, add‑edit‑delete shops, add‑edit‑delete managers, view shop & manager lists, update admin profile, navigation bar |
| **Manager** | Login / logout, add‑edit‑delete product categories, add‑edit‑delete items, upload item images |
| **Public** | Home page, product listings, contact‑support form |
| **Database** | SQL dump (`Database/homemade_db.sql`) with tables for users, shops, categories, items, and support tickets |
| **Documentation** | Project brief (`Smart Home made Shop.docx`) |
| **Styling** | Central stylesheet (`css/style.css`) for a clean, responsive UI |

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3 (no external frameworks) |
| **Server** | Apache / Nginx (LAMP stack) |
| **Version Control** | Git (GitHub) |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/your-username/homemade.git
   cd homemade
   ```

2. **Create a MySQL database**  

   ```sql
   CREATE DATABASE homemade_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Import the schema**  

   ```bash
   mysql -u your_user -p homemade_db < Database/homemade_db.sql
   ```

4. **Configure database credentials**  

   Edit `config.php` (root) and `admin/config.php` / `manager/config.php` to match your environment:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'homemade_db');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

5. **Set up the web server**  

   * Point the document root to the project folder.  
   * Ensure PHP is enabled and the `uploads/` directories (e.g., `admin/shop_pictures/`) are writable.

6. **Optional – Secure the site**  

   * Enable HTTPS.  
   * Move the `config.php` files outside the web root if desired.

---

## Usage  

### Admin Interface  

| URL | Description |
|-----|-------------|
| `admin/admin_login.php` | Log in as an administrator |
| `admin/admin_home.php` | Dashboard with quick links |
| `admin/add_shop.php` | Create a new shop |
| `admin/view_shops.php` | List, edit, or delete shops |
| `admin/add_manager.php` | Create a manager account for a shop |
| `admin/view_managers.php` | List, edit, or delete managers |
| `admin/logout.php` | End admin session |

### Manager Interface  

| URL | Description |
|-----|-------------|
| `manager/config.php` (login page) | Manager login (uses the same `login.php` script) |
| `manager/add_category