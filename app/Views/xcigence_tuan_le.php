<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xcigence - Tuan Le Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
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
            white-space: pre-wrap;
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

        li {
            overflow: auto;
        }
    </style>
</head>
<body>
<div class="d-flex h-100">
    <div class="col-md-3 col-lg-2 sidebar">
        <h1 class="home">Xcigence</h1>
        <div class="sidebar-buttons"></div>
        <div class="signature"></div>
    </div>
    <div class="col-md-9 col-lg-10 d-flex flex-column content">
        <h2 id="header" class="header"></h2>
        <pre></pre>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const reportData = <?php if (isset($report)) {
            echo json_encode($report);
        } else {
            echo null;
        }?>;
        if (reportData !== null) {
            function formatKeyName(key) {
                return key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            }

            function showHome() {
                const buttons = document.querySelectorAll('.sidebar button.btn');
                buttons.forEach(button => button.classList.remove('active'));
                constructContent('Home', reportData);
            }

            function showBasedOnChildClicked(event, key) {
                constructContent(formatKeyName(key), reportData[key]);
                const activeButton = document.querySelector('.sidebar .btn.active');
                if (activeButton) {
                    activeButton.classList.remove('active');
                }
                event.target.classList.add('active');
            }

            function constructContent(headerContent, contentData) {
                const header = document.getElementById('header');
                header.innerText = headerContent;
                const content = document.querySelector('.content pre');
                switch (headerContent) {
                    case 'Home':
                        content.innerHTML = `<h2>Report Detail</h2><br>${reportDetailContent(contentData['report_detail'])}<br><h2>Digital User Risk</h2><br>${digitalUserRiskContent(contentData['Digital_User_Risk'])}<br><h2>Threatened</h2><br>${threatenedContent(contentData['Threatened'])}`;
                        break;
                    case 'Report Detail':
                        content.innerHTML = reportDetailContent(contentData);
                        break;
                    case 'Threatened':
                        content.innerHTML = threatenedContent(contentData);
                        break;
                    case 'Digital User Risk':
                        content.innerHTML = digitalUserRiskContent(contentData);
                        break;
                    default:
                        break;
                }
            }

            function threatenedContent(contentData) {
                if (Array.isArray(contentData) && contentData.length > 0) {
                    let contentHTML = '<ul>';
                    contentData.forEach((threatenedItem, index) => {
                        contentHTML += `<li><strong>Threat ${index + 1}:</strong><br>`;
                        contentHTML += '<table class="table table-bordered">';
                        for (const [key, value] of Object.entries(threatenedItem)) {
                            if (Array.isArray(value)) {
                                contentHTML += `<tr>
                                                    <td><strong>${formatKeyName(key)}:</strong></td>
                                                    <td>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>${Object.keys(value[0]).map((innerKey) => `<th>${formatKeyName(innerKey)}</th>`).join('')}</tr>
                                                            </thead>
                                                            <tbody>
                                                                ${value.map((item) => `<tr>${Object.values(item).map((innerValue) => `<td>${innerValue}</td>`).join('')}</tr>`).join('')}
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>`;
                            } else if (typeof value === 'object') {
                                contentHTML += `<tr>
                                                    <td><strong>${formatKeyName(key)}:</strong></td>
                                                    <td>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                ${Object.entries(value).map(([innerKey, innerValue]) => `<tr><td><strong>${formatKeyName(innerKey)}</strong></td><td>${innerValue}</td></tr>`).join('')}
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>`;
                            } else {
                                contentHTML += `<tr><td><strong>${formatKeyName(key)}:</strong></td><td>${value}</td></tr>`;
                            }
                        }
                        contentHTML += '</table>';
                        contentHTML += '</li>';
                    });
                    contentHTML += '</ul>';
                    return contentHTML;
                } else {
                    return '<p>No threatened data available.</p>';
                }
            }

            function digitalUserRiskContent(contentData) {
                if (Array.isArray(contentData) && contentData.length > 0) {
                    const riskItem = contentData[0]; // Assuming there is only one risk item in the array
                    return `<h4>Email At Risk Low</h4>
                        ${generateEmailList(riskItem["email_at_risk_low"])}
                        <h4>Email At Risk Medium</h4>
                        ${generateEmailList(riskItem["email_at_risk_medium"])}
                        <h4>Email At Risk High</h4>
                        ${generateEmailList(riskItem["email_at_risk_high"])}
                        <h4>Email Risks</h4>
                        <p>${riskItem["email_risks"]}</p>
                        <h4>Email Risks Solution</h4>
                        <p>${riskItem["email_risks_solution"]}</p>
                        <h4>Hacked Email Addresses</h4>
                        ${generateHackedEmailAddresses(riskItem["hacked_email_address"]["hacked_email_address"])}
                        <h4>Hacked Email Addresses Solution</h4>
                        <p>${riskItem["hacked_email_address"]["hacked_email_address_solution"]}</p>`;
                } else {
                    return '<p>No Digital User Risk data available.</p>';
                }
            }

            function generateEmailList(emails) {
                if (Array.isArray(emails) && emails.length > 0) {
                    let emailListHTML = '<ul>';
                    emails.forEach((email) => {
                        emailListHTML += `<li>${email}</li>`;
                    });
                    emailListHTML += '</ul>';
                    return emailListHTML;
                } else {
                    return '<p>No email data available.</p>';
                }
            }

            function generateHackedEmailAddresses(hackedEmails) {
                if (Array.isArray(hackedEmails) && hackedEmails.length > 0) {
                    let hackedEmailsHTML = '<ul>';
                    hackedEmails.forEach((hackedEmail) => {
                        hackedEmailsHTML += `<li>${hackedEmail}</li>`;
                    });
                    hackedEmailsHTML += '</ul>';
                    return hackedEmailsHTML;
                } else {
                    return '<p>No hacked email addresses available.</p>';
                }
            }

            function reportDetailContent(contentData) {
                return `<div>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Client ID:</strong></td>
                                <td>${contentData["clientid"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Scan ID:</strong></td>
                                <td>${contentData["scan_id"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Client Name:</strong></td>
                                <td>${contentData["client_name"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Client Email:</strong></td>
                                <td>${contentData["client_email"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Client Industry:</strong></td>
                                <td>${contentData["client_industry"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Client Web/IP:</strong></td>
                                <td><a href="${contentData["client_web_ip"]}">${contentData["client_web_ip"]}</a></td>
                            </tr>
                            <tr>
                                <td><strong>Final Score:</strong></td>
                                <td>${contentData["final_score"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>${contentData["status"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Low Vulnerabilities:</strong></td>
                                <td>${contentData["Low_vuln"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Medium Vulnerabilities:</strong></td>
                                <td>${contentData["Medium_vuln"]}</td>
                            </tr>
                            <tr>
                                <td><strong>High Vulnerabilities:</strong></td>
                                <td>${contentData["High_vuln"]}</td>
                            </tr>
                            <tr>
                                <td><strong>Critical Vulnerabilities:</strong></td>
                                <td>${contentData["Critical_vuln"]}</td>
                            </tr>
                        </table>
                        <h4>Vulnerability Details</h4>
                        <ul>
                            <li><strong>Alert:</strong> ${contentData["Vulnerability"][0].alert}</li>
                            <li><strong>CVE:</strong> ${contentData["Vulnerability"][0]["CVE"]}</li>
                            <li><strong>Severity:</strong> ${contentData["Vulnerability"][0]["severity"]}</li>
                            <li><strong>URI:</strong> <a href="${contentData["Vulnerability"][0]["uri"]}">${contentData["Vulnerability"][0]["uri"]}</a></li>
                            <li><strong>Description:</strong> ${contentData["Vulnerability"][0]["description"]}</li>
                            <li><strong>Solution:</strong> ${contentData["Vulnerability"][0]["solution"]}</li>
                        </ul>
                    </div>`;
            }

            showHome();
            const showHomeButton = document.querySelector('.sidebar .home');
            showHomeButton.onclick = showHome;
            const sidebarButtons = document.querySelector('.sidebar .sidebar-buttons');
            const mostRecentChildKeys = Object.keys(reportData);
            mostRecentChildKeys.forEach(key => {
                const button = document.createElement('button');
                button.className = 'btn btn-primary btn-block';
                button.innerText = formatKeyName(key);
                button.onclick = (event) => showBasedOnChildClicked(event, key);
                sidebarButtons.appendChild(button);
            });
            const signature = document.querySelector('.sidebar .signature');
            signature.innerText = new Date().getFullYear().toString() + ' Nguyen Anh Tuan Le';
        } else {
            alert('No data found!');
        }
    });
</script>
</body>
</html>