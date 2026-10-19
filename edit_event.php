<?php
require 'config/db.php';
require 'includes/header.php';

// 1. Security Check
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// 2. Fetch Existing Event Data
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) die("Event not found.");

// 3. Verify Ownership
if ($role != 'admin' && $event['organizer_id'] != $user_id) {
    die("Access Denied. You do not own this event.");
}

$error = '';

// 4. Handle Update Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Default to keeping the old poster
    $posterPath = $event['poster']; 

    // --- NEW ROBUST UPLOAD LOGIC ---
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        // USE ABSOLUTE PATH (Fixes the missing file issue)
        $targetDir = __DIR__ . "/assets/uploads/";
        
        // Create folder if it doesn't exist (Just in case)
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["poster"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];

        if(in_array($fileType, $allowedTypes)){
            if(move_uploaded_file($_FILES["poster"]["tmp_name"], $targetFilePath)){
                $posterPath = $fileName; // Success! Update variable
            } else {
                $error = "Error: PHP could not move the file. Check folder permissions.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }
    // -------------------------------

    if (empty($error)) {
        $sql = "UPDATE events SET 
                event_name=?, type=?, description=?, venue=?, mode=?, visibility=?, 
                event_date=?, event_time=?, registration_close_date=?, 
                contact_person=?, contact_no=?, max_participants=?, fee=?, remarks=?, poster=? 
                WHERE id=?";
        
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                $_POST['event_name'],
                $_POST['type'],
                $_POST['description'],
                $_POST['venue'],
                $_POST['mode'],
                $_POST['visibility'],
                $_POST['event_date'],
                $_POST['event_time'],
                $_POST['close_date'],
                $_POST['contact_person'],
                $_POST['contact_no'],
                $_POST['max_participants'],
                $_POST['fee'],
                $_POST['remarks'],
                $posterPath,
                $event_id
            ]);
            
            echo "<script>alert('Event updated successfully!'); window.location.href='dashboard.php';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<h2>Edit Event: <?= htmlspecialchars($event['event_name']) ?></h2>
<?php if($error) echo "<p style='color:red; font-weight:bold; background:#ffe6e6; padding:10px;'>$error</p>"; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Event Name *</label>
            <input type="text" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Event Type *</label>
            <select name="type" required>
                <?php 
                $types = ['Workshop', 'Seminar', 'Competition', 'Festival', 'Sport', 'Course'];
                foreach($types as $t) {
                    $selected = ($event['type'] == $t) ? 'selected' : '';
                    echo "<option value='$t' $selected>$t</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Description *</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Venue *</label>
            <input type="text" name="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Mode *</label>
            <select name="mode" required>
                <option value="Physical" <?= $event['mode']=='Physical'?'selected':'' ?>>Physical</option>
                <option value="Online" <?= $event['mode']=='Online'?'selected':'' ?>>Online</option>
                <option value="Hybrid" <?= $event['mode']=='Hybrid'?'selected':'' ?>>Hybrid</option>
            </select>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Visibility *</label>
            <select name="visibility" required>
                <option value="public" <?= $event['visibility']=='public'?'selected':'' ?>>Public (Everyone)</option>
                <option value="university" <?= $event['visibility']=='university'?'selected':'' ?>>University Only</option>
            </select>
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Event Date *</label>
            <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Time *</label>
            <input type="time" name="event_time" value="<?= $event['event_time'] ?>" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label>Registration Close Date *</label>
            <input type="date" name="close_date" value="<?= $event['registration_close_date'] ?>" required>
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="<?= htmlspecialchars($event['contact_person']) ?>">
        </div>
        <div class="form-group" style="flex:1;">
            <label>Contact No</label>
            <input type="text" name="contact_no" value="<?= htmlspecialchars($event['contact_no']) ?>">
        </div>
    </div>

    <div style="display:flex; gap: 20px;">
        <div class="form-group" style="flex:1;">
            <label>Max Participants</label>
            <input type="number" name="max_participants" value="<?= $event['max_participants'] ?>">
        </div>
        <div class="form-group" style="flex:1;">
            <label>Fee (RM) *</label>
            <input type="number" step="0.01" name="fee" value="<?= $event['fee'] ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Update Event Poster (Optional - leave blank to keep current)</label>
        <br>
        <?php if(!empty($event['poster'])): ?>
            <img src="assets/uploads/<?= $event['poster'] ?>" style="height: 100px; margin-bottom: 10px;">
        <?php endif; ?>
        <input type="file" name="poster" accept="image/*">
    </div>

    <div class="form-group">
        <label>Remarks / Notes</label>
        <input type="text" name="remarks" value="<?= htmlspecialchars($event['remarks']) ?>">
    </div>

    <button type="submit" class="btn">Update Event</button>
    <a href="dashboard.php" style="margin-left:10px; color:#666;">Cancel</a>
</form>

<?php require 'includes/footer.php'; ?>