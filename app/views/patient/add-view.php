<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Ajouter un patient</h1>
        <a href="/patient" class="neo-btn neo-btn-primary">
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
        <form method="post" action="/patient/create" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" name="nom" id="nom" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" name="prenom" id="prenom" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="sexe" class="form-label">Sexe :</label>
                <select name="sexe" id="sexe" class="form-select" required>
                    <option value="">-- Sélectionner le sexe --</option>
                    <option value="H">Homme</option>
                    <option value="F">Femme</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="num_secu" class="form-label">Numéro de sécurité :</label>
                <input type="text" name="num_secu" id="num_secu" class="form-control" required>
                <!-- Conteneur pour l'erreur de numéro de sécu -->
                <span id="numSecuError" style="color: red; display: none;">Ce numéro de sécurité existe déjà.</span>
            </div>
            <div class="form-group mb-3">
                <label for="photo" class="form-label">Photo :</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            </div>

            <!-- Sélecteurs pour Allergies et Antécédents (comme précédemment) -->
            <h3 class="neo-title" style="font-size:1.25rem;">Allergies associées</h3>
            <div id="allergy-selects" class="mb-3">
                <div class="neo-select-group mb-2">
                    <select name="allergies[]" class="form-select">
                        <option value="">-- Choisir une allergie --</option>
                        <?php foreach($allergies as $a): ?>
                            <option value="<?= $a->getAllergieId() ?>"><?= htmlspecialchars($a->getLibelle()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="button" id="add-allergy" class="neo-btn neo-btn-info mb-3">
                <i class="bi bi-plus-circle"></i> Ajouter allergie
            </button>

            <h3 class="neo-title" style="font-size:1.25rem;">Antécédents associés</h3>
            <div id="antecedent-selects" class="mb-3">
                <div class="neo-select-group mb-2">
                    <select name="antecedents[]" class="form-select">
                        <option value="">-- Choisir un antécédent --</option>
                        <?php foreach($antecedents as $ant): ?>
                            <option value="<?= $ant->getAntecedentId() ?>"><?= htmlspecialchars($ant->getLibelle()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="button" id="add-antecedent" class="neo-btn neo-btn-info mb-3">
                <i class="bi bi-plus-circle"></i> Ajouter antécédent
            </button>

            <br>
            <button type="submit" class="neo-btn neo-btn-primary">
                <i class="bi bi-check2-circle"></i> Ajouter le patient
            </button>
        </form>
    </div>
</div>

<script>
    // Vérification asynchrone du numéro de sécurité
    document.getElementById("num_secu").addEventListener("blur", async function() {
        const numSecu = this.value.trim();
        const errorSpan = document.getElementById("numSecuError");
        if(numSecu !== ""){
            try {
                const response = await fetch(`/patient/checkNumSecu?num_secu=${encodeURIComponent(numSecu)}`);
                const data = await response.json();
                if(data.exists){
                    errorSpan.style.display = "inline";
                    document.querySelector("button[type=submit]").disabled = true;
                } else {
                    errorSpan.style.display = "none";
                    document.querySelector("button[type=submit]").disabled = false;
                }
            } catch(e) {
                console.error(e);
            }
        }
    });

    // Ajout dynamique des sélecteurs
    document.getElementById('add-allergy').addEventListener('click', function() {
        var container = document.getElementById('allergy-selects');
        var group = container.querySelector('.neo-select-group');
        container.insertAdjacentHTML('beforeend', group.outerHTML);
    });
    document.getElementById('add-antecedent').addEventListener('click', function() {
        var container = document.getElementById('antecedent-selects');
        var group = container.querySelector('.neo-select-group');
        container.insertAdjacentHTML('beforeend', group.outerHTML);
    });
    const uploadField = document.getElementById("photo");

    uploadField.onchange = function() {
        if(this.files[0].size > 2097152) {
            alert("La taille de l'image ne doit pas dépasser 2 Mo.");
            this.value = "";
        }
    };
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
    .neo-btn-primary { color: var(--neo-primary); }
    .neo-btn-info { color: var(--neo-info); }
    .neo-alert {
        border-radius: var(--neo-border-radius);
        padding: 1rem;
        display: flex;
        align-items: center;
        background-color: rgba(231, 76, 60, 0.1);
        color: #c0392b;
        box-shadow: 3px 3px 6px var(--neo-dark-shadow),
        -3px -3px 6px var(--neo-light-shadow);
    }
</style>
