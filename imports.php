<?php
session_start();
include 'db.php'; 
include 'navbar.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
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


if (isset($_GET['export_import'])) {
    $importId = $_GET['export_import'];
    
    
    $sql_get_import = "SELECT * FROM Import WHERE ImportId='$importId'";
    $result_get_import = $conn->query($sql_get_import);
    
    if ($result_get_import->num_rows > 0) {
        $import_row = $result_get_import->fetch_assoc();
        
        
        $furnitureId = $import_row['FurnitureId'];
        $exportDate = $import_row['ImportDate']; 
        $quantity = $import_row['Quantity'];
        
        $sql_export = "INSERT INTO Export (FurnitureId, ExportDate, Quantity) VALUES ('$furnitureId', '$exportDate', '$quantity')";
        if ($conn->query($sql_export) === TRUE) {
            
            $sql_delete_import = "DELETE FROM Import WHERE ImportId='$importId'";
            $conn->query($sql_delete_import);
            echo "<p class='text-green-500'>Import exported successfully!</p>";
        } else {
            echo "<p class='text-red-500'>Error exporting import: " . $conn->error . "</p>";
        }
    }
}


if (isset($_GET['delete_import'])) {
    $importId = $_GET['delete_import'];
    $sql = "DELETE FROM Import WHERE ImportId='$importId'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Import deleted successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $conn->error . "</p>";
    }
}


$sql_imports = "SELECT * FROM Import";
$result_imports = $conn->query($sql_imports);


$sql_furniture = "SELECT * FROM Furniture";
$result_furniture = $conn->query($sql_furniture);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGO Ltd - Imports Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Imports Management</h2>

        <!-- Add Import Form -->
        <form method="POST" class="mb-6">
            <select name="furniture_id" required class="border p-2 mb-2 w-full">
                <option value="">Select Furniture</option>
                <?php
                while($row = $result_furniture->fetch_assoc()) {
                    echo "<option value='" . $row["FurnitureId"] . "'>" . $row["FurnitureName"] . "</option>";
                }
                ?>
            </select>
            <input type="date" name="import_date" required class="border p-2 mb-2 w-full">
            <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 mb-2 w-full">
            <button type="submit" name="add_import" class="bg-blue-500 text-white px-4 py-2 rounded">Add Import</button>
        </form>

        <!-- Existing Imports List -->
        <h3 class="text-lg font-semibold mb-2">Existing Imports</h3>
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
                                <a href='imports.php?export_import=" . $row["ImportId"] . "' class='bg-blue-500 text-white px-2 py-1 rounded'>Export</a>
                                <a href='imports.php?delete_import=" . $row["ImportId"] . "' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='border border-gray-300 p-2'>No imports found.</td></tr>";
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