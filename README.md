# CRUD Application with Advanced Features (Task-3)

Enhanced CRUD app with:
- Search functionality for posts
- Pagination (5 posts per page)
- UI improvements using Bootstrap 5

## Features
- User authentication (register/login)
- Create, Read, Update, Delete posts
- Search and pagination on posts
- Responsive UI with Bootstrap
# Blog App — Task-4 (Security + Responsive UI)

Features:
- Secure auth (password_hash, prepared statements)
- User roles (admin/editor/user) + role-based access
- CRUD on posts (owner can edit/delete; admin can manage all; editor can edit/delete only his/her posts)
- Search + Pagination (8 per page)
- Responsive UI with Bootstrap 5 + custom CSS
- Admin page to manage users

## Demo Accounts
- Admin → **admin / Admin@1**
- Admin → **editor1 / Editor@1**
- Admin → **editor2 / Editor@2**
- Admin → **user1 / User@1**
- Admin → **user2 / User@2**
- User  → **user3 / User@3**

## Setup
1) Create DB & import `database.sql` via phpMyAdmin.
2) Put this folder in your web root: `htdocs/blog`.
3) Visit: `http://localhost/blog/`