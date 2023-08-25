<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather App</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha512-...your-sha512-value..." crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@900&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <?php
    include 'index.php';
    ?>
    <div id="main">
      <!-- Your main content here -->
    </div>
    <div class="weatherHistory">
      <h2 class="font">Weather History
        <?php
        if (isset($_POST['city'])) {
          $cityName = $_POST['city'];
          echo $cityName;
        }
        ?>
      </h2>

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

      if (isset($_POST['city'])) {
        $cityName = $_POST['city'];
        echo $cityName;
    } else {
        $cityName = "Southampton";
    }
    
    $sql = "SELECT * FROM weather_forecast WHERE city = '$cityName' ORDER BY currentDate DESC LIMIT 7";
    $result = $conn->query($sql);
      if ($result === false) {
        // Query execution failed
        echo "Error executing query: " . $conn->error;
      } elseif ($result->num_rows > 0) {
        echo '<div class="weather-history">';

        while ($row = $result->fetch_assoc()) {
          echo '<div class="weather-ins">';
          echo '<div class="icon" style="padding-left:15px;"><img src="http://openweathermap.org/img/w/' . $row['icon'] . '.png" alt="Weather Icon"></div>';
          echo '<div class="weather-description">' . $row['description'] . '</div>'; // Displaying weather description
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"
    integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="script.js"></script>
</body>

</html>