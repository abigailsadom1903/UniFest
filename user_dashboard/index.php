<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Student Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <header class="hero">
    <button class="hamburger" id="menu-toggle">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <h1>My Dashboard</h1>
  </header>

  <div class="overlay" id="overlay"></div>

  <div class="admin-container">
    <aside class="sidebar">
      <a href="index.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Dashboard</a>
      <a href="profile.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>">My Profile</a>
      <a href="logout.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'logout.php' ? 'active' : '' ?>">Logout</a>
    </aside>

    <main class="main-content">
      <h2>Welcome, Ahmad Zulkifli!</h2>
      <p>View your event participation, stats, and upcoming activities.</p>

      <!-- Personal Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <i class="fas fa-calendar-check"></i>
          <h3>Events Participated</h3>
          <p><strong>7</strong></p>
        </div>
        <div class="stat-card">
          <i class="fas fa-user-check"></i>
          <h3>Attendance Rate</h3>
          <p>86%</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-trophy"></i>
          <h3>Top Category</h3>
          <p>Technology</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-hourglass-start"></i>
          <h3>Upcoming Events</h3>
          <p><strong>2</strong></p>
        </div>
      </div>

      <!-- Participation History -->
      <h2>My Event Participation</h2>
      <div style="margin: 1.5rem 0; background: white; padding: 1.2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="border-bottom: 2px solid var(--maroon);">
              <th style="text-align: left; padding: 0.5rem;">Event Name</th>
              <th style="text-align: left; padding: 0.5rem;">Date</th>
              <th style="text-align: left; padding: 0.5rem;">Role</th>
              <th style="text-align: center; padding: 0.5rem;">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Hackathon 2026</td>
              <td style="padding: 0.6rem;">03 Mar 2026</td>
              <td style="padding: 0.6rem;">Participant</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: var(--maroon);">Upcoming</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Career Fair</td>
              <td style="padding: 0.6rem;">15 Jan 2026</td>
              <td style="padding: 0.6rem;">Attendee</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: green;">Attended</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Tech Talk: AI Ethics</td>
              <td style="padding: 0.6rem;">12 Dec 2025</td>
              <td style="padding: 0.6rem;">Attendee</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: green;">Attended</span></td>
            </tr>
            <tr>
              <td style="padding: 0.6rem;">Art Exhibition</td>
              <td style="padding: 0.6rem;">22 Feb 2026</td>
              <td style="padding: 0.6rem;">Volunteer</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: #B22222;">Registered</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <a href="../index.html" class="action-btn" style="margin-top: 1.5rem;"><i class="fas fa-home"></i> Back to Public Site</a>
    </main>
  </div>

  <footer>
    <hr>
    <p>&copy; UniFest Student Portal | <?= date('Y') ?></p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('menu-toggle');
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.getElementById('overlay');
      if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('active');
          overlay.classList.toggle('active');
          toggle.classList.toggle('active');
        });
        overlay.addEventListener('click', () => {
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
          toggle.classList.remove('active');
        });
      }
    });
  </script>
</body>
</html>