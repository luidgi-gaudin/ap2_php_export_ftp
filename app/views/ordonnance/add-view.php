<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Ajouter une ordonnance</h1>
        <a href="/ordonnance" class="neo-btn neo-btn-primary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="neo-alert neo-alert-danger mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i> <?= $_SESSION['error_message']; ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>
    <div class="neo-card">
        <form method="post" action="/ordonnance/create">
            <div class="form-group mb-3">
                <label for="posologie" class="form-label">Posologie :</label>
                <textarea name="posologie" id="posologie" class="form-control" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="duree_traitement" class="form-label">Durée du traitement :</label>
                <input type="text" name="duree_traitement" id="duree_traitement" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="instructions" class="form-label">Instructions spécifiques :</label>
                <textarea name="instructions" id="instructions" class="form-control" required></textarea>
            </div>
            <div class="form-group mb-3 patient-search-container">
                <label for="patientSearch" class="form-label">Patient :</label>
                <?php if(!isset($patient)): ?>
                    <input type="text" id="patientSearch" class="form-control" placeholder="Rechercher par nom, prénom ou n° secu" autocomplete="off">
                    <input type="hidden" name="patientId" id="patientId">
                    <div id="patientSuggestions" class="suggestions-box"></div>
                <?php else: ?>
                    <input type="text" id="patientSearch" class="form-control" placeholder="Rechercher par nom, prénom ou n° secu" value="<?= $patient->getPrenom()?> <?= $patient->getNom()?> (<?= $patient->getNumSecu()?>)" autocomplete="off" disabled>
                    <input type="hidden" name="patientId" id="patientId" value="<?= $patient->getPatientId() ?>">
                <?php endif; ?>
            </div>
            <h3 class="neo-title" style="font-size:1.25rem;">Médicaments prescrits</h3>
            <div id="medicament-rows" class="mb-3">
                <div class="medication-row mb-2">
                    <input type="text" class="form-control medication-search" placeholder="Rechercher un médicament" autocomplete="off">
                    <input type="hidden" name="medicaments[i][id]" class="medicament-id">
                    <input type="number" name="medicaments[i][quantite]" class="form-control medication-quantity" value="1" min="1">
                    <button type="button" class="neo-btn neo-btn-danger remove-medicament"><i class="bi bi-trash"></i></button>
                    <div class="medication-suggestions suggestions-box"></div>
                </div>
            </div>
            <button type="button" id="add-medicament" class="neo-btn neo-btn-info mb-3">
                <i class="bi bi-plus-circle"></i> Ajouter médicament
            </button>
            <br>
            <button type="submit" class="neo-btn neo-btn-primary">
                <i class="bi bi-check2-circle"></i> Enregistrer l'ordonnance
            </button>
        </form>
    </div>
</div>

<script>
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    let patientAllergyIds = [];

    // Recherche asynchrone pour le patient
    document.getElementById('patientSearch').addEventListener('input', debounce(function(){
        const query = this.value.trim();
        const suggestionsBox = document.getElementById('patientSuggestions');

        if(query.length < 2) {
            suggestionsBox.innerHTML = '';
            return;
        }

        fetch('/patient/search?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                let suggestions = '';
                if(data.length === 0) {
                    suggestionsBox.innerHTML = '<div class="suggestion-item no-results">Aucun patient trouvé</div>';
                    return;
                }

                data.forEach(patient => {
                    const allergiesData = patient.allergies ? patient.allergies.join(',') : '';
                    suggestions += `<div class="suggestion-item patient-suggestion" data-id="${patient.id}" data-allergies="${allergiesData}">
                            ${patient.prenom} ${patient.nom} (${patient.num_secu || patient.secu})
                          </div>`;
                });
                suggestionsBox.innerHTML = suggestions;
                suggestionsBox.style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
                suggestionsBox.innerHTML = '<div class="suggestion-item error">Erreur de recherche</div>';
            });
    }, 300));

    // Sélection d'un patient dans les suggestions
    document.addEventListener('click', function(e) {
        const suggestionsBox = document.getElementById('patientSuggestions');

        if(e.target.classList.contains('patient-suggestion') && !e.target.classList.contains('no-results') && !e.target.classList.contains('error')) {
            const id = e.target.getAttribute('data-id');
            const allergies = e.target.getAttribute('data-allergies');
            document.getElementById('patientId').value = id;
            document.getElementById('patientSearch').value = e.target.textContent.trim();
            patientAllergyIds = allergies ? allergies.split(',').filter(id => id !== '') : [];
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
        } else if(!e.target.closest('#patientSearch') && !e.target.closest('#patientSuggestions')) {
            // Fermer les suggestions si on clique ailleurs
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
        }
    });

    // Gestion des lignes de médicaments
    function addMedicationRow(medData = {}) {
        const container = document.getElementById('medicament-rows');
        const row = document.createElement('div');
        row.className = 'medication-row mb-2';
        row.innerHTML = `
        <input type="text" class="form-control medication-search" placeholder="Rechercher un médicament" autocomplete="off" value="${medData.libelle ? medData.libelle : ''}">
        <input type="hidden" name="medicaments[i][id]" class="medicament-id" value="${medData.id ? medData.id : ''}">
        <input type="number" name="medicaments[i][quantite]" class="form-control medication-quantity" value="${medData.quantite ? medData.quantite : 1}" min="1">
        <button type="button" class="neo-btn neo-btn-danger remove-medicament"><i class="bi bi-trash"></i></button>
        <div class="medication-suggestions suggestions-box"></div>
    `;
        container.appendChild(row);
    }

    document.getElementById('add-medicament').addEventListener('click', function(){
        addMedicationRow();
    });

    // Suppression d'une ligne de médicament
    document.addEventListener('click', function(e) {
        if(e.target.closest('.remove-medicament')) {
            e.target.closest('.medication-row').remove();
        }
    });

    // Recherche asynchrone pour les médicaments
    document.addEventListener('input', debounce(function(e) {
        if(e.target.classList.contains('medication-search')) {
            const input = e.target;
            const query = input.value.trim();
            const suggestionBox = input.closest('.medication-row').querySelector('.medication-suggestions');

            if(query.length < 2) {
                suggestionBox.innerHTML = '';
                suggestionBox.style.display = 'none';
                return;
            }

            let exclude = patientAllergyIds.join(',');
            fetch('/medicament/search?query=' + encodeURIComponent(query) + '&excludeAllergyIds=' + encodeURIComponent(exclude))
                .then(response => response.json())
                .then(data => {
                    let suggestions = '';
                    if(data.length === 0) {
                        suggestionBox.innerHTML = '<div class="suggestion-item no-results">Aucun médicament compatible trouvé</div>';
                        suggestionBox.style.display = 'block';
                        return;
                    }

                    data.forEach(med => {
                        suggestions += `<div class="suggestion-item med-suggestion" data-id="${med.id}" data-libelle="${med.libelle}">
                                ${med.libelle}
                            </div>`;
                    });
                    suggestionBox.innerHTML = suggestions;
                    suggestionBox.style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    suggestionBox.innerHTML = '<div class="suggestion-item error">Erreur de recherche</div>';
                    suggestionBox.style.display = 'block';
                });
        }
    }, 300));

    // Sélection d'un médicament dans les suggestions
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('med-suggestion') && !e.target.classList.contains('no-results') && !e.target.classList.contains('error')) {
            const medId = e.target.getAttribute('data-id');
            const libelle = e.target.getAttribute('data-libelle');
            const row = e.target.closest('.medication-row');

            if(row) {
                row.querySelector('.medication-search').value = libelle;
                row.querySelector('.medicament-id').value = medId;
                const suggestionBox = e.target.closest('.medication-suggestions');
                if(suggestionBox) {
                    suggestionBox.innerHTML = '';
                    suggestionBox.style.display = 'none';
                }
            }
        }
    });
</script>

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
    }

    .neo-title {
        color: var(--neo-text);
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--neo-text);
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
        margin-top: 0.5rem;
    }

    .neo-btn:hover {
        box-shadow: 6px 6px 10px var(--neo-dark-shadow),
        -6px -6px 10px var(--neo-light-shadow);
    }

    .neo-btn:active {
        box-shadow: inset 4px 4px 8px var(--neo-dark-shadow),
        inset -4px -4px 8px var(--neo-light-shadow);
    }

    .neo-btn-primary { color: var(--neo-primary); }
    .neo-btn-info { color: var(--neo-info); }
    .neo-btn-danger { color: var(--neo-danger); }

    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1rem;
        display: flex;
        align-items: center;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
    }

    .neo-alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        color: #c0392b;
    }

    .patient-search-container {
        position: relative;
    }

    .suggestions-box {
        position: absolute;
        width: 100%;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #ddd;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: none;
    }

    .suggestion-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background-color: #f0f5ff;
    }

    .suggestion-item.no-results,
    .suggestion-item.error {
        color: #999;
        font-style: italic;
    }

    .medication-row {
        display: flex;
        gap: 10px;
        align-items: center;
        position: relative;
        margin-bottom: 10px;
    }

    .medication-row .form-control {
        flex: 1;
    }

    .medication-row .medication-quantity {
        width: 80px;
        flex: 0 0 80px;
    }

    .medication-row .neo-btn-danger {
        flex: 0 0 auto;
    }

    .medication-row .medication-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        width: calc(100% - 140px);
        z-index: 1000;
    }
</style>