<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xcigence Tuan Le Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Add any additional CSS stylesheets or libraries here -->
    <style>
        .threatened-system-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }

        .threatened-system-card h2 {
            margin-bottom: 10px;
        }

        .threatened-system-card p {
            margin: 5px 0;
        }

        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Add your dashboard layout here -->
    <h1>Xcigence Tuan Le Report Dashboard</h1>

    <!-- Threatened Systems -->
    <div class="threatened-system-card">
        <h2>Threatened System</h2>
        <?php if (isset($report['Threatened']) && count($report['Threatened']) > 0) : ?>
            <?php $threatenedSystem = $report['Threatened'][0]; ?>
            <p><strong>System Name:</strong> <?php echo $threatenedSystem['system_defense']; ?></p>
            <?php if (isset($threatenedSystem['vulnerability_threat']) && count($threatenedSystem['vulnerability_threat']) > 0) : ?>
                <p><strong>Vulnerability Types:</strong>
                    <?php
                    $vulnerabilityTypes = array_map(function($vulnerability) {
                        return $vulnerability['type'] ?? 'Unknown';
                    }, $threatenedSystem['vulnerability_threat']);
                    echo implode(', ', $vulnerabilityTypes);
                    ?>
                </p>
            <?php else : ?>
                <p>No vulnerability types found for the threatened system.</p>
            <?php endif; ?>
            <?php if (isset($threatenedSystem['geolocation'])) : ?>
                <p><strong>Geolocation:</strong> <?php echo $threatenedSystem['geolocation']; ?></p>
            <?php else : ?>
                <p>No geolocation data available for the threatened system.</p>
            <?php endif; ?>
        <?php else : ?>
            <p>No threatened systems data available.</p>
        <?php endif; ?>
    </div>

    <!-- Threatened Systems Chart -->
    <div class="chart-container">
        <canvas id="threatenedSystemsChart"></canvas>
    </div>

    <!-- Digital User Risk -->
    <h2>Digital User Risk</h2>
    <div class="row">
        <!-- Add graphs, charts, and infographics for Digital User Risk here -->
        <div class="row">
            <div class="col">
                <canvas id="digitalUserRiskChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fetch JSON data from the PHP controller
    const threatenedSystemsData = <?php if (isset($report['Threatened'])) {
        echo json_encode($report['Threatened']);
    } else {echo null;}  ?>;
    const digitalUserRiskDataJson = <?php if (isset($report['Digital_User_Risk'])) {
        echo json_encode($report['Digital_User_Risk']);
    } else {echo null;} ?>;
    if (threatenedSystemsData !== null && digitalUserRiskDataJson !== null) {
        console.log(threatenedSystemsData);
        console.log(digitalUserRiskDataJson);

        // Function to create a bar chart
        function createBarChart(elementId, labels, data, chartTitle) {
            const canvas = document.getElementById(elementId);
            if (!canvas) {
                console.error(`Canvas element with ID ${elementId} not found.`);
                return;
            }

            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: chartTitle,
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
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
        }


        // Create charts for Threatened Systems
        const threatenedSystemLabels = threatenedSystemsData.map(item => item.system_defense);
        const threatenedSystemChartData = threatenedSystemsData.map(item => item.vulnerability_threat.length);
        createBarChart('threatenedSystemsChart', threatenedSystemLabels, threatenedSystemChartData, 'Threatened Systems');

        // Create charts for Digital User Risk
        const digitalUserRiskLabels = ['Low', 'Medium', 'High'];
        const digitalUserRiskData = [
            digitalUserRiskDataJson[0].email_at_risk_low.length,
            digitalUserRiskDataJson[0].email_at_risk_medium.length,
            digitalUserRiskDataJson[0].email_at_risk_high.length
        ];
        createBarChart('digitalUserRiskChart', digitalUserRiskLabels, digitalUserRiskData, 'Digital User Risk');
    } else {
        alert('No data found!');
    }
</script>
</body>
</html>