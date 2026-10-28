# UniFest: Campus Event Management System

A PHP and MySQL web platform that helps students, staff and the public organise, discover and register for university events, with an administrator workflow for approving events and managing users.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Problem Statement](#problem-statement)
- [Features](#features)
- [User Roles](#user-roles)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Architecture](#architecture)
- [Database](#database)
- [Key Implementation Decisions](#key-implementation-decisions)
- [Installation](#installation)
- [Security Notes](#security-notes)
- [Known Limitations](#known-limitations)
- [Author](#author)

---

## Project Overview

UniFest is a web-based event management system for a university campus. Organisers publish events with full details and an optional poster. Administrators review and approve them before they appear to participants. Participants browse events, register, receive notifications and leave feedback after attending. Administrators can export participant lists and view reports.

## Problem Statement

University events are often promoted in many places at once and managed by hand. Organisers struggle to track registrations and capacity, participants miss updates, and there is no structured way to collect feedback or control which events are visible to the public versus only to the university community.

UniFest brings these tasks into one system with approval, visibility controls, registration tracking, notifications and feedback.

## Features

**For participants**
- Register and log in with a profile, including interest categories.
- Browse approved events and view full event details.
- Register for events and manage registrations.
- Receive in-app notifications (registration confirmations and organiser updates).
- Submit ratings and comments after an event.

**For organisers**
- Create, edit and delete events with type, venue, date, time, mode, fee, capacity and contact person.
- Upload an event poster.
- View participants for each event and export the list.
- Read feedback submitted for an event.

**For administrators**
- Approve or archive events (`pending`, `approved`, `archived`).
- Manage users, promote users and suspend accounts.
- Generate administrative reports.

**Event options**
- Types: Workshop, Seminar, Competition, Festival, Sport, Course.
- Modes: Physical, Online, Hybrid.
- Visibility: public, or university-only.

## User Roles

| Role | Access |
| --- | --- |
| Admin | Full control: approvals, user management, reports |
| Staff | Create and manage events, view participants |
| Student | Browse, register, give feedback |
| Public | Browse public events and register |

## Technology Stack

| Layer | Technology |
| --- | --- |
| Language | PHP 8 |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Local server | XAMPP (Apache, MariaDB, phpMyAdmin) |
| Version control | Git and GitHub |

## Project Structure

```
UniFest/
├── admin/             # Administrator pages
├── assets/            # CSS, JavaScript, images, uploaded posters
├── config/            # Database configuration
├── feedback/          # Feedback handling
├── includes/          # Shared components
├── user_dashboard/    # Participant dashboard
├── index.php          # Landing page
├── login.php / register.php / logout.php
├── dashboard.php
├── create_event.php / edit_event.php / delete_event.php
├── event_details.php
├── view_participants.php / export_participants.php
├── view_feedback.php
├── notifications.php
├── manage_users.php / manage_user.php / edit_user.php / promote_user.php
├── admin_action.php / admin_reports.php
├── profile.php
└── unifest_db.sql     # Database schema and sample data
```

## Architecture

UniFest is a **procedural, page-per-action PHP application** with shared includes rather than a framework.

- **`config/db.php`** creates a single PDO connection (`$pdo`) that pages include.
- **`includes/`** holds the shared layout (`header.php`, `footer.php`) and helper functions (`functions.php`).
- **Top-level pages and handlers** (for example `create_event.php`, `edit_event.php`, `view_participants.php`, `promote_user.php`) each handle one action and redirect back to a listing page.
- **`admin/`, `user_dashboard/` and `feedback/`** group the administrator, participant and feedback pages.

```
Browser ──▶ page / handler (.php)
              │  include config/db.php  ($pdo)
              │  include includes/header.php, functions.php
              ▼
           MySQL (UniFest_db)
              │
              ▼
   addNotification() / sendEmail()  ──▶  notifications table / email_log.txt
```

The `role` stored on each user record (admin, staff, student, public) determines which features a user can use.

## Database

Database name: `UniFest_db`

| Table | Purpose |
| --- | --- |
| `users` | Accounts with category, role (`admin`, `staff`, `student`, `public`), interests and account status |
| `events` | Event details, type, mode, fee, capacity, poster, approval status and visibility |
| `registrations` | Links users to events with a status |
| `feedback` | Ratings and comments per event and user |
| `notifications` | In-app messages for users, with read status |

### Design notes

- **Five InnoDB tables** (`users`, `events`, `registrations`, `feedback`, `notifications`) using `utf8mb4`.
- **Relationships:** a user organises many events and registers for many events; each event has many registrations and feedback entries; each user has many notifications.
- **ENUM columns** keep event type, mode, status (`pending`, `approved`, `archived`), visibility and user role consistent at the database level.
- **Unique `email`** on `users` prevents duplicate accounts.
- **Cascade deletes** on `events`, `feedback` and `notifications` keep related data tidy when a user or event is removed.
- **`registrations` foreign keys have no `ON DELETE` rule**, so a user or event with registrations cannot be deleted until those rows are removed.

## Key Implementation Decisions

- **PDO with prepared statements** and `ERRMODE_EXCEPTION`, so database errors surface as exceptions rather than silent failures.
- **Hashed passwords** are stored in the `password` column.
- **Notification helpers are centralised** in `includes/functions.php` (`addNotification`, `sendEmail`), so every page notifies users the same way.
- **Event lifecycle as an enum** (`pending` → `approved` → `archived`) lets events be reviewed before publication and retired without deleting history.
- **A visibility flag** shows an event to everyone or only to university members.
- **Database-enforced consistency** through ENUMs and foreign keys rather than relying only on application checks.

## Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any stack with PHP 8, Apache and MySQL/MariaDB)
- Git

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/abigailsadom1903/UniFest.git
   ```

2. **Copy the project into your web root**, for example:

   ```
   C:\xampp\htdocs\UniFest
   ```

3. **Start Apache and MySQL** from the XAMPP Control Panel.

4. **Create and import the database**
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Create a new database named `UniFest_db`.
   - Select it, choose **Import**, and upload `unifest_db.sql`.

5. **Check the connection settings** in the `config` folder and confirm they match your local MySQL host, user and password.

6. **Open the application**

   [http://localhost/UniFest/](http://localhost/UniFest/)

7. **Sign in.** The sample database includes an administrator account and sample staff, student and public accounts for testing. See the Security Notes below.

## Security Notes

- The sample database ships with demo accounts for local testing only. Change their passwords, or remove them, before any real deployment.
- Do not publish real credentials in the repository or in documentation.
- Keep uploaded posters and configuration files out of version control when they contain private information.

## Known Limitations

- **Emails are simulated.** `sendEmail()` writes each message to `email_log.txt` instead of sending it. Integrate an SMTP service (for example PHPMailer) for real delivery.
- **Default database credentials** (`root`, empty password) are set in `config/db.php` for XAMPP. Change them for any other environment.
- **Seeded accounts share one password hash.** Change these passwords after importing the database.
- **Some seeded `registrations` rows have a blank `status`.** Valid values are `confirmed` and `cancelled`.
- **Not hardened for production.** HTTPS, CSRF tokens, rate limiting and automated tests are not included.

## Author

**Abigail Alau R. Sadom**
Bachelor of Computer Science (Software Engineering), Universiti Malaysia Sabah
GitHub: [@abigailsadom1903](https://github.com/abigailsadom1903)
