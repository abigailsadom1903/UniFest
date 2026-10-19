<?php
require 'config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    // Capture the matrix_id; if public, this may be empty
    $matrix_id = isset($_POST['matrix_id']) ? $_POST['matrix_id'] : ''; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role']; 
    $organization = $_POST['organization']; 
    
    $interests = isset($_POST['interests']) ? implode(',', $_POST['interests']) : '';

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email already registered.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (name, matrix_id, email, password, role, phone, organization, interested_categories) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$name, $matrix_id, $email, $hashed_password, $role, $phone, $organization, $interests]);
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UniFest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #800000 0%, #2c0000 100%);
            min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .register-card {
            background: white; padding: 40px 50px; border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); width: 100%; max-width: 600px;
        }
        .register-card h2 { color: #800000; font-weight: 700; text-align: center; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 0.9rem; text-align: center; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-full { grid-column: span 2; }
        .form-group { margin-bottom: 15px; }
        label { display: block; color: #333; margin-bottom: 5px; font-size: 0.85rem; font-weight: 600; }
        input, select { width: 100%; padding: 10px; border: 2px solid #eee; border-radius: 8px; font-family: 'Poppins', sans-serif; box-sizing: border-box; transition: all 0.3s ease; }
        select[multiple] { height: 120px; }
        input:focus, select:focus { border-color: #800000; outline: none; box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1); }
        .btn { background: #800000; color: white; width: 100%; padding: 14px; border: none; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; margin-top: 20px; }
        .btn:hover { background: #5c0000; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3); }
        .links { margin-top: 20px; font-size: 0.85rem; color: #666; text-align: center; }
        .links a { color: #800000; text-decoration: none; font-weight: 600; }
        .error-msg { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid #f87171; text-align: center; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .form-full { grid-column: span 1; } }
    </style>
</head>
<body>

    <div class="register-card">
        <h2>Join UniFest</h2>
        <p class="subtitle">Create an account to manage and join campus events.</p>

        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>I am a...</label>
                    <select name="role" id="roleSelect" onchange="toggleIDField()" required style="height: auto;">
                        <option value="student">Student</option>
                        <option value="staff">Staff</option>
                        <option value="public">Public / Guest</option>
                    </select>
                </div>

                <div class="form-group" id="idFieldGroup">
                    <label id="idLabel">Student / Staff ID</label>
                    <input type="text" name="matrix_id" id="matrixInput" required placeholder="e.g. BI23110295">
                </div>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Full Name">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="example@gmail.com">
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" required placeholder="012-3456789">
                </div>
                
                <div class="form-group">
                    <label>Organization / Faculty</label>
                    <input type="text" name="organization" required placeholder="e.g. FKI UMS">
                </div>

                <div class="form-group form-full">
                    <label>Interests (Hold Ctrl to select multiple)</label>
                    <select name="interests[]" multiple required>
                        <option value="Workshop">Workshop</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Competition">Competition</option>
                        <option value="Festival">Festival</option>
                        <option value="Sport">Sport</option>
                        <option value="Course">Course</option>
                    </select>
                </div>

                <div class="form-group form-full">
                    <label>Create Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn">Register Account</button>
        </form>

        <div class="links">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
    function toggleIDField() {
        const role = document.getElementById('roleSelect').value;
        const idGroup = document.getElementById('idFieldGroup');
        const idInput = document.getElementById('matrixInput');
        const idLabel = document.getElementById('idLabel');

        if (role === 'public') {
            // Hide the field and remove 'required' so the form can still submit
            idGroup.style.display = 'none';
            idInput.required = false;
            idInput.value = ''; // Clear value if hidden
        } else {
            // Show the field and make it mandatory
            idGroup.style.display = 'block';
            idInput.required = true;
            
            // Dynamic label update for better UX
            idLabel.innerText = (role === 'student') ? 'Student ID (Matrix No)' : 'Staff ID';
        }
    }

    // Run once on page load to set initial state
    window.onload = toggleIDField;
    </script>

</body>
</html>