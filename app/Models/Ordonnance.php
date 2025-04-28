<?php
namespace App\Models;

use App\Core\Database;
use DateTime;

class Ordonnance {
    private ?int $ordonnanceId;
    private string $posologie;
    private DateTime $dateCreation;
    private string $duree_traitement;
    private string $instructions_specifique;
    private string $medecinId;
    private int $patientId;
    // Tableau associatif des médicaments avec leur quantité (ex: [ ['id' => 1, 'quantite' => 2], ... ])
    private array $medicaments = [];

    public function __construct($ordonnanceId = null, $posologie = '', $dateCreation = null, $duree_traitement = '', $instructions_specifique = '', $medecinId = '', $patientId = 0) {
        $this->ordonnanceId = $ordonnanceId;
        $this->posologie = $posologie;
        $this->dateCreation = $dateCreation ?? new DateTime();
        $this->duree_traitement = $duree_traitement;
        $this->instructions_specifique = $instructions_specifique;
        $this->medecinId = $medecinId;
        $this->patientId = $patientId;
    }

    // Getters et Setters
    public function getOrdonnanceId(): ?int {
        return $this->ordonnanceId;
    }

    public function getPosologie(): string {
        return $this->posologie;
    }
    public function setPosologie($posologie): void {
        $this->posologie = $posologie;
    }

    public function getDateCreation(): DateTime {
        return $this->dateCreation;
    }
    public function setDateCreation($dateCreation): void {
        $this->dateCreation = $dateCreation;
    }

    public function getDureeTraitement(): string {
        return $this->duree_traitement;
    }
    public function setDureeTraitement($duree_traitement): void {
        $this->duree_traitement = $duree_traitement;
    }

    public function getInstructionsSpecifique(): string {
        return $this->instructions_specifique;
    }
    public function setInstructionsSpecifique($instructions_specifique): void {
        $this->instructions_specifique = $instructions_specifique;
    }

    public function getMedecinId(): string {
        return $this->medecinId;
    }
    public function setMedecinId($medecinId): void {
        $this->medecinId = $medecinId;
    }

    public function getPatientId(): int {
        return $this->patientId;
    }
    public function setPatientId($patientId): void {
        $this->patientId = $patientId;
    }

    public function setMedicaments(array $medicaments): void {
        $this->medicaments = $medicaments;
    }
    public function getMedicaments(): array {
        return $this->medicaments;
    }

    // Méthode save incluant la gestion des associations
    public function save(): ?int {
        $db = Database::getInstance();
        $dateFormat = $this->dateCreation->format('Y-m-d H:i:s');

        if ($this->ordonnanceId) {
            // Update
            $sql = "UPDATE ordonnances SET Posologie = :posologie, DateCréation = :date_creation,
                        Duree_traitement = :duree_traitement, Instructions_specifique = :instructions,
                        MedecinId = :medecin_id, PatientId = :patient_id WHERE OrdonnanceId = :id";
            $db->prepare($sql);
            $db->bind(':posologie', $this->posologie);
            $db->bind(':date_creation', $dateFormat);
            $db->bind(':duree_traitement', $this->duree_traitement);
            $db->bind(':instructions', $this->instructions_specifique);
            $db->bind(':medecin_id', $this->medecinId);
            $db->bind(':patient_id', $this->patientId);
            $db->bind(':id', $this->ordonnanceId);
            if ($db->execute()) {
                // Mise à jour des associations : suppression des anciennes puis réinsertion
                $this->deleteMedicamentsAssociations();
                $this->saveMedicamentsAssociations();
                return $this->ordonnanceId;
            }
        } else {
            // Insert
            $sql = "INSERT INTO ordonnances (Posologie, DateCréation, Duree_traitement, Instructions_specifique, MedecinId, PatientId)
                        VALUES (:posologie, :date_creation, :duree_traitement, :instructions, :medecin_id, :patient_id)";
            $db->prepare($sql);
            $db->bind(':posologie', $this->posologie);
            $db->bind(':date_creation', $dateFormat);
            $db->bind(':duree_traitement', $this->duree_traitement);
            $db->bind(':instructions', $this->instructions_specifique);
            $db->bind(':medecin_id', $this->medecinId);
            $db->bind(':patient_id', $this->patientId);
            if ($db->execute()) {
                $this->ordonnanceId = (int)$db->lastInsertId();
                $this->saveMedicamentsAssociations();
                return $this->ordonnanceId;
            }
        }

        return null;
    }

    private function saveMedicamentsAssociations(): void {
        $db = Database::getInstance();
        foreach ($this->medicaments as $med) {
            $medId = $med['id'] ?? null;
            $quantity = $med['quantite'] ?? 1;
            if (!empty($medId)) {
                $sql = "INSERT INTO ordonnance_medicament (ordonnanceId, medicamentId, quantity)
                        VALUES (:ordonnanceId, :medicamentId, :quantity)";
                $db->prepare($sql);
                $db->bind(':ordonnanceId', $this->ordonnanceId);
                $db->bind(':medicamentId', $medId);
                $db->bind(':quantity', $quantity);
                $db->execute();
            }
        }
    }

    private function deleteMedicamentsAssociations(): void {
        $db = Database::getInstance();
        $sql = "DELETE FROM ordonnance_medicament WHERE ordonnanceId = :ordonnanceId";
        $db->prepare($sql);
        $db->bind(':ordonnanceId', $this->ordonnanceId);
        $db->execute();
    }

    public static function findById($id): ?Ordonnance {
        $db = Database::getInstance();
        $sql = "SELECT * FROM ordonnances WHERE OrdonnanceId = :id";
        $result = $db->single($sql, [':id' => $id]);

        if ($result) {
            return new Ordonnance(
                $result['OrdonnanceId'],
                $result['Posologie'],
                new DateTime($result['DateCréation']),
                $result['Duree_traitement'],
                $result['Instructions_specifique'],
                $result['MedecinId'],
                $result['PatientId']
            );
        }
        return null;
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $ordonnances = [];
        $results = $db->query("SELECT * FROM ordonnances ORDER BY DateCréation DESC");
        foreach ($results as $row) {
            $ordonnances[] = new Ordonnance(
                $row['OrdonnanceId'],
                $row['Posologie'],
                new DateTime($row['DateCréation']),
                $row['Duree_traitement'],
                $row['Instructions_specifique'],
                $row['MedecinId'],
                $row['PatientId']
            );
        }
        return $ordonnances;
    }

    public function delete(): bool {
        if (!$this->ordonnanceId) return false;
        $db = Database::getInstance();
        $sql = "DELETE FROM ordonnances WHERE OrdonnanceId = :id";
        $db->prepare($sql);
        $db->bind(':id', $this->ordonnanceId);
        return $db->execute();
    }

    public function getMedecin(): ?User {
        return User::findById($this->medecinId);
    }
    public function getPatient(): ?Patient {
        return Patient::findById($this->patientId);
    }
}
