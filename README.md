# Kaama Liito | Restaurant Management System

Kaama Liito is a modern, web-based Restaurant Management System designed to streamline dining experiences and restaurant operations. This platform bridges the gap between delicious food and technology, offering a premium and efficient environment for customers, staff, and administrators.

## 🚀 Features

### 👤 Customer View
- **Browse Menu:** Explore a visually appealing menu with signature dishes.
- **Easy Registration:** Simple sign-up process to start ordering.
- **Seamless Ordering:** Place orders quickly from any device.
- **Order Tracking:** Monitor the status of your orders.

### 👥 Staff View
- **Order Management:** View and update the status of incoming orders.
- **Profile Management:** Update personal staff information.

### 🛠️ Admin Dashboard
- **Comprehensive Analytics:** Track sales, customers, and transactions at a glance.
- **Menu Management:** Add, update, or remove menu items with ease.
- **Inventory Control:** Manage restaurant stock and inventory.
- **User Management:** Oversee staff and customer accounts.
- **Reporting:** Generate detailed reports for business insights.
- **Payment Processing:** Monitor and manage transaction records.

## 🛠️ Tech Stack

- **Frontend:** HTML5, Vanilla CSS, Bootstrap 5, FontAwesome, AOS (Animate On Scroll).
- **Backend:** PHP (Native).
- **Database:** MySQL.

## 📦 Installation Guide

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or any PHP/MySQL environment.

### Steps
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/updyfitaahalapa-commits/Restaurant-management-system.git
   ```
2. **Move to Web Directory:**
   Move the project folder to your `htdocs` directory (e.g., `C:\xampp\htdocs\restaurant__system`).
3. **Database Setup:**
   - Open PHPMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database named `restaurant_system`.
   - Import the `database.sql` file located in the project root.
4. **Configuration:**
   - Ensure the database connection settings in `config/db.php` match your environment.
   ```php
   $conn = mysqli_connect("localhost", "root", "", "restaurant_system");
   ```
5. **Run the Application:**
   Open your browser and navigate to `http://localhost/restaurant__system`.

## 🔐 Default Credentials

| Role  | Username | Password |
|-------|----------|----------|
| Admin | `admin`  | `admin`  |
| Staff | `staff`  | `staff`  |

> [!NOTE]
> Passwords for the default users in `database.sql` are hashed. Use the provided login credentials to access the system.

## 👨‍💻 Developer

**Abdifitaah**
- **Email:** cabdifitaaxmaxamuud@gmail.com
- **WhatsApp:** +252 613 177 377
- **Location:** Wadajir, Mogadishu

---
Developed with ❤️ by Abdifitaah
