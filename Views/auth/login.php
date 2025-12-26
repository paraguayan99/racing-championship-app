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

    <form action="index.php?controller=auth&action=login" method="POST" class="login-form">
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
            <button type="submit" class="btn">Se connecter</button>
        </div>
    </form>
</div>


<?php
// SCRIPT POUR CREER MOT DE PASSE HASHE ET SECURISE DANS LA BDD

// $motdepasse = 'user'; // Le mot de passe en clair
// $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
// echo $hash;
?>