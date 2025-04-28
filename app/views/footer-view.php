</main>
<?php $host = getenv("HOST");?>
<footer class="neo-footer">
        <div class="footer-content">
            <div class="footer-info">
                <p>&copy; <?= date('Y') ?> GSB - Tous droits réservés</p>
            </div>
            <div class="footer-links">
                <a href="<?= $host ?>/contact" class="neo-footer-btn">Contact</a>
                <a href="<?= $host ?>/faq" class="neo-footer-btn">FAQ</a>
            </div>
        </div>
</footer>

<style>
    .neo-footer {
        padding: 1.5rem 0;
        background-color: var(--primary-bg);
        border-top: 1px solid rgba(0,0,0,0.05);
        margin-top: 2rem;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 0 2rem;
    }

    .footer-links {
        display: flex;
        gap: 1rem;
        align-content: flex-end;
        margin: 0 2rem;
    }

    .neo-footer-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-color);
        box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .neo-footer-btn:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 10px var(--shadow-dark), -6px -6px 10px var(--shadow-light);
        color: var(--accent-color);
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
            justify-content: center; !important;
        }
    }
</style>

</body>
</html>