<?php
$error = $_SESSION['login_error'] ?? '';
if(isset($_SESSION['login_error'])) {
    unset($_SESSION['login_error']);
}
?>

<div class="neo-login-container">
    <div class="neo-card">
        <div class="neo-card-header">
            <h1>Connexion</h1>
        </div>

        <div class="neo-card-body">
            <form action="<?= getenv('HOST') ?>/user/authenticate" method="POST">
                <div class="neo-form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <div class="neo-input-wrapper">
                        <input type="text" id="username" name="username"
                               value="<?= htmlspecialchars($data['old']['username'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="neo-form-group">
                    <label for="password">Mot de passe</label>
                    <div class="neo-input-wrapper password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="password-toggle" aria-label="Afficher/Masquer le mot de passe">
                            <i class="bi bi-eye-slash" id="togglePassword"></i>
                        </button>
                    </div>
                </div>

                <?php if(!empty($data['error'])): ?>
                    <div class="neo-error-message"><?= htmlspecialchars($data['error']) ?></div>
                <?php endif; ?>

                <div class="neo-form-actions">
                    <button type="submit" class="neo-button">Connexion</button>
                </div>

                <div class="neo-form-footer">
                    <p>Pas encore de compte ? <a href="<?= getenv('HOST') ?>/user/register">Inscrivez-vous</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Changer le type du champ
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Changer l'icône
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    });
</script>

<style>
    /* Import des icônes Bootstrap */
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css");

    .neo-login-container {
        max-width: 450px;
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

    @media (max-width: 500px) {
        .neo-login-container {
            margin: 1rem auto;
            padding: 10px;
        }

        .neo-card-body {
            padding: 1.5rem;
        }
    }
</style>