# Real Estate Portal - Windows Setup Guide

## Prerequisites
- **XAMPP** installed (Detected at: `D:\xampp`)
- **Apache** service running in XAMPP Control Panel
- **MySQL** is **NOT** required (We are using SQLite)

## Automated Setup (Recommended)
1. Open PowerShell in this directory.
2. Run the installation script:
   ```powershell
   .\install_windows.ps1
   ```
3. Follow the on-screen prompts.

## Manual Setup

### 1. Database Setup
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Click **New** in the left sidebar.
4. Enter `realestate_portal` as the database name and click **Create**.
5. Select the `realestate_portal` database.
6. Click the **Import** tab.
7. Click **Choose File** and select `realestate_portal.sql` from this project folder.
8. Click **Import** at the bottom.

### 2. Project Setup
1. Copy the `realestate_portal` folder to your XAMPP `htdocs` directory:
   - Source: `[Current Project Path]\realestate_portal`
   - Destination: `C:\xampp\htdocs\realestate_portal`
   
   *Note: Ensure the folder structure is `htdocs\realestate_portal\public\index.php`*

### 3. Access the Application
Open your browser and visit:
[http://localhost/realestate_portal/public](http://localhost/realestate_portal/public)

## Default Credentials

**Admin:**
- Email: `admin@realestate.com`
- Password: `admin123`

**User:**
- Email: `john@example.com`
- Password: `password`
