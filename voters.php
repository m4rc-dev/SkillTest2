// voters.php - Voters Management
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_voter'])) {
        $voterID = $_POST['voterID'];
        $voterPass = password_hash($_POST['voterPass'], PASSWORD_DEFAULT);
        $voterFName = $_POST['voterFName'];
        $voterMName = $_POST['voterMName'];
        $voterLName = $_POST['voterLName'];
        $voterStat = $_POST['voterStat'];
        $voted = $_POST['voted'];
        
        $sql = "INSERT INTO Voters (voterID, voterPass, voterFName, voterMName, voterLName, voterStat, voted) VALUES ('$voterID', '$voterPass', '$voterFName', '$voterMName', '$voterLName', '$voterStat', '$voted')";
        if ($conn->query($sql) === TRUE) {
            echo "New voter added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['update_voter'])) {
        $voterID = $_POST['voterID'];
        $voterPass = password_hash($_POST['voterPass'], PASSWORD_DEFAULT);
        $voterFName = $_POST['voterFName'];
        $voterMName = $_POST['voterMName'];
        $voterLName = $_POST['voterLName'];
        $voterStat = $_POST['voterStat'];
        $voted = $_POST['voted'];
        
        $sql = "UPDATE Voters SET voterPass='$voterPass', voterFName='$voterFName', voterMName='$voterMName', voterLName='$voterLName', voterStat='$voterStat', voted='$voted' WHERE voterID='$voterID'";
        if ($conn->query($sql) === TRUE) {
            echo "Voter updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    if (isset($_POST['deactivate_voter'])) {
        $voterID = $_POST['voterID'];
        $sql = "UPDATE Voters SET voterStat='inactive' WHERE voterID='$voterID'";
        if ($conn->query($sql) === TRUE) {
            echo "Voter deactivated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voters Management</title>
</head>
<body>
    <h2>Voters Management</h2>
    
    <!-- Add Voter Form -->
    <h3>Add New Voter</h3>
    <form method="post">
        <input type="text" name="voterID" placeholder="Voter ID" required><br>
        <input type="password" name="voterPass" placeholder="Password" required><br>
        <input type="text" name="voterFName" placeholder="First Name" required><br>
        <input type="text" name="voterMName" placeholder="Middle Name"><br>
        <input type="text" name="voterLName" placeholder="Last Name" required><br>
        <select name="voterStat">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <select name="voted">
            <option value="n">No</option>
            <option value="y">Yes</option>
        </select><br>
        <input type="submit" name="add_voter" value="Add Voter">
    </form>
    
    <!-- Update Voter Form -->
    <h3>Update Voter</h3>
    <form method="post">
        <input type="text" name="voterID" placeholder="Voter ID" required><br>
        <input type="password" name="voterPass" placeholder="Password" required><br>
        <input type="text" name="voterFName" placeholder="First Name" required><br>
        <input type="text" name="voterMName" placeholder="Middle Name"><br>
        <input type="text" name="voterLName" placeholder="Last Name" required><br>
        <select name="voterStat">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <select name="voted">
            <option value="n">No</option>
            <option value="y">Yes</option>
        </select><br>
        <input type="submit" name="update_voter" value="Update Voter">
    </form>
    
    <!-- Deactivate Voter Form -->
    <h3>Deactivate Voter</h3>
    <form method="post">
        <input type="text" name="voterID" placeholder="Voter ID" required><br>
        <input type="submit" name="deactivate_voter" value="Deactivate Voter">
    </form>
    
    <!-- Display Current Voters -->
    <h3>Current Voters</h3>
    <?php
    $result = $conn->query("SELECT * FROM Voters WHERE voterStat='active'");
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Status</th><th>Voted</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["voterID"]."</td><td>".$row["voterFName"]." ".$row["voterMName"]." ".$row["voterLName"]."</td><td>".$row["voterStat"]."</td><td>".$row["voted"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No active voters found";
    }
    ?>
</body>
</html>