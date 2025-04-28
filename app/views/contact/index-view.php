<?php
use App\Models\User;
$errors = $_SESSION['contact_errors'] ?? null;
$_SESSION['contact_errors'] = null;
$UserId = $_SESSION['userId'] ?? null;
$user = null;
if($UserId) {
    $user = User::findById($UserId);
}else{
    $user = new User();
}
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="neo-title">Contactez-nous</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="neo-alert neo-alert-danger mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="neo-alert neo-alert-success mb-4">
            <i class="bi bi-check2-circle me-2"></i> <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <form action="/contact/send" method="POST">
        <div class="neo-card p-4">
            <div class="mb-3">
                <label for="name" class="form-label neo-text">Nom</label>
                <input type="text" id="name" name="name" class="neo-search-input w-100" value="<?= htmlspecialchars($user->getUserName() ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label neo-text">Email</label>
                <input type="email" id="email" name="email" class="neo-search-input w-100" value="<?= htmlspecialchars($user->getEmail() ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label neo-text">Message</label>
                <textarea id="message" name="message" class="neo-search-input w-100" rows="5"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="neo-btn neo-btn-primary">Envoyer</button>
        </div>
    </form>
</div>

<style>
    :root { --neo-bg-color: #e0e5ec; --neo-light-shadow: rgba(255,255,255,0.7); --neo-dark-shadow: rgba(70,70,70,0.12); --neo-text: #566573; --neo-border-radius: 20px; }
    .neo-title { color: var(--neo-text); font-weight: 600; }
    .neo-alert { border-radius: var(--neo-border-radius); padding: 1rem; display: flex; align-items: center; }
    .neo-alert-success { background-color: rgba(76,175,80,0.1); color: #2e7d32; box-shadow: 3px 3px 6px var(--neo-dark-shadow), -3px -3px 6px var(--neo-light-shadow); }
    .neo-alert-danger { background-color: rgba(208, 2, 27, 0.1); color: #cf0812; box-shadow: 3px 3px 6px var(--neo-dark-shadow), -3px -3px 6px var(--neo-light-shadow); }
    .neo-text { color: var(--neo-text); }
    .neo-card { background: var(--neo-bg-color); border-radius: var(--neo-border-radius); box-shadow: 8px 8px 16px var(--neo-dark-shadow), -8px -8px 16px var(--neo-light-shadow); }
    .neo-search-input { padding: 0.75rem 1rem; border: none; border-radius: var(--neo-border-radius); background: var(--neo-bg-color); box-shadow: inset 4px 4px 8px var(--neo-dark-shadow), inset -4px -4px 8px var(--neo-light-shadow); }
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
        box-shadow: 4px 4px 8px var(--neo-dark-shadow), -4px -4px 8px var(--neo-light-shadow);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .neo-btn-primary {
        color: var(--neo-primary);
    }
</style>
