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
            background-color: #007BFF; /* Blue color */
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
        }

        .content {
            padding: 20px;
            background-color: #f0f0f0; /* Light gray color for content area */
            flex: 1; /* Occupy remaining height */
            min-height: 0; /* Reset the default min-height property */
        }

        /* Optional: Style the pre element to make the JSON content scrollable if it overflows */
        pre {
            overflow: auto;
            max-height: 100%;
        }

    </style>
</head>
<body>
<div class="d-flex h-100">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 sidebar">
        <!-- Add your sidebar content here -->
        Sidebar Content
    </div>
    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 d-flex flex-column content">

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const reportData = <?php if (isset($report)) {
        echo json_encode($report);
    } else {echo null;}  ?>;
    if (reportData !== null) {
        console.log(reportData);
        const container = document.querySelector('.content');
        const pre = document.createElement('pre');
        pre.innerText = JSON.stringify(reportData, null, 2);
        container.appendChild(pre);
    } else {
        alert('No data found!');
    }
</script>
</body>
</html>