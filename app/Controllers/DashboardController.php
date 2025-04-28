<?php
namespace App\Controllers;

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireLogin();
        $db = Database::getInstance();

        // Récupération des données directement
        $totalPatients = (int)$db->single("SELECT COUNT(*) AS count FROM patients")['count'];
        $totalOrdonnances = (int)$db->single("SELECT COUNT(*) AS count FROM ordonnances")['count'];

        $prescriptionsByDay = $db->query(
            "SELECT DATE(DateCréation) AS date, COUNT(*) AS count
             FROM ordonnances
             GROUP BY DATE(DateCréation)
             ORDER BY date DESC
             LIMIT 7"
        );

        $topMeds = $db->query(
            "SELECT m.Libelle_med AS label, SUM(om.quantity) AS count
             FROM ordonnance_medicament om
             JOIN medicaments m ON m.MedicamentId = om.medicamentId
             GROUP BY m.Libelle_med
             ORDER BY count DESC
             LIMIT 5"
        );

        $stockLevels = $db->query("SELECT Libelle_med AS label, Stock AS value FROM medicaments");

        // Passer les données à la vue
        $this->view('dashboard/index-view', [
            'totalPatients' => $totalPatients,
            'totalOrdonnances' => $totalOrdonnances,
            'prescriptionsByDay' => $prescriptionsByDay,
            'topMeds' => $topMeds,
            'stockLevels' => $stockLevels
        ]);
    }
}