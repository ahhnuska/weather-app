<?php
$Api = "116ac04a9a45a0546f051907feb5c62c";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weatherdata";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function currentData($timezone)
{
    $currentDate = new DateTime();
    $utcOffset = $currentDate->getTimezone()->getOffset($currentDate) * 1000;
    $cityOffset = $timezone * 1000;
    $currentTimestamp = $currentDate->getTimestamp() * 1000 + $utcOffset + $cityOffset;
    $localDate = new DateTime();
    $localDate->setTimestamp($currentTimestamp / 1000);
    $formattedDate = $localDate->format('l, F j, Y - H:i');
    return $formattedDate;
}

function getWeatherData($cityName)
{
    global $Api;

    $url = "https://api.openweathermap.org/data/2.5/weather?q=$cityName&units=metric&appid=$Api";
    $response = file_get_contents($url);

    if ($response !== false) {
        return json_decode($response, true);
    }

    return null;
}

function saveWeatherData($cityData)
{
    global $servername, $username, $password, $dbname;

    $city = $cityData['name'];
    $temperature = $cityData['main']['temp'];
    $description = $cityData['weather'][0]['description'];
    $currentDate = date('Y-m-d');
    $pressure = $cityData['main']['pressure'];
    $windSpeed = $cityData['wind']['speed'];
    $humidity = $cityData['main']['humidity'];
    $icon = $cityData['weather'][0]['icon'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $checkSql = "SELECT * FROM weather_forecast WHERE city = ? AND currentDate = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $city, $currentDate);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        $insertSql = "INSERT INTO weather_forecast (city, temperature, description, currentDate, pressure, windSpeed, humidity, icon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssssssss", $city, $temperature, $description, $currentDate, $pressure, $windSpeed, $humidity, $icon);

        $insertStmt->execute();
        echo "Weather data inserted for city " . $city;
    } else {
        echo "Weather data for city " . $city . " on " . $currentDate . " already exists.";
    }
    $conn->close();
}

$cityName = "Southampton"; // Default city
if (isset($_POST['city'])) {
    $cityName = $_POST['city'];
    $weatherData = getWeatherData($cityName);
    if ($weatherData === null || $weatherData['cod'] != 200) {
        echo "Error! The name of the city is invalid!";
        exit;
    }
    saveWeatherData($weatherData);
} else {
    // Fetch default weather data
    $weatherData = getWeatherData($cityName);
    if ($weatherData === null || $weatherData['cod'] != 200) {
        echo "Error! Default city data not available!";
        exit;
    }
    saveWeatherData($weatherData);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="style.css">
    <!-- Add your CSS links here -->
</head>

<body>
    <div id="main">
        <div class="outside">
            <div class="search-container">
                <form id="searchForm" method="post">
                    <input type="text" id="searchBar" placeholder="Enter the name of the city..." name="city">
                    <button id="btnsearch" type="submit">Search</button>
                </form>
            </div>
            <img src="http://openweathermap.org/img/w/<?php echo $weatherData['weather'][0]['icon']; ?>.png"
                alt="Weather Icon">
            <div class="des">
                <?php echo $weatherData['weather'][0]['description']; ?>
            </div>
            <div class="weather-info.country">
                <?php echo $weatherData['name']; ?>
            </div>
            <div><b>
                    <?php echo $weatherData['main']['temp']; ?>°C
                </b></div>
            <div class="descriptionBox">
                <div id="current-day-and-date">
                    <?php echo currentData($weatherData['timezone']); ?>
                </div>
                <div class="weather-info">Pressure:
                    <?php echo $weatherData['main']['pressure']; ?>MBar
                </div>
                <div class="weather-info">Windspeed:
                    <?php echo $weatherData['wind']['speed']; ?>m/s
                </div>
                <div class="weather-info">Humidity:
                    <?php echo $weatherData['main']['humidity']; ?>%
                </div>
            </div>
        </div>
    </div>
</body>

</html>
