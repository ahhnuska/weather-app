<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather App</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-...your-sha512-value..." crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@900&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="search-container">
      <input type="text" id="searchBar" placeholder="Enter the name of the city...">
      <button id="btnsearch" type="submit"><i class="fas fa-search"></i></button>
    </div>
    <div id="main">
      <!-- Your main content here -->
    </div>
    <div class="weatherHistory">
      <h2 class="font">Weather History</h2>
      
      <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "weatherdata";
        

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM weather_forecast ORDER BY currentDate DESC LIMIT 7";
        $result = $conn->query($sql);

        if ($result === false) {
          // Query execution failed
          echo "Error executing query: " . $conn->error;
        }
        elseif($result->num_rows > 0) {
          echo '<div class="weather-history">';
            
            while ($row = $result->fetch_assoc()) {
                echo '<div class="weather-ins">';
                echo '<div class="icon" style="padding-left:15px;"><img src="http://openweathermap.org/img/w/' . $row['icon'] . '.png" alt="Weather Icon"></div>';
                echo '<div class="temperature">' . $row['temperature'] . '°C</div>';
                
                echo '<div class="weather-day">' . $row['currentDate'] . '<br></div>';
                echo '</div>';

               
            }
            echo '</div>';
        } else {
            echo "No weather data available.";
        }
        

        $conn->close();
      ?>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="script.js"></script>
</body>
</html>

<?php
$apiKey = "116ac04a9a45a0546f051907feb5c62c"; 
$city = "Southampton";
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}";

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$apiResponse = curl_exec($curl);

if ($apiResponse === false) {
    echo "Error fetching weather data from the API: " . curl_error($curl);
    curl_close($curl);
    exit();
}
curl_close($curl);

$weatherData = json_decode($apiResponse, true);

if ($weatherData['cod'] === 200) {
    $temperature = $weatherData['main']['temp'];
    $description = $weatherData['weather'][0]['description'];
    $currentDate = date('Y-m-d');
    $pressure = $weatherData['main']['pressure'];
    $windSpeed = $weatherData['wind']['speed'];
    $humidity = $weatherData['main']['humidity'];
    $icon = $weatherData['weather'][0]['icon'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $checkSql = "SELECT * FROM weather_forecast WHERE currentDate = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $currentDate);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        $insertSql = "INSERT INTO weather_forecast (city, temperature, description, current_day_and_date, pressure, wind_speed, humidity, icon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssssssss", $city, $temperature, $description, $currentDate, $pressure, $windSpeed, $humidity, $icon);

        $insertStmt->execute();
    }
    $conn->close();
}
?>
