<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Allergie;

class AllergieController extends Controller
{
    public function index()
    {
        $this->view('allergie/index-view');
    }
    public function edit($id = null)
    {
        if (!$id) {
            // Rediriger vers la liste si aucun ID n'est fourni
            header('Location: /allergie');
            exit;
        }

        $allergie = Allergie::findById($id);

        if (!$allergie) {
            // Rediriger si l'allergie n'existe pas
            $_SESSION['error_message'] = "L'allergie demandée n'existe pas";
            header('Location: /allergie');
            exit;
        }

        // Traitement du formulaire si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $allergie->setLibelle($_POST['libelle'] ?? '');

            if ($allergie->save()) {
                $_SESSION['success_message'] = "L'allergie a été mise à jour avec succès";
                header('Location: /allergie');
                exit;
            }
        }

        // Afficher le formulaire d'édition
        $this->view('allergie/edit-view', ['allergie' => $allergie]);
    }

    public function create()
    {
        // Traitement du formulaire si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';

            if (empty($libelle)) {
                $_SESSION['error_message'] = "Le libellé de l'allergie est requis";
                $this->view('allergie/add-view');
                return;
            }

            $allergie = new Allergie(null, $libelle);

            if ($allergie->save()) {
                $_SESSION['success_message'] = "L'allergie a été ajoutée avec succès";
                header('Location: /allergie');
                exit;
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout de l'allergie";
            }
        }

        // Afficher le formulaire d'ajout
        $this->view('allergie/add-view');
    }

    public function details($id = null)
    {
        if (!$id) {
            $_SESSION['error_message'] = "ID d'allergie non spécifié";
            header('Location: /allergie');
            exit;
        }

        $allergie = Allergie::findById($id);

        if (!$allergie) {
            $_SESSION['error_message'] = "L'allergie demandée n'existe pas";
            header('Location: /allergie');
            exit;
        }
        $medicaments = $allergie->getMedicaments();

        $this->view('allergie/details-view', ['allergie' => $allergie, 'medicaments' => $medicaments]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            $_SESSION['error_message'] = "ID d'allergie non spécifié";
            header('Location: /allergie');
            exit;
        }

        $allergie = Allergie::findById($id);

        if (!$allergie) {
            $_SESSION['error_message'] = "L'allergie demandée n'existe pas";
        } else if ($allergie->delete()) {
            $_SESSION['success_message'] = "L'allergie a été supprimée avec succès";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'allergie";
        }

        header('Location: /allergie');
        exit;
    }

}