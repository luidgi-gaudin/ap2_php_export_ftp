<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="neo-title">Détails du médicament</h1>
        </div>
        <div class="col-lg-6 text-lg-end add-btn-container">
            <a href="/medicament" class="neo-btn neo-btn-primary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="neo-card">
        <div class="mb-3">
            <p><strong> <?= htmlspecialchars($medicament->getLibelle()) ?></strong></p>
            <p><strong>Contre-indication :</strong> <?= htmlspecialchars($medicament->getContrIndication()) ?></p>
        </div>

        <h2 class="neo-title" style="font-size: 1.25rem;">Allergies associées</h2>
        <div class="mb-3">
            <?php if (!empty($allergies)): ?>
                <ul class="list-group">
                    <?php foreach ($allergies as $allergie): ?>
                        <li class="list-group-item"><?= htmlspecialchars($allergie->getLibelle()) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune allergie associée.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    :root {
        --neo-bg-color: #e0e5ec;
        --neo-light-shadow: rgba(255, 255, 255, 0.7);
        --neo-dark-shadow: rgba(70, 70, 70, 0.12);
        --neo-primary: #6a8caf;
        --neo-secondary: #7c8495;
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

    .container {
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .neo-title {
        color: var(--neo-text);
        font-weight: 600;
        letter-spacing: 0.5px;
        padding-left: 0.5rem;
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

    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow),
        -8px -8px 16px var(--neo-light-shadow);
        padding: 2.5rem;
        margin-bottom: 2rem;
        transition: var(--neo-transition);
    }

    .neo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.9rem 1.8rem;
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
        gap: 0.5rem;
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

    .add-btn-container {
        margin-bottom: 1rem;
    }

    .mb-3 p {
        padding: 1rem;
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: inset 3px 3px 6px var(--neo-dark-shadow),
        inset -3px -3px 6px var(--neo-light-shadow);
        margin-bottom: 1rem;
        transition: var(--neo-transition);
    }

    .mb-3 p strong {
        color: var(--neo-primary);
        font-weight: 600;
        min-width: 150px;
        display: inline-block;
    }

    .list-group {
        list-style: none;
        padding: 0;
    }

    .list-group-item {
        background-color: var(--neo-bg-color);
        border: none;
        border-radius: var(--neo-border-radius);
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
        padding: 1rem;
        margin-bottom: 0.8rem;
        transition: var(--neo-transition);
    }

    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 5px 5px 10px var(--neo-dark-shadow),
        -5px -5px 10px var(--neo-light-shadow);
    }

    @media (max-width: 992px) {
        .neo-card {
            padding: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .text-lg-end {
            text-align: left !important;
            margin-top: 1rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 0.5rem;
        }

        .neo-card {
            padding: 1.25rem;
        }

        .neo-btn {
            width: 100%;
        }
    }
</style>