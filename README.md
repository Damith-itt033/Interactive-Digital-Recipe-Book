# Savory Share - Digital Recipe Book

Hello! This is our group project for the **ICT 1209: Web Technologies** course. We are Group 41 from the 2024 Batch (BICT).

Our project is a simple recipe website where people can find different food recipes, create accounts, and share their own recipes.

## Group Members
* W.M.N.D. Wicramasooriya (ITT/2024/118)
* D.M.D.T.R. Dissanayaka (ITT/2024/033)

## What we did in Phase 1 (Frontend)
In the first phase, we only focused on the design of the website. 
* We created the Home page, Recipe Dashboard, and Contact Us page.
* We used HTML and Bootstrap 5 to make the layout look good on both mobile phones and laptops.
* We used custom CSS for colors and styling.
* We added simple JavaScript to make things like menus and search filters work.

## What we did in Phase 2 (Backend & Database)
In the second phase, we made the website actually work by adding a backend and a database.
* We used **PHP** for the backend coding.
* We created a **MySQL database** using XAMPP to store the recipes, user accounts, and messages.
* Now, the recipes load directly from the database instead of plain HTML.
* We also added a Login and Registration system so users can create their own accounts safely.
* When someone sends a message through our Contact page, it now saves directly to the database.

## Technologies We Used
* HTML, CSS, Bootstrap 5
* JavaScript
* PHP & MySQL
* XAMPP

## How to test our project locally
1. Download and install XAMPP.
2. Put our project folder inside the `htdocs` folder in XAMPP (usually `C:\xampp\htdocs\`).
3. Open XAMPP and start Apache and MySQL.
4. Go to `http://localhost/phpmyadmin` and import our database SQL file.
5. Go to your browser and type `http://localhost/Your-Folder-Name` to view the website.