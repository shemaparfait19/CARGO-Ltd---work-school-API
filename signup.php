<?php
session_start();
include 'db.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGO Ltd - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Sign Up</h1>
        <form method="POST" action="signup.php">
            <input type="text" name="username" placeholder="Username" required class="border p-2 mb-4 w-full">
            <input type="password" name="password" placeholder="Password" required class="border p-2 mb-4 w-full">
            <button type="submit" name="register" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
        </form>
        <p class="mt-4">Already have an account? <a href="login.php" class="text-blue-500">Login here</a></p>
    </div>

    <?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        $userName = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO Manager (UserName, Password) VALUES ('$userName', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='text-green-500'>Registration successful!</p>";
        } else {
            echo "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
    ?>
</body>
</html> 