<?php
require 'config/db.php';
require 'includes/header.php';

// Security: Only logged in users can create events
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Handle File Upload (Poster)
    $posterPath = '';
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $targetDir = "assets/uploads/";
        $fileName = time() . "_" . basename($_FILES["poster"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if(in_array($fileType, ['jpg', 'png', 'jpeg', 'gif'])){
            if(move_uploaded_file($_FILES["poster"]["tmp_name"], $targetFilePath)){
                $posterPath = $fileName;
            } else {
                $error = "Error uploading image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if (empty($error)) {
        // 2. Insert Data into Database (Including Visibility)
        $sql = "INSERT INTO events (event_name, description, organizer_id, contact_person, contact_no, type, venue, event_date, event_time, mode, registration_close_date, max_participants, fee, remarks, poster, status, visibility) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                $_POST['event_name'],
                $_POST['description'],
                $_SESSION['user_id'],
                $_POST['contact_person'],
                $_POST['contact_no'],
                $_POST['type'],
                $_POST['venue'],
                $_POST['event_date'],       // This fixes the "Undefined key" error
                $_POST['event_time'],       // This fixes the missing Time
                $_POST['mode'],
                $_POST['close_date'],       // This fixes the missing Close Date
                $_POST['max_participants'],
                $_POST['fee'],
                $_POST['remarks'],
                $posterPath,
                'pending',                  // Default status
                $_POST['visibility']        // The new Visibility setting
            ]);
            
            echo "<script>alert('Event created successfully!'); window.location.href='dashboard.php';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<h2>Create New Event</h2>
<?php if($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Event Name *</label>
            <input type="text" name="event_name" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Event Type *</label>
            <select name="type" required>
                <option value="Workshop">Workshop</option>
                <option value="Seminar">Seminar</option>
                <option value="Competition">Competition</option>
                <option value="Festival">Festival</option>
                <option value="Sport">Sport</option>
                <option value="Course">Course</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Description *</label>
        <textarea name="description" rows="4" required></textarea>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Venue *</label>
            <input type="text" name="venue" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Mode *</label>
            <select name="mode" required>
                <option value="Physical">Physical</option>
                <option value="Online">Online</option>
                <option value="Hybrid">Hybrid</option>
            </select>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Visibility *</label>
            <select name="visibility" required>
                <option value="public">Public (Everyone)</option>
                <option value="university">University Only (Login Required)</option>
            </select>
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Event Date *</label>
            <input type="date" name="event_date" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Time *</label>
            <input type="time" name="event_time" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Registration Close Date *</label>
            <input type="date" name="close_date" required>
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Contact Person</label>
            <input type="text" name="contact_person">
        </div>
        <div class="form-group" style="flex:1;">
            <label>Contact No</label>
            <input type="text" name="contact_no">
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Max Participants</label>
            <input type="number" name="max_participants">
        </div>
        <div class="form-group" style="flex:1;">
            <label>Fee (RM) *</label>
            <input type="number" step="0.01" name="fee" value="0.00" required>
        </div>
    </div>

    <div class="form-group">
        <label>Event Poster (Max 1 file)</label>
        <input type="file" name="poster" accept="image/*">
    </div>

    <div class="form-group">
        <label>Remarks / Notes</label>
        <input type="text" name="remarks">
    </div>

    <button type="submit" class="btn">Submit Event</button>
    <a href="dashboard.php" style="margin-left:10px; color:#666;">Cancel</a>
</form>

<?php require 'includes/footer.php'; ?>