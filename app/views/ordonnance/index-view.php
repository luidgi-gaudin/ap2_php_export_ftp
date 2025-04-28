<?php use App\Models\User; ?>
<div class="container mt-5">
    <!-- En-tête avec titre et bouton Ajouter -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Ordonnances</h1>
        <a href="/ordonnance/create" class="neo-btn neo-btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter une ordonnance
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="neo-search-container mb-4">
        <input type="text" id="searchInput" class="neo-search-input" placeholder="Rechercher une ordonnance...">
        <i class="bi bi-search neo-search-icon"></i>
    </div>
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="neo-alert neo-alert-success mb-4">
            <i class="bi bi-check2-circle me-2"></i> <?= $_SESSION['success_message']; ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    <!-- Cards d'ordonnances -->
    <?php if(count($ordonnances) > 0):?>
        <div class="row" id="ordonnanceCards">
        <?php foreach($ordonnances as $ordo): ?>
            <div class="col-md-4 mb-4 ordonnance-card">
                <div class="neo-card">
                    <?php
                    $medecin = User::findById($ordo->getMedecinId());
                    // Récupération du patient associé
                    $patient = \App\Models\Patient::findById($ordo->getPatientId());
                    $patientName = $patient ? htmlspecialchars($patient->getPrenom() . ' ' . $patient->getNom()) : 'Inconnu';
                    ?>
                    <h3><?= $patientName ?></h3>
                    <p><strong>Médecin :</strong> <?= $medecin->getNom()?> <?= $medecin->getPrenom() ?></p>
                    <p><strong>Date :</strong> <?= htmlspecialchars($ordo->getDateCreation()->format('d/m/Y')) ?></p>
                    <div class="neo-btn-group">
                        <a href="/ordonnance/details/<?= $ordo->getOrdonnanceId() ?>" class="neo-btn neo-btn-info"><i class="bi bi-eye"></i></a>
                        <a href="/ordonnance/edit/<?= $ordo->getOrdonnanceId() ?>" class="neo-btn neo-btn-info"><i class="bi bi-pencil-square"></i></a>
                        <a href="/ordonnance/delete/<?= $ordo->getOrdonnanceId() ?>" class="neo-btn neo-btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="row">
        <a class="text-center neo-empty">Aucune ordonnance trouvé</a>
    </div>
    <?php endif; ?>
</div>

<style>
    :root {
        --neo-bg-color: #e0e5ec;
        --neo-light-shadow: rgba(255,255,255,0.7);
        --neo-dark-shadow: rgba(70,70,70,0.12);
        --neo-primary: #6a8caf;
        --neo-info: #5eadb0;
        --neo-danger: #e17a7a;
        --neo-text: #566573;
        --neo-border-radius: 20px;
    }
    .neo-title {
        color: var(--neo-text);
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        padding: 1.5rem;
        box-shadow: 8px 8px 16px var(--neo-dark-shadow), -8px -8px 16px var(--neo-light-shadow);
        text-align: center;
    }

    .neo-empty {
        font-style: italic;
        color: #95a5a6;
        padding: 2rem !important;
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
    .neo-btn-primary { color: var(--neo-primary); }
    .neo-btn-info { color: var(--neo-info); }
    .neo-btn-danger { color: var(--neo-danger); }
    .neo-btn-group {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .neo-search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .neo-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: none;
        border-radius: var(--neo-border-radius);
        background-color: var(--neo-bg-color);
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow), inset -4px -4px 8px var(--neo-light-shadow);
        font-size: 1rem;
        color: var(--neo-text);
    }
    .neo-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--neo-text);
        opacity: 0.6;
    }
    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1rem;
        display: flex;
        align-items: center;
    }

    .neo-alert-success {
        background-color: rgba(76, 175, 80, 0.1);
        color: #2e7d32;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
    }
</style>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.ordonnance-card');
        cards.forEach(card => {
            // On recherche dans l'ensemble du texte de la carte
            if (card.textContent.toLowerCase().includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
