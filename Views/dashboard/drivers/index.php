<?php $title = 'Team-eRacing - Pilotes'; ?>

<!-- Définir des valeurs par défaut si les variables $message et $classMsg n'existent pas -->
<!-- Cela permet d'éviter les Warning PHP -->
<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="/dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Pilotes
            </h2>
            <p class="dashboard-crud-subtitle">Créer leurs pseudos et nationalités</p>
        </div>

        <a class="nav-btn-dashboard" href="/drivers/create">Ajouter pilote</a>
    </div>

    <form method="POST" action="/drivers/status">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Auth::csrfToken() ?>">

        <div>
            <button class="status-btn" type="submit" name="status" value="active">Activer</button>
            <button class="status-btn" type="submit" name="status" value="desactive">Désactiver</button>
        </div>

        <div class="table-responsive with-status-btn">
            <table class="dashboard-table fix">
                <thead>
                    <tr>
                        <th class="width-checkbox">
                            <input type="checkbox" id="check-all">
                        </th>
                        <th>Pseudo</th>
                        <th>Pays</th>
                        <th class="status text-center">Statut</th>
                        <th class="width-actions text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($list as $driver): ?>
                    <tr>
                        <td class="width-checkbox">
                            <input type="checkbox" name="drivers[]" value="<?= $driver->id ?>">
                        </td>
                        <td class="down"><?= htmlspecialchars($driver->nickname) ?></td>
                        <td class="down"><?= htmlspecialchars($driver->country) ?></td>
                        <td class="status text-center down"><?= htmlspecialchars($driver->status) ?></td>
                        <td class="width-actions text-center">
                            <a class="action-btn edit" href="/drivers/update/<?= $driver->id ?>">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a class="action-btn delete" href="/drivers/delete/<?= $driver->id ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </form>

</div>

<script>
document.getElementById('check-all').addEventListener('change', function () {
    document.querySelectorAll('input[name="drivers[]"]').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>
