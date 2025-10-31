# House Services Booking System (HTML5, PHP, MySQL)

## 📘 About the Project
This is a simple web application made using **HTML5**, **CSS**, **PHP**, and **MySQL**.  
It helps users book home services (like cleaning, plumbing, electrical works, etc.) and allows the admin to manage all services, bookings, and feedback.  

Created by [@soundaryaarunkumar3-crypto](https://github.com/soundaryaarunkumar3-crypto) to understand basic web development, databases, and PHP sessions.

---

## Features
- User registration and login  
- Admin login and dashboard  
- Book a home service (appointment)  
- View booked services  
- Add and manage services (Admin)  
- Submit and view feedback  
- Logout securely  

---

## Technologies Used
- Frontend: **HTML5, CSS3, JavaScript**
- Backend: **PHP (MySQLi)**
- Database: **MySQL / phpMyAdmin**
- Local Server: **XAMPP / LAMP**

---

##How to Run This Project

### Step 1 – Install Required Tools
1. Install **XAMPP** or any PHP + MySQL environment.  
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

### Step 2 – Copy the Project
1. Download or clone this project:  
   ```bash
   git clone https://github.com/soundaryaarunkumar3-crypto/Home-Services-Booking-System.git
   ```
2. Place the folder inside:
   ```
   C:\xampp\htdocs\
   ```

### Step 3 – Setup the Database
1. Open **phpMyAdmin** (http://localhost/phpmyadmin).  
2. Create a new database:
   ```sql
   CREATE DATABASE house_services_db;
   ```
3. Import the following SQL schema:

```sql
CREATE DATABASE IF NOT EXISTS house_services_db;
USE house_services_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  service_id INT,
  booking_date DATE,
  booking_time TIME,
  status VARCHAR(50) DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  message TEXT,
  rating INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

4. Update your connection file (`inc/connect.php`):
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "house_services_db";
   ```

5. Run the app:  
   Open in browser → [http://localhost/Home-Services-Booking-System/](http://localhost/Home-Services-Booking-System/

---

## 📦 Folder Structure
```
Home-Services-Booking-System/
├── admin/              → Admin pages
├── inc/                → Database connection (connect.php)
├── processdataPHP/     → PHP scripts (login, register, booking)
├── css/                → Stylesheets
├── js/                 → JavaScript files
├── images/             → Icons and images
├── index.php           → Home page
├── login.php           → Login page
└── dashboard.php       → User dashboard
```

---

## 🧠 Learning Outcomes
- Understanding of how PHP interacts with MySQL  
- Basics of CRUD operations (Create, Read, Update, Delete)  
- Session handling in PHP  
- Simple database relationships (ER diagram)  
- Basic HTML/CSS structure for web apps  

---

## 👨‍💻 Author
**Soundarya Arun Kumar**  
Finalyear  Project — for learning PHP & MySQL  
GitHub: [@soundaryaarunkumar3-crypto](https://github.com/soundaryaarunkumar3-crypto)
