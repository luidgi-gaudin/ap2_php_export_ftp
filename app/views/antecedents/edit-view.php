<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <a href="/antecedants" class="neo-back-btn me-3">
                    <i class="bi bi-x-lg"></i>
                </a>
                <h1 class="neo-title mb-0">Modifier l'antécédent</h1>
            </div>
        </div>
    </div>

    <div class="neo-card">
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="neo-alert neo-alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="/antecedants/edit/<?= $data['antecedent']->getAntecedentId() ?>" method="POST">
            <div class="mb-4">
                <label for="libelle" class="neo-label">Libellé de l'antécédent</label>
                <div class="neo-input-container">
                    <input
                        type="text"
                        class="neo-input"
                        id="libelle"
                        name="libelle"
                        value="<?= htmlspecialchars($data['antecedent']->getLibelle()) ?>"
                        required
                        autofocus
                    >
                </div>
                <div class="neo-input-help">Entrez un nom clair et descriptif pour cette antécédent</div>
            </div>

            <div class="neo-form-actions mt-5">
                <a href="/antecedants" class="neo-btn neo-btn-secondary">
                    <i class="fas fa-times me-2"></i> Annuler
                </a>
                <button type="submit" class="neo-btn neo-btn-primary">
                    <i class="fas fa-save me-2"></i> Enregistrer
                </button>
            </div>
        </form>
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
    }

    body {
        background-color: var(--neo-bg-color);
        color: var(--neo-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .neo-title {
        color: var(--neo-text);
        font-weight: 600;
        letter-spacing: 0.5px;
        padding-left: 0.5rem;
    }

    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow),
        -8px -8px 16px var(--neo-light-shadow);
        padding: 2.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .neo-label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 500;
        color: var(--neo-text);
        padding-left: 0.5rem;
    }

    .neo-input-container {
        position: relative;
        margin-bottom: 0.5rem;
    }

    .neo-input {
        width: 100%;
        padding: 1.2rem 1.5rem;
        font-size: 1rem;
        border: none;
        border-radius: var(--neo-border-radius);
        background-color: var(--neo-bg-color);
        color: var(--neo-text);
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow),
        inset -4px -4px 8px var(--neo-light-shadow);
        outline: none;
        transition: all 0.3s ease;
    }

    .neo-input:focus {
        box-shadow: inset 6px 6px 10px var(--neo-dark-shadow),
        inset -6px -6px 10px var(--neo-light-shadow);
    }

    .neo-input-help {
        font-size: 0.85rem;
        padding-left: 1rem;
        color: #95a5a6;
        margin-top: 0.5rem;
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
        transition: all 0.3s ease;
        box-shadow: 6px 6px 12px var(--neo-dark-shadow),
        -6px -6px 12px var(--neo-light-shadow);
        cursor: pointer;
    }

    .neo-btn:hover, .neo-btn:focus {
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow),
        inset -4px -4px 8px var(--neo-light-shadow);
        text-decoration: none;
        color: var(--neo-text);
    }

    .neo-btn-primary {
        color: var(--neo-primary);
    }

    .neo-btn-secondary {
        color: var(--neo-secondary);
    }

    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1.2rem;
        display: flex;
        align-items: center;
    }

    .neo-alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        color: #c0392b;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
    }

    .neo-back-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--neo-bg-color);
        box-shadow: 4px 4px 8px var(--neo-dark-shadow),
        -4px -4px 8px var(--neo-light-shadow);
        color: var(--neo-text);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .neo-back-btn:hover {
        box-shadow: inset 2px 2px 5px var(--neo-dark-shadow),
        inset -2px -2px 5px var(--neo-light-shadow);
        color: var(--neo-text);
    }

    .neo-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .me-3 {
        margin-right: 1rem;
    }
</style>