<?php
session_start();
include 'db.php'; 


if (isset($_SESSION['username'])) {
    header("Location: furniture.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $userName = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM Manager WHERE UserName='$userName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['username'] = $userName;
            header("Location: furniture.php"); 
            exit();
        } else {
            echo "<p class='text-red-500'>Invalid password.</p>";
        }
    } else {
        echo "<p class='text-red-500'>No user found.</p>";
    }
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGO Ltd - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4 text-center">Welcome to CARGO Ltd</h1>
        
        <form method="POST" class="mb-4">
            <input type="text" name="username" placeholder="Username" required class="border p-2 mb-2 w-full">
            <input type="password" name="password" placeholder="Password" required class="border p-2 mb-2 w-full">
            <button type="submit" name="login" class="bg-green-500 text-white px-4 py-2 rounded w-full">Login</button>
        </form>
        
        <p class="mt-4 text-center">Don't have an account? <a href="signup.php" class="text-blue-500">Sign up here</a></p>
    </div>
</body>
</html>
