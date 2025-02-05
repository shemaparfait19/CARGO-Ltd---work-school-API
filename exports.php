<?php
session_start();
include 'db.php'; 
include 'navbar.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
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


if (isset($_GET['delete_export'])) {
    $exportId = $_GET['delete_export'];
    $sql = "DELETE FROM Export WHERE ExportId='$exportId'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500'>Export deleted successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error: " . $conn->error . "</p>";
    }
}


$sql_exports = "SELECT * FROM Export";
$result_exports = $conn->query($sql_exports);


$sql_furniture = "SELECT * FROM Furniture";
$result_furniture = $conn->query($sql_furniture);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGO Ltd - Exports Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Exports Management</h2>

        <!-- Add Export Form -->
        <form method="POST" class="mb-6">
            <select name="furniture_id" required class="border p-2 mb-2 w-full">
                <option value="">Select Furniture</option>
                <?php
                while($row = $result_furniture->fetch_assoc()) {
                    echo "<option value='" . $row["FurnitureId"] . "'>" . $row["FurnitureName"] . "</option>";
                }
                ?>
            </select>
            <input type="date" name="export_date" required class="border p-2 mb-2 w-full">
            <input type="number" name="quantity" placeholder="Quantity" required class="border p-2 mb-2 w-full">
            <button type="submit" name="add_export" class="bg-blue-500 text-white px-4 py-2 rounded">Add Export</button>
        </form>

        <!-- Existing Exports List -->
        <h3 class="text-lg font-semibold mb-2">Existing Exports</h3>
        <table class="min-w-full border-collapse border border-gray-300 mb-6">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Furniture ID</th>
                    <th class="border border-gray-300 p-2">Export Date</th>
                    <th class="border border-gray-300 p-2">Quantity</th>
                    <th class="border border-gray-300 p-2">Actions</th>
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
                        echo "<td class='border border-gray-300 p-2'>
                                <a href='exports.php?delete_export=" . $row["ExportId"] . "' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='border border-gray-300 p-2'>No exports found.</td></tr>";
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