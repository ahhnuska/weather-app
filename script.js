
// const main_Division = document.querySelector("#main");
// const Api = "116ac04a9a45a0546f051907feb5c62c";

// const deleteWeatherInfo = () => {
//   while (main_Division.firstChild) {
//     main_Division.removeChild(main_Division.firstChild);
//   }
// };

// function currentData(timezone) {
//   let currentDate = new Date();
//   let utcOffset = currentDate.getTimezoneOffset() * 60 * 1000;
//   let cityOffset = timezone * 1000;
//   let currentTimestamp = currentDate.getTime() + utcOffset + cityOffset;
//   let localDate = new Date(currentTimestamp);
//   let options = {
//     weekday: 'long',
//     year: 'numeric',
//     month: 'long',
//     day: 'numeric',
//     hour: 'numeric',
//     minute: 'numeric'
//   };
//   let formattedDate = localDate.toLocaleDateString(undefined, options);
//   document.getElementById('current-day-and-date').textContent = formattedDate;
// }

// function fetchForecast(cityName) {
//   fetch(`main.php?city=${cityName}`)
//     .then(response => response.json())
//     .then(data => {
//       const forecastContainer = document.querySelector(".forecast");
//       forecastContainer.innerHTML = "";

//       data.forecast.forEach(day => {
//         const forecastDay = document.createElement("div");
//         forecastDay.className = "forecast-day";
//         forecastDay.innerHTML = `
//           <div class="date">${day.date}</div>
//           <img class="forecast-icon" src="http://openweathermap.org/img/w/${day.icon}.png" alt="Weather Icon">
//           <div class="forecast-temperature">${day.temperature}°C</div>
//         `;
//         forecastContainer.appendChild(forecastDay);
//       });
//     })
//     .catch(error => {
//       console.error("Error fetching forecast data:", error);
//     });
// }

// const done = async (cityname = "Southampton") => {
//   console.log("city name ", cityname);
//   deleteWeatherInfo(); // Delete previous weather information

//   const doing = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityname}&units=metric&appid=${Api}`);
//   const data = await doing.json();

//   if (data.cod != 200) {
//     alert("Error! The name of the city is invalid!")
//     return;
//   }

//   const built = document.createElement("div");
//   built.innerHTML = `
//     <div class="outside">
//       <img src="http://openweathermap.org/img/w/${data.weather[0].icon}.png" alt="Weather Icon">
//       <div class="des">${data.weather[0].description}</div>
//       <div class="weather-info.country">${data.name}</div>
//       <div><b>${data.main.temp}°C</b></div>
//       <div class="descriptionBox">
//         <div id="current-day-and-date"></div>
//         <div class="weather-info"> Pressure: ${data.main.pressure}MBar</div>
//         <div class="weather-info">Windspeed: ${data.wind.speed}m/s</div>
//         <div class="weather-info">Humidity: ${data.main.humidity}%</div>
//       </div>
//     </div>`;

//   main_Division.append(built);
//   currentData(data.timezone);
//   fetchForecast(cityname);
// }

// document.querySelector("#btnsearch").addEventListener("click", async (e) => {
//   e.preventDefault();
// });


// // document.querySelector("#searchBar").addEventListener("keydown", (e) => {
// //   if (e.key === "Enter") {
// //     done(document.querySelector("#searchBar").value);
// //   }
// // });

// done();
