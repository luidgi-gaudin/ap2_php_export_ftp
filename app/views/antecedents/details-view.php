<div class="neo-container" style="justify-content: center">
    <div class="neo-card">
        <h2>Détails de l'antécédent</h2>

        <?php if (isset($antecedent)): ?>
            <div class="neo-details">
                <div class="neo-detail-item">
                    <span class="label">Libellé :</span>
                    <span class="value"><?= $antecedent->getLibelle() ?></span>
                </div>
            </div>

            <!-- Section des patients associés -->
            <div class="neo-section">
                <h3 class="neo-title">Patients ayant cet antécédent</h3>

                <?php if (!empty($patients)): ?>
                    <ul>
                        <?php foreach ($patients as $patient): ?>
                            <li>
                                <a href="/patient/details/<?= $patient->getPatientId() ?>" class="neo-btn neo-btn-info neo-btn-small">
                                    <?= $patient->getNom() ?> <?= $patient->getPrenom() ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="neo-alert neo-alert-info">
                        Aucun patient n'est associé à cet antécédent.
                    </div>
                <?php endif; ?>
            </div>

            <div class="neo-btn-group" style="margin-top: 2rem;">
                <a href="/antecedants" class="neo-btn">Retour</a>
                <a href="/antecedants/edit/<?= $antecedent->getAntecedentId() ?>" class="neo-btn neo-btn-primary">Modifier</a>
                <button class="neo-btn neo-btn-danger" onclick="confirmDelete(<?= $antecedent->getAntecedentId() ?>)">Supprimer</button>
            </div>
        <?php else: ?>
            <div class="neo-alert neo-alert-danger">
                L'antécédent demandé n'existe pas.
            </div>
            <div class="neo-btn-group">
                <a href="/antecedants" class="neo-btn">Retour</a>
            </div>
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
        --neo-border-radius: 15px;
        --neo-transition: all 0.3s ease;
    }

    body {
        background-color: var(--neo-bg-color);
        color: var(--neo-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .neo-container {
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow),
        -8px -8px 16px var(--neo-light-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: var(--neo-transition);
    }

    .neo-details {
        margin-bottom: 2rem;
    }

    .neo-detail-item {
        display: flex;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: inset 3px 3px 6px var(--neo-dark-shadow),
        inset -3px -3px 6px var(--neo-light-shadow);
        transition: var(--neo-transition);
    }

    .neo-detail-item .label {
        font-weight: 600;
        min-width: 120px;
        color: var(--neo-primary);
    }

    .neo-detail-item .value {
        flex: 1;
    }

    .neo-section {
        margin-top: 2.5rem;
    }

    .neo-title {
        margin-bottom: 1.5rem;
        color: var(--neo-text);
        position: relative;
        display: inline-block;
        padding-bottom: 0.5rem;
    }

    .neo-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50%;
        height: 3px;
        background: linear-gradient(to right, var(--neo-primary), transparent);
        border-radius: 3px;
    }

    .neo-table-container {
        overflow-x: auto;
        border-radius: var(--neo-border-radius);
        box-shadow: 5px 5px 10px var(--neo-dark-shadow),
        -5px -5px 10px var(--neo-light-shadow);
        margin-bottom: 1.5rem;
    }

    .neo-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.8rem;
    }

    .neo-table thead th {
        background-color: transparent;
        border: none;
        color: var(--neo-text);
        font-weight: 600;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-align: left;
        white-space: nowrap;
    }

    .neo-table tbody tr {
        background-color: var(--neo-bg-color);
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
        border-radius: var(--neo-border-radius);
        transition: var(--neo-transition);
    }

    .neo-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 5px 5px 10px var(--neo-dark-shadow),
        -5px -5px 10px var(--neo-light-shadow);
    }

    .neo-table tbody td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
    }

    .neo-table tbody tr td:first-child {
        border-top-left-radius: var(--neo-border-radius);
        border-bottom-left-radius: var(--neo-border-radius);
        font-weight: 500;
    }

    .neo-table tbody tr td:last-child {
        border-top-right-radius: var(--neo-border-radius);
        border-bottom-right-radius: var(--neo-border-radius);
    }

    .neo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.8rem 1.5rem;
        background-color: var(--neo-bg-color);
        border: none;
        border-radius: var(--neo-border-radius);
        color: var(--neo-text);
        font-weight: 500;
        text-decoration: none;
        transition: var(--neo-transition);
        box-shadow: 6px 6px 12px var(--neo-dark-shadow),
        -6px -6px 12px var(--neo-light-shadow);
        cursor: pointer;
    }

    .neo-btn:hover, .neo-btn:focus {
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow),
        inset -4px -4px 8px var(--neo-light-shadow);
        text-decoration: none;
        color: var(--neo-text);
        transform: translateY(1px);
    }

    .neo-btn-primary {
        color: var(--neo-primary);
    }

    .neo-btn-info {
        color: var(--neo-info);
    }

    .neo-btn-danger {
        color: var(--neo-danger);
    }

    .neo-btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .neo-btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 2rem;
    }

    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
    }

    .neo-alert-info {
        background-color: rgba(94, 173, 176, 0.1);
        color: var(--neo-info);
    }

    .neo-alert-danger {
        background-color: rgba(225, 122, 122, 0.1);
        color: var(--neo-danger);
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .neo-card {
            padding: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .neo-detail-item {
            flex-direction: column;
        }

        .neo-detail-item .label {
            margin-bottom: 0.5rem;
        }

        .neo-btn-group {
            justify-content: space-between;
        }

        .neo-btn {
            padding: 0.7rem 1.2rem;
        }
    }

    @media (max-width: 576px) {
        .neo-container {
            padding: 0 0.5rem;
        }

        .neo-card {
            padding: 1.25rem;
            box-shadow: 5px 5px 10px var(--neo-dark-shadow),
            -5px -5px 10px var(--neo-light-shadow);
        }

        .neo-btn-group {
            flex-direction: column;
            width: 100%;
        }

        .neo-btn {
            width: 100%;
            margin-bottom: 0.5rem;
            justify-content: center;
        }

        .neo-table thead th {
            padding: 0.75rem 0.5rem;
        }

        .neo-table tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<script>
    function confirmDelete(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet antécédent ?")) {
            window.location.href = "/antecedants/delete/" + id;
        }
    }
</script>
