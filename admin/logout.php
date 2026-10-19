<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Logout</title>
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
    <h1>Session Ended</h1>
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
      <h2>You Have Been Logged Out</h2>

      <div style="max-width: 600px; margin: 1.5rem 0; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08); text-align: center;">
        <div style="font-size: 3rem; color: var(--maroon); margin-bottom: 1rem;">
          <i class="fas fa-lock"></i>
        </div>
        <p style="font-size: 1.1rem; line-height: 1.6;">
          Your admin session has been securely terminated.<br>
          For security, all active tokens have been invalidated.
        </p>
      </div>

      <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
        <a href="../index.html" class="action-btn" style="background: var(--maroon-dark);">
          <i class="fas fa-home"></i> Back to Public Site
        </a>
        <a href="index.php" class="action-btn">
          <i class="fas fa-tachometer-alt"></i> Re-enter Dashboard
        </a>
      </div>

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