# SkillConnect – Student Skill Exchange Platform

SkillConnect is a web-based student skill exchange platform that helps students connect with each other based on the skills they know and the skills they want to learn.

The platform allows students to create profiles, manage their skills, find suitable skill partners, send connection requests, and build learning connections.

## 🚀 Features

- Student registration and login
- Secure password hashing
- Student profile management
- Add skills you know
- Add skills you want to learn
- Skill-based student matching
- Send connection requests
- Accept or reject connection requests
- View connected students
- Session-based authentication
- Logout functionality
- Responsive web interface

## 🛠️ Technology Stack

### Frontend
- HTML
- CSS

### Backend
- PHP

### Database
- MariaDB / MySQL

### Cloud & Server
- AWS EC2
- Amazon Linux
- Nginx

## 🏗️ System Architecture

Internet
   ↓
AWS EC2
   ↓
Nginx Web Server
   ↓
PHP Application
   ↓
MariaDB Database

## 📂 Project Structure

skillconnect/
│
├── config/
│   ├── database.php
│   └── database.example.php
│
├── database/
│   └── skillconnect.sql
│
├── css/
│
├── index.php
├── register.php
├── login.php
├── logout.php
├── dashboard.php
├── profile.php
├── skills.php
├── matches.php
├── connect.php
└── connections.php

## 🗄️ Database

The project uses four main tables:

- `users` – stores student account information
- `skills` – stores available skills
- `user_skills` – connects students with their known/wanted skills
- `connections` – manages student connection requests

The database structure is available in:

`database/skillconnect.sql`

## ⚙️ Installation

1. Clone the repository.

2. Create the SkillConnect database using:

```bash
mysql -u root -p < database/skillconnect.sql
