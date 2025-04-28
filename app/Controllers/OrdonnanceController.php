<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\Medicament;
use App\Core\Database;
use DateTime;
use Dompdf\Dompdf;

class OrdonnanceController extends Controller {

    public function index() {
        $ordonnances = Ordonnance::getAll();
        $this->view('ordonnance/index-view', ['ordonnances' => $ordonnances]);
    }

    public function create() {
        // Récupération des données nécessaires
        $patients = Patient::getAll();
        $medicaments = Medicament::getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posologie = $_POST['posologie'] ?? '';
            $duree_traitement = $_POST['duree_traitement'] ?? '';
            $instructions = $_POST['instructions'] ?? '';
            $medecinId = $_SESSION['userId'];
            $patientId = $_POST['patientId'] ?? 0;
            $selectedMedicaments = $_POST['medicaments'] ?? []; // Chaque ligne contient 'id' et 'quantite'

            if (empty($posologie) || empty($duree_traitement) || empty($instructions) || empty($medecinId) || empty($patientId)) {
                $_SESSION['error_message'] = "Tous les champs obligatoires doivent être renseignés.";
                $this->view('ordonnance/add-view', ['patients' => $patients, 'medicaments' => $medicaments]);
                return;
            }

            // Récupérer le patient et ses allergies
            $patient = Patient::findById($patientId);
            if (!$patient) {
                $_SESSION['error_message'] = "Patient non trouvé.";
                $this->view('ordonnance/add-view', ['patients' => $patients, 'medicaments' => $medicaments]);
                return;
            }
            $patientAllergies = $patient->getAllergies();
            $patientAllergieIds = [];
            foreach ($patientAllergies as $allergie) {
                $patientAllergieIds[] = $allergie->getAllergieId();
            }

            // Vérifier pour chaque médicament sélectionné qu'il n'a pas d'allergie en commun avec le patient
            $conflictFound = false;
            foreach ($selectedMedicaments as $line) {
                $medId = $line['id'] ?? '';
                if (!empty($medId)) {
                    $medicament = Medicament::findById($medId);
                    if ($medicament) {
                        $medAllergies = $medicament->getAllergies();
                        foreach ($medAllergies as $medAllergie) {
                            if (in_array($medAllergie->getAllergieId(), $patientAllergieIds)) {
                                $conflictFound = true;
                                break 2;
                            }
                        }
                    }
                }
            }
            if ($conflictFound) {
                $_SESSION['error_message'] = "Conflit allergique détecté : le patient est allergique à l'un des médicaments sélectionnés.";
                $this->view('ordonnance/add-view', ['patients' => $patients, 'medicaments' => $medicaments]);
                return;
            }

            // Création de l'ordonnance avec ses associations
            $ordonnance = new Ordonnance(null, $posologie, new DateTime(), $duree_traitement, $instructions, $medecinId, $patientId);
            $ordonnance->setMedicaments($selectedMedicaments);
            if ($ordonnance->save()) {
                $_SESSION['success_message'] = "L'ordonnance a été ajoutée avec succès.";
                header('Location: /ordonnance');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la création de l'ordonnance.";
            }
        }
        if (isset($_GET['patientId'])) {
            $patientId = $_GET['patientId'];
            $patient = Patient::findById($patientId);
            $this->view('ordonnance/add-view', ['patient' => $patient]);

        } else {
        $this->view('ordonnance/add-view');
        }
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: /ordonnance');
            exit;
        }
        $medicaments = Medicament::getAll();
        $ordonnance = Ordonnance::findById($id);
        if (!$ordonnance) {
            $_SESSION['error_message'] = "L'ordonnance demandée n'existe pas.";
            header('Location: /ordonnance');
            exit;
        }
        // Récupération des associations de médicaments pour préremplir le formulaire
        $db = Database::getInstance();
        $sql = "SELECT MedicamentId, quantity FROM ordonnance_medicament WHERE ordonnanceId = :id";
        $result = $db->query($sql, [':id' => $ordonnance->getOrdonnanceId()]);
        $associatedMedicaments = [];
        $patient = Patient::findById($ordonnance->getPatientId());
        foreach ($result as $row) {
            $medicament = Medicament::findById($row['MedicamentId']);
            if ($medicament) {
                $medicament->quantite = $row['quantity'];
                $associatedMedicaments[] = $medicament;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ordonnance->setPosologie($_POST['posologie'] ?? '');
            $ordonnance->setDureeTraitement($_POST['duree_traitement'] ?? '');
            $ordonnance->setInstructionsSpecifique($_POST['instructions'] ?? '');
            $medecinId = $_SESSION['userId'] ?? '';
            $patientId = $_POST['patientId'] ?? 0;
            $ordonnance->setMedecinId($medecinId);
            $ordonnance->setPatientId($patientId);
            $selectedMedicaments = $_POST['medicaments'] ?? [];

            // Vérification des conflits allergiques
            $patient = Patient::findById($patientId);
            $patientAllergies = $patient->getAllergies();
            $patientAllergieIds = [];
            foreach ($patientAllergies as $allergie) {
                $patientAllergieIds[] = $allergie->getAllergieId();
            }
            $conflictFound = false;
            foreach ($selectedMedicaments as $line) {
                $medId = $line['id'] ?? '';
                if (!empty($medId)) {
                    $medicament = Medicament::findById($medId);
                    if ($medicament) {
                        $medAllergies = $medicament->getAllergies();
                        foreach ($medAllergies as $medAllergie) {
                            if (in_array($medAllergie->getAllergieId(), $patientAllergieIds)) {
                                $conflictFound = true;
                                break 2;
                            }
                        }
                    }
                }
            }
            if ($conflictFound) {
                // Reconstruire le tableau avec les quantités modifiées par l'utilisateur
                $associatedMedicaments = [];
                foreach ($selectedMedicaments as $line) {
                    if (!empty($line['id'])) {
                        $med = Medicament::findById($line['id']);
                        if ($med) {
                            $med->quantite = $line['quantite']; // Affecter la quantité modifiée
                            $associatedMedicaments[] = $med;
                        }
                    }
                }
                $_SESSION['error_message'] = "Conflit allergique détecté : le patient est allergique à l'un des médicaments sélectionnés.";
                $this->view('ordonnance/edit-view', [
                    'ordonnance' => $ordonnance,
                    'patients' => $patients,
                    'medicaments' => $medicaments,
                    'associatedMedicaments' => $associatedMedicaments
                ]);
                return;
            }


            // Affecter les associations et sauvegarder
            $ordonnance->setMedicaments($selectedMedicaments);

            if ($ordonnance->save()) {
                $_SESSION['success_message'] = "L'ordonnance a été mise à jour avec succès.";
                header('Location: /ordonnance');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour de l'ordonnance.";
            }
        }
        $this->view('ordonnance/edit-view', [
            'ordonnance' => $ordonnance,
            'patient' => $patient,
            'medicaments' => $medicaments,
            'associatedMedicaments' => $associatedMedicaments
        ]);
    }

    public function details($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID de l'ordonnance non spécifié.";
            header('Location: /ordonnance');
            exit;
        }
        $ordonnance = Ordonnance::findById($id);
        if (!$ordonnance) {
            $_SESSION['error_message'] = "L'ordonnance demandée n'existe pas.";
            header('Location: /ordonnance');
            exit;
        }
        // Récupérer les médicaments associés
        $db = Database::getInstance();
        $sql = "SELECT m.*, om.quantity FROM medicaments m 
                JOIN ordonnance_medicament om ON m.MedicamentId = om.medicamentId 
                WHERE om.ordonnanceId = :id";
        $medicamentRows = $db->query($sql, [':id' => $ordonnance->getOrdonnanceId()]);
        $medicamentsAssocies = [];
        foreach ($medicamentRows as $row) {
            $medicament = new Medicament(
                $row['MedicamentId'],
                $row['Libelle_med'],
                $row['Contr_indication'],
                $row['Stock']
            );
            // On ajoute la quantité
            $medicament->quantite = $row['quantity'];
            $medicamentsAssocies[] = $medicament;
        }
        $this->view('ordonnance/details-view', [
            'ordonnance' => $ordonnance,
            'medicaments' => $medicamentsAssocies
        ]);
    }

    public function delete($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID de l'ordonnance non spécifié.";
            header('Location: /ordonnance');
            exit;
        }
        $ordonnance = Ordonnance::findById($id);
        if (!$ordonnance) {
            $_SESSION['error_message'] = "L'ordonnance demandée n'existe pas.";
            header('Location: /ordonnance');
            exit;
        }
        if ($ordonnance->delete()) {
            $_SESSION['success_message'] = "L'ordonnance a été supprimée avec succès.";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'ordonnance.";
        }
        header('Location: /ordonnance');
        exit;
    }
    public function pdf($id = null)
    {
        if (!$id) {
            $_SESSION['error_message'] = "ID de l'ordonnance non spécifié.";
            header('Location: /ordonnance');
            exit;
        }

        // Récupération de l'ordonnance
        $ordonnance = Ordonnance::findById($id);
        if (!$ordonnance) {
            $_SESSION['error_message'] = "Ordonnance introuvable.";
            header('Location: /ordonnance');
            exit;
        }

        // Récupérer les médicaments associés
        $db = Database::getInstance();
        $sql = "SELECT m.*, om.quantity FROM medicaments m 
            JOIN ordonnance_medicament om ON m.MedicamentId = om.medicamentId 
            WHERE om.ordonnanceId = :id";
        $medicamentRows = $db->query($sql, [':id' => $ordonnance->getOrdonnanceId()]);
        $medicaments = [];
        foreach ($medicamentRows as $row) {
            $med = new Medicament(
                $row['MedicamentId'],
                $row['Libelle_med'],
                $row['Contr_indication'],
                $row['Stock']
            );
            $med->quantite = $row['quantity'];
            $medicaments[] = $med;
        }

        // 3) Capture du HTML
        ob_start();
        $this->view('ordonnance/pdf-view', [
            'ordonnance'  => $ordonnance,
            'medicaments' => $medicaments
        ], false);
        $html = ob_get_clean();

        // 3. Instancier Dompdf avec options
        $dompdf = new Dompdf([
            'defaultFont'     => 'DejaVu Sans',
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("ordonnance_$id.pdf", ['Attachment' => false]);
        exit;
    }
}
