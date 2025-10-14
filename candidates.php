<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_candidate'])) {
        $candFName = $_POST['candFName'];
        $candMName = $_POST['candMName'];
        $candLName = $_POST['candLName'];
        
        // Check if posID is set and valid
        if (isset($_POST['posID']) && !empty($_POST['posID'])) {
            $posID = $_POST['posID'];
            
            // Verify that the position exists before inserting candidate
            $checkPos = $conn->query("SELECT posID FROM Positions WHERE posID = '$posID'");
            if ($checkPos->num_rows > 0) {
                $candStat = $_POST['candStat'] ?? 'active';
                
                $sql = "INSERT INTO Candidates (candFName, candMName, candLName, posID, candStat) VALUES ('$candFName', '$candMName', '$candLName', '$posID', '$candStat')";
                if ($conn->query($sql) === TRUE) {
                    echo "New candidate added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error: Position does not exist";
            }
        } else {
            echo "Error: Please select a valid position";
        }
    }
    
    if (isset($_POST['update_candidate'])) {
        $candID = $_POST['candID'];
        $candFName = $_POST['candFName'];
        $candMName = $_POST['candMName'];
        $candLName = $_POST['candLName'];
        
        // Check if posID is set and valid
        if (isset($_POST['posID']) && !empty($_POST['posID'])) {
            $posID = $_POST['posID'];
            
            // Verify that the position exists before updating candidate
            $checkPos = $conn->query("SELECT posID FROM Positions WHERE posID = '$posID'");
            if ($checkPos->num_rows > 0) {
                $candStat = $_POST['candStat'] ?? 'active';
                
                $sql = "UPDATE Candidates SET candFName='$candFName', candMName='$candMName', candLName='$candLName', posID='$posID', candStat='$candStat' WHERE candID=$candID";
                if ($conn->query($sql) === TRUE) {
                    echo "Candidate updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error: Position does not exist";
            }
        } else {
            echo "Error: Please select a valid position";
        }
    }
    
    if (isset($_POST['deactivate_candidate'])) {
        $candID = $_POST['candID'];
        $sql = "UPDATE Candidates SET candStat='inactive' WHERE candID=$candID";
        if ($conn->query($sql) === TRUE) {
            echo "Candidate deactivated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidates Management</title>
</head>
<body>
    <h2>Candidates Management</h2>
    
    <!-- Add Candidate Form -->
    <h3>Add New Candidate</h3>
    <form method="post">
        <input type="text" name="candFName" placeholder="First Name" required><br>
        <input type="text" name="candMName" placeholder="Middle Name"><br>
        <input type="text" name="candLName" placeholder="Last Name" required><br>
        <select name="posID">
            <?php
            $positions = $conn->query("SELECT posID, posName FROM Positions WHERE posStat='open'");
            while($row = $positions->fetch_assoc()) {
                echo "<option value='".$row["posID"]."'>".$row["posName"]."</option>";
            }
            ?>
        </select><br>
        <select name="candStat">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <input type="submit" name="add_candidate" value="Add Candidate">
    </form>
    
    <!-- Update Candidate Form -->
    <h3>Update Candidate</h3>
    <form method="post">
        <input type="number" name="candID" placeholder="Candidate ID" required><br>
        <input type="text" name="candFName" placeholder="First Name" required><br>
        <input type="text" name="candMName" placeholder="Middle Name"><br>
        <input type="text" name="candLName" placeholder="Last Name" required><br>
        <select name="posID">
            <?php
            $positions = $conn->query("SELECT posID, posName FROM Positions WHERE posStat='open'");
            while($row = $positions->fetch_assoc()) {
                echo "<option value='".$row["posID"]."'>".$row["posName"]."</option>";
            }
            ?>
        </select><br>
        <select name="candStat">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <input type="submit" name="update_candidate" value="Update Candidate">
    </form>
    
    <!-- Deactivate Candidate Form -->
    <h3>Deactivate Candidate</h3>
    <form method="post">
        <input type="number" name="candID" placeholder="Candidate ID" required><br>
        <input type="submit" name="deactivate_candidate" value="Deactivate Candidate">
    </form>
    
    <!-- Display Current Candidates -->
    <h3>Current Candidates</h3>
    <?php
    $result = $conn->query("SELECT c.candID, c.candFName, c.candMName, c.candLName, p.posName, c.candStat 
                           FROM Candidates c 
                           JOIN Positions p ON c.posID = p.posID 
                           WHERE c.candStat='active'");
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Position</th><th>Status</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["candID"]."</td><td>".$row["candFName"]." ".$row["candMName"]." ".$row["candLName"]."</td><td>".$row["posName"]."</td><td>".$row["candStat"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No active candidates found";
    }
    ?>
</body>
</html>