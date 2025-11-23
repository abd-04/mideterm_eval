# 🎓 Final Evaluation Guide

**Project:** Real Estate Portal
**Status:** Ready for Demo
**Database:** SQLite (File-based)

---

## 🛠️ Prerequisites (Do this BEFORE the demo)

1.  **XAMPP Installed:** Ensure XAMPP is installed at `D:\xampp`.
2.  **DB Browser for SQLite:**
    *   Download the **Portable Version** here: [https://sqlitebrowser.org/dl/](https://sqlitebrowser.org/dl/)
    *   Unzip it and keep the folder ready on your desktop.
    *   *You will use this to show the database tables.*

---

## 🚀 Step 1: Turn On the Project

1.  Go to your project folder: `realestate_portal`.
2.  Double-click the file named **`start_server.bat`**.
3.  **A black window will appear.**
    *   ⚠️ **DO NOT CLOSE THIS WINDOW.** If you close it, the site stops working.
    *   Minimize it if you want, but keep it running.
4.  Your browser should automatically open to: `http://localhost:8081`

---

## 📊 Step 2: View the Database (Two Ways)

### Method A: The "Live View" Page (Easiest)
I created a special page that shows the raw database table in your browser. This is great for quickly proving that data is being saved.

*   **Link:** [http://localhost:8081/view_properties.php](http://localhost:8081/view_properties.php)
*   **How to demo:**
    1.  Open this link in a new tab.
    2.  Go to the main site and **Add a Property**.
    3.  Come back to this tab and **Refresh**.
    4.  Show the evaluator the new row that just appeared!

### Method B: Using "DB Browser" (Professional)
If they ask to see the actual database file structure:

1.  Open **DB Browser for SQLite**.
2.  Click **"Open Database"**.
3.  Navigate to your project folder and select: `realestate_portal/database.sqlite`.
4.  Click the **"Browse Data"** tab.
5.  Select **"properties"** (or "users") from the Table dropdown.
6.  You will see all the data stored in the file.

---

## 🔑 Login Details

*   **Admin User:** `admin@realestate.com` / `admin123`
*   **Regular User:** `john@example.com` / `password`

---

## ❓ Troubleshooting (Don't Panic!)

*   **"Site can't be reached":**
    *   Check if the black `start_server.bat` window is open. If not, double-click it again.
*   **"Property not adding":**
    *   Make sure you fill in all required fields.
    *   You *don't* need to upload an image (I fixed the default image issue), but you *can* if you want.
*   **"Where is the database file?":**
    *   It is the file named `database.sqlite` in your project folder.
