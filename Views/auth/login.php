<?php
$title = "Team-eRacing - Connexion au Dashboard";
$errorMessage = $error ?? '';
use App\Core\Auth;
?>

<div class="login-container">
    <h1>Connexion</h1>

    <?php if ($errorMessage): ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <form action="/auth/login" method="POST" class="login-form">
        <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">

        <div class="form-group">
            <label for="email">Adresse email :</label>
            <input type="email" id="email" name="email" required placeholder="Votre email">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
        </div>

        <div class="form-group">
            <label><?= Auth::generateCaptcha(); ?></label>
            <input type="number" name="captcha" required placeholder="Votre réponse">
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Se connecter</button>
        </div>
    </form>
</div>
