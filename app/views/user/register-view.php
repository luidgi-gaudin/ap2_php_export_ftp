<?php
use App\Models\Role;

$roles = Role::getAll();
?>
<div class="neo-login-container">
    <div class="neo-card">
        <div class="neo-card-header">
            <h1>Inscription</h1>
        </div>

        <div class="neo-card-body">
            <form id="registerForm" action="<?= getenv('HOST') ?>/user/processRegistration" method="POST">
                <div class="neo-form-row">
                    <div class="neo-form-group">
                        <label for="nom">Nom</label>
                        <div class="neo-input-wrapper <?= !empty($data['errors']['nom']) ? 'neo-input-error' : '' ?>">
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($data['old']['nom'] ?? '') ?>">
                        </div>
                        <?php if(!empty($data['errors']['nom'])): ?>
                            <div class="neo-field-error"><?= htmlspecialchars($data['errors']['nom']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="neo-form-group">
                        <label for="prenom">Prénom</label>
                        <div class="neo-input-wrapper <?= !empty($data['errors']['prenom']) ? 'neo-input-error' : '' ?>">
                            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($data['old']['prenom'] ?? '') ?>">
                        </div>
                        <?php if(!empty($data['errors']['prenom'])): ?>
                            <div class="neo-field-error"><?= htmlspecialchars($data['errors']['prenom']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="neo-form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <div class="neo-input-wrapper <?= !empty($data['errors']['date_naissance']) ? 'neo-input-error' : '' ?>">
                        <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($data['old']['date_naissance'] ?? '') ?>">
                    </div>
                    <?php if(!empty($data['errors']['date_naissance'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['date_naissance']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="neo-form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <div class="neo-input-wrapper <?= !empty($data['errors']['username']) ? 'neo-input-error' : '' ?>">
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['old']['username'] ?? '') ?>">
                    </div>
                    <?php if(!empty($data['errors']['username'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="neo-form-group">
                    <label for="email">Email</label>
                    <div class="neo-input-wrapper <?= !empty($data['errors']['email']) ? 'neo-input-error' : '' ?>">
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['old']['email'] ?? '') ?>">
                    </div>
                    <?php if(!empty($data['errors']['email'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['email']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="neo-form-group">
                    <label for="role_id">Rôle</label>
                    <div class="neo-input-wrapper <?= !empty($data['errors']['role_id']) ? 'neo-input-error' : '' ?>">
                        <select id="role_id" name="role_id">
                            <option value="">Sélectionner un rôle</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role->getId()) ?>"
                                        <?= (isset($data['old']['role_id']) && $data['old']['role_id'] == $role->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if(!empty($data['errors']['role_id'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['role_id']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="neo-form-group" x-data="{ show: false }">
                    <label for="password">Mot de passe</label>
                    <div class="neo-input-wrapper password-wrapper <?= !empty($data['errors']['password']) ? 'neo-input-error' : '' ?>">
                        <input :type="show ? 'text' : 'password'" id="password" name="password">
                        <button type="button" class="password-toggle" aria-label="Afficher/Masquer le mot de passe" x-on:click="show = !show">
                            <template x-if="show">
                                <i class="bi bi-eye" id="togglePassword"></i>
                            </template>
                            <template x-if="!show">
                                <i class="bi bi-eye-slash" id="togglePassword"></i>
                            </template>
                        </button>
                    </div>
                    <?php if(!empty($data['errors']['password'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['password']) ?></div>
                    <?php endif; ?>
                    <div id="password-strength" class="neo-password-strength"></div>
                </div>

                <div class="neo-form-group" x-data="{ show: false }">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <div class="neo-input-wrapper password-wrapper <?= !empty($data['errors']['confirm_password']) ? 'neo-input-error' : '' ?>">
                        <input :type="show ? 'text' : 'password'" id="confirm_password" name="confirm_password">
                        <button type="button" class="password-toggle" aria-label="Afficher/Masquer le mot de passe" x-on:click="show = !show">
                            <template x-if="show">
                                <i class="bi bi-eye" id="toggleConfirmPassword"></i>
                            </template>
                            <template x-if="!show">
                                <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
                            </template>
                        </button>
                    </div>
                    <?php if(!empty($data['errors']['confirm_password'])): ?>
                        <div class="neo-field-error"><?= htmlspecialchars($data['errors']['confirm_password']) ?></div>
                    <?php endif; ?>
                    <div id="password-match" class="neo-password-match"></div>
                </div>

                <?php if(!empty($data['errors']['general'])): ?>
                    <div class="neo-error-message"><?= htmlspecialchars($data['errors']['general']) ?></div>
                <?php endif; ?>

                <div class="neo-form-actions">
                    <button type="submit" id="registerBtn" class="neo-button" disabled>S'inscrire</button>
                </div>

                <div class="neo-form-footer">
                    <p>Déjà inscrit ? <a href="<?= getenv('HOST') ?>/user/login">Connectez-vous</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Éléments du formulaire
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('registerBtn');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        const passwordStrength = document.getElementById('password-strength');
        const passwordMatch = document.getElementById('password-match');

        // Liste des champs requis
        const requiredFields = [
            'nom', 'prenom', 'date_naissance', 'username', 'email', 'role_id', 'password', 'confirm_password'
        ];

        // Fonction pour valider l'email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Fonction pour valider le mot de passe
        function validatePassword(password) {
            let score = 0;
            let message = '';

            if (password.length < 8) {
                message = 'Le mot de passe doit contenir au moins 8 caractères';
                return { valid: false, score: score, message: message };
            }

            // Critères de force
            if (password.length >= 8) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            // Déterminer le message selon le score
            if (score < 3) {
                message = 'Mot de passe faible';
                return { valid: false, score: score, message: message };
            } else if (score < 5) {
                message = 'Mot de passe moyen';
                return { valid: true, score: score, message: message };
            } else {
                message = 'Mot de passe fort';
                return { valid: true, score: score, message: message };
            }
        }

        // Fonction pour afficher la force du mot de passe
        function updatePasswordStrength() {
            const password = passwordField.value;

            if (!password) {
                passwordStrength.textContent = '';
                passwordStrength.className = 'neo-password-strength';
                return;
            }

            const validation = validatePassword(password);
            passwordStrength.textContent = validation.message;

            // Appliquer la classe appropriée
            passwordStrength.className = 'neo-password-strength';
            if (validation.score < 3) {
                passwordStrength.classList.add('weak');
            } else if (validation.score < 5) {
                passwordStrength.classList.add('medium');
            } else {
                passwordStrength.classList.add('strong');
            }
        }

        // Fonction pour vérifier si les mots de passe correspondent
        function checkPasswordMatch() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (!password || !confirmPassword) {
                passwordMatch.textContent = '';
                passwordMatch.className = 'neo-password-match';
                return true;
            }

            if (password === confirmPassword) {
                passwordMatch.textContent = 'Les mots de passe correspondent';
                passwordMatch.className = 'neo-password-match strong';
                return true;
            } else {
                passwordMatch.textContent = 'Les mots de passe ne correspondent pas';
                passwordMatch.className = 'neo-password-match weak';
                return false;
            }
        }

        // Fonction pour valider le formulaire complet
        function validateForm() {
            let isValid = true;

            // Vérifier les champs requis
            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field || !field.value.trim()) {
                    isValid = false;
                    break;
                }
            }

            // Valider l'email
            const email = document.getElementById('email').value;
            if (email && !validateEmail(email)) {
                isValid = false;
            }

            // Valider le mot de passe
            const password = passwordField.value;
            if (password && !validatePassword(password).valid) {
                isValid = false;
            }

            // Vérifier correspondance des mots de passe
            if (!checkPasswordMatch()) {
                isValid = false;
            }

            // Activer/désactiver le bouton d'inscription
            submitBtn.disabled = !isValid;
        }

        // Configuration des toggles de mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        function configurePasswordToggle(toggleButton, passwordField) {
            if (toggleButton && passwordField) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Changer l'icône
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
        }

        configurePasswordToggle(togglePassword, passwordField);
        configurePasswordToggle(toggleConfirmPassword, confirmPasswordField);

        // Ajouter les écouteurs d'événements
        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', validateForm);
            }
        }

        passwordField.addEventListener('input', updatePasswordStrength);
        confirmPasswordField.addEventListener('input', checkPasswordMatch);

        // Initialiser
        updatePasswordStrength();
        validateForm();
    });
</script>

<style>
    /* Import des icônes Bootstrap */
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css");

    /* Style pour le select */
    .neo-input-wrapper select {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: var(--text-color);
        font-size: 1rem;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
    }

    /* Style lorsque le select est focus */
    .neo-input-wrapper select:focus {
        color: var(--accent-color);
    }

    .neo-input-wrapper select:focus + .neo-input-wrapper::after {
        transform: translateY(-50%) rotate(180deg);
    }

    /* Style pour les options */
    .neo-input-wrapper select option {
        background-color: var(--primary-bg);
        color: var(--text-color);
        padding: 12px;
    }

    /* Style pour l'option sélectionnée et le survol */
    .neo-input-wrapper select option:checked,
    .neo-input-wrapper select option:hover {
        background-color: rgba(var(--accent-color-rgb), 0.1);
    }

    /* Style pour option vide/placeholder */
    .neo-input-wrapper select option[value=""] {
        color: #999;
    }

    .neo-input-error {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light), 0 0 0 2px rgba(229, 62, 62, 0.3);
    }

    .neo-field-error {
        color: #e53e3e;
        font-size: 0.85rem;
        margin-top: 5px;
        padding-left: 5px;
    }

    .neo-password-strength, .neo-password-match {
        margin-top: 8px;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .neo-password-strength.weak {
        background-color: rgba(229, 62, 62, 0.1);
        color: #e53e3e;
    }

    .neo-password-strength.medium {
        background-color: rgba(236, 201, 75, 0.1);
        color: #b7791f;
    }

    .neo-password-strength.strong {
        background-color: rgba(72, 187, 120, 0.1);
        color: #2f855a;
    }

    .neo-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .neo-button:disabled:hover {
        box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        transform: none;
    }

    .neo-login-container {
        max-width: 550px;
        margin: 2rem auto;
        padding: 20px;
    }

    .neo-card {
        background-color: var(--primary-bg);
        border-radius: 15px;
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        overflow: hidden;
    }

    .neo-card-header {
        padding: 1.5rem;
        text-align: center;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .neo-card-header h1 {
        margin: 0;
        color: var(--text-color);
        font-size: 1.8rem;
    }

    .neo-card-body {
        padding: 2rem;
    }

    .neo-form-row {
        display: flex;
        gap: 15px;
    }

    .neo-form-row .neo-form-group {
        flex: 1;
    }

    .neo-form-group {
        margin-bottom: 1.5rem;
    }

    .neo-form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-weight: 500;
    }

    .neo-input-wrapper {
        border-radius: 10px;
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        padding: 0.2rem;
    }

    .neo-input-wrapper input {
        width: 100%;
        padding: 12px 15px;
        border: none;
        background: transparent;
        color: var(--text-color);
        font-size: 1rem;
        outline: none;
    }

    /* Style pour le wrapper du mot de passe */
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        background: transparent;
        border: none;
        color: var(--text-color);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 1.2rem;
    }

    .password-toggle:hover {
        color: var(--accent-color);
    }

    .password-toggle:active,
    .password-toggle:focus {
        outline: none;
    }

    .neo-error-message {
        color: #e53e3e;
        background-color: rgba(229, 62, 62, 0.1);
        padding: 10px 15px;
        border-radius: 10px;
        margin: 15px 0;
        box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
    }

    .neo-form-actions {
        margin-top: 25px;
        text-align: center;
    }

    .neo-button {
        padding: 12px 30px;
        border: none;
        background-color: var(--primary-bg);
        color: var(--accent-color);
        font-weight: 600;
        font-size: 1rem;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .neo-button:hover {
        box-shadow: 6px 6px 10px var(--shadow-dark), -6px -6px 10px var(--shadow-light);
        transform: translateY(-2px);
    }

    .neo-button:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        transform: translateY(0);
    }

    .neo-form-footer {
        margin-top: 20px;
        text-align: center;
    }

    .neo-form-footer a {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 500;
    }

    .neo-form-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .neo-form-row {
            flex-direction: column;
            gap: 0;
        }

        .neo-login-container {
            margin: 1rem auto;
            padding: 10px;
        }

        .neo-card-body {
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour basculer la visibilité du mot de passe
        function togglePasswordVisibility(toggleButton, passwordField) {
            toggleButton.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Changer l'icône
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        }

        // Appliquer aux deux champs de mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePasswordVisibility(togglePassword, password);

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirm_password');
        togglePasswordVisibility(toggleConfirmPassword, confirmPassword);
    });
</script>