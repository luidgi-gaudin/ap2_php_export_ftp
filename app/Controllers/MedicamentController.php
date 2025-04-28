<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Medicament;
use App\Models\Allergie;

class MedicamentController extends Controller {
    public function index() {
        $medicaments = Medicament::getAll();
        $this->view('medicament/index-view', ['medicaments' => $medicaments]);
    }

    public function create() {
        $allergies = Allergie::getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            $contrIndication = $_POST['contr_indication'] ?? '';
            $selectedAllergies = $_POST['allergies'] ?? []; // tableau d'IDs d'allergies

            if (empty($libelle)) {
                $_SESSION['error_message'] = "Le libellé du médicament est requis";
                $this->view('medicament/add-view', ['allergies' => $allergies]);
                return;
            }

            $medicament = new Medicament(null, $libelle, $contrIndication);
            if ($medicament->save()) {
                // Ajout des allergies associées
                foreach ($selectedAllergies as $allergieId) {
                    if (!empty($allergieId)) {
                        $medicament->addAllergie((int)$allergieId);
                    }
                }
                $_SESSION['success_message'] = "Le médicament a été ajouté avec succès";
                header('Location: /medicament');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout du médicament";
            }
        }
        $this->view('medicament/add-view', ['allergies' => $allergies]);
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: /medicament');
            exit;
        }

        $medicament = Medicament::findById($id);
        if (!$medicament) {
            $_SESSION['error_message'] = "Le médicament demandé n'existe pas";
            header('Location: /medicament');
            exit;
        }

        $allergies = Allergie::getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicament->setLibelle($_POST['libelle'] ?? '');
            $medicament->setContrIndication($_POST['contr_indication'] ?? '');
            if ($medicament->save()) {
                // Mise à jour des allergies : on supprime les associations existantes et on réajoute
                foreach ($medicament->getAllergies() as $allergy) {
                    $medicament->removeAllergie($allergy->getAllergieId());
                }
                $selectedAllergies = $_POST['allergies'] ?? [];
                foreach ($selectedAllergies as $allergieId) {
                    if (!empty($allergieId)) {
                        $medicament->addAllergie((int)$allergieId);
                    }
                }
                $_SESSION['success_message'] = "Le médicament a été mis à jour avec succès";
                header('Location: /medicament');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour du médicament";
            }
        }
        $this->view('medicament/edit-view', ['medicament' => $medicament, 'allergies' => $allergies]);
    }

    public function details($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID du médicament non spécifié";
            header('Location: /medicament');
            exit;
        }

        $medicament = Medicament::findById($id);
        if (!$medicament) {
            $_SESSION['error_message'] = "Le médicament demandé n'existe pas";
            header('Location: /medicament');
            exit;
        }

        $allergies = $medicament->getAllergies();
        $this->view('medicament/details-view', ['medicament' => $medicament, 'allergies' => $allergies]);
    }

    public function delete($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID du médicament non spécifié";
            header('Location: /medicament');
            exit;
        }

        $medicament = Medicament::findById($id);
        if (!$medicament) {
            $_SESSION['error_message'] = "Le médicament demandé n'existe pas";
        } elseif ($medicament->delete()) {
            $_SESSION['success_message'] = "Le médicament a été supprimé avec succès";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression du médicament";
        }

        header('Location: /medicament');
        exit;
    }

    public function search() {
        $query = $_GET['query'] ?? '';
        $excludeAllergyIds = isset($_GET['excludeAllergyIds']) ? explode(',', $_GET['excludeAllergyIds']) : [];
        if (empty($query)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }
        // Méthode à implémenter dans votre modèle Medicament pour rechercher par libellé et exclure selon les IDs d'allergies
        $medicaments = Medicament::search($query, $excludeAllergyIds);
        $data = [];
        foreach ($medicaments as $med) {
            $data[] = [
                'id'      => $med->getMedicamentId(),
                'libelle' => $med->getLibelle()
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
