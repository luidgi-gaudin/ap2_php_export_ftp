<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="neo-title">Ajouter un médicament</h1>
        </div>
        <div class="col-lg-6 text-lg-end add-btn-container">
            <a href="/medicament" class="neo-btn neo-btn-primary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="neo-card">
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="neo-alert neo-alert-danger mb-4">
                <i class="bi bi-exclamation-triangle me-2"></i> <?= $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/medicament/create">
            <div class="mb-3">
                <label for="libelle" class="form-label">Libellé :</label>
                <input type="text" name="libelle" id="libelle" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contr_indication" class="form-label">Contre-indication :</label>
                <textarea name="contr_indication" id="contr_indication" class="form-control" required></textarea>
            </div>

            <h2 class="neo-title mt-4" style="font-size: 1.25rem;">Allergies associées</h2>
            <div id="allergy-selects" class="mb-3">
                <div class="allergy-select mb-2">
                    <select name="allergies[]" class="form-select">
                        <option value="">-- Sélectionner une allergie --</option>
                        <?php foreach ($allergies as $allergie): ?>
                            <option value="<?= $allergie->getAllergieId() ?>"><?= htmlspecialchars($allergie->getLibelle()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="button" id="add-allergy" class="neo-btn neo-btn-info mb-3">
                <i class="bi bi-plus-circle"></i> Ajouter une autre allergie
            </button>
            <br>
            <button type="submit" class="neo-btn neo-btn-primary">
                <i class="bi bi-check2-circle"></i> Ajouter le médicament
            </button>
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

    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 500;
        color: var(--neo-text);
        padding-left: 0.5rem;
    }

    .form-control {
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

    .form-control:focus {
        box-shadow: inset 6px 6px 10px var(--neo-dark-shadow),
        inset -6px -6px 10px var(--neo-light-shadow);
    }

    .form-select {
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
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23566573' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.5rem center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        box-shadow: inset 6px 6px 10px var(--neo-dark-shadow),
        inset -6px -6px 10px var(--neo-light-shadow);
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
        transform: translateY(1px);
    }

    .neo-btn-primary {
        color: var(--neo-primary);
    }

    .neo-btn-secondary {
        color: var(--neo-secondary);
    }

    .neo-btn-info {
        color: var(--neo-info);
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

    .allergy-select {
        position: relative;
    }

    .add-btn-container {
        margin-bottom: 1rem;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
</style>

<script>
    document.getElementById('add-allergy').addEventListener('click', function() {
        var container = document.getElementById('allergy-selects');
        var selects = container.getElementsByClassName('allergy-select');
        var newSelectDiv = selects[0].cloneNode(true);
        newSelectDiv.getElementsByTagName('select')[0].selectedIndex = 0;
        container.appendChild(newSelectDiv);
    });
</script>
