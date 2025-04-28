<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Patients</h1>
        <a href="/patient/create" class="neo-btn neo-btn-primary"><i class="bi bi-plus-lg"></i> Ajouter un patient</a>
    </div>

    <div class="mb-4">
        <div class="neo-search-container">
            <i class="bi bi-search neo-search-icon"></i>
            <input type="text" id="searchInput" class="neo-search-input" placeholder="Rechercher un patient...">
        </div>
    </div>
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="neo-alert neo-alert-success mb-4">
            <i class="bi bi-check2-circle me-2"></i> <?= $_SESSION['success_message']; ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    <?php if(count($patients) > 0):?>
    <div class="row">
        <?php foreach($patients as $patient): ?>
            <div class="col-md-4 mb-4">
                <div class="neo-card patient-card">
                    <img src="<?= $patient->getPhoto() ?? '/images/patients/default.png' ?>" class="patient-photo" alt="Photo de <?= htmlspecialchars($patient->getPrenom() . ' ' . $patient->getNom()) ?>">
                    <h3><?= htmlspecialchars($patient->getPrenom() . ' ' . $patient->getNom()) ?></h3>
                    <p><strong>N° sécurité :</strong> <a id="numsecu"><?= htmlspecialchars($patient->getNumSecu()) ?></a></p>
                    <div class="neo-btn-group">
                        <a href="/patient/details/<?= $patient->getPatientId() ?>" class="neo-btn neo-btn-info"><i class="bi bi-eye"></i></a>
                        <a href="/patient/edit/<?= $patient->getPatientId() ?>" class="neo-btn neo-btn-info"><i class="bi bi-pencil-square"></i></a>
                        <a href="/patient/delete/<?= $patient->getPatientId() ?>" class="neo-btn neo-btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')"><i class="bi bi-trash3"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="row">
            <a class="text-center neo-empty">Aucun patient trouvé</a>
        </div>
    <?php endif; ?>
</div>

<!-- Style Neomorphic -->
<style>
    :root {
        --neo-bg-color: #E0E5EC;
        --neo-light-shadow: rgba(255,255,255,0.7);
        --neo-dark-shadow: rgba(70,70,70,0.12);
        --neo-primary: #6a8caf;
        --neo-info: #5eadb0;
        --neo-danger: #e17a7a;
        --neo-text: #566573;
        --neo-border-radius: 20px;
    }
    body {
        background: var(--neo-bg-color);
        color: var(--neo-text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .neo-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .neo-card {
        background: var(--neo-bg-color);
        border-radius: var(--neo-border-radius);
        box-shadow: 8px 8px 16px var(--neo-dark-shadow), -8px -8px 16px var(--neo-light-shadow);
        padding: 20px;
        text-align: center;
    }
    .neo-empty {
        font-style: italic;
        color: #95a5a6;
        padding: 2rem !important;
    }
    .patient-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        box-shadow: 4px 4px 8px var(--neo-dark-shadow), -4px -4px 8px var(--neo-light-shadow);
    }
    .neo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        margin: 5px;
        background: var(--neo-bg-color);
        border: none;
        border-radius: var(--neo-border-radius);
        color: var(--neo-text);
        font-weight: 500;
        text-decoration: none;
        box-shadow: 4px 4px 8px var(--neo-dark-shadow), -4px -4px 8px var(--neo-light-shadow);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .neo-btn:hover {
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow), inset -4px -4px 8px var(--neo-light-shadow);
    }
    .neo-btn-primary { color: var(--neo-primary); }
    .neo-btn-info { color: var(--neo-info); }
    .neo-btn-danger { color: var(--neo-danger); }
    .neo-search-container {
        position: relative;
    }
    .neo-search-input {
        width: 100%;
        padding: 1rem 1.5rem 1rem 3rem;
        border: none;
        border-radius: var(--neo-border-radius);
        background: var(--neo-bg-color);
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow), inset -4px -4px 8px var(--neo-light-shadow);
        font-size: 1rem;
        outline: none;
    }
    .neo-search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.6;
        color: var(--neo-text);
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

<!-- Recherche dynamique -->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.patient-card');
        searchInput.addEventListener('input', function(){
            const term = this.value.toLowerCase();
            rows.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                card.parentElement.style.display = name.includes(term) ? "" : "none";
            });
        });
    });
</script>
