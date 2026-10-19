<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Manage Users</title>
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
    <h1>Manage Users</h1>
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
      <h2>Manage System Users</h2>
      <p>Admins can view, edit, suspend, or promote users.</p>

      <div style="margin: 1.5rem 0;">
        <table style="width:100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
          <thead>
            <tr style="background: var(--maroon); color: white;">
              <th style="padding: 0.8rem; text-align: left;">Name</th>
              <th style="padding: 0.8rem; text-align: left;">Email</th>
              <th style="padding: 0.8rem; text-align: left;">Role</th>
              <th style="padding: 0.8rem; text-align: left;">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.8rem;">Dr. Lim</td>
              <td style="padding: 0.8rem;">lim@unifest.edu</td>
              <td style="padding: 0.8rem;"><span style="color: var(--maroon); font-weight: bold;">Staff</span></td>
              <td style="padding: 0.8rem;">
                <button class="action-promote" style="background: var(--maroon); color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">Promote</button>
                <button class="action-edit" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; margin-left: 0.3rem; cursor: pointer;">Edit</button>
              </td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.8rem;">Ahmad Z.</td>
              <td style="padding: 0.8rem;">ahmad@student.unifest.edu</td>
              <td style="padding: 0.8rem;"><span style="color: #B22222;">Student</span></td>
              <td style="padding: 0.8rem;">
                <button class="action-suspend" style="background: #B22222; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">Suspend</button>
              </td>
            </tr>
          </tbody>
        
        </table>
      </div>

      <a href="index.php" class="action-btn" style="margin-top: 1rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
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

       document.querySelectorAll('.action-promote').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.textContent = 'Promoted!';
        btn.style.background = 'green';
        btn.style.cursor = 'default';
        btn.disabled = true;
      });
    });

    document.querySelectorAll('.action-suspend').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.textContent = 'Suspended';
        btn.style.background = 'orange';
        btn.style.cursor = 'default';
        btn.disabled = true;
      });
    });

    });
  </script>
</body>
</html>