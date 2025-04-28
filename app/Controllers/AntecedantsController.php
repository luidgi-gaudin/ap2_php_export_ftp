<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Antecedent;

class AntecedantsController extends Controller {
    public function index() {
        $antecedents = Antecedent::getAll();
        $this->view('antecedents/index-view', ['antecedents' => $antecedents]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';

            if (empty($libelle)) {
                $_SESSION['error_message'] = "Le libellé de l'antécédent est requis";
                $this->view('antecedents/add-view');
                return;
            }

            $antecedent = new Antecedent(null, $libelle);

            if ($antecedent->save()) {
                $_SESSION['success_message'] = "L'antécédent a été ajouté avec succès";
                header('Location: /antecedants');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout de l'antécédent";
            }
        }
        $this->view('antecedents/add-view');
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: /antecedants');
            exit;
        }

        $antecedent = Antecedent::findById($id);

        if (!$antecedent) {
            $_SESSION['error_message'] = "L'antécédent demandé n'existe pas";
            header('Location: /antecedants');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $antecedent->setLibelle($_POST['libelle'] ?? '');

            if ($antecedent->save()) {
                $_SESSION['success_message'] = "L'antécédent a été mis à jour avec succès";
                header('Location: /antecedants');
                exit;
            }
        }

        $this->view('antecedents/edit-view', ['antecedent' => $antecedent]);
    }

    public function details($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID d'antécédent non spécifié";
            header('Location: /antecedants');
            exit;
        }

        $antecedent = Antecedent::findById($id);

        if (!$antecedent) {
            $_SESSION['error_message'] = "L'antécédent demandé n'existe pas";
            header('Location: /antecedants');
            exit;
        }

        $patients = $antecedent->getPatients();

        $this->view('antecedents/details-view', [
            'antecedent' => $antecedent,
            'patients' => $patients
        ]);
    }

    public function delete($id = null) {
        if (!$id) {
            $_SESSION['error_message'] = "ID d'antécédent non spécifié";
            header('Location: /antecedants');
            exit;
        }

        $antecedent = Antecedent::findById($id);

        if (!$antecedent) {
            $_SESSION['error_message'] = "L'antécédent demandé n'existe pas";
        } elseif ($antecedent->delete()) {
            $_SESSION['success_message'] = "L'antécédent a été supprimé avec succès";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'antécédent";
        }

        header('Location: /antecedants');
        exit;
    }
}
