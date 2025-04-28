<?php

    namespace App\Models;

    use App\Core\Database;

    class Allergie {
        private ?int $allergieId;
        private string $libelle_al;

        public function __construct($allergieId = null, $libelle_al = '') {
            $this->allergieId = $allergieId;
            $this->libelle_al = $libelle_al;
        }

        // Getters et Setters
        public function getAllergieId(): ?int {
            return $this->allergieId;
        }

        public function getLibelle(): string {
            return $this->libelle_al;
        }

        public function setLibelle($libelle_al): void {
            $this->libelle_al = $libelle_al;
        }

        // Méthodes CRUD
        public function save(): ?int {
            $db = Database::getInstance();

            if ($this->allergieId) {
                // Update
                $sql = "UPDATE allergies SET Libelle_al = :libelle WHERE AllergieId = :id";
                $db->prepare($sql);
                $db->bind(':libelle', $this->libelle_al);
                $db->bind(':id', $this->allergieId);
                if ($db->execute()) {
                    return $this->allergieId;
                }
            } else {
                // Insert
                $sql = "INSERT INTO allergies (Libelle_al) VALUES (:libelle)";
                $db->prepare($sql);
                $db->bind(':libelle', $this->libelle_al);
                if ($db->execute()) {
                    $this->allergieId = (int)$db->lastInsertId();
                    return $this->allergieId;
                }
            }

            return null;
        }

        public static function findById($id): ?Allergie {
            $db = Database::getInstance();
            $sql = "SELECT * FROM allergies WHERE AllergieId = :id";
            $result = $db->single($sql, [':id' => $id]);

            if ($result) {
                return new Allergie($result['AllergieId'], $result['Libelle_al']);
            }

            return null;
        }

        public static function getAll(): array {
            $db = Database::getInstance();
            $allergies = [];

            $results = $db->query("SELECT * FROM allergies");

            foreach ($results as $row) {
                $allergies[] = new Allergie($row['AllergieId'], $row['Libelle_al']);
            }

            return $allergies;
        }

        public function delete(): bool {
            if (!$this->allergieId) return false;

            $db = Database::getInstance();
            $sql = "DELETE FROM allergies WHERE AllergieId = :id";
            $db->prepare($sql);
            $db->bind(':id', $this->allergieId);

            return $db->execute();
        }

        public function getMedicaments(): array {
            $db = Database::getInstance();
            $medicaments = [];

            $sql = "SELECT m.* FROM medicaments m 
            JOIN medicament_allergie ma ON m.MedicamentId = ma.MedicamentId 
            WHERE ma.AllergieId = :allergieId";

            $results = $db->query($sql, [':allergieId' => $this->allergieId]);

            foreach ($results as $row) {
                $medicaments[] = new Medicament(
                    $row['MedicamentId'],
                    $row['Libelle_med'],
                    $row['Contr_indication'],
                    $row['Stock']
                );
            }

            return $medicaments;
        }

        public function getPatients(): array {
            $db = Database::getInstance();
            $patients = [];

            $sql = "SELECT p.* FROM patients p 
            JOIN patient_allergie pa ON p.PatientId = pa.PatientId 
            WHERE pa.AllergieId = :allergieId";

            $results = $db->query($sql, [':allergieId' => $this->allergieId]);

            foreach ($results as $row) {
                $patients[] = new Patient(
                    $row['PatientId'],
                    $row['Nom_p'],
                    $row['Prenom_p'],
                    $row['Sexe_p'],
                    $row['Num_secu']
                );
            }

            return $patients;
        }

        public function addMedicament(int $medicamentId): bool {
            $db = Database::getInstance();
            $sql = "INSERT IGNORE INTO medicament_allergie (MedicamentId, AllergieId) 
            VALUES (:medicamentId, :allergieId)";
            $db->prepare($sql);
            $db->bind(':medicamentId', $medicamentId);
            $db->bind(':allergieId', $this->allergieId);

            return $db->execute();
        }

        public function removeMedicament(int $medicamentId): bool {
            $db = Database::getInstance();
            $sql = "DELETE FROM medicament_allergie 
            WHERE MedicamentId = :medicamentId AND AllergieId = :allergieId";
            $db->prepare($sql);
            $db->bind(':medicamentId', $medicamentId);
            $db->bind(':allergieId', $this->allergieId);

            return $db->execute();
        }

        public function addPatient(int $patientId): bool {
            $db = Database::getInstance();
            $sql = "INSERT IGNORE INTO patient_allergie (PatientId, AllergieId) 
            VALUES (:patientId, :allergieId)";
            $db->prepare($sql);
            $db->bind(':patientId', $patientId);
            $db->bind(':allergieId', $this->allergieId);

            return $db->execute();
        }

        public function removePatient(int $patientId): bool {
            $db = Database::getInstance();
            $sql = "DELETE FROM patient_allergie 
            WHERE PatientId = :patientId AND AllergieId = :allergieId";
            $db->prepare($sql);
            $db->bind(':patientId', $patientId);
            $db->bind(':allergieId', $this->allergieId);

            return $db->execute();
        }
    }