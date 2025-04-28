<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Détails du patient</h1>
        <a href="/patient" class="neo-btn neo-btn-primary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div class="neo-card">
        <img src="<?= $patient->getPhoto() ? $patient->getPhoto() : '/images/patients/default.png' ?>"
             alt="Photo de <?= htmlspecialchars($patient->getPrenom() . ' ' . $patient->getNom()) ?>"
             class="patient-photo">
        <h2><?= htmlspecialchars($patient->getPrenom() . ' ' . $patient->getNom()) ?></h2>
        <p><strong>Numéro de sécurité :</strong> <?= htmlspecialchars($patient->getNumSecu()) ?></p>

        <h3 class="neo-title" style="font-size:1.25rem;">Allergies associées</h3>
        <?php if(!empty($allergies)): ?>
            <ul class="list-group">
                <?php foreach($allergies as $allergie): ?>
                    <li class="list-group-item"><?= htmlspecialchars($allergie->getLibelle()) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune allergie associée.</p>
        <?php endif; ?>

        <h3 class="neo-title" style="font-size:1.25rem;">Antécédents associés</h3>
        <?php if(!empty($antecedents)): ?>
            <ul class="list-group">
                <?php foreach($antecedents as $antecedent): ?>
                    <li class="list-group-item"><?= htmlspecialchars($antecedent->getLibelle()) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun antécédent associé.</p>
        <?php endif; ?>

        <h3 class="neo-title" style="font-size:1.25rem;">Ordonnances</h3>
        <a href="/ordonnance/create?patientId=<?= $patient->getPatientId()?>" class="neo-btn neo-btn-primary">
            Crée une ordonnance
        </a>
        <?php if(!empty($ordonnances)): ?>
            <ul class="list-group">
                <?php foreach($ordonnances as $ordo): ?>
                    <li class="list-group-item">
                        <a href="/ordonnance/details/<?= $ordo->getOrdonnanceId() ?>">
                            Ordonnance du <?= $ordo->getDateCreation()->format('d/m/Y') ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune ordonnance associée.</p>
        <?php endif; ?>
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
        box-shadow: 8px 8px 16px var(--neo-dark-shadow),
        -8px -8px 16px var(--neo-light-shadow);
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
    .patient-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        box-shadow: 4px 4px 8px var(--neo-dark-shadow), -4px -4px 8px var(--neo-light-shadow);
    }
    .list-group {
        margin-top: 1rem;
        text-align: left;
    }
    .list-group-item {
        background-color: var(--neo-bg-color);
        border: none;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
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
        box-shadow: 4px 4px 8px var(--neo-dark-shadow),
        -4px -4px 8px var(--neo-light-shadow);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .neo-btn-primary { color: var(--neo-primary); }
</style>
