# 📅 AttendEase – Employee Attendance Management System

[![Live Demo](https://img.shields.io/badge/Live%20Demo-Visit-success?style=for-the-badge)](https://attendease.site.je)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Status](https://img.shields.io/badge/Status-Live-brightgreen?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

<p align="center">
  <img src="https://raw.githubusercontent.com/MohitAggarwal1/readme_images/main/employee-attendance/landing.png" alt="AttendEase Banner" width="100%">
</p>

## 🌐 Live Demo

🔗 **Website:** https://attendease.site.je

## 👨‍💻 Connect with Me

- 💼 LinkedIn: https://www.linkedin.com/in/mohitaggarwalofficial
- 📸 Instagram: https://www.instagram.com/mohhitaggarwal
- 🐙 GitHub: https://github.com/MohitAggarwal1

---

# 📌 Project Overview

**AttendEase** is a modern **full-stack Employee Attendance Management System** designed to simplify office administration, automate log records, track daily check-ins, manage leave applications, and streamline task assignments.

Built using **PHP, MySQL, Tailwind CSS, Vanilla CSS, and GSAP**, AttendEase delivers a smooth and premium user experience featuring interactive elements, custom mouse cursors, and an off-canvas drawer navigation. It provides role-based portal access (RBAC) specifically tailored for Administrators, HR Personnel, and General Employees.

---

# ✨ Key Features

## 🔐 Secure Role-Based Authentication

- Multi-role secure login (Admin, HR, and Employee)
- Secure session-based authentication & route protection
- Secure password hashing (`password_verify` & `password_hash`)
- Visual password eye visibility toggle on the peeking character form

---

## 📅 Smart Attendance Tracking

- Real-time attendance logging (Present, Absent, Late)
- Monthly stats calculation and automated reports
- Detailed logs viewable on a responsive dashboard
- Quick-action buttons for daily check-ins

---

## 🏖️ Leave Management System

- Multi-category leave requests (Sick, Casual, Annual, Emergency, Maternity, Unpaid)
- Custom reason inputs, start dates, and end dates
- Live status tracker for employees (Pending, Approved, Rejected)
- HR/Admin dashboard for review, approval, and rejection of leaves

---

## 📋 Task Board & Assignment

- Admin panel to create and assign tasks to employees
- Real-time task checklists for employees
- Status tracking (Pending, In Progress, Completed)
- Consolidated task logs

---

## 📣 Complaints Box

- Anonymous or open complaints portal for employee feedback
- Secure databases storage for complaints
- Admin complaint board to review, action, and manage feedbacks
- Promotes transparency and a healthy workspace

---

## 📣 Premium Visual Elements

- **Custom Cursor Overlay**: Dynamic mouse tracking with GSAP. Smooth lagging outer outline with transition scaling on interactive hovers.
- **Peeking Character Login**: Aesthetic form layout featuring a peeking visual mascot matching the background design.

---

# 🖼️ Application Preview

---

## 🏠 Landing Page

![Landing Page](https://raw.githubusercontent.com/MohitAggarwal1/readme_images/refs/heads/main/employee-attendance/landing.png)

Features:
- Premium brand logo and modern illustrations
- Interactive landing scroll sections (Home, Features, About, Contact)
- Fluid GSAP mouse cursor trackers

---

## 🔐 Login Page

![Login Page](https://raw.githubusercontent.com/MohitAggarwal1/readme_images/refs/heads/main/employee-attendance/login.png)

Includes:
- Peeking illustration mascot
- Responsive login card
- Dynamic eye visibility toggle for passwords

---

## 📊 Admin / HR Dashboard

![Admin Dashboard](https://raw.githubusercontent.com/MohitAggarwal1/readme_images/refs/heads/main/employee-attendance/dasboard.png)

Shows:
- Summary metrics cards (Total Employees, Today Present, Today Absent, Today Late)
- Quick navigation shortcut cards
- Live attendance log feed

---

## 👨‍💼 Employee Portal

![Employee Dashboard](https://raw.githubusercontent.com/MohitAggarwal1/readme_images/refs/heads/main/employee-attendance/employee.png)

Features:
- Individual checklist updates (Tasks, Check-in status)
- Leave application form
- Complaints submission box

---

## 📅 Attendance Logs

![Attendance Logs](https://raw.githubusercontent.com/MohitAggarwal1/readme_images/refs/heads/main/employee-attendance/attendance.png)

Includes:
- Detailed records
- Quick filter sorting
- Print-ready attendance reports

---

# 🛠️ Tech Stack

### Frontend

- **HTML5 & Vanilla CSS** (Tailwind CSS utilized on Landing Page)
- **Javascript (ES6+)**
- **GSAP** (GreenSock Animation Platform for smooth mouse physics)

### Backend

- **PHP** (Object-oriented database queries using PDO)
- **Session-Based Authentication**

### Database

- **MySQL / MariaDB** (Prepared statements for SQL Injection protection)

---

# 🏗️ System Architecture

```text
                             User Browser
                                  │
                                  ▼
                        PHP Routing & Layout
                     (includes/header & footer)
                                  │
        ┌─────────────────────────┼─────────────────────────┐
        │                         │                         │
        ▼                         ▼                         ▼
   Admin Portal               HR Portal              Employee Portal
• Manage Employees        • View Attendance        • Log Attendance
• Assign Tasks            • Manage Leaves          • Apply for Leaves
• Review Complaints       • Generate Reports       • File Complaints
        │                         │                         │
        └─────────────────────────┼─────────────────────────┘
                                  │
                                  ▼
                              MySQL DB
                       (attendance_system)
```

---

# 📂 Project Structure

```text
employee-attendance/
│
├── attendance/          # Attendance reports, check-ins, monthly summaries
├── complaints/          # Complaint submission and admin feedback logs
├── config/              # DB connection config and helper utilities
├── css/                 # Stylesheets (index.css, landing.css, style.css)
├── database/            # Database backups and migrations
├── employees/           # Employee records management (add, edit, delete, list)
├── images/              # Media assets, logos, visual banners, custom cursors
├── includes/            # Reusable header and footer layouts
├── js/                  # Scripts (script.js, custom-cursor.js)
├── leaves/              # Leave management modules
├── tasks/               # Task checklists and task assign panel
│
├── index.php            # Landing Page
├── login.php            # Login Screen
├── logout.php           # Session destroyer
├── dashboard.php        # Admin Home Panel
├── employee_dashboard.php# Employee Home Panel
├── profile.php          # User profile details
├── change_password.php  # Security Settings
├── sql_commands.sql     # Database structure definitions
├── package.json         # Project information
└── README.md            # Project documentation
```

---

# 🚀 Local Setup

## 1️⃣ Clone Repository

```bash
git clone https://github.com/MohitAggarwal1/employee-attendance.git
```

---

## 2️⃣ Navigate to Project

```bash
cd employee-attendance
```

---

## 3️⃣ Set up Database Server (XAMPP / WAMP)

1. Start Apache and MySQL modules in your local server panel (e.g., XAMPP Control Panel).
2. Open `phpMyAdmin` in your browser:
   ```
   http://localhost/phpmyadmin
   ```
3. Create a new database named `attendance_system`.
4. Select the database, click the **Import** tab, choose the file sql_commands.sql from the root project directory, and click Import/Go.

---

## 4️⃣ Configure Environment & Credentials

Open config/config.php and adjust your local MySQL credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'attendance_system');
define('DB_USER', 'root');
define('DB_PASS', 'YOUR_DB_PASSWORD'); // Set your local database password here
```

---

## 5️⃣ Run Local Server

Copy the project directory to your server's root directory (e.g. `C:/xampp/htdocs/employee-attendance`).

Open your browser and navigate to:
```
http://localhost/employee-attendance
```

---

# 🎯 Core Functionalities

- 🔐 Safe session validations
- 👥 CRUD employee management
- 📊 Metrics dashboard summary
- 📅 Live daily check-ins
- 🏝️ Leave request workflow
- 📋 Employee task assignments
- 📣 Secure complaints box
- 📱 Responsive viewport support
- 💫 Premium GSAP animation effects

---

# 📱 Responsive Design

AttendEase dashboard features collapsible navigation drawer sliders and block tables, making it fully optimized for:

- 📱 Mobile
- 📲 Tablet
- 💻 Laptop
- 🖥 Desktop

---

# 💡 What This Project Demonstrates

- Role-based Access Control (RBAC) in web platforms
- Secure database interaction utilizing PDO prepared queries
- Collapsible responsive drawer layouts and overflow scrolling tables
- Modern typography integrations and aesthetic color palettes
- Clean file organization separation (CSS, JS, PHP, DB)

---

# 🚀 Future Enhancements

- 📊 Interactive charts showing monthly attendance stats
- 🔔 Real-time email notifications for leave status updates
- 📆 Google Calendar API integration
- 📊 PDF report exports
- 🌙 Dark Mode support

---

# 📚 Learning Outcomes

During this project development, I gained hands-on experience with:

- Flexbox and Grid layout constraints for dashboards
- Responsive tables and canvas side-drawer panels
- Clean session-based routing and database connections in PHP
- Enhancing web aesthetics using cursor wrappers and animation libraries
- Standard responsive layout design workflows

---

# 📄 License

This project is licensed under the **MIT License** — free to use, modify, and distribute.

---

# 🙌 Acknowledgements

Special thanks to:
- Open-source communities for resources on CSS grid/flexboxes
- XAMPP/MariaDB for database modules
- Mohit Aggarwal for project code updates

---

# 👨💻 About Me

Hi, I'm **Mohit Aggarwal**, a Full Stack Developer focused on building scalable, responsive, and beautiful web applications.

My interests include:
- Full Stack Web Development
- UI/UX Design & Micro-animations
- Database Management & Security

---

### ⭐ If you found this project helpful, don't forget to give it a star!
