<?php
include 'db.php';

//adding
if(isset($_POST['add'])){
    $candFName = $_POST['candFName'];
    $candMName = $_POST['candMName'];
    $candLName = $_POST['candLName'];
    $posID = $_POST['posID'];
    $candStat = $_POST['candStat'];
    $conn->query("INSERT INTO Candidates (candFName, candMName, candLName, posID, candStat) VALUES ('$candFName', '$candMName', '$candLName', '$posID', '$candStat')"); 
}

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $candFName = $_POST['candFName'];
    $candMName = $_POST['candMName'];
    $candLName = $_POST['candLName'];
    $posID = $_POST['posID'];
    $candStat = $_POST['candStat'];
    $conn->query("UPDATE Candidates SET candFName='$candFName', candMName='$candMName', candLName='$candMName', posID='$posID', candStat='$candStat' WHERE candID=$id");
}

$edit_mode = false;
$edit_candidate = null;
if(isset($_GET['edit'])){
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT c.*, p.posName
                            FROM Candidates c
                            JOIN Positions p ON c.posID=p.posID
                            WHERE c.candID=$edit_id");
    $edit_candidate = $result->fetch_assoc();
}

if(isset($_GET['deactivate'])){
    $id = $_GET['deactivate'];
    $conn->query("UPDATE Candidates SET candStat='inactive' WHERE candID=$id");
}

$positions = $conn->query("SELECT * FROM Positions WHERE posStat='open'");

$candidates = $conn->query("SELECT c.*, p.posName
                            FROM Candidates c
                            JOIN Positions p ON c.posID=p.posID");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOTING SYSTEM</title>
</head>
<body>
    <h1>CANDIDATES MANAGEMENT</h1>
    <!--Edting Candidates Form-->
    <?php if($edit_mode && $edit_candidate) :?>
    <form method="post">
        <input type="hidden" name="id" value="<?=$edit_candidate['candID']?>">
        FIRST NAME: <input type="text" name="candFName" value="<?=$edit_candidate['candFName']?>" required><br>
        MIDDLE NAME : <input type="text" name="candMName" value="<?=$edit_candidate['candMName']?>" required><br>
        LAST NAME : <input type="text" name="candLName" value="<?=$edit_candidate['candLName']?>" required><br>
        POSITION :
        <SELECT name="posID">
            <?php while($p = $positions->fetch_assoc()):?>
                <option value="<?=$p['posID']?>"><?=$p['posName']?></option>
            <?php endwhile;?>    
        </SELECT><br>
        STATUS :
        <SELECT name="candStat">
            <option value="active" <?=$edit_candidate['candStat']=='active' ? 'selected' : ''?>>active</option>
            <option value="inactive" <?=$edit_candidate['candStat']=='inactive' ? 'selected' : ''?>>inactive</option>
        </SELECT><br>
        <button type="submit" name="edit">Update Candiate</button>
        <a href="candidates.php">Cancel</a>
    </form>
    <?php else:?>
    <!--Adding Candidates Form-->
    <h3>ADDING CANDIDATES</h3>
    <form method="post">
        <input type="hidden" name="id" value="">
        FIRST NAME: <input type="text" name="candFName" required><br>
        MIDDLE NAME : <input type="text" name="candMName" required><br>
        LAST NAME : <input type="text" name="candLName" required><br>
        POSITION :
        <SELECT name="posID">
            <?php while($p = $positions->fetch_assoc()):?>
                <option value="<?=$p['posID']?>"><?=$p['posName']?></option>
            <?php endwhile;?>
        </SELECT><br>
        STATUS:
        <SELECT>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </SELECT><br>
        <button type="submit" name="add">Add Candidate</button>
    </form>
    <?php endif;?>
    <table border=1>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>POSITION</th>
            <th>STATUS</th>
            <th>ACTIONS</th>
        </tr>

        <?php while($row = $candidates->fetch_assoc()):?>
            <tr>
                <td><?=$row['candID']?></td>
                <td><?=$row['candFName']?> <?=$row['candMName']?> <?=$row['candLName']?></td>
                <td><?=$row['posName']?></td>
                <td><?=$row['candStat']?></td>
                <td>
                    <a href="?edit=<?=$row['candID']?>">EDIT</a>
                    <?php if($row['candStat'] == 'active'):?>
                        <a href="?deactivate=<?=$row['candID']?>">DEACTIVATE</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endwhile;?>
    </table>
</body>
</html>
