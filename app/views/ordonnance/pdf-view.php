<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ordonnance #<?= $ordonnance->getOrdonnanceId() ?></title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; }
        header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #444; margin-bottom: 15px; }
        header h1 { font-size: 18px; margin: 0; }
        .info { display: grid; grid-template-columns: auto 1fr; gap: 10px; align-items: center; margin-bottom: 15px; }
        .info img { width: 80px; height: 80px; object-fit: cover; border: 1px solid #666; border-radius: 4px; }
        .info .details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .info .details p { margin: 4px 0; }
        .info .details p strong { width: 100px; display: inline-block; }
        h2 { font-size: 14px; margin-top: 25px; border-bottom: 1px solid #888; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        th, td { border: 1px solid #666; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #fafafa; }
        footer { position: fixed; bottom: 10mm; width: 100%; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 4px; }
    </style>
</head>
<body>

<header>
    <h1>Ordonnance n°<?= $ordonnance->getOrdonnanceId() ?></h1>
</header>

<section class="info">
    <?php if ($medecin = $ordonnance->getMedecin()): ?>
        <div class="details">
            <p><strong>Nom :</strong> <?= htmlspecialchars($medecin->getNom() . ' ' . $medecin->getPrenom()) ?></p>
            <p><strong>Spécialité :</strong> <?= htmlspecialchars($medecin->getRole()->getName()) ?></p>
        </div>
    <?php endif; ?>

    <?php if ($patient = $ordonnance->getPatient()): ?>
        <div class="details">
            <p><strong>Nom :</strong> <?= htmlspecialchars($patient->getNom() . ' ' . $patient->getPrenom()) ?></p>
            <p><strong>Sexe :</strong> <?= htmlspecialchars($patient->getSexe()) ?></p>
            <p><strong>N° Sécu :</strong> <?= htmlspecialchars($patient->getNumSecu()) ?></p>
            <p><strong>Date :</strong> <?= $ordonnance->getDateCreation()->format('d/m/Y') ?></p>
        </div>
    <?php endif; ?>
</section>

<section>
    <p><strong>Posologie :</strong> <?= htmlspecialchars($ordonnance->getPosologie()) ?></p>
    <?php if ($ordonnance->getInstructionsSpecifique()): ?>
        <p><strong>Instructions :</strong> <?= htmlspecialchars($ordonnance->getInstructionsSpecifique()) ?></p>
    <?php endif; ?>
</section>

<h2>Médicaments prescrits</h2>
<table>
    <thead>
    <tr>
        <th>Libellé</th>
        <th>Quantité</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($medicaments as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m->getLibelle()) ?></td>
            <td><?= intval($m->quantite) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<footer>
    <p>Signature du médecin : ____________________________</p>
</footer>

</body>
</html>
