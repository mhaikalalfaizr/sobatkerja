<?php
require_once 'Database.php';

class Vacancy {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createVacancy($umkm_id, $title, $description, $requirements, $job_type, $location, $category) {
        $query = "INSERT INTO Vacancies (umkm_id, title, description, requirements, job_type, location, category) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issssss", $umkm_id, $title, $description, $requirements, $job_type, $location, $category);
        return $stmt->execute();
    }

    public function getVacancyDetails($vacancy_id) {
        $query = "SELECT * FROM Vacancies WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $vacancy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateVacancy($vacancy_id, $title, $description, $requirements, $job_type, $location) {
        $query = "UPDATE Vacancies SET title = ?, description = ?, requirements = ?, job_type = ?, location = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssssi", $title, $description, $requirements, $job_type, $location, $vacancy_id);
        return $stmt->execute();
    }

    public function deleteVacancy($vacancy_id) {
        $query = "DELETE FROM Vacancies WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $vacancy_id);
        return $stmt->execute();
    }

    public function searchVacancies($search_keyword, $job_type = '', $location = '', $category = '') {
        $query = "SELECT * FROM Vacancies WHERE title LIKE ?";
        
        $params = ["%$search_keyword%"];
        $types = "s";

        if (!empty($job_type)) {
            $query .= " AND job_type = ?";
            $types .= "s";
            $params[] = $job_type;
        }

        if (!empty($location)) {
            $query .= " AND location = ?";
            $types .= "s";
            $params[] = $location;
        }

        if (!empty($category)) {
            $query .= " AND category = ?";
            $types .= "s";
            $params[] = $category;
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllJobTypes() {
        return ["Full-time", "Part-time", "Freelance"];
    }

    public function getAllCategories() {
        return ["Retail", "Kuliner", "Jasa", "Teknologi", "Kerajinan", "Pertanian", "Peternakan", "Fashion", "Kesehatan", "Pendidikan", "Keuangan"];
    }

    public function getAllLocations() {
        return [
            "Jakarta", "Bandung", "Surabaya", "Yogyakarta", "Medan", "Makassar", "Denpasar", "Semarang", "Palembang", 
            "Banjarmasin", "Pontianak", "Batam", "Balikpapan", "Malang", "Padang", "Samarinda", "Pekanbaru", 
            "Manado", "Mataram", "Ambon", "Kupang", "Jayapura", "Sorong", "Ternate", "Gorontalo"
        ];
    }

    public function createApplication($jobseeker_id, $vacancy_id, $cv_path) {
        $query = "INSERT INTO Applications (jobseeker_id, vacancy_id, application_date, cv_path) VALUES (?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iis", $jobseeker_id, $vacancy_id, $cv_path);
        return $stmt->execute();
    }
    
}
?>
