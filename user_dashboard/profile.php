<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UniFest - My Profile</title>
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
    <h1>My Profile</h1>
  </header>

  <div class="overlay" id="overlay"></div>

  <div class="admin-container">
    <aside class="sidebar">
      <a href="index.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Dashboard</a>
      <a href="profile.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>">My Profile</a>
      <a href="logout.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'logout.php' ? 'active' : '' ?>">Logout</a>
    </aside>

    <main class="main-content">
      <h2>Personal Information</h2>
      
      <!-- Profile View (Default) -->
      <div id="profileView" style="max-width: 600px; background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <div style="display: flex; align-items: center; margin-bottom: 1.2rem;">
          <div style="width: 80px; height: 80px; background: var(--maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
            <i class="fas fa-user"></i>
          </div>
          <div style="margin-left: 1.2rem;">
            <h3 style="margin: 0; color: var(--maroon-dark);">Ahmad Zulkifli</h3>
            <p style="margin: 0.2rem 0; color: #666;">SD23001</p>
            <p style="margin: 0.2rem 0; color: #666;">Computer Science</p>
          </div>
        </div>

        <div style="margin-top: 1rem; line-height: 1.6;">
          <p><strong>Full Name:</strong> Ahmad Zulkifli bin Abdullah</p>
          <p><strong>Age:</strong> 20</p>
          <p><strong>Birthday:</strong> 15 May 2005</p>
          <p><strong>Gender:</strong> Male</p>
          <p><strong>Email:</strong> ahmad@student.unifest.edu</p>
          <p><strong>Phone:</strong> +60 12-345 6789</p>
        </div>

        <div style="margin-top: 1.5rem;">
          <button id="editBtn" class="action-btn"><i class="fas fa-edit"></i> Edit Profile</button>
        </div>
      </div>

      <!-- Edit Form (Initially Hidden) -->
      <form id="editProfileForm" style="display: none; max-width: 600px; margin-top: 1.5rem; background: white; padding: 1.8rem; border-radius: 8px; box-shadow: 0 3px 8px rgba(0,0,0,0.08);">
        <h3 style="color: var(--maroon-dark); margin-bottom: 1.2rem;"><i class="fas fa-user-edit"></i> Edit Your Details</h3>

        <!-- Full Name -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label for="full_name" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Full Name *</label>
          <input type="text" id="full_name" value="Ahmad Zulkifli bin Abdullah" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
          <div class="error-msg" id="error_full_name" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Age -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label for="age" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Age *</label>
          <input type="number" id="age" value="20" min="15" max="30" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
          <div class="error-msg" id="error_age" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Birthday -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label for="birthday" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Birthday *</label>
          <input type="date" id="birthday" value="2005-05-15" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
          <div class="error-msg" id="error_birthday" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Gender -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Gender *</label>
          <div>
            <label style="margin-right: 1rem;"><input type="radio" name="gender" value="male" checked /> Male</label>
            <label style="margin-right: 1rem;"><input type="radio" name="gender" value="female" /> Female</label>
            <label><input type="radio" name="gender" value="other" /> Other</label>
          </div>
          <div class="error-msg" id="error_gender" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Email -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label for="email" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Email *</label>
          <input type="email" id="email" value="ahmad@student.unifest.edu" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
          <div class="error-msg" id="error_email" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Phone -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
          <label for="phone" style="display: block; margin-bottom: 0.4rem; font-weight: 600; color: var(--maroon-dark);">Phone *</label>
          <input type="tel" id="phone" value="+60123456789" placeholder="+60 12-345 6789" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" />
          <div class="error-msg" id="error_phone" style="color: var(--maroon); font-size: 0.85rem; margin-top: 0.3rem; display: none;"></div>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
          <button type="submit" id="saveBtn" class="action-btn"><i class="fas fa-save"></i> Save Changes</button>
          <button type="button" id="cancelBtn" style="background: #666; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer;"><i class="fas fa-times"></i> Cancel</button>
        </div>
      </form>
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

      // === Toggle Edit Form ===
      const editBtn = document.getElementById('editBtn');
      const profileView = document.getElementById('profileView');
      const editForm = document.getElementById('editProfileForm');
      const cancelBtn = document.getElementById('cancelBtn');

      if (editBtn) {
        editBtn.addEventListener('click', () => {
          profileView.style.display = 'none';
          editForm.style.display = 'block';
        });
      }

      if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
          editForm.style.display = 'none';
          profileView.style.display = 'block';
        });
      }

      // === Form Validation ===
      const form = document.getElementById('editProfileForm');
      if (form) {
        const inputs = ['full_name', 'age', 'birthday', 'email', 'phone'];
        inputs.forEach(id => {
          const el = document.getElementById(id);
          if (el) {
            el.addEventListener('input', () => hideError(id));
            el.addEventListener('blur', () => validateField(id));
          }
        });

        // Gender validation on change
        document.querySelectorAll('input[name="gender"]').forEach(radio => {
          radio.addEventListener('change', () => hideError('gender'));
        });

        form.addEventListener('submit', (e) => {
          e.preventDefault();
          if (validateAll()) {
            alert('✅ Profile updated successfully!');
            // In real system: submit to server, then update profileView
            editForm.style.display = 'none';
            profileView.style.display = 'block';
          }
        });
      }

      function validateAll() {
        let valid = true;
        ['full_name', 'age', 'birthday', 'email', 'phone'].forEach(id => {
          if (!validateField(id)) valid = false;
        });
        if (!validateGender()) valid = false;
        return valid;
      }

      function validateField(fieldId) {
        const el = document.getElementById(fieldId);
        let value = el.value.trim();
        let error = '';

        if (fieldId === 'full_name') {
          if (!value) error = 'Full name is required.';
          else if (value.length < 3) error = 'Name must be at least 3 characters.';
        } else if (fieldId === 'age') {
          const age = parseInt(value);
          if (!value) error = 'Age is required.';
          else if (isNaN(age) || age < 15 || age > 30) error = 'Age must be between 15 and 30.';
        } else if (fieldId === 'birthday') {
          if (!value) error = 'Birthday is required.';
          else {
            const birthDate = new Date(value);
            const today = new Date();
            const minDate = new Date();
            minDate.setFullYear(today.getFullYear() - 30);
            const maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() - 15);
            if (birthDate > maxDate || birthDate < minDate) {
              error = 'Must be between 15 and 30 years old.';
            }
          }
        } else if (fieldId === 'email') {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!value) error = 'Email is required.';
          else if (!emailRegex.test(value)) error = 'Please enter a valid email.';
        } else if (fieldId === 'phone') {
          const phoneRegex = /^\+?[\d\s\-\(\)]{10,}$/;
          if (!value) error = 'Phone number is required.';
          else if (!phoneRegex.test(value)) error = 'Please enter a valid phone number (e.g., +60 12-345 6789).';
        }

        if (error) {
          showError(fieldId, error);
          return false;
        } else {
          hideError(fieldId);
          return true;
        }
      }

      function validateGender() {
        const genderSelected = document.querySelector('input[name="gender"]:checked');
        if (!genderSelected) {
          showError('gender', 'Please select your gender.');
          return false;
        } else {
          hideError('gender');
          return true;
        }
      }

      function showError(fieldId, message) {
        document.getElementById('error_' + fieldId).textContent = message;
        document.getElementById('error_' + fieldId).style.display = 'block';
        const el = document.getElementById(fieldId);
        if (el) {
          el.style.borderColor = 'var(--maroon)';
          el.style.borderWidth = '2px';
        }
      }

      function hideError(fieldId) {
        document.getElementById('error_' + fieldId).style.display = 'none';
        const el = document.getElementById(fieldId);
        if (el) {
          el.style.borderColor = '#ccc';
          el.style.borderWidth = '1px';
        }
      }
    });
  </script>
</body>
</html>