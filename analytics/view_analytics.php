<?php
require_once '../db_connection.php';
require_once '../helper/db_helper.php';


// Selectează datele din tabelul analytics
$query1 = "SELECT ip_address, user_agent, page_url, visit_time FROM analytics ORDER BY visit_time DESC";
$data1 = db_select($query1);


// Selectează numărul de vizite per pagină
$query2 = "SELECT page_url, COUNT(*) as visits FROM analytics GROUP BY page_url ORDER BY visits DESC";
$data2 = db_select($query2);

// Format pentru JavaScript
$pages = [];
$visits = [];
foreach ($data2 as $row) {
    $pages[] = $row['page_url'];
    $visits[] = $row['visits'];
}

// Convertim datele în JSON pentru utilizare în JavaScript
$pages_json = json_encode($pages);
$visits_json = json_encode($visits);
?>



<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="container mt-5">
        
        <h1 class="mb-4">Website Analytics</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Adresa IP</th>
                    <th>User Agent</th>
                    <th>Pagina Vizitată</th>
                    <th>Timpul Vizitei</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data1 as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_agent']); ?></td>
                        <td><?php echo htmlspecialchars($row['page_url']); ?></td>
                        <td><?php echo htmlspecialchars($row['visit_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Analytics - Vizualizări pe pagină</h1>
        <canvas id="analyticsChart" width="400" height="200"></canvas>
    </div>
</body>
</html>



<script>
    // Obține datele din PHP
    const pages = <?php echo $pages_json; ?>;
    const visits = <?php echo $visits_json; ?>;

    // Generează culori diferite pentru fiecare bară
    const backgroundColors = pages.map(() => {
        return `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.2)`;
    });

    const borderColors = pages.map(() => {
        return `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 1)`;
    });

    // Creează graficul folosind Chart.js
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    const analyticsChart = new Chart(ctx, {
        type: 'bar', // Tipul de grafic (bar, line, pie etc.)
        data: {
            labels: pages, // Etichetele (numele paginilor)
            datasets: [{
                label: 'Număr de vizite',
                data: visits, // Datele (numărul de vizite)
                backgroundColor: backgroundColors, // Culorile pentru bare
                borderColor: borderColors, // Culorile pentru margini
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>

