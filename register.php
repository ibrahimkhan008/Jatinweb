<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection
$servername = "localhost"; // Change if your database is on a different server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "user_registration"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmailQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $result = $checkEmailQuery->get_result();
    
    if ($result->num_rows > 0) {
        echo "Email already registered.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        // Insert the hashed password into the password column and the plain password into the viewpass column
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password, viewpass) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $hashed_password, $password); // Use the plain password for viewpass

        // Execute the statement
        if ($stmt->execute()) {
            echo "success"; // Return success message
        } else {
            echo "Error: " . $stmt->error; // Return error message
        }

        $stmt->close();
    }

    $checkEmailQuery->close();
}

$conn->close();
?>
