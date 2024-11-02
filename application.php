<?php
require_once 'Database.php';

class Application {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function applyForVacancy($jobseeker_id, $vacancy_id, $umkm_id, $cv_path) {
        $query = "INSERT INTO Applications (vacancy_id, jobseeker_id, umkm_id, cv_path) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiis", $vacancy_id, $jobseeker_id, $umkm_id, $cv_path);
        return $stmt->execute();
    }

    public function getApplicants($vacancy_id) {
        $query = "
            SELECT JobSeeker.full_name, Applications.application_date, Applications.cv_path 
            FROM Applications
            INNER JOIN JobSeeker ON Applications.jobseeker_id = JobSeeker.jobseeker_id
            WHERE Applications.vacancy_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $vacancy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}
?>
