<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($userType, $email, $password, $contact, $additionalData) {
        $errors = $this->validateUniqueFields(
            $userType, 
            $email, 
            $contact, 
            $additionalData['business_name'] ?? null, 
            $additionalData['full_name'] ?? null
        );
        
        if (!empty($errors)) {
            return $errors;
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        if ($userType === 'UMKM') {
            $query = "INSERT INTO UMKM (email, password, contact, full_name, business_name, business_type, address) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssssss", 
                $email, 
                $hashedPassword, 
                $contact, 
                $additionalData['full_name'], 
                $additionalData['business_name'], 
                $additionalData['business_type'], 
                $additionalData['address']
            );
        } else {
            $query = "INSERT INTO JobSeeker (email, password, contact, full_name) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssss", $email, $hashedPassword, $contact, $additionalData['full_name']);
        }
    
        if ($stmt->execute()) {
            return [];
        } else {
            error_log("Execution error: " . $stmt->error);
            return ["Database error: Unable to register user."];
        }
    }    

    private function validateUniqueFields($userType, $email, $contact, $business_name = null, $full_name = null) {
        $errors = [];
        if ($this->checkFieldExists('email', $email, $userType)) {
            $errors['email'] = "Email sudah digunakan.";
        }
        if ($this->checkFieldExists('contact', $contact, $userType)) {
            $errors['contact'] = "Nomor kontak sudah digunakan.";
        }
        if ($userType === 'UMKM' && $business_name && $this->checkFieldExists('business_name', $business_name, $userType)) {
            $errors['business_name'] = "Nama usaha sudah digunakan.";
        }
        if ($userType === 'JobSeeker' && $full_name && $this->checkFieldExists('full_name', $full_name, $userType)) {
            $errors['full_name'] = "Nama lengkap sudah digunakan.";
        }
        return $errors;
    }

    private function checkFieldExists($field, $value, $userType) {
        $table = $userType === 'UMKM' ? 'UMKM' : 'JobSeeker';
        $query = "SELECT $field FROM $table WHERE $field = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function login($identifier, $password, $userType) {
        $table = $userType === 'UMKM' ? 'UMKM' : 'JobSeeker';

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM $table WHERE email = ?";
        } else {
            $query = "SELECT * FROM $table WHERE contact = ?";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result[$userType === 'UMKM' ? 'umkm_id' : 'jobseeker_id'];
            $_SESSION['userType'] = $userType;
            return true;
        }
        return false;
    }

    public function getProfile($userId, $userType) {
        $table = $userType === 'UMKM' ? 'UMKM' : 'JobSeeker';
        $query = "SELECT * FROM $table WHERE " . ($userType === 'UMKM' ? 'umkm_id' : 'jobseeker_id') . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
