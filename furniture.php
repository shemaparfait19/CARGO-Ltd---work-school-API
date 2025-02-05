<?php
session_start();
include 'db.php';
include 'navbar.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_furniture'])) {
    $furnitureName = $_POST['furniture_name'];
    $furnitureOwnerName = $_POST['furniture_owner_name'];
    $sql = "INSERT INTO Furniture (FurnitureName, FurnitureOwnerName) VALUES ('$furnitureName', '$furnitureOwnerName')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Furniture added successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_furniture'])) {
    $furnitureId = $_POST['furniture_id'];
    $furnitureName = $_POST['furniture_name'];
    $furnitureOwnerName = $_POST['furniture_owner_name'];
    $sql = "UPDATE Furniture SET FurnitureName='$furnitureName', FurnitureOwnerName='$furnitureOwnerName' WHERE FurnitureId='$furnitureId'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Furniture updated successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}


if (isset($_GET['delete'])) {
    $furnitureId = $_GET['delete'];
    $sql = "DELETE FROM Furniture WHERE FurnitureId='$furnitureId'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Furniture deleted successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $conn->error . "</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_import'])) {
    $furnitureId = $_POST['furniture_id'];
    $importDate = $_POST['import_date'];
    $quantity = $_POST['quantity'];
    $sql = "INSERT INTO Import (FurnitureId, ImportDate, Quantity) VALUES ('$furnitureId', '$importDate', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Import added successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_export'])) {
    $furnitureId = $_POST['furniture_id'];
    $exportDate = $_POST['export_date'];
    $quantity = $_POST['quantity'];
    $sql = "INSERT INTO Export (FurnitureId, ExportDate, Quantity) VALUES ('$furnitureId', '$exportDate', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Export added successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}


$sql = "SELECT * FROM Furniture";
$result = $conn->query($sql);


$sql_imports = "SELECT * FROM Import";
$result_imports = $conn->query($sql_imports);


$sql_exports = "SELECT * FROM Export";
$result_exports = $conn->query($sql_exports);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGO Ltd - Furniture Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Furniture Management</h2>

        <!-- Navigation -->
        <nav class="mb-4">
            <a href="furniture.php" class="text-blue-500">Furniture</a> |
            <a href="imports.php" class="text-blue-500">Imports</a> |
            <a href="exports.php" class="text-blue-500">Exports</a>
        </nav>
        
        <!-- Add Furniture Form -->
        <form method="POST" class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Add Furniture</h3>
            <input type="text" name="furniture_name" placeholder="Furniture Name" required class="border p-2 mb-2 w-full">
            <input type="text" name="furniture_owner_name" placeholder="Furniture Owner Name" required class="border p-2 mb-2 w-full">
            <button type="submit" name="add_furniture" class="bg-blue-500 text-white px-4 py-2 rounded">Add Furniture</button>
        </form>

        <!-- Existing Furniture List -->
        <h3 class="text-lg font-semibold mb-2">Existing Furniture</h3>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Name</th>
                    <th class="border border-gray-300 p-2">Owner</th>
                    <th class="border border-gray-300 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["FurnitureId"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["FurnitureName"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["FurnitureOwnerName"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>
                                <form method='POST' class='inline'>
                                    <input type='hidden' name='furniture_id' value='" . $row["FurnitureId"] . "'>
                                    <input type='text' name='furniture_name' placeholder='New Name' class='border p-1'>
                                    <input type='text' name='furniture_owner_name' placeholder='New Owner' class='border p-1'>
                                    <button type='submit' name='update_furniture' class='bg-yellow-500 text-white px-2 py-1 rounded'>Update</button>
                                </form>
                                <a href='furniture.php?delete=" . $row["FurnitureId"] . "' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='border border-gray-300 p-2'>No furniture found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add Import Form -->
        <h3 class="text-lg font-semibold mb-2 mt-6">Add Import</h3>
        <form method="POST" class="mb-6">
            <select name="furniture_id" required class="border p-2 mb-2 w-full">
                <option value="">Select Furniture</option>
                <?php
                
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["FurnitureId"] . "'>" . $row["FurnitureName"] . "</option>";
                }
                ?>
            </select>
            <input type="date" name="import_date" required class="border p-2 mb-2 w-full">
            <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 mb-2 w-full">
            <button type="submit" name="add_import" class="bg-blue-500 text-white px-4 py-2 rounded">Add Import</button>
        </form>

        <!-- Add Export Form -->
        <h3 class="text-lg font-semibold mb-2 mt-6">Add Export</h3>
        <form method="POST" class="mb-6">
            <select name="furniture_id" required class="border p-2 mb-2 w-full">
                <option value="">Select Furniture</option>
                <?php
                
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["FurnitureId"] . "'>" . $row["FurnitureName"] . "</option>";
                }
                ?>
            </select>
            <input type="date" name="export_date" required class="border p-2 mb-2 w-full">
            <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 mb-2 w-full">
            <button type="submit" name="add_export" class="bg-blue-500 text-white px-4 py-2 rounded">Add Export</button>
        </form>

        <!-- Existing Imports List -->
        <h3 class="text-lg font-semibold mb-2 mt-6">Existing Imports</h3>
        <table class="min-w-full border-collapse border border-gray-300 mb-6">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Furniture ID</th>
                    <th class="border border-gray-300 p-2">Import Date</th>
                    <th class="border border-gray-300 p-2">Quantity</th>
                    <th class="border border-gray-300 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_imports->num_rows > 0) {
                    while($row = $result_imports->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["ImportId"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["FurnitureId"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["ImportDate"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["Quantity"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>
                                <a href='furniture.php?delete_import=" . $row["ImportId"] . "' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='border border-gray-300 p-2'>No imports found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Existing Exports List -->
        <h3 class="text-lg font-semibold mb-2 mt-6">Existing Exports</h3>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Furniture ID</th>
                    <th class="border border-gray-300 p-2">Export Date</th>
                    <th class="border border-gray-300 p-2">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_exports->num_rows > 0) {
                    while($row = $result_exports->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["ExportId"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["FurnitureId"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["ExportDate"] . "</td>";
                        echo "<td class='border border-gray-300 p-2'>" . $row["Quantity"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='border border-gray-300 p-2'>No exports found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?> 