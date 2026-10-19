<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Reports</title>
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
    <h1>Reports</h1>
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
      <h2>System Reports & Analytics</h2>
      <p>View participation trends, user activity, and event performance metrics.</p>

      <!-- Summary Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <i class="fas fa-calendar-check"></i>
          <h3>Events This Month</h3>
          <p><strong>8</strong></p>
        </div>
        <div class="stat-card">
          <i class="fas fa-users"></i>
          <h3>Total Participants</h3>
          <p><strong>1,246</strong></p>
        </div>
        <div class="stat-card">
          <i class="fas fa-chart-line"></i>
          <h3>Engagement Rate</h3>
          <p>72%</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-file-export"></i>
          <h3>Export</h3>
          <p><a href="#" onclick="alert('CSV exported!')" style="color: var(--maroon); text-decoration: underline;">Download</a></p>
        </div>
      </div>

      <!-- Staff & Student Event Stats -->
      <h3>Top Event Organizers (Staff & Students)</h3>
      <div style="margin: 1.2rem 0; background: white; padding: 1.2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="border-bottom: 2px solid var(--maroon);">
              <th style="text-align: left; padding: 0.5rem;">Name</th>
              <th style="text-align: left; padding: 0.5rem;">Role</th>
              <th style="text-align: center; padding: 0.5rem;">Events Created</th>
              <th style="text-align: center; padding: 0.5rem;">Avg. Participants</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Dr. Lim</td>
              <td style="padding: 0.6rem;"><span style="color: var(--maroon);">Staff</span></td>
              <td style="padding: 0.6rem; text-align: center;">5</td>
              <td style="padding: 0.6rem; text-align: center;">182</td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Ahmad Z.</td>
              <td style="padding: 0.6rem;"><span style="color: #B22222;">Student</span></td>
              <td style="padding: 0.6rem; text-align: center;">3</td>
              <td style="padding: 0.6rem; text-align: center;">94</td>
            </tr>
            <tr>
              <td style="padding: 0.6rem;">Prof. Tan</td>
              <td style="padding: 0.6rem;"><span style="color: var(--maroon);">Staff</span></td>
              <td style="padding: 0.6rem; text-align: center;">4</td>
              <td style="padding: 0.6rem; text-align: center;">210</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Participant Data per Event -->
      <h3>Top Events by Participation</h3>
      <div style="margin: 1.2rem 0; background: white; padding: 1.2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="border-bottom: 2px solid var(--maroon);">
              <th style="text-align: left; padding: 0.5rem;">Event Name</th>
              <th style="text-align: left; padding: 0.5rem;">Date</th>
              <th style="text-align: center; padding: 0.5rem;">Participants</th>
              <th style="text-align: center; padding: 0.5rem;">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Career Fair 2026</td>
              <td style="padding: 0.6rem;">15 Jan 2026</td>
              <td style="padding: 0.6rem; text-align: center;">428</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: green;">Completed</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 0.6rem;">Hackathon 2026</td>
              <td style="padding: 0.6rem;">03 Mar 2026</td>
              <td style="padding: 0.6rem; text-align: center;">215</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: #B22222;">Upcoming</span></td>
            </tr>
            <tr>
              <td style="padding: 0.6rem;">Art Exhibition</td>
              <td style="padding: 0.6rem;">22 Feb 2026</td>
              <td style="padding: 0.6rem; text-align: center;">189</td>
              <td style="padding: 0.6rem; text-align: center;"><span style="color: #B22222;">Upcoming</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
  <button id="btnMonthly" class="action-btn"><i class="fas fa-calendar"></i> Monthly Summary</button>
  <button id="btnParticipants" class="action-btn"><i class="fas fa-users"></i> View Participant Data</button>
  <a href="index.php" class="action-btn" style="background: #666;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- Containers for dynamic content -->
<div id="monthlyReport" style="display: none; margin-top: 2rem; background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08);">
  <h3 style="color: var(--maroon-dark); margin-bottom: 1rem;"><i class="fas fa-chart-bar"></i> Monthly Summary: January 2026</h3>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 1.5rem;">
    <div style="text-align: center; padding: 1rem; background: #f8f4f4; border-radius: 6px;">
      <div style="font-size: 1.8rem; color: var(--maroon);">8</div>
      <div>Events Held</div>
    </div>
    <div style="text-align: center; padding: 1rem; background: #f0f8f0; border-radius: 6px;">
      <div style="font-size: 1.8rem; color: green;">1,246</div>
      <div>Total Participants</div>
    </div>
    <div style="text-align: center; padding: 1rem; background: #fff0f5; border-radius: 6px;">
      <div style="font-size: 1.8rem; color: #B22222;">72%</div>
      <div>Engagement Rate</div>
    </div>
  </div>
  <h4 style="color: var(--maroon-dark); margin: 1rem 0;">Top Events</h4>
  <ul style="padding-left: 1.2rem; color: #555;">
    <li>Career Fair – 428 participants</li>
    <li>Hackathon – 215 participants</li>
    <li>Art Exhibition – 189 participants</li>
  </ul>
</div>

<div id="participantData" style="display: none; margin-top: 2rem; background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08);">
  <h3 style="color: var(--maroon-dark); margin-bottom: 1rem;"><i class="fas fa-user-friends"></i> Participant Data: Career Fair 2026</h3>
  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
      <thead>
        <tr style="background: var(--maroon); color: white;">
          <th style="padding: 0.7rem; text-align: left;">Name</th>
          <th style="padding: 0.7rem; text-align: left;">Student ID</th>
          <th style="padding: 0.7rem; text-align: left;">Program</th>
          <th style="padding: 0.7rem; text-align: left;">Email</th>
          <th style="padding: 0.7rem; text-align: left;">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid #eee;">
          <td style="padding: 0.7rem;">Ali Rahman</td>
          <td style="padding: 0.7rem;">SD23001</td>
          <td style="padding: 0.7rem;">Computer Science</td>
          <td style="padding: 0.7rem;">ali@student.unifest.edu</td>
          <td style="padding: 0.7rem;"><span style="color: green;">Attended</span></td>
        </tr>
        <tr style="border-bottom: 1px solid #eee;">
          <td style="padding: 0.7rem;">Siti Aminah</td>
          <td style="padding: 0.7rem;">SD23015</td>
          <td style="padding: 0.7rem;">Information Technology</td>
          <td style="padding: 0.7rem;">siti@student.unifest.edu</td>
          <td style="padding: 0.7rem;"><span style="color: #B22222;">Registered</span></td>
        </tr>
        <tr style="border-bottom: 1px solid #eee;">
          <td style="padding: 0.7rem;">Tan Wei</td>
          <td style="padding: 0.7rem;">ST22089</td>
          <td style="padding: 0.7rem;">Software Engineering</td>
          <td style="padding: 0.7rem;">wei@student.unifest.edu</td>
          <td style="padding: 0.7rem;"><span style="color: green;">Attended</span></td>
        </tr>
      </tbody>
    </table>
  </div>
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

    // === NEW: Report Interaction ===
    const btnMonthly = document.getElementById('btnMonthly');
    const btnParticipants = document.getElementById('btnParticipants');
    const monthlyReport = document.getElementById('monthlyReport');
    const participantData = document.getElementById('participantData');

    if (btnMonthly && monthlyReport) {
      btnMonthly.addEventListener('click', () => {
        monthlyReport.style.display = 'block';
        participantData.style.display = 'none'; 
      });
    }

    if (btnParticipants && participantData) {
      btnParticipants.addEventListener('click', () => {
        participantData.style.display = 'block';
        monthlyReport.style.display = 'none'; 
      });
    }
  });
</script>
</body>
</html>