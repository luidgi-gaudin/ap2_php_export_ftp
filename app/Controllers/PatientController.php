<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Allergie;
use App\Models\Antecedent;
use App\Models\Patient;
use App\Models\Ordonnance;
use App\Core\Database;

class PatientController extends Controller {

    public function index() {
        $patients = Patient::getAll();
        $this->view('patient/index-view', ['patients' => $patients]);
    }

    public function create() {
        $antecedents = Antecedent::getAll();
        $allergies = Allergie::getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $sexe = $_POST['sexe'] ?? '';
            $num_secu = $_POST['num_secu'] ?? '';

            if (empty($nom) || empty($prenom) || empty($sexe) || empty($num_secu)) {
                $_SESSION['error_message'] = "Tous les champs sont requis.";
                $this->view('patient/add-view', ['allergies' => $allergies, 'antecedents' => $antecedents]);
                return;
            }

            $patient = new Patient(null, $nom, $prenom, $sexe, $num_secu);

            // Gestion de l'upload de la photo
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $filename = time() . '_' . $_FILES['photo']['name'];
                // Adaptez ce chemin selon votre architecture
                $destination = __DIR__ . '/../../public/images/patients/' . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                    $patient->setPhoto('/images/patients/' . $filename);
                }
            }

            if ($patient->save()) {
                // Gestion des allergies associées
                $selectedAllergies = $_POST['allergies'] ?? [];
                foreach ($selectedAllergies as $allergieId) {
                    if (!empty($allergieId)) {
                        $patient->addAllergie((int)$allergieId);
                    }
                }

                // Gestion des antécédents associés
                $selectedAntecedents = $_POST['antecedents'] ?? [];
                if (method_exists($patient, 'addAntecedent')) {
                    foreach ($selectedAntecedents as $antecedentId) {
                        if (!empty($antecedentId)) {
                            $patient->addAntecedent((int)$antecedentId);
                        }
                    }
                }

                $_SESSION['success_message'] = "Le patient a été ajouté avec succès.";
                header('Location: /patient');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout du patient.";
            }
        }
        $this->view('patient/add-view', ['allergies' => $allergies, 'antecedents' => $antecedents]);
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: /patient');
            exit;
        }
        $allergies = Allergie::getAll();
        $antecedents = Antecedent::getAll();
        $patient = Patient::findById($id);
        if (!$patient) {
            $_SESSION['error_message'] = "Le patient demandé n'existe pas.";
            header('Location: /patient');
            exit;
        }

        // Récupérer les associations déjà existantes
        $patientAllergies = $patient->getAllergies();
        $patientAntecedents = [];
        if (method_exists($patient, 'getAntecedents')) {
            $patientAntecedents = $patient->getAntecedents();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient->setNom($_POST['nom'] ?? '');
            $patient->setPrenom($_POST['prenom'] ?? '');
            $patient->setSexe($_POST['sexe'] ?? '');

            // Gestion de l'upload de la photo
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $filename = time() . '_' . $_FILES['photo']['name'];
                $destination = __DIR__ . '/../../public/images/patients/' . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                    $patient->setPhoto('/images/patients/' . $filename);
                }
            }

            if ($patient->save()) {
                // Mise à jour des allergies : suppression des associations existantes et réajout
                foreach ($patient->getAllergies() as $allergie) {
                    $patient->removeAllergie($allergie->getAllergieId());
                }
                $selectedAllergies = $_POST['allergies'] ?? [];
                foreach ($selectedAllergies as $allergieId) {
                    if (!empty($allergieId)) {
                        $patient->addAllergie((int)$allergieId);
                    }
                }

                // Mise à jour des antécédents : suppression puis réajout (si les méthodes existent)
                if (method_exists($patient, 'getAntecedents') && method_exists($patient, 'removeAntecedent') && method_exists($patient, 'addAntecedent')) {
                    foreach ($patient->getAntecedents() as $ant) {
                        $patient->removeAntecedent($ant->getAntecedentId());
                    }
                    $selectedAntecedents = $_POST['antecedents'] ?? [];
                    foreach ($selectedAntecedents as $antecedentId) {
                        if (!empty($antecedentId)) {
                            $patient->addAntecedent((int)$antecedentId);
                        }
                    }
                }

                $_SESSION['success_message'] = "Le patient a été mis à jour avec succès.";
                header('Location: /patient');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour du patient.";
            }
        }
        $this->view('patient/edit-view', [
            'patient' => $patient,
            'allergies' => $allergies,
            'antecedents' => $antecedents,
            'patientAllergies' => $patientAllergies,
            'patientAntecedents' => $patientAntecedents
        ]);
    }

    public function details($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID du patient non spécifié.";
            header('Location: /patient');
            exit;
        }
        $patient = Patient::findById($id);
        if (!$patient) {
            $_SESSION['error_message'] = "Le patient demandé n'existe pas.";
            header('Location: /patient');
            exit;
        }
        $allergies = $patient->getAllergies();
        $antecedents = [];
        if (method_exists($patient, 'getAntecedents')) {
            $antecedents = $patient->getAntecedents();
        }
        // Récupération des ordonnances associées
        $db = Database::getInstance();
        $sql = "SELECT * FROM ordonnances WHERE PatientId = :id";
        $ordonnancesRows = $db->query($sql, [':id' => $patient->getPatientId()]);
        $ordonnances = [];
        foreach ($ordonnancesRows as $row) {
            $ordonnances[] = new Ordonnance(
                $row['OrdonnanceId'],
                $row['Posologie'],
                new \DateTime($row['DateCréation']),
                $row['Duree_traitement'],
                $row['Instructions_specifique'],
                $row['MedecinId'],
                $row['PatientId']
            );
        }
        $this->view('patient/details-view', [
            'patient' => $patient,
            'allergies' => $allergies,
            'antecedents' => $antecedents,
            'ordonnances' => $ordonnances
        ]);
    }

    public function delete($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID du patient non spécifié.";
            header('Location: /patient');
            exit;
        }
        $patient = Patient::findById($id);
        if (!$patient) {
            $_SESSION['error_message'] = "Le patient demandé n'existe pas.";
            header('Location: /patient');
            exit;
        }
        // Vérifier si le patient a des ordonnances associées
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as count FROM ordonnances WHERE PatientId = :id";
        $result = $db->single($sql, [':id' => $patient->getPatientId()]);
        if ($result && $result['count'] > 0) {
            $_SESSION['error_message'] = "Le patient ne peut être supprimé car il a une ordonnance associée.";
            header('Location: /patient');
            exit;
        }
        if ($patient->delete()) {
            $_SESSION['success_message'] = "Le patient a été supprimé avec succès.";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression du patient.";
        }
        header('Location: /patient');
        exit;
    }
    public function checkNumSecu() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $numSecu = $_GET['num_secu'] ?? '';
            $db = \App\Core\Database::getInstance();
            $sql = "SELECT COUNT(*) as count FROM patients WHERE Num_secu = :num_secu";
            $result = $db->single($sql, [':num_secu' => $numSecu]);
            header('Content-Type: application/json');
            echo json_encode(['exists' => ($result && $result['count'] > 0)]);
            exit;
        }
    }
    public function search() {
        $query = $_GET['query'] ?? '';
        if (empty($query)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }
        // Méthode à implémenter dans votre modèle Patient pour rechercher par nom, prénom ou numéro de secu
        $patients = Patient::search($query);
        $data = [];
        foreach ($patients as $patient) {
            // On récupère les allergies sous forme d'un tableau d'IDs
            $allergies = [];
            foreach ($patient->getAllergies() as $allergy) {
                $allergies[] = $allergy->getAllergieId();
            }
            $data[] = [
                'id'        => $patient->getPatientId(),
                'nom'       => $patient->getNom(),
                'prenom'    => $patient->getPrenom(),
                'num_secu'  => $patient->getNumSecu(), // Correction ici
                'allergies' => $allergies
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}
