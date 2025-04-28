<?php

namespace App\Models;

use App\Core\Database;

class Patient {
    private ?int $patientId;
    private string $nom_p;
    private string $prenom_p;
    private string $sexe_p;
    private string $num_secu;
    private ?string $photo;

    public function __construct($patientId = null, $nom_p = '', $prenom_p = '', $sexe_p = '', $num_secu = '', $photo = null) {
        $this->patientId = $patientId;
        $this->nom_p = $nom_p;
        $this->prenom_p = $prenom_p;
        $this->sexe_p = $sexe_p;
        $this->num_secu = $num_secu;
        $this->photo = $photo;
    }

    // Getters et Setters
    public function getPatientId(): ?int {
        return $this->patientId;
    }

    public function getNom(): string {
        return $this->nom_p;
    }

    public function setNom($nom_p): void {
        $this->nom_p = $nom_p;
    }

    public function getPrenom(): string {
        return $this->prenom_p;
    }

    public function setPrenom($prenom_p): void {
        $this->prenom_p = $prenom_p;
    }

    public function getSexe(): string {
        return $this->sexe_p;
    }

    public function setSexe($sexe_p): void {
        $this->sexe_p = $sexe_p;
    }

    public function getNumSecu(): string {
        return $this->num_secu;
    }

    public function setNumSecu($num_secu): void {
        $this->num_secu = $num_secu;
    }

    public function getPhoto(): ?string {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void {
        $this->photo = $photo;
    }

    // Méthodes CRUD
    public function save(): ?int {
        $db = Database::getInstance();

        if ($this->patientId) {
            // Update
            $sql = "UPDATE patients SET Nom_p = :nom, Prenom_p = :prenom, Sexe_p = :sexe, 
                    Num_secu = :num_secu, photo = :photo WHERE PatientId = :id";
            $db->prepare($sql);
            $db->bind(':nom', $this->nom_p);
            $db->bind(':prenom', $this->prenom_p);
            $db->bind(':sexe', $this->sexe_p);
            $db->bind(':num_secu', $this->num_secu);
            $db->bind(':photo', $this->photo);
            $db->bind(':id', $this->patientId);
            if ($db->execute()) {
                return $this->patientId;
            }
        } else {
            // Insert
            $sql = "INSERT INTO patients (Nom_p, Prenom_p, Sexe_p, Num_secu, photo) 
                    VALUES (:nom, :prenom, :sexe, :num_secu, :photo)";
            $db->prepare($sql);
            $db->bind(':nom', $this->nom_p);
            $db->bind(':prenom', $this->prenom_p);
            $db->bind(':sexe', $this->sexe_p);
            $db->bind(':num_secu', $this->num_secu);
            $db->bind(':photo', $this->photo);
            if ($db->execute()) {
                $this->patientId = (int)$db->lastInsertId();
                return $this->patientId;
            }
        }

        return null;
    }

    public static function findById($id): ?Patient {
        $db = Database::getInstance();
        $sql = "SELECT * FROM patients WHERE PatientId = :id";
        $result = $db->single($sql, [':id' => $id]);

        if ($result) {
            return new Patient(
                $result['PatientId'],
                $result['Nom_p'],
                $result['Prenom_p'],
                $result['Sexe_p'],
                $result['Num_secu'],
                $result['photo'] ?? null
            );
        }

        return null;
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $patients = [];

        $results = $db->query("SELECT * FROM patients");

        foreach ($results as $row) {
            $patients[] = new Patient(
                $row['PatientId'],
                $row['Nom_p'],
                $row['Prenom_p'],
                $row['Sexe_p'],
                $row['Num_secu'],
                $row['photo'] ?? null
            );
        }

        return $patients;
    }

    public function delete(): bool {
        if (!$this->patientId) return false;

        $db = Database::getInstance();
        $sql = "DELETE FROM patients WHERE PatientId = :id";
        $db->prepare($sql);
        $db->bind(':id', $this->patientId);

        return $db->execute();
    }

    // Gestion des allergies
    public function getAllergies(): array {
        $db = Database::getInstance();
        $allergies = [];

        $sql = "SELECT a.* FROM allergies a 
                JOIN patient_allergie pa ON a.AllergieId = pa.AllergieId 
                WHERE pa.PatientId = :patientId";

        $results = $db->query($sql, [':patientId' => $this->patientId]);

        foreach ($results as $row) {
            $allergies[] = new Allergie(
                $row['AllergieId'],
                $row['Libelle_al']
            );
        }

        return $allergies;
    }

    public function addAllergie(int $allergieId): bool {
        $db = Database::getInstance();
        $sql = "INSERT IGNORE INTO patient_allergie (PatientId, AllergieId) 
                VALUES (:patientId, :allergieId)";
        $db->prepare($sql);
        $db->bind(':patientId', $this->patientId);
        $db->bind(':allergieId', $allergieId);

        return $db->execute();
    }

    public function removeAllergie(int $allergieId): bool {
        $db = Database::getInstance();
        $sql = "DELETE FROM patient_allergie 
                WHERE PatientId = :patientId AND AllergieId = :allergieId";
        $db->prepare($sql);
        $db->bind(':patientId', $this->patientId);
        $db->bind(':allergieId', $allergieId);

        return $db->execute();
    }

    // Gestion des antécédents
    public function getAntecedents(): array {
        $db = Database::getInstance();
        $antecedents = [];

        $sql = "SELECT a.* FROM antecedents a 
                JOIN patient_antecedent pa ON a.AntecedentId = pa.AntecedentId 
                WHERE pa.PatientId = :patientId";
        $results = $db->query($sql, [':patientId' => $this->patientId]);

        foreach ($results as $row) {
            $antecedents[] = new Antecedent(
                $row['AntecedentId'],
                $row['Libelle_a']
            );
        }

        return $antecedents;
    }

    public function addAntecedent(int $antecedentId): bool {
        $db = Database::getInstance();
        $sql = "INSERT IGNORE INTO patient_antecedent (PatientId, AntecedentId) 
                VALUES (:patientId, :antecedentId)";
        $db->prepare($sql);
        $db->bind(':patientId', $this->patientId);
        $db->bind(':antecedentId', $antecedentId);

        return $db->execute();
    }

    public function removeAntecedent(int $antecedentId): bool {
        $db = Database::getInstance();
        $sql = "DELETE FROM patient_antecedent 
                WHERE PatientId = :patientId AND AntecedentId = :antecedentId";
        $db->prepare($sql);
        $db->bind(':patientId', $this->patientId);
        $db->bind(':antecedentId', $antecedentId);

        return $db->execute();
    }
    public static function search(string $query): array {
        $db = \App\Core\Database::getInstance();
        $query = trim($query);
        $likeQuery = '%' . $query . '%';
        $sql = "SELECT * FROM patients 
            WHERE CONCAT_WS(' ', Nom_p, Prenom_p, Num_secu) LIKE :q
            ORDER BY Nom_p, Prenom_p";
        $params = [':q' => $likeQuery];
        $results = $db->query($sql, $params);
        $patients = [];
        foreach ($results as $row) {
            $patients[] = new Patient(
                $row['PatientId'],
                $row['Nom_p'],
                $row['Prenom_p'],
                $row['Sexe_p'],
                $row['Num_secu'],
                $row['photo'] ?? null
            );
        }
        return $patients;
    }


}
