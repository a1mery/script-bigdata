jsonurl = "http://api.openweathermap.org/data/2.5/weather?lat=35&lon=139";
$.get(jsonurl,getData).error(errorHandler);
alert('1');