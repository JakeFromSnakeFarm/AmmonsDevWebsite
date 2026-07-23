<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client and Appliance Information</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            padding: 20px;
            color: #000;
        }
        h1 {
            color: #000;
        }
        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            max-width: 800px;
            margin: 20px auto;
        }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 2px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
            font-size: large;
        }
        input[type="text"]:focus, input[type="email"]:focus, textarea:focus, select:focus {
            border-color: #0056b3;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border-spacing: 0;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #777;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        ::placeholder {
            color: black;
            font-style: bold;
            font-size: large;
            opacity: 1; /* Firefox */
        }
    </style>
</head>

<body>
    <h1>Client Information Form</h1>
    <form id="dataForm">
        <input type="text" name="clientName" placeholder="Client Name" required>
        <input type="text" name="clientAddress" placeholder="Client Address" required>
        <input type="text" name="clientCity" placeholder="Client City" required>
        <input type="text" name="clientPhoneNumber" placeholder="Client Phone Number" required>
        <input type="email" name="clientEmail" placeholder="Client Email" required>
        <textarea name="gateCode" placeholder="Gate Code/User Notes"></textarea>
        <select name="applianceType" required>
            <option value="" disabled selected>Type of Appliance</option>
            <option value="Washer">Washer</option>
            <option value="Dryer">Dryer</option>
            <option value="Stove">Stove</option>
            <option value="Microwave">Microwave</option>
            <option value="Fridge">Fridge</option>
            <option value="Dishwasher">Dishwasher</option>
            <option value="Oven">Oven</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="additionalApplianceType" placeholder="Appliance Type if Other">
        <input type="text" name="applianceModelNumber" placeholder="Appliance Model Number" required>
        <input type="text" name="applianceBrandName" placeholder="Appliance Brand Name" required>
        <input type="text" name="applianceModelName" placeholder="Appliance Model Name" required>
        <textarea name="applianceSymptoms" placeholder="Appliance Symptoms"></textarea>
        <input type="datetime-local" name="jobDateTime" placeholder="Job Date and Time" required>
        <textarea name="repairAssumptions" placeholder="Repair Assumptions"></textarea>
        <button type="button" onclick="submitData()">Submit</button>
        <button type="button" onclick="submitToJames()">Submit to James</button>
    </form>
    <h1>Stored Data</h1>
    <div id="dataDisplay"></div>
    <h1>Stored Data for James</h1>
    <div id="dataForJamesDisplay"></div>
    <!--     <script>
        function submitData() {
            $.post('submit.php', $('#dataForm').serialize(), function(response) {
                alert(response.message);
                fetchData(); // Refresh regular data display
                fetchDataForJames(); // Also refresh data for James in case it's needed
            }, 'json');
            document.getElementById("dataForm").reset();
        }

        function submitToJames() {
            $.post('submitToJames.php', $('#dataForm').serialize(), function(response) {
                alert(response.message);
                fetchDataForJames(); // Refresh data display specifically for James
            }, 'json');
            document.getElementById("dataForm").reset();
        }

        // function saveEdit(index) {
        //     var row = $('tr[data-index="' + index + '"]');
        //     var obj = {};
        //     row.find('td[contenteditable="true"]').each(function() {
        //         var key = $(this).data('field');
        //         var value = $(this).text();
        //         obj[key] = value;
        //     });

        //     $.post('update.php', {index: index, data: obj}, function(response) {
        //         alert(response.message);
        //         fetchData(); // Refresh data display
        //     }, 'json');
        // }

        // function deleteEntry(index) {
        //     $.post('delete.php', { index: index }, function(response) {
        //         alert(response.message);
        //         fetchData();
        //     }, 'json');
        // }

        function fetchData() {
            $.get('fetch.php', function(data) {
                renderData(data, '#dataDisplay');
            });
        }

        function fetchDataForJames() {
            $.get('fetchForJames.php', function(data) {
                renderData(data, '#dataForJamesDisplay');
            });
        }

        function renderData(data, selector) {
            if (Array.isArray(data)) {
                var html = '<table border="1" id="data-table"><thead><tr><th>Client Name</th><th>Client Address</th><th>Client City</th><th>Client Phone Number</th><th>Client Email</th><th>Gate Code/User Notes</th><th>Appliance Type</th><th>Additional Appliance Type</th><th>Appliance Model Number</th><th>Appliance Brand Name</th><th>Appliance Model Name</th><th>Appliance Symptoms</th><th>Job Schedule</th><th>Repair Assumptions</th><th>Actions</th></tr></thead><tbody>';
                data.forEach(function(item, index) {
                    html += '<tr data-index="' + index + '">';
                    Object.keys(item).forEach(function(key) {
                        html += '<td contenteditable="true" data-field="' + key + '">' + item[key] + '</td>';
                    });
                    html += '<td><button onclick="saveEdit(' + index + ')">Save</button> <button onclick="deleteEntry(' + index + ')">Delete</button></td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
            } else {
                var html = 'No data available or data is corrupted.';
            }
            $(selector).html(html);
        }

        $(document).ready(function() {
            fetchData(); // Load regular data on page load
            fetchDataForJames(); // Load data for James on page load
        });
    </script> -->
    <script>
    function submitData() {
        $.post('submit.php', $('#dataForm').serialize(), function(response) {
            alert(response.message);
            fetchData();
        }, 'json');
        document.getElementById("dataForm").reset()
    }

    function submitToJames() {
        $.post('submitToJames.php', $('#dataForm').serialize(), function(response) {
            alert(response.message);
            fetchDataForJames();
        }, 'json');
        document.getElementById("dataForm").reset()
    }

    function fetchData() {
        $.get('fetch.php', function(data) {
            renderData(data, '#dataDisplay', 'general');
        });
    }

    function fetchDataForJames() {
        $.get('fetchForJames.php', function(data) {
            renderData(data, '#dataForJamesDisplay', 'james');
        });
    }

    function renderData(data, selector, type) {
        if (Array.isArray(data)) {
            var html = '<table border="1"><thead><tr><th>Client Name</th><th>Client Address</th><th>Client City</th><th>Client Phone Number</th><th>Client Email</th><th>Gate Code/User Notes</th><th>Appliance Type</th><th>Additional Appliance Type</th><th>Appliance Model Number</th><th>Appliance Brand Name</th><th>Appliance Model Name</th><th>Appliance Symptoms</th><th>Job Date and Time</th><th>Repair Assumptions</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(function(item, index) {
                html += '<tr data-index="' + index + '">';
                Object.keys(item).forEach(function(key) {
                    html += '<td contenteditable="true" data-field="' + key + '">' + item[key] + '</td>';
                });
                html += '<td><button onclick="saveEdit(' + index + ', \'' + type + '\')">Save</button> <button onclick="deleteEntry(' + index + ', \'' + type + '\')">Delete</button></td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
        } else {
            var html = 'No data available or data is corrupted.';
        }
        $(selector).html(html);
    }

    function saveEdit(index, type) {
        var endpoint = type === 'james' ? 'updateForJames.php' : 'update.php';
        var tableSelector = type === 'james' ? '#dataForJamesDisplay' : '#dataDisplay';
        var row = $(tableSelector + ' tr[data-index="' + index + '"]');
        var obj = {};
        row.find('td[contenteditable="true"]').each(function() {
            var key = $(this).data('field');
            var value = $(this).text();
            obj[key] = value;
        });

        $.post(endpoint, { index: index, data: obj }, function(response) {
            alert(response.message);
            if (type === 'james') {
                fetchDataForJames();
            } else {
                fetchData();
            }
        }, 'json');
    }


    function deleteEntry(index, type) {
        var endpoint = type === 'james' ? 'deleteForJames.php' : 'delete.php';
        $.post(endpoint, { index: index }, function(response) {
            alert(response.message);
            if (type === 'james') {
                fetchDataForJames();
            } else {
                fetchData();
            }
        }, 'json');
    }

    $(document).ready(function() {
        fetchData();
        fetchDataForJames();
    });
    </script>
</body>

</html>