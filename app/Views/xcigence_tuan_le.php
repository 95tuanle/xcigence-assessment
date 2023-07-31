<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xcigence - Tuan Le Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .sidebar {
            background-color: #0D1B61;
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
        }
        .content {
            padding: 20px;
            background-color: #f0f0f0;
            flex: 1;
            min-height: 0;
        }
        pre {
            overflow: auto;
            max-height: 100%;
        }
    </style>
</head>
<body>
<div class="d-flex h-100">
    <div class="col-md-3 col-lg-2 sidebar">
        <h1>Xcigence</h1>
    </div>
    <div class="col-md-9 col-lg-10 d-flex flex-column content"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const reportData = <?php if (isset($report)) {
        echo json_encode($report);
    } else {echo null;}  ?>;
    if (reportData !== null) {
        console.log(reportData);
        const content = document.querySelector('.content');
        const pre = document.createElement('pre');
        pre.innerText = JSON.stringify(reportData, null, 2);
        content.appendChild(pre);

        const sidebar = document.querySelector('.sidebar');

        const mostRecentChildKeys = getMostRecentChildKeys(reportData);

        function getMostRecentChildKeys(data) {
            let mostRecentChildren = [];
            if (Array.isArray(data)) {
                mostRecentChildren = data.map(item => item.name);
            } else if (typeof data === 'object' && data !== null) {
                mostRecentChildren = Object.keys(data)
            }
            return mostRecentChildren;
        }

        mostRecentChildKeys.forEach(key => {
            const button = document.createElement('button');
            button.className = 'btn btn-primary btn-block';
            button.innerText = formatKeyName(key);
            button.onclick = () => showData(key);
            sidebar.appendChild(button);
        });

        function formatKeyName(key) {
            const words = key.split('_');
            return words.map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        function showData(key) {
            console.log(`Show data for ${key}`);
        }
    } else {
        alert('No data found!');
    }
</script>
</body>
</html>
