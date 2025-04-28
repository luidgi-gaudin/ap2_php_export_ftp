<?php
$apos = "'";
$faqs = [
    ['q' => 'Comment créer un compte ?', 'a' => 'Pour créer un compte, cliquez sur "S'.$apos.'inscrire" en haut à droite et remplissez le formulaire.'],
    ['q' => 'Comment réinitialiser mon mot de passe ?', 'a' => 'Cliquez sur "Mot de passe oublié" sur la page de connexion et suivez les instructions envoyées par email.'],
    ['q' => 'Comment contacter le support ?', 'a' => 'Rendez-vous sur la page de contact et remplissez le formulaire, ou envoyez un email à support@medmanager.fr.'],
];
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">FAQ</h1>
    </div>

    <div class="neo-search-container mb-4">
        <input type="text" id="searchFaq" class="neo-search-input" placeholder="Rechercher une question...">
        <i class="bi bi-search neo-search-icon"></i>
    </div>

    <div class="row" id="faqItems">
        <?php foreach ($faqs as $faq): ?>
            <div class="col-md-6 mb-4 faq-item">
                <div class="neo-card">
                    <p><strong><?= htmlspecialchars($faq['q']) ?></strong></p>
                    <p><?= htmlspecialchars($faq['a']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    /* Styles néomorphiques (copiés de la page existante) */
    :root {
        --neo-bg-color: #e0e5ec;
        --neo-light-shadow: rgba(255,255,255,0.7);
        --neo-dark-shadow: rgba(70,70,70,0.12);
        --neo-primary: #6a8caf;
        --neo-info: #5eadb0;
        --neo-danger: #e17a7a;
        --neo-text: #566573;
        --neo-border-radius: 20px;
    }
    .neo-title { color: var(--neo-text); font-weight: 600; letter-spacing: 0.5px; }
    .neo-card { background: var(--neo-bg-color); border-radius: var(--neo-border-radius); padding: 1.5rem; box-shadow: 8px 8px 16px var(--neo-dark-shadow), -8px -8px 16px var(--neo-light-shadow); }
    .neo-search-container { position: relative; margin-bottom: 1.5rem; }
    .neo-search-input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: none; border-radius: var(--neo-border-radius); background: var(--neo-bg-color); box-shadow: inset 4px 4px 8px var(--neo-dark-shadow), inset -4px -4px 8px var(--neo-light-shadow); font-size: 1rem; color: var(--neo-text); }
    .neo-search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--neo-text); opacity: 0.6; }
</style>

<script>
    document.getElementById('searchFaq').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.faq-item').forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(term) ? 'block' : 'none';
        });
    });
</script>
