<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>AttendEase</title>

<link rel="icon" type="image/png" href="images/favicon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config={
theme:{
extend:{
colors:{
primary:"#274368",
secondary:"#33A58C",
dark:"#0f172a"
}
}
}
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>

<link rel="stylesheet" href="css/landing.css">

</head>

<body>

<div class="gradient-bg">

<div class="blob blob1"></div>

<div class="blob blob2"></div>

<div class="blob blob3"></div>

</div>

<header class="glass fixed top-0 left-0 w-full z-50">

<div class="max-w-7xl mx-auto px-4 sm:px-8">

<div class="flex items-center justify-between h-20">

<a href="#" class="flex items-center gap-3">

<img src="images/logo.png"
class="w-32 sm:w-44">

</a>

<nav class="hidden lg:flex gap-10 font-medium">

<a href="#" class="hover:text-secondary transition">Home</a>

<a href="#features" class="hover:text-secondary transition">Features</a>

<a href="#about" class="hover:text-secondary transition">About</a>

<a href="#contact" class="hover:text-secondary transition">Contact</a>

</nav>

<a href="login.php"
class="bg-secondary text-white px-5 sm:px-7 py-2.5 sm:py-3 rounded-xl hover:scale-110 transition glow text-sm sm:text-base">

Login

</a>

</div>

</div>

</header>

<section class="min-h-screen flex items-center pt-28 sm:pt-0">

<div class="max-w-7xl mx-auto px-4 sm:px-8 grid lg:grid-cols-2 gap-10 sm:gap-20 items-center">

<div>

<div class="inline-flex px-4 sm:px-5 py-2 rounded-full bg-emerald-100 text-secondary font-semibold text-sm sm:text-base">

🚀 Smart Attendance System

</div>

<h1 class="hero-title text-4xl sm:text-6xl lg:text-7xl font-extrabold text-primary mt-8 leading-tight max-w-xl">

Manage Attendance

<span class="text-secondary">

Effortlessly

</span>

</h1>

<p class="mt-8 text-base sm:text-lg text-gray-600 leading-8 sm:leading-9">

AttendEase is a modern Employee Attendance Management System
designed to simplify attendance tracking, employee management,
report generation and office operations using an elegant dashboard.

</p>

<div class="flex flex-col sm:flex-row gap-4 sm:gap-5 mt-10">

<a href="login.php"

class="bg-secondary text-white px-8 py-4 rounded-xl font-semibold hover:scale-110 transition glow text-center">

Get Started

</a>

<a href="#features"

class="border-2 border-secondary text-secondary px-8 py-4 rounded-xl hover:bg-secondary hover:text-white transition text-center">

Explore

</a>

</div>

</div>

<div class="relative mt-8 lg:mt-0">

<div class="glass rounded-[25px] sm:rounded-[35px] overflow-hidden p-3 sm:p-4 hero-img floating">

<img src="images/banner.jpeg"

class="rounded-3xl w-full h-auto object-cover">

</div>

<div class="relative mt-4 sm:absolute sm:-top-10 sm:-left-10 glass p-5 rounded-3xl">

<h2 class="text-4xl font-bold text-secondary">

100%

</h2>

<p class="text-gray-600">

Attendance Accuracy

</p>

</div>

<div class="relative mt-4 sm:absolute sm:-bottom-8 sm:-right-8 glass p-5 rounded-3xl">

<h2 class="text-4xl font-bold text-primary">

24/7

</h2>

<p class="text-gray-600">

Secure Access

</p>

</div>

</div>

</div>

</section>

<!-- ===========================
FEATURES
=========================== -->

<section id="features" class="py-20 sm:py-28">

<div class="max-w-7xl mx-auto px-4 sm:px-8">

<div class="text-center mb-16">

<span class="text-secondary font-semibold tracking-widest uppercase">
Powerful Features
</span>

<h2 class="section-title mt-3">
Everything You Need
</h2>

<p class="text-gray-600 max-w-3xl mx-auto mt-6 text-base sm:text-lg leading-8">
AttendEase provides everything required to efficiently manage employee attendance,
records, reports and daily office operations through a clean and intuitive interface.
</p>

</div>

<div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8">

<div class="card glass rounded-3xl p-8 feature-card">

<div class="text-5xl mb-5">👨‍💼</div>

<h3 class="text-2xl font-bold text-primary mb-4">
Employee Management
</h3>

<p class="text-gray-600 leading-8">
Create, update and organize employee records with ease using a centralized dashboard.
</p>

</div>

<div class="card glass rounded-3xl p-8 feature-card">

<div class="text-5xl mb-5">📅</div>

<h3 class="text-2xl font-bold text-primary mb-4">
Attendance Tracking
</h3>

<p class="text-gray-600 leading-8">
Monitor attendance in real time with automatic records and detailed logs.
</p>

</div>

<div class="card glass rounded-3xl p-8 feature-card">

<div class="text-5xl mb-5">📊</div>

<h3 class="text-2xl font-bold text-primary mb-4">
Analytics & Reports
</h3>

<p class="text-gray-600 leading-8">
Generate monthly attendance reports and gain valuable insights instantly.
</p>

</div>

<div class="card glass rounded-3xl p-8 feature-card">

<div class="text-5xl mb-5">🔒</div>

<h3 class="text-2xl font-bold text-primary mb-4">
Secure Login
</h3>

<p class="text-gray-600 leading-8">
Role-based authentication keeps employee information secure and protected.
</p>

</div>

</div>

</div>

</section>



<!-- ===========================
ABOUT
=========================== -->

<section id="about" class="py-20 sm:py-28">

<div class="max-w-7xl mx-auto px-4 sm:px-8">

<div class="grid lg:grid-cols-2 gap-10 sm:gap-20 items-center mb-12 sm:mb-16">

<div>

<span class="text-secondary font-semibold uppercase tracking-widest">
Why AttendEase?
</span>

<h2 class="section-title mt-4">
Designed For Modern Workplaces
</h2>

<p class="mt-8 text-gray-600 leading-8 sm:leading-9 text-base sm:text-lg">

AttendEase combines speed, simplicity and security to streamline attendance management.
The dashboard allows administrators to manage employees, monitor attendance,
generate reports and improve workplace productivity with ease.

</p>

</div>

<div>

<img src="images/banner2.png"
class="rounded-[35px] shadow-2xl w-full about-image">

</div>

</div>

<div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">

<div class="glass rounded-2xl p-6 about-card">

<h3 class="font-bold text-xl text-primary mb-3">

⚡ Fast Performance

</h3>

<p class="text-gray-600">
Lightning-fast dashboard built for everyday office operations.
</p>

</div>

<div class="glass rounded-2xl p-6 about-card">

<h3 class="font-bold text-xl text-primary mb-3">

📱 Responsive UI

</h3>

<p class="text-gray-600">
Works beautifully across desktop, tablet and mobile devices.
</p>

</div>

<div class="glass rounded-2xl p-6 about-card">

<h3 class="font-bold text-xl text-primary mb-3">

🔒 Secure

</h3>

<p class="text-gray-600">
Modern authentication keeps every employee record protected.
</p>

</div>

<div class="glass rounded-2xl p-6 about-card">

<h3 class="font-bold text-xl text-primary mb-3">

📈 Smart Reports

</h3>

<p class="text-gray-600">
Export organized attendance reports with one click.
</p>

</div>

</div>

</div>

</section>



<!-- ===========================
CONTACT
=========================== -->

<section id="contact" class="py-20 sm:py-28">

<div class="max-w-7xl mx-auto px-4 sm:px-8">

<div class="text-center">

<span class="text-secondary uppercase tracking-widest font-semibold">

Contact

</span>

<h2 class="section-title mt-3">

Let's Connect

</h2>

<p class="text-lg text-gray-600 mt-5">

We'd love to hear from you.

</p>

</div>

<div class="grid lg:grid-cols-3 gap-6 sm:gap-8 mt-12 sm:mt-16">

<div class="glass rounded-3xl p-10 text-center card contact-card">

<div class="text-5xl mb-5">

📍

</div>

<h3 class="text-2xl font-bold text-primary">

Address

</h3>

<p class="text-gray-600 mt-4">

New Delhi, Delhi, India

</p>

</div>

<div class="glass rounded-3xl p-10 text-center card contact-card">

<div class="text-5xl mb-5">

📧

</div>

<h3 class="text-2xl font-bold text-primary">

Email

</h3>

<p class="text-gray-600 mt-4">

<a href="mailto:mohitaggarwal0510@gmail.com" class="hover:text-secondary transition">mohitaggarwal0510@gmail.com</a>

</p>

</div>

<div class="glass rounded-3xl p-10 text-center card contact-card">

<div class="text-5xl mb-5">

📞

</div>

<h3 class="text-2xl font-bold text-primary">

Phone

</h3>

<p class="text-gray-600 mt-4">

<a href="tel:+918376825101" class="hover:text-secondary transition">+91 83768 25101</a>

</p>

</div>

</div>

</div>

</section>



<!-- ===========================
FOOTER
=========================== -->

<footer class="bg-slate-900 text-white pt-20 pb-8">

<div class="max-w-7xl mx-auto px-4 sm:px-8">

<div class="grid lg:grid-cols-4 gap-8 sm:gap-12">

<div>

<img src="images/logo.png"
class="w-48 brightness-0 invert mb-6">

<p class="text-gray-400 leading-8">

AttendEase is a modern attendance management solution built
to simplify employee attendance, reporting and office management.

</p>

</div>

<div>

<h3 class="text-xl font-bold mb-6">

Quick Links

</h3>

<ul class="space-y-4 text-gray-400">

<li><a href="#" class="hover:text-secondary transition">Home</a></li>

<li><a href="#features" class="hover:text-secondary transition">Features</a></li>

<li><a href="#about" class="hover:text-secondary transition">About</a></li>

<li><a href="#contact" class="hover:text-secondary transition">Contact</a></li>

</ul>

</div>

<div>

<h3 class="text-xl font-bold mb-6">

Services

</h3>

<ul class="space-y-4 text-gray-400">

<li>Attendance Tracking</li>

<li>Employee Records</li>

<li>Reports</li>

<li>Dashboard</li>

</ul>

</div>

<div>

<h3 class="text-xl font-bold mb-6">

Newsletter

</h3>

<p class="text-gray-400 mb-5">

Stay updated with new features.

</p>

<div class="flex flex-col sm:flex-row gap-2 sm:gap-0">

<input
type="email"
placeholder="Email Address"
class="w-full rounded-xl sm:rounded-l-xl sm:rounded-r-none px-4 py-3 text-black outline-none">

<button class="bg-secondary px-6 py-3 rounded-xl sm:rounded-r-xl sm:rounded-l-none hover:bg-emerald-600 transition">

Subscribe

</button>

</div>

</div>

</div>

<div class="border-t border-slate-700 mt-16 pt-8 text-center text-gray-400">
    © 2026 AttendEase. All Rights Reserved. Made with ❤ by
    <a href="https://mohitaggarwal.vercel.app" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300">
        Mohit Aggarwal
    </a>
</div>

</div>

</footer>

<script src="js/custom-cursor.js"></script>

</body>
</html>