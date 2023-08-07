const main_Division = document.querySelector("#main");
const Api = "116ac04a9a45a0546f051907feb5c62c";

const deleteWeatherInfo = () => {
  while (main_Division.firstChild) {
    main_Division.removeChild(main_Division.firstChild);
  }
};
function currentData(timezone) {
  let currentDate = new Date();
  let utcOffset = currentDate.getTimezoneOffset() * 60 * 1000;
  let cityOffset = timezone * 1000;
  let currentTimestamp = currentDate.getTime() + utcOffset + cityOffset;
  let localDate = new Date(currentTimestamp);
  let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
  let formattedDate = localDate.toLocaleDateString(undefined, options);
  document.getElementById('current-day-and-date').textContent = formattedDate;
}
const done = async (cityname = "Southampton") => {
  console.log("city name ", cityname);
  deleteWeatherInfo(); // Delete previous weather information
  
  const doing = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityname}&units=metric&appid=${Api}`);
  const data = await doing.json();
  console.log(data)
  if (data.cod != 200) {
    alert("Error!.The name of the city is invalid!")
    return;
  }
  
  const built = document.createElement("div");
  

  built.innerHTML = `
<div class="outside">
<img src="http://openweathermap.org/img/w/${data.weather[0].icon}.png" alt="Weather Icon">
<div class="des" style="font-size:12px"><i>${data.weather[0].description}</i></div>
<div style="font-size:1.5rem" class="weather-info.country">${data.name}</div>
<div style="font-size:25px" ><b> ${data.main.temp}°C</b></div> 
<div class=descriptionBox>
<div id="current-day-and-date"><i></i></div>
  <div class="weather-info"> Pressure: ${data.main.pressure}MBar</div>
  <div class="weather-info">Windspeed: ${data.wind.speed}m/s</div>
  <div class="weather-info">Humidity: ${data.main.humidity}%</div>
  
<div>
<div>
`;


main_Division.append(built);
currentData(data.timezone)
  console.log(main_Division);
  console.log(data);
};

document.querySelector("#btnsearch").addEventListener("click", () => done(document.querySelector("#searchBar").value));

document.querySelector("#searchBar").addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    done(document.querySelector("#searchBar").value);

  }

});
done()