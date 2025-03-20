<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "eshopper_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // SQL query to insert the form data
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<h3>Message sent successfully!</h3>";
    } else {
        echo "<h3>Error: " . $stmt->error . "</h3>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo "<h3>Invalid request</h3>";
}
?>
