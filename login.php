<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection details
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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch user data
        // Verify the password against the hashed password
        if (password_verify($password, $user['password'])) {
            // User found, login successful
            echo "Login successful!";
            // Here you can set session variables or redirect the user to another page
        } else {
            // Password is incorrect
            echo "Invalid email or password.";
        }
    } else {
        // User not found
        echo "Invalid email or password.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
