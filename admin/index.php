<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Admin Dashboard</title>
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
    <h1>Admin Dashboard</h1>
  </header>

  <div class="overlay" id="overlay"></div>

  <div class="admin-container">
    <aside class="sidebar">
      <a href="index.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Dashboard</a>
      <a href="users.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">Manage Users</a>
      <a href="events.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'events.php' ? 'active' : '' ?>">Manage Events</a>
      <a href="create_event.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'create_event.php' ? 'active' : '' ?>">Create Event</a>
      <a href="reports.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>">Reports</a>
      <a href="logout.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'logout.php' ? 'active' : '' ?>">Logout</a>
    </aside>

    <main class="main-content">
      <h2>System Overview</h2>

      <div class="stats-grid">
        <a href="users.php" class="stat-card">
          <i class="fas fa-users"></i>
          <h3>Total Users</h3>
          <p><strong>126</strong></p>
        </a>
        <a href="events.php" class="stat-card">
          <i class="far fa-calendar-alt"></i>
          <h3>Events Pending Approval</h3>
          <p><strong>4</strong></p>
        </a>
        <a href="reports.php" class="stat-card">
          <i class="fas fa-user-check"></i>
          <h3>Participants This Month</h3>
          <p><strong>328</strong></p>
        </a>
        <a href="reports.php" class="stat-card">
          <i class="fas fa-chart-pie"></i>
          <h3>Event Categories</h3>
          <p>6 Active</p>
        </a>
      </div>

      <h2>Quick Actions</h2>
      <div class="action-links">
        <a href="users.php" class="action-btn"><i class="fas fa-users"></i> Manage Users</a>
        <a href="events.php" class="action-btn"><i class="fas fa-bell"></i> Approve Events</a>
        <a href="create_event.php" class="action-btn"><i class="fas fa-plus"></i> Create Event</a>
        <a href="reports.php" class="action-btn"><i class="fas fa-file-alt"></i> View Reports</a>
      </div>

      <h2>Recent Activity</h2>
      <p style="margin: 0.8rem 0; color: #555;">
        • New event "Hackathon 2026" submitted by Dr. Lim (Staff) — <a href="events.php">Review</a><br>
        • 12 participants registered for "Career Fair"<br>
        • User "Ahmad Z." (Student) promoted to Event Coordinator
      </p>
    </main>
  </div>

  <footer>
    <hr>
    <p>&copy; UniFest Admin Panel | <?= date('Y') ?></p>
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