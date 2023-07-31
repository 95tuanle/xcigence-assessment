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
        .content {
            padding: 20px;
            background-color: #f0f0f0;
            flex: 1;
            min-height: 0;
        }
        .header {
            color: #192462;
        }
        pre {
            overflow: auto;
            max-height: 100%;
        }
        .sidebar {
            background-color: #192462;
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .sidebar button.btn {
            color: #B6BACF;
            background-color: transparent;
            border: 0;
            text-align: left;
        }
        .sidebar button.btn.active {
            color: #192462;
            background-color: #fff;
            border: 0;
        }
        .signature {
            margin-top: auto;
            font-size: 12px;
            color: #B6BACF;
        }
    </style>
</head>
<body>
<div class="d-flex h-100">
    <div class="col-md-3 col-lg-2 sidebar">
        <h1 onclick="showHome()">Xcigence</h1>
    </div>
    <div class="col-md-9 col-lg-10 d-flex flex-column content">
        <h2 id="header" class="header"></h2>
        <pre>
        </pre>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script>
    const reportData = <?php if (isset($report)) {echo json_encode($report);} else {echo null;}?>;
    if (reportData !== null) {
        showHome();

        const sidebar = document.querySelector('.sidebar');
        const mostRecentChildKeys = getMostRecentChildKeys(reportData);

        mostRecentChildKeys.forEach(key => {
            const button = document.createElement('button');
            button.className = 'btn btn-primary btn-block';
            button.innerText = formatKeyName(key);
            button.onclick = (event) => showData(event, key);
            sidebar.appendChild(button);
        });

        const signature = document.createElement('div');
        signature.className = 'signature';
        signature.innerText = new Date().getFullYear().toString() + ' Nguyen Anh Tuan Le';
        sidebar.appendChild(signature);

        function getMostRecentChildKeys(data) {
            let mostRecentChildren = [];
            if (Array.isArray(data)) {
                mostRecentChildren = data.map(item => item.name);
            } else if (typeof data === 'object' && data !== null) {
                mostRecentChildren = Object.keys(data)
            }
            return mostRecentChildren;
        }

        function formatKeyName(key) {
            const words = key.split('_');
            return words.map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        function showData(event, key) {
            console.log(reportData[key]);
            constructContent(formatKeyName(key), reportData[key]);
            event.target.classList.add('active');
        }

        function showHome() {
            console.log(reportData);
            constructContent('Home', reportData)
        }

        function constructContent(headerContent, dataContent) {
            clearButtonsStyle();
            const header = document.getElementById('header');
            header.innerText = headerContent;
            const content = document.querySelector('.content pre');
            content.innerText = JSON.stringify(dataContent, null, 2);
        }

        function clearButtonsStyle() {
            const buttons = document.querySelectorAll('.sidebar button.btn');
            buttons.forEach(button => button.classList.remove('active'));
        }
    } else {
        alert('No data found!');
    }
</script>
</body>
</html>
