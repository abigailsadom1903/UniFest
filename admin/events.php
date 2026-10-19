<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Manage Events</title>
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
    <h1>Manage Events</h1>
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
      <h2>Manage All Events</h2>
      <p>Approve, highlight, archive, or edit event listings submitted by staff and students.</p>

      <div style="margin: 1.5rem 0;">
        <table style="width:100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
          <thead>
            <tr style="background: var(--maroon); color: white;">
              <th style="padding: 0.8rem; text-align: left;">Event Name</th>
              <th style="padding: 0.8rem; text-align: left;">Category</th>
              <th style="padding: 0.8rem; text-align: left;">Status</th>
              <th style="padding: 0.8rem; text-align: left;">Actions</th>
            </tr>
          </thead>

        <tbody>
          <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 0.8rem;">Hackathon 2026</td>
            <td style="padding: 0.8rem;">Technology</td>
            <td style="padding: 0.8rem;">
              <span class="status-badge" data-status="pending" style="font-weight: bold; color: #B22222;">Pending Approval</span>
            </td>
            <td style="padding: 0.8rem;">
              <button class="event-action" data-action="approve" data-original-text="Approve" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">Approve</button>
              <button class="event-action" data-action="reject" data-original-text="Reject" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; margin-left: 0.3rem; cursor: pointer;">Reject</button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 0.8rem;">Career Fair</td>
            <td style="padding: 0.8rem;">Career</td>
            <td style="padding: 0.8rem;">
              <span class="status-badge" data-status="pending" style="font-weight: bold; color: #B22222;">Pending Approval</span>
            </td>
            <td style="padding: 0.8rem;">
              <button class="event-action" data-action="highlight" data-original-text="Highlight" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">Highlight</button>
              <button class="event-action" data-action="archive" data-original-text="Archive" style="background: #888; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; margin-left: 0.3rem; cursor: pointer;">Archive</button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 0.8rem;">Art Exhibition</td>
            <td style="padding: 0.8rem;">Culture</td>
            <td style="padding: 0.8rem;">
              <span class="status-badge" data-status="pending" style="font-weight: bold; color: #B22222;">Pending Approval</span>
            </td>
            <td style="padding: 0.8rem;">
              <button class="event-action" data-action="approve" data-original-text="Approve" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">Approve</button>
              <button class="event-action" data-action="reject" data-original-text="Reject" style="background: #666; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; margin-left: 0.3rem; cursor: pointer;">Reject</button>
            </td>
          </tr>
        </tbody>

        </table>
      </div>

      <a href="index.php" class="action-btn" style="margin-top: 1.5rem;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
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

      actionConfig = {
        approve: { color: 'green', statusText: 'Approved' },
        reject: { color: 'orange', statusText: 'Rejected' },
        highlight: { color: '#FF69B4', statusText: 'Highlighted' },
        archive: { color: '#696969', statusText: 'Archived' }
      };

      document.querySelectorAll('.event-action').forEach(button => {
        button.addEventListener('click', function() {
          const row = this.closest('tr');
          const action = this.getAttribute('data-action');
          const config = actionConfig[action];

          if (!config) return;

          row.querySelectorAll('.event-action').forEach(btn => {
            const origText = btn.getAttribute('data-original-text');
            let bgColor = '#666'; 
            if (btn.getAttribute('data-action') === 'approve' || btn.getAttribute('data-action') === 'highlight') {
              bgColor = '#666';
            } else if (btn.getAttribute('data-action') === 'archive') {
              bgColor = '#888';
            }
            btn.textContent = origText;
            btn.style.background = bgColor;
            btn.disabled = false;
            btn.style.cursor = 'pointer';
          });

          this.textContent = config.statusText;
          this.style.background = config.color;
          this.disabled = true;
          this.style.cursor = 'default';

          const statusBadge = row.querySelector('.status-badge');
          if (statusBadge) {
            statusBadge.textContent = config.statusText;
            statusBadge.style.color = config.color;
            statusBadge.setAttribute('data-status', action);
          }

        });
      });
    });
  </script>

</body>
</html>