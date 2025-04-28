<?php
$medecin = $ordonnance->getMedecin();
$patient = $ordonnance->getPatient();
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Détails de l'ordonnance</h1>
        <a href="/ordonnance" class="neo-btn neo-btn-primary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div class="neo-card">
        <h3> <?= htmlspecialchars($patient->getNom()) ?> <?= htmlspecialchars($patient->getPrenom())?></h3>
        <p><strong>Posologie :</strong> <?= htmlspecialchars($ordonnance->getPosologie()) ?></p>
        <p><strong>Durée du traitement :</strong> <?= htmlspecialchars($ordonnance->getDureeTraitement()) ?></p>
        <p><strong>Instructions :</strong> <?= htmlspecialchars($ordonnance->getInstructionsSpecifique()) ?></p>
        <p><strong>Médecin :</strong> <?= htmlspecialchars($medecin->getNom()) ?> <?= htmlspecialchars($medecin->getPrenom())?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($ordonnance->getDateCreation()->format('d/m/Y')) ?></p>

        <h3 class="neo-title" style="font-size:1.25rem;">Médicaments prescrits</h3>
        <?php if(!empty($medicaments)): ?>
            <ul class="list-group">
                <?php foreach($medicaments as $m): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($m->getLibelle()) ?>
                        <?php
                        $medAllergies = $m->getAllergies();
                        if(!empty($medAllergies)):
                            $names = [];
                            foreach($medAllergies as $a) {
                                $names[] = $a->getLibelle();
                            }
                            ?>
                            <small> (Allergies : <?= implode(', ', $names) ?>)</small>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun médicament prescrit.</p>
        <?php endif; ?>
        <a href="/ordonnance/pdf/<?= $ordonnance->getOrdonnanceId() ?>" target="_blank" class="neo-btn neo-btn-primary">
            <i class="bi bi-file-earmark-pdf"></i> Télécharger PDF
        </a>

    </div>
</div>

<style>
    :root {
        --neo-bg-color: #e0e5ec;
        --neo-light-shadow: rgba(255, 255, 255, 0.7);
        --neo-dark-shadow: rgba(70, 70, 70, 0.12);
        --neo-primary: #6a8caf;
        --neo-info: #5eadb0;
        --neo-danger: #e17a7a;
        --neo-text: #566573;
        --neo-border-radius: 20px;
    }
    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow), -8px -8px 16px var(--neo-light-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    .neo-title {
        color: var(--neo-text);
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }
    .list-group {
        margin-top: 1rem;
        text-align: left;
        padding: 0;
        list-style: none;
    }
    .list-group-item {
        background-color: var(--neo-bg-color);
        border: none;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow), -3px -3px 6px var(--neo-light-shadow);
        border-radius: var(--neo-border-radius);
        margin-bottom: 0.5rem;
        padding: 1rem;
    }
    .neo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        background-color: var(--neo-bg-color);
        border: none;
        border-radius: var(--neo-border-radius);
        color: var(--neo-text);
        font-weight: 500;
        text-decoration: none;
        box-shadow: 4px 4px 8px var(--neo-dark-shadow), -4px -4px 8px var(--neo-light-shadow);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .neo-btn-primary {
        color: var(--neo-primary);
    }
</style>
