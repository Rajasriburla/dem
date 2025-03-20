<?php
ob_start();  // Start output buffering

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "dbs";

// Hide all errors from the user
error_reporting(0); // No errors displayed

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if user already exists
    $checkUser = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        $checkUser->close();
        header("Location: login.html");
        exit();
    }
    $checkUser->close();

    // SQL query to insert data into the table
    $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $phone);

    if ($stmt->execute()) {
        // Redirect to index.html after successful registration
        header("Location: index.html");
        exit();
    } else {
        throw new Exception("Execution failed: " . $stmt->error);
    }

} catch (Exception $e) {
    // Log errors to a file instead of displaying them
    file_put_contents('error_log.txt', $e->getMessage() . "\n", FILE_APPEND);
    echo "<h3>Something went wrong. Please try again later.</h3>";
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}

ob_end_flush();  // Send the output buffer and disable buffering
?>
