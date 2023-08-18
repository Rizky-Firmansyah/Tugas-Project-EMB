<!DOCTYPE html>
<html>

<head>
    <title>Data Sensor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            border: 1px solid #000000;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>

    <script>
        function fetchSensorData() {
            $.ajax({
                url: '<?= site_url('sensor/get_sensor_data') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    createSensorTable(data);
                    createSensorChart(data);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Fungsi untuk mengambil waktu saat ini
        function getCurrentTime() {
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();
            hours = (hours < 10 ? "0" : "") + hours;
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;
            var formattedTime = hours + ":" + minutes + ":" + seconds;
            document.getElementById("current-time").innerHTML = "Waktu saat ini: " + formattedTime;
        }

        // Fungsi untuk membuat tabel dari data sensor
        function createSensorTable(data) {
            var tableHtml = '<table>';
            tableHtml += '<tr><th>No.</th><th>Waktu</th><th>Nilai LDR</th></tr>';

            for (var i = 0; i < data.length; i++) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + data[i].id + '</td>';
                tableHtml += '<td>' + data[i].timestamp + '</td>';
                tableHtml += '<td>' + data[i].ldr_value + '</td>';
                tableHtml += '</tr>';
            }

            tableHtml += '</table>';
            $('#sensor-table').html(tableHtml); // Menggunakan jQuery untuk mengganti konten HTML
        }

        function createSensorChart(data) {
            var timestamps = data.map(item => item.timestamp);
            var ldrValues = data.map(item => item.ldr_value);

            var ctx = document.getElementById('sensor-chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'Nilai LDR',
                        data: ldrValues,
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: {
                        duration: 0,
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'second'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }


        // Fungsi untuk memperbarui data secara otomatis
        function autoUpdate() {
            getCurrentTime();
            fetchSensorData();
        }

        $(document).ready(function () {
            autoUpdate();
            setInterval(autoUpdate, 1000);
        });
    </script>



</head>

<body onload="fetchSensorData(); setInterval(fetchSensorData, 1000);" style="background-color: aquamarine;">
    <h1>Data Sensor LDR</h1>
    <div id="current-time" style="font-weight: bold;"></div>

    <canvas id="sensor-chart" width="10000" height="5000" style="background-color: white;"></canvas>

    <div id="sensor-table" style="background-color: cornsilk;"></div>

</body>

</html>