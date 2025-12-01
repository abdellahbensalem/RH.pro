<?php include 'views/sidebar.php'; ?>
<div class="main" id="mainContent">

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des évaluations</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #f8f9fa; transition: background 0.3s, color 0.3s; }
        h2 { color: #2c3e50; font-weight: bold; }
        .card { border-radius: 10px; transition: background 0.3s, color 0.3s; }
        .table thead th { text-align: center; }
        .table td, .table th { vertical-align: middle; }
        .badge { font-size: 0.9rem; }

        /* Mode sombre */
        body.dark-mode {
            background: #1e1e2f;
            color: #eaeaea;
        }
        body.dark-mode h2 {
            color: #f1f1f1;
        }
        body.dark-mode .card {
            background: #2c2c3e;
            color: #fff;
        }
        body.dark-mode .table {
            color: #ddd;
        }
        body.dark-mode .table thead {
            background: #444;
        }
        body.dark-mode .btn-secondary {
            background: #6c63ff;
            border: none;
        }
        body.dark-mode .alert-info {
            background: #444;
            color: #ddd;
            border: none;
        }

        /* Toggle bouton */
        .dark-toggle {
            position: fixed;
            top: 15px;
            right: 20px;
            cursor: pointer;
            font-size: 22px;
            z-index: 1000;
            color: #333;
        }
        body.dark-mode .dark-toggle {
            color: #f1f1f1;
        }
    </style>
</head>
<body>

<!-- Bouton mode sombre -->
<i class="fa-solid fa-moon dark-toggle" id="darkToggle"></i>

<div class="container mt-4">
    <h2 class="mb-4"><i class="fa fa-clipboard-check"></i> Gestion des évaluations</h2>
<div class="mb-3 text-end">
  <a href="excel/export_evaluations.php" class="btn btn-success">
    <i class="fa fa-file-excel"></i> Exporter en Excel
  </a>
</div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (($user["role"] ?? "") === "admin" || ($user["role"] ?? "") === "directeur"): ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fa fa-plus-circle"></i> Nouvelle évaluation
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employé</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">-- Sélectionner un employé --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>">
                                <?= htmlspecialchars($emp['prenom'] . " " . $emp['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="date_eval" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Note (/20)</label>
                    <input type="number" name="note" class="form-control" min="0" max="20" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Évaluateur</label>
                    <select name="evaluateur_id" class="form-select" required>
                        <option value="">-- Sélectionner un évaluateur --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>">
                                <?= htmlspecialchars($emp['prenom'] . " " . $emp['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Commentaire</label>
                    <textarea name="commentaire" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" name="add" class="btn btn-success">
                        <i class="fa fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <i class="fa fa-list"></i> Liste des évaluations
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Employé</th>
                        <th>Date</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Évaluateur</th>
                        <?php if (($user["role"] ?? "") === "admin" || ($user["role"] ?? "") === "directeur"): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($evaluations)): ?>
                        <?php foreach($evaluations as $eval): ?>
                            <tr>
                                <td><?= $eval["id"] ?></td>
                                <td><?= htmlspecialchars($eval["prenom"] . " " . $eval["nom"]) ?></td>
                                <td><?= $eval["date_eval"] ?></td>
                                <td><span class="badge bg-info"><?= $eval["note"] ?>/20</span></td>
                                <td><?= htmlspecialchars($eval["commentaire"]) ?></td>
                                <td><?= $eval["evaluateur_id"] ?></td>
                                <?php if (($user["role"] ?? "") === "admin" || ($user["role"] ?? "") === "directeur"): ?>
                                    <td>
                                        <a href="index.php?page=evaluations&action=delete&id=<?= $eval["id"] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer cette évaluation ?')">
                                            <i class="fa fa-trash"></i> Supprimer
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= (($user["role"] ?? "") === "admin" || ($user["role"] ?? "") === "directeur") ? 7 : 6 ?>" 
                                class="text-center text-muted">
                                Aucune évaluation trouvée.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="mt-3">
                <a href="index.php?page=dashboard" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Retour au Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle = document.getElementById("darkToggle");
    const body = document.body;

    // 🔹 Charger le thème depuis le localStorage (comme le dashboard)
    let theme = localStorage.getItem('theme') || 'light';
    if (theme === 'dark') {
        body.classList.add('dark-mode');
        toggle.classList.replace('fa-moon', 'fa-sun');
    }

    // 🔹 Quand on clique sur l’icône
    toggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        toggle.classList.toggle('fa-sun');
        toggle.classList.toggle('fa-moon');
        // 🔸 Sauvegarder le même choix dans le localStorage global
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });
</script>
</body>
</html>





