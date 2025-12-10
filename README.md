# RentLet Blog System

A PHP & MySQL based blog with user roles (Admin, Editor, User), authentication, CRUD operations, profile management, and responsive Bootstrap 5 UI.

## Features
- 🔑 Authentication (login with username or mobile + password)
- 👤 Roles: Admin, Editor, User
  - Admin: Manage all posts & users
  - Editor: Add/edit/delete own posts (created by Admin)
  - User: View posts only, manage own account
- 📂 Account management (update username, name, mobile, password, profile pic)
- 📝 CRUD operations for posts
- 🔍 Search & pagination
- 🔒 Secure with prepared statements, password hashing
- 🎨 Responsive UI + Bootstrap modals

## Deployment (XAMPP)

1. **Install XAMPP** and start Apache & MySQL.
2. **Extract Project**
   - Copy project folder into `C:/xampp/htdocs/blog`.
3. **Create Database**
   - Open `http://localhost/phpmyadmin/`
   - Create DB `blog`
   - Import `database.sql` (included)
4. **Configure DB**
   - Edit `config.php` if your MySQL credentials differ.
5. **Login**
   - Visit `http://localhost/blog/login.php`
   - ## Demo Accounts
    - Admin → **admin / Admin@1**
    - Editor → **editor1 / Editor@1**
    - Editor → **editor2 / Editor@2**
    - User → **user1 / User@1**
    - User → **user2 / User@2**
    - User  → **user3 / User@3**
6. **Roles**
   - Admin creates Editors.
   - Users register themselves.