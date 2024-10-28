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
}

?>
