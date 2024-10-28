<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($userType, $email, $password, $contact, $additionalData) {
        $errors = $this->validateUniqueFields($userType, $email, $contact, $additionalData['business_name'] ?? null, $additionalData['full_name'] ?? null);

        if (!empty($errors)) {
            return $errors;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($userType === 'UMKM') {
            $query = "INSERT INTO UMKM (email, password, contact, business_name, business_type, address) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssssss", $email, $hashedPassword, $contact, $additionalData['business_name'], $additionalData['business_type'], $additionalData['address']);
        } else {
            $query = "INSERT INTO JobSeeker (email, password, contact, full_name, job_field, skills) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssssss", $email, $hashedPassword, $contact, $additionalData['full_name'], $additionalData['job_field'], $additionalData['skills']);
        }

        $stmt->execute();
        return [];
    }

    private function validateUniqueFields($userType, $email, $contact, $business_name = null, $full_name = null) {
        $errors = [];

        // Periksa email unik
        $emailExists = $this->checkFieldExists('email', $email, $userType);
        if ($emailExists) {
            $errors['email'] = "Email sudah digunakan.";
        }

        // Periksa nomor kontak unik
        $contactExists = $this->checkFieldExists('contact', $contact, $userType);
        if ($contactExists) {
            $errors['contact'] = "Nomor kontak sudah digunakan.";
        }

        // Periksa nama usaha unik (khusus UMKM)
        if ($userType === 'UMKM' && $business_name) {
            $businessNameExists = $this->checkFieldExists('business_name', $business_name, $userType);
            if ($businessNameExists) {
                $errors['business_name'] = "Nama usaha sudah digunakan.";
            }
        }

        // Periksa nama lengkap unik (khusus JobSeeker)
        if ($userType === 'JobSeeker' && $full_name) {
            $fullNameExists = $this->checkFieldExists('full_name', $full_name, $userType);
            if ($fullNameExists) {
                $errors['full_name'] = "Nama lengkap sudah digunakan.";
            }
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
}
