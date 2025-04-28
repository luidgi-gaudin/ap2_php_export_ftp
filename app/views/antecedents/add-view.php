<div class="neo-container" style="justify-content: center;">
    <div class="neo-card">
        <h2>Ajouter un antécédent</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="neo-alert neo-alert-danger">
                <?= $_SESSION['error_message'] ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="/antecedants/create" method="POST">
            <div class="neo-form-group">
                <label for="libelle">Libellé</label>
                <div class="neo-search-container">
                    <input type="text" id="libelle" name="libelle" class="neo-search-input" required>
                </div>
            </div>

            <div class="neo-btn-group">
                <a href="/antecedants" class="neo-btn">Annuler</a>
                <button type="submit" class="neo-btn neo-btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Styles néomorphiques -->
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
    }

    body {
        background-color: var(--neo-bg-color);
        color: var(--neo-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    tr {
        background-color: var(--neo-bg-color); !important;!important;
    }

    .neo-card {
        background-color: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow),
        -8px -8px 16px var(--neo-light-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
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
        transition: all 0.3s ease;
        box-shadow: 6px 6px 12px var(--neo-dark-shadow),
        -6px -6px 12px var(--neo-light-shadow);
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

    .neo-btn-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 1rem;
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
    }

    .neo-table tbody tr {
        background-color: var(--neo-bg-color);
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
        border-radius: var(--neo-border-radius);
        transition: all 0.2s ease;
    }

    .neo-table tbody tr:hover {
        transform: translateY(-2px);
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

    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1rem;
        display: flex;
        align-items: center;
    }

    /* Barre de recherche néomorphique */
    .neo-search-container {
        position: relative;
        margin-bottom: 0.5rem;
    }

    .neo-search-input {
        width: 100%;
        padding: 1.2rem 1.5rem 1.2rem 3rem;
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

    .neo-search-input:focus {
        box-shadow: inset 6px 6px 10px var(--neo-dark-shadow),
        inset -6px -6px 10px var(--neo-light-shadow);
    }
</style>
