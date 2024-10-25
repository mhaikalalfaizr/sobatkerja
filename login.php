<?php
$conn = new mysqli("localhost", "root", "", "sbtkrj");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userType = $_POST["userType"];

    $query = $userType === "UMKM"
        ? "SELECT * FROM UMKM WHERE email = ?"
        : "SELECT * FROM JobSeeker WHERE email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        echo "Login successful!";
    } else {
        echo "Invalid email or password";
    }

    $stmt->close();
}

$conn->close();
?>