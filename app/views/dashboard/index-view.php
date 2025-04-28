<div class="neo-dashboard-container">
    <div class="neo-dashboard-header">
        <h1>Tableau de bord</h1>
    </div>

    <div class="neo-dashboard-content">
        <div class="neo-card">
            <div class="neo-card-header">
                <h2>Statistiques Globales</h2>
            </div>
            <div class="neo-card-body stats-grid mt-4">
                <div class="neo-info-item">
                    <span class="neo-label">Patients totaux :</span>
                    <span class="neo-value"><?= $totalPatients ?></span>
                </div>
                <div class="neo-info-item">
                    <span class="neo-label">Ordonnances totales :</span>
                    <span class="neo-value"><?= $totalOrdonnances ?></span>
                </div>
            </div>
        </div>
        <div class="neo-card">
            <div class="neo-card-header">
                <h2>Ordonnances par jour (7 jours)</h2>
            </div>
            <div class="neo-card-body">
                <canvas id="prescriptionsByDayChart"></canvas>
            </div>
        </div>
        <div class="neo-card">
            <div class="neo-card-header">
                <h2>Top 5 Médicaments Prescrits</h2>
            </div>
            <div class="neo-card-body">
                <canvas id="topMedsChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stats = {
            prescriptionsByDay: <?= json_encode($prescriptionsByDay) ?>,
            topMeds: <?= json_encode($topMeds) ?>,
            stockLevels: <?= json_encode($stockLevels) ?>
        };

        // Helpers to render each chart
        function renderLineChart(canvasId, labels, data, label) {
            new Chart(
                document.getElementById(canvasId).getContext('2d'), {
                    type: 'line',
                    data: { labels, datasets: [{ label, data, fill: false }] },
                    options: { responsive: true, maintainAspectRatio: false }
                }
            );
        }
        function renderBarChart(canvasId, labels, data, label) {
            new Chart(
                document.getElementById(canvasId).getContext('2d'), {
                    type: 'bar',
                    data: { labels, datasets: [{ label, data }] },
                    options: { responsive: true, maintainAspectRatio: false }
                }
            );
        }

        // Render des graphiques
        renderLineChart(
            'prescriptionsByDayChart',
            stats.prescriptionsByDay.map(o => o.date),
            stats.prescriptionsByDay.map(o => o.count),
            'Ordonnances'
        );
        renderBarChart(
            'topMedsChart',
            stats.topMeds.map(o => o.label),
            stats.topMeds.map(o => o.count),
            'Prescriptions'
        );
    });
</script>

<style>
    .neo-dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .neo-dashboard-header h1 {
        font-size: 2.5rem;
        color: var(--text-color);
        padding: 1rem 2rem;
        border-radius: 20px;
        box-shadow: 6px 6px 15px var(--shadow-dark), -6px -6px 15px var(--shadow-light);
    }
    .neo-dashboard-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .neo-card-header {
        background-color: var(--primary-bg-hover);
        padding: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        text-align: center;
    }
    .neo-card-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--text-color);
    }
    .stats-grid {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .neo-info-item {
        flex: 1 1 calc(50% - 1rem);
        flex-direction: column;
        background-color: var(--primary-bg);
        padding: 1rem;
        border-radius: 10px;
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    canvas {
        width: 100% !important;
        height: 300px !important;
    }
    @media (max-width: 600px) {
        .neo-info-item {
            flex: 1 1 100%;
        }
    }
</style>
