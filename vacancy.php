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

    public function getVacancies($umkm_id = null) {
        $query = "
            SELECT Vacancies.*, 
                   (SELECT COUNT(*) FROM Applications WHERE Applications.vacancy_id = Vacancies.id) AS applicant_count 
            FROM Vacancies 
            " . ($umkm_id ? "WHERE Vacancies.umkm_id = ?" : "");
    
        $stmt = $this->db->prepare($query);
        if ($umkm_id) {
            $stmt->bind_param("i", $umkm_id);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getVacancyDetails($vacancy_id) {
        $query = "SELECT * FROM Vacancies WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $vacancy_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
