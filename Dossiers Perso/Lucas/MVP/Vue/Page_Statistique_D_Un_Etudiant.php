<?php
require_once '../Presentation/Statistique_Etudiant_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Etudiant Statistique</title>
    <link rel="stylesheet" href="css/Style_Page_Statistique_Dun_Etudiant.css">
</head>

<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_Selection_Etudiant_Statistique.php" class="bouton-statistique">Choisir un autre étudiant</a>
        <a href="Page_Statistique_Accueil.php" class="bouton-statistique">Liste des rattrapages</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">

    <h2>Statistiques d'absences de <?php echo $nomEtudiant; ?></h2>

    <?php if ($totalAbsences > 0): ?>
        <p>Total des absences enregistrées : <?php echo $totalAbsences; ?></p>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

        <script>
            Chart.register(ChartDataLabels);
            // On définit des options pour les pourcentages
            Chart.defaults.set('plugins.datalabels', {
                formatter: (value, ctx) => {
                    const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    const percentage = (value / sum) * 100;
                    return percentage < 3 ? '' : percentage.toFixed(1) + '%';
                },
                color: '#000',
                font: { weight: 'bold' }
            });

            // On AFFICHE la légende en dessous du camembert
            Chart.defaults.set('plugins.legend', {
                display: true,
                position: 'bottom'
            });
        </script>

        <div class="chart-container">
            <div class="chart-box">
                <h3>Répartition par Ressource</h3>
                <div class="chart-options">
                    <label class="radio-custom">
                        <input type="radio" name="viewMode" value="semestre" onclick="toggleRessourceView('semestre')">
                        <span class="radio-check"></span>
                        Répartition par semestre
                    </label>
                    <div id="semestre-choices" style="display:none; padding-left: 30px; margin-top: 10px;">
                        <label class="radio-custom small" id="label-S1">
                            <input type="radio" name="viewSemestre" value="S1" onclick="updateSemesterChart('S1')" checked>
                            <span class="radio-check"></span> Semestre 1
                        </label>
                        <label class="radio-custom small" id="label-S2">
                            <input type="radio" name="viewSemestre" value="S2" onclick="updateSemesterChart('S2')">
                            <span class="radio-check"></span> Semestre 2
                        </label>
                        <label class="radio-custom small" id="label-S3">
                            <input type="radio" name="viewSemestre" value="S3" onclick="updateSemesterChart('S3')">
                            <span class="radio-check"></span> Semestre 3
                        </label>
                        <label class="radio-custom small" id="label-S4">
                            <input type="radio" name="viewSemestre" value="S4" onclick="updateSemesterChart('S4')">
                            <span class="radio-check"></span> Semestre 4
                        </label>
                        <label class="radio-custom small" id="label-S5">
                            <input type="radio" name="viewSemestre" value="S5" onclick="updateSemesterChart('S5')">
                            <span class="radio-check"></span> Semestre 5
                        </label>
                        <label class="radio-custom small" id="label-S6">
                            <input type="radio" name="viewSemestre" value="S6" onclick="updateSemesterChart('S6')">
                            <span class="radio-check"></span> Semestre 6
                        </label>
                    </div>
                    <label class="radio-custom">
                        <input type="radio" name="viewMode" value="annee" checked onclick="toggleRessourceView('annee')">
                        <span class="radio-check"></span>
                        Répartition dans l'année
                    </label>
                </div>
                <div class="chart-canvas-container">
                    <canvas id="camembertAbsences"></canvas>
                </div>

                <script>
                    const dataAnnee = {
                        labels: <?php echo json_encode($labels_ressources); ?>,
                        data: <?php echo json_encode($donnees_absences); ?>,
                        colors: [
                            'rgba(230, 25, 75, 0.7)', 'rgba(60, 180, 75, 0.7)', 'rgba(255, 225, 25, 0.7)',
                            'rgba(0, 130, 200, 0.7)', 'rgba(245, 130, 48, 0.7)', 'rgba(145, 30, 180, 0.7)',
                            'rgba(70, 240, 240, 0.7)', 'rgba(240, 50, 230, 0.7)', 'rgba(210, 245, 60, 0.7)',
                            'rgba(250, 190, 212, 0.7)', 'rgba(0, 128, 128, 0.7)', 'rgba(220, 190, 255, 0.7)',
                            'rgba(170, 110, 40, 0.7)', 'rgba(255, 250, 200, 0.7)', 'rgba(128, 0, 0, 0.7)',
                            'rgba(170, 255, 195, 0.7)', 'rgba(128, 128, 0, 0.7)', 'rgba(255, 215, 180, 0.7)',
                            'rgba(0, 0, 128, 0.7)', 'rgba(128, 128, 128, 0.7)', 'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)', 'rgba(199, 199, 199, 0.7)'
                        ]
                    };
                    const dataParSemestre = <?php echo json_encode($donnees_par_semestre); ?>;
                    const activeSemestreGroup = <?php echo json_encode($semestreGroup); ?>;
                    const ctx = document.getElementById('camembertAbsences').getContext('2d');
                    const ressourceChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: dataAnnee.labels,
                            datasets: [{
                                label: 'Nombre d\'absences',
                                data: dataAnnee.data,
                                backgroundColor: dataAnnee.colors
                            }]
                        }
                    });
                    function updateSemesterChart(semestre) {
                        const semestreData = dataParSemestre[semestre] || { labels: [], data: [] };
                        ressourceChart.data.labels = semestreData.labels;
                        ressourceChart.data.datasets[0].data = semestreData.data;
                        ressourceChart.data.datasets[0].backgroundColor = dataAnnee.colors;
                        ressourceChart.update();
                    }
                    function toggleRessourceView(view) {
                        const semestreChoicesDiv = document.getElementById('semestre-choices');
                        for (let i = 1; i <= 6; i++) {
                            document.getElementById('label-S' + i).style.display = 'none';
                        }
                        if (view === 'semestre') {
                            semestreChoicesDiv.style.display = 'block';
                            let defaultSemester = 'S1';
                            if (activeSemestreGroup === 'S5S6') {
                                document.getElementById('label-S5').style.display = 'block';
                                document.getElementById('label-S6').style.display = 'block';
                                defaultSemester = 'S5';
                            } else if (activeSemestreGroup === 'S3S4') {
                                document.getElementById('label-S3').style.display = 'block';
                                document.getElementById('label-S4').style.display = 'block';
                                defaultSemester = 'S3';
                            } else {
                                document.getElementById('label-S1').style.display = 'block';
                                document.getElementById('label-S2').style.display = 'block';
                                defaultSemester = 'S1';
                            }
                            document.querySelector('input[name="viewSemestre"][value="' + defaultSemester + '"]').checked = true;
                            updateSemesterChart(defaultSemester);
                        } else {
                            semestreChoicesDiv.style.display = 'none';
                            ressourceChart.data.labels = dataAnnee.labels;
                            ressourceChart.data.datasets[0].data = dataAnnee.data;
                            ressourceChart.data.datasets[0].backgroundColor = dataAnnee.colors;
                            ressourceChart.update();
                        }
                    }
                </script>
            </div>

            <div class="chart-box">
                <h3>Répartition par Type de Cours</h3>
                <div class="chart-options">
                    <label class="radio-custom">
                        <input type="radio" name="viewModeType" value="semestre" onclick="toggleTypeView('semestre')">
                        <span class="radio-check"></span>
                        Répartition par semestre
                    </label>
                    <div id="semestre-choices-type" style="display:none; padding-left: 30px; margin-top: 10px;">
                        <label class="radio-custom small" id="label-S1-type">
                            <input type="radio" name="viewSemestreType" value="S1" onclick="updateSemesterChart_Type('S1')" checked>
                            <span class="radio-check"></span> Semestre 1
                        </label>
                        <label class="radio-custom small" id="label-S2-type">
                            <input type="radio" name="viewSemestreType" value="S2" onclick="updateSemesterChart_Type('S2')">
                            <span class="radio-check"></span> Semestre 2
                        </label>
                        <label class="radio-custom small" id="label-S3-type">
                            <input type="radio" name="viewSemestreType" value="S3" onclick="updateSemesterChart_Type('S3')">
                            <span class="radio-check"></span> Semestre 3
                        </label>
                        <label class="radio-custom small" id="label-S4-type">
                            <input type="radio" name="viewSemestreType" value="S4" onclick="updateSemesterChart_Type('S4')">
                            <span class="radio-check"></span> Semestre 4
                        </label>
                        <label class="radio-custom small" id="label-S5-type">
                            <input type="radio" name="viewSemestreType" value="S5" onclick="updateSemesterChart_Type('S5')">
                            <span class="radio-check"></span> Semestre 5
                        </label>
                        <label class="radio-custom small" id="label-S6-type">
                            <input type="radio" name="viewSemestreType" value="S6" onclick="updateSemesterChart_Type('S6')">
                            <span class="radio-check"></span> Semestre 6
                        </label>
                    </div>
                    <label class="radio-custom">
                        <input type="radio" name="viewModeType" value="annee" checked onclick="toggleTypeView('annee')">
                        <span class="radio-check"></span>
                        Répartition dans l'année
                    </label>
                </div>
                <div class="chart-canvas-container">
                    <canvas id="camembertTypesCours"></canvas>
                </div>

                <script>
                    const dataAnnee_Type = {
                        labels: <?php echo json_encode($labels_typecours); ?>,
                        data: <?php echo json_encode($donnees_typecours); ?>,
                        colors: [
                            'rgba(70, 240, 240, 0.7)', 'rgba(240, 50, 230, 0.7)', 'rgba(210, 245, 60, 0.7)',
                            'rgba(128, 0, 0, 0.7)', 'rgba(0, 128, 128, 0.7)'
                        ]
                    };
                    const dataParSemestre_Type = <?php echo json_encode($typecours_par_semestre); ?>;
                    const ctxType = document.getElementById('camembertTypesCours').getContext('2d');
                    const typeChart = new Chart(ctxType, {
                        type: 'pie',
                        data: {
                            labels: dataAnnee_Type.labels,
                            datasets: [{
                                label: 'Nombre d\'absences',
                                data: dataAnnee_Type.data,
                                backgroundColor: dataAnnee_Type.colors
                            }]
                        }
                    });
                    function updateSemesterChart_Type(semestre) {
                        const semestreData = dataParSemestre_Type[semestre] || { labels: [], data: [] };
                        typeChart.data.labels = semestreData.labels;
                        typeChart.data.datasets[0].data = semestreData.data;
                        typeChart.data.datasets[0].backgroundColor = dataAnnee_Type.colors;
                        typeChart.update();
                    }
                    function toggleTypeView(view) {
                        const semestreChoicesDiv = document.getElementById('semestre-choices-type');
                        for (let i = 1; i <= 6; i++) {
                            document.getElementById('label-S' + i + '-type').style.display = 'none';
                        }
                        if (view === 'semestre') {
                            semestreChoicesDiv.style.display = 'block';
                            let defaultSemester = 'S1';
                            if (activeSemestreGroup === 'S5S6') {
                                document.getElementById('label-S5-type').style.display = 'block';
                                document.getElementById('label-S6-type').style.display = 'block';
                                defaultSemester = 'S5';
                            } else if (activeSemestreGroup === 'S3S4') {
                                document.getElementById('label-S3-type').style.display = 'block';
                                document.getElementById('label-S4-type').style.display = 'block';
                                defaultSemester = 'S3';
                            } else {
                                document.getElementById('label-S1-type').style.display = 'block';
                                document.getElementById('label-S2-type').style.display = 'block';
                                defaultSemester = 'S1';
                            }
                            document.querySelector('input[name="viewSemestreType"][value="' + defaultSemester + '"]').checked = true;
                            updateSemesterChart_Type(defaultSemester);
                        } else {
                            semestreChoicesDiv.style.display = 'none';
                            typeChart.data.labels = dataAnnee_Type.labels;
                            typeChart.data.datasets[0].data = dataAnnee_Type.data;
                            typeChart.data.datasets[0].backgroundColor = dataAnnee_Type.colors;
                            typeChart.update();
                        }
                    }
                </script>
            </div>
        </div>

        <div class="chart-box-courbe">
            <h3>Tendance des absences par ressource</h3>
            <div class="chart-canvas-container" style="max-width: none; height: 400px; padding: 10px;">
                <canvas id="courbeTendance"></canvas>
            </div>
            <script>
                const ctxTendance = document.getElementById('courbeTendance').getContext('2d');
                new Chart(ctxTendance, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($tendance_labels); ?>,
                        datasets: <?php echo json_encode($tendance_datasets); ?>
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    <?php else: ?>
        <p>Cet étudiant n'a aucune absence enregistrée.</p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

</div>

<footer class="main-footer"></footer>

</body>
</html>