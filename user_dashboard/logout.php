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
      <a href="index.php" class="menu-item">Dashboard</a>
      <a href="profile.php" class="menu-item">My Profile</a>
      <a href="logout.php" class="menu-item active">Logout</a>
    </aside>

    <main class="main-content">
      <h2>See You Soon!</h2>
      <div style="max-width: 600px; margin: 1.5rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08); text-align: center;">
        <div style="font-size: 3rem; color: var(--maroon); margin-bottom: 1rem;">
          <i class="fas fa-sign-out-alt"></i>
        </div>
        <p style="font-size: 1.1rem;">
          You've been logged out of your student account.<br>
          Thank you for being part of UniFest!
        </p>
      </div>
      <div style="text-align: center; margin-top: 1.5rem;">
        <a href="../index.html" class="action-btn"><i class="fas fa-home"></i> Back to UniFest</a>
      </div>
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