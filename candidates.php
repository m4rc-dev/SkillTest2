<?php
// candidates.php - CRUD for Candidates table
require_once 'config.php';

// Check if editing
$edit_mode = false;
$edit_candidate = null;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    
    // Fetch the candidate's data
    $result = $conn->query("SELECT c.*, p.posName FROM Candidates c 
                          JOIN Positions p ON c.posID = p.posID 
                          WHERE c.candID=$edit_id");
    $edit_candidate = $result->fetch_assoc();
}

// Handle Add Candidate
if (isset($_POST['add'])) {
    $candFName = $_POST['candFName'];
    $candMName = $_POST['candMName'];
    $candLName = $_POST['candLName'];
    $posID = $_POST['posID'];
    $candStat = $_POST['candStat'] ?? 'active';
    
    $conn->query("INSERT INTO Candidates (candFName, candMName, candLName, posID, candStat) VALUES ('$candFName', '$candMName', '$candLName', '$posID', '$candStat')");
}

// Handle Edit Candidate
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $candFName = $_POST['candFName'];
    $candMName = $_POST['candMName'];
    $candLName = $_POST['candLName'];
    $posID = $_POST['posID'];
    $candStat = $_POST['candStat'] ?? 'active';
    
    $conn->query("UPDATE Candidates SET candFName='$candFName', candMName='$candMName', candLName='$candLName', posID='$posID', candStat='$candStat' WHERE candID=$id");
}

// Handle Deactivate Candidate
if (isset($_GET['deactivate'])) {
    $id = $_GET['deactivate'];
    $conn->query("UPDATE Candidates SET candStat='inactive' WHERE candID=$id");
}

// Fetch all active candidates
$candidates = $conn->query("SELECT c.*, p.posName FROM Candidates c 
                          JOIN Positions p ON c.posID = p.posID 
                          WHERE c.candStat='active'");
?>

<?php if ($edit_mode && $edit_candidate): ?>
<!-- Edit Candidate Form -->
<h2>Edit Candidate</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= $edit_candidate['candID'] ?>">
    First Name: <input type="text" name="candFName" value="<?= $edit_candidate['candFName'] ?>" required><br>
    Middle Name: <input type="text" name="candMName" value="<?= $edit_candidate['candMName'] ?>"><br>
    Last Name: <input type="text" name="candLName" value="<?= $edit_candidate['candLName'] ?>" required><br> 
    Position:
    <select name="posID">
    <?php
    $positions = $conn->query("SELECT posID, posName FROM Positions WHERE posStat='open'");
    while($row = $positions->fetch_assoc()) {
        $selected = ($row["posID"] == $edit_candidate["posID"]) ? 'selected' : '';
        echo "<option value='{$row["posID"]}' {$selected}>{$row["posName"]}</option>";
    }
    ?>
</select><br>
    Status: 
    <select name="candStat">
        <option value="active" <?= $edit_candidate['candStat'] == 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $edit_candidate['candStat'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select><br>
    <button type="submit" name="edit">Update Candidate</button>
    <a href="candidates.php">Cancel</a>
</form>
<?php else: ?>
<!-- Add Candidate Form -->
<h2>Candidates Management</h2>
<form method="post">
    <input type="hidden" name="id" value="">
    First Name: <input type="text" name="candFName" required><br>
    Middle Name: <input type="text" name="candMName"><br>
    Last Name: <input type="text" name="candLName" required><br>
    Position: 
    <select name="posID">
        <?php
        $positions = $conn->query("SELECT posID, posName FROM Positions WHERE posStat='open'");
        while($row = $positions->fetch_assoc()) {
            echo "<option value='".$row["posID"]."'>".$row["posName"]."</option>";
        }
        ?>
    </select><br>
    Status: 
    <select name="candStat">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select><br>
    <button type="submit" name="add">Add Candidate</button>
</form>
<?php endif; ?>

<button><a href="index.php">Back</a></button>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Position</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $candidates->fetch_assoc()): ?>
    <tr>
        <td><?= $row['candID'] ?></td>
        <td><?= $row['candFName'] ?> <?= $row['candMName'] ?> <?= $row['candLName'] ?></td>
        <td><?= $row['posName'] ?></td>
        <td><?= $row['candStat'] ?></td>
        <td>
            <a href="?edit=<?= $row['candID'] ?>">Edit</a>
            <?php if ($row['candStat'] == 'active'): ?>
                <a href="?deactivate=<?= $row['candID'] ?>">Deactivate</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>