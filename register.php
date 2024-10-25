<?php
$conn = new mysqli("localhost", "root", "", "sbtkrj");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST["userType"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $contact = $_POST["contact"];

    if ($userType == "UMKM") {
        $business_name = $_POST["businessName"];
        $business_type = $_POST["businessType"];
        $address = $_POST["address"];

        $query = "INSERT INTO UMKM (email, password, contact, business_name, business_type, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $email, $password, $contact, $business_name, $business_type, $address);
    } elseif ($userType == "JobSeeker") {
        $full_name = $_POST["fullName"];
        $job_field = $_POST["jobField"];
        $skills = $_POST["skills"];

        $query = "INSERT INTO JobSeeker (email, password, contact, full_name, job_field, skills) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $email, $password, $contact, $full_name, $job_field, $skills);
    }

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
