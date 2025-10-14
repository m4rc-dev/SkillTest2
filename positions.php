<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_position'])) {
        $posName = $_POST['posName'];
        $numOfPositions = $_POST['numOfPositions'];
        $posStat = $_POST['posStat'];
        
        $sql = "INSERT INTO Positions (posName, numOfPositions, posStat) VALUES ('$posName', '$numOfPositions', '$posStat')";
        if ($conn->query($sql) === TRUE) {
            echo "New position added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['update_position'])) {
        $posID = $_POST['posID'];
        $posName = $_POST['posName'];
        $numOfPositions = $_POST['numOfPositions'];
        $posStat = $_POST['posStat'];
        
        $sql = "UPDATE Positions SET posName='$posName', numOfPositions='$numOfPositions', posStat='$posStat' WHERE posID=$posID";
        if ($conn->query($sql) === TRUE) {
            echo "Position updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['deactivate_position'])) {
        $posID = $_POST['posID'];
        $sql = "UPDATE Positions SET posStat='closed' WHERE posID=$posID";
        if ($conn->query($sql) === TRUE) {
            echo "Position deactivated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Positions Management</title>
</head>
<body>
    <h2>Positions Management</h2>
    
    <!-- Add Position Form -->
    <h3>Add New Position</h3>
    <form method="post">
        <input type="text" name="posName" placeholder="Position Name" required><br>
        <input type="number" name="numOfPositions" placeholder="Number of Positions" required><br>
        <select name="posStat">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select><br>
        <input type="submit" name="add_position" value="Add Position">
    </form>
    
    <!-- Update Position Form -->
    <h3>Update Position</h3>
    <form method="post">
        <input type="number" name="posID" placeholder="Position ID" required><br>
        <input type="text" name="posName" placeholder="Position Name" required><br>
        <input type="number" name="numOfPositions" placeholder="Number of Positions" required><br>
        <select name="posStat">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select><br>
        <input type="submit" name="update_position" value="Update Position">
    </form>
    
    <!-- Deactivate Position Form -->
    <h3>Deactivate Position</h3>
    <form method="post">
        <input type="number" name="posID" placeholder="Position ID" required><br>
        <input type="submit" name="deactivate_position" value="Deactivate Position">
    </form>
    
    <!-- Display Current Positions -->
    <h3>Current Positions</h3>
    <?php
    $result = $conn->query("SELECT * FROM Positions");
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Num Positions</th><th>Status</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["posID"]."</td><td>".$row["posName"]."</td><td>".$row["numOfPositions"]."</td><td>".$row["posStat"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No positions found";
    }
    ?>
</body>
</html>