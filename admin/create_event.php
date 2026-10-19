<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - Create Event</title>
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
    <h1> Create Event</h1>
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
      <h2>Create New Event</h2>
      <p>Fill in the details below to add a new event to UniFest.</p>

    <form id="createEventForm" style="max-width: 700px; margin-top: 1.5rem; background: white; padding: 1.8rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08);">
      <!-- Event Name -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="event_name" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Event Name *</label>
        <input type="text" id="event_name" placeholder="e.g., Annual Tech Symposium" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
        <div class="error-msg" id="error_event_name" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
      </div>

      <!-- Category -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="category" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Category *</label>
        <select id="category" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; background: white;">
          <option value="">-- Select Category --</option>
          <option value="academic">Academic</option>
          <option value="career">Career</option>
          <option value="culture">Culture & Arts</option>
          <option value="sports">Sports</option>
          <option value="technology">Technology</option>
          <option value="community">Community Service</option>
        </select>
        <div class="error-msg" id="error_category" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
      </div>

      <!-- Date -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="date" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Event Date *</label>
        <input type="date" id="date" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
        <div class="error-msg" id="error_date" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
      </div>

      <!-- Venue -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="venue" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Venue *</label>
        <input type="text" id="venue" placeholder="e.g., Main Hall, Block C" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
        <div class="error-msg" id="error_venue" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
      </div>

      <!-- Mode -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="mode" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Mode</label>
        <select id="mode" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; background: white;">
          <option value="physical">Physical</option>
          <option value="online">Online</option>
          <option value="hybrid">Hybrid</option>
        </select>
      </div>

      <!-- Poster -->
      <div class="form-group" style="margin-bottom: 1.2rem;">
        <label for="poster" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Event Poster (Optional)</label>
        <input type="file" id="poster" accept="image/*" style="width: 100%; padding: 0.4rem; border: 1px dashed #ccc; border-radius: 4px; background: #fafafa;" />
      </div>

      <!-- Description -->
      <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="description" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Description *</label>
        <textarea id="description" rows="4" placeholder="Briefly describe the event, objectives, and who should attend..." style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"></textarea>
        <div class="error-msg" id="error_description" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
      </div>

      <!-- Buttons -->
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button type="submit" style="background: var(--maroon); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 600; cursor: pointer;">✅ Create Event</button>
        <button type="button" onclick="window.location.href='index.php'" style="background: #666; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer;">← Cancel</button>
      </div>
    </form>

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

    // === Form Validation & Submission ===
    const form = document.getElementById('createEventForm');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    if (form && submitBtn) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
          submitBtn.textContent = 'Event Created!';
          submitBtn.disabled = true;
          submitBtn.style.background = '#ccc';
          submitBtn.style.color = '#666';
          submitBtn.style.cursor = 'not-allowed';

          // Optional: Reset form after delay (not typical for "create", but you can uncomment if needed)
          // setTimeout(() => {
          //   form.reset();
          //   document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');
          //   document.querySelectorAll('.form-group input, .form-group textarea, .form-group select').forEach(el => {
          //     el.style.borderColor = '#ccc';
          //     el.style.borderWidth = '1px';
          //   });
          //   submitBtn.textContent = '✅ Create Event';
          //   submitBtn.disabled = false;
          //   submitBtn.style.background = '';
          //   submitBtn.style.color = '';
          // }, 5000);
        }
      });

      // Real-time validation
      const inputs = ['event_name', 'category', 'date', 'venue', 'description'];
      inputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          el.addEventListener('input', () => hideError(id));
          el.addEventListener('blur', () => validateField(id));
        }
      });
    }

    // --- Validation Functions ---
    function validateForm() {
      let isValid = true;
      ['event_name', 'category', 'date', 'venue', 'description'].forEach(id => {
        if (!validateField(id)) isValid = false;
      });
      return isValid;
    }

    function validateField(fieldId) {
      const el = document.getElementById(fieldId);
      let value = el.value.trim();
      let error = '';

      if (fieldId === 'event_name') {
        if (!value) error = 'Event name is required.';
        else if (value.length < 3) error = 'Event name must be at least 3 characters.';
      } 
      else if (fieldId === 'category') {
        if (!value) error = 'Please select a category.';
      } 
      else if (fieldId === 'date') {
        if (!value) error = 'Event date is required.';
        else {
          const selectedDate = new Date(value);
          const today = new Date();
          today.setHours(0,0,0,0);
          if (selectedDate < today) error = 'Event date must be today or in the future.';
        }
      } 
      else if (fieldId === 'venue') {
        if (!value) error = 'Venue is required.';
        else if (value.length < 2) error = 'Venue name is too short.';
      } 
      else if (fieldId === 'description') {
        if (!value) error = 'Description is required.';
        else if (value.length < 10) error = 'Description must be at least 10 characters.';
      }

      if (error) {
        showError(fieldId, error);
        return false;
      } else {
        hideError(fieldId);
        return true;
      }
    }

    function showError(fieldId, message) {
      document.getElementById('error_' + fieldId).textContent = message;
      document.getElementById('error_' + fieldId).style.display = 'block';
      document.getElementById(fieldId).style.borderColor = 'var(--maroon)';
      document.getElementById(fieldId).style.borderWidth = '2px';
    }

    function hideError(fieldId) {
      document.getElementById('error_' + fieldId).style.display = 'none';
      document.getElementById(fieldId).style.borderColor = '#ccc';
      document.getElementById(fieldId).style.borderWidth = '1px';
    }
  });
</script>
</body>
</html>