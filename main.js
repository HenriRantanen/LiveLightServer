/*******************************************************************************************
Functiot valaistuseditorille
********************************************************************************************/
function init()
{
	/*document.getElementById("red").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	document.getElementById("green").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	document.getElementById("blue").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	document.getElementById("red2").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	document.getElementById("green2").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	document.getElementById("blue2").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("channelSlider"), false);
	
	document.getElementById("hue").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("hslSlider"), false);
	document.getElementById("saturation").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("hslSlider"), false);
	document.getElementById("brightness").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights("hslSlider"), false);
	document.getElementById("dimmer").parentNode.childNodes[1].childNodes[1].addEventListener("mouseup", updateLights(), false);
	
	document.getElementById("red").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("green").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("blue").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("red2").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("green2").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("blue2").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("hue").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("saturation").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("brightness").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);
	document.getElementById("dimmer").parentNode.childNodes[1].childNodes[1].addEventListener("touchend", updateLights("channelSlider"), false);*/
}

function updateLights(source)
{
	// Lomakkeen nimi
	lomake 	= document.getElementById("main");
	
	// Kanavasäätimen tiedot
	ch1 	= lomake.elements["red"].value;
	ch2 	= lomake.elements["green"].value;
	ch3 	= lomake.elements["blue"].value;
	ch4 	= lomake.elements["red2"].value;
	ch5 	= lomake.elements["green2"].value;
	ch6 	= lomake.elements["blue2"].value;
	
	// Debug stuff
	console.clear();
	console.log("Channel 1: "+ch1);
	console.log("Channel 2: "+ch2);
	console.log("Channel 3: "+ch3);
	console.log("Channel 4: "+ch4);
	console.log("Channel 5: "+ch5);
	console.log("Channel 6: "+ch6);
	
	// Create HEX and write to console
	if (ch1 < 16){red = "0"+parseInt(ch1).toString(16)}
	else {red = parseInt(ch1).toString(16)};
	if (ch2 < 16){green = "0"+parseInt(ch2).toString(16)}
	else {green = parseInt(ch2).toString(16)};
	if (ch3 < 16){blue = "0"+parseInt(ch3).toString(16)}
	else {blue = parseInt(ch3).toString(16)};
	
	console.log("HEX1: "+(red+green+blue).toUpperCase());
	
	if (ch4 < 16){red = "0"+parseInt(ch4).toString(16)}
	else {red = parseInt(ch4).toString(16)};
	if (ch5 < 16){green = "0"+parseInt(ch5).toString(16)}
	else {green = parseInt(ch5).toString(16)};
	if (ch6 < 16){blue = "0"+parseInt(ch6).toString(16)}
	else {blue = parseInt(ch6).toString(16)};
	
	console.log("HEX2: "+(red+green+blue).toUpperCase());
	

	// HSL-valitsimen tiedot
	hue 		= lomake.elements["hue"].value;
	saturation 	= lomake.elements["saturation"].value;
	brightness 	= lomake.elements["brightness"].value;
	
	// Himmentimen tieto
	dimmer 		= lomake.elements["dimmer"].value;
	
	// Himmentimen tieto
	cct 		= lomake.elements["cct"].value;

	// Värivalitsimen tieto
	colorPicker = encodeURIComponent(lomake.elements["colorPicker"].value);
	
	// Valitse väriavaruus
	switch (source)
	{
		case "colourPicker":
			colorspace = "picker"
			break;
		case "channelSlider":
			colorspace = "rgb"
			break;
		case "hslSlider":
			colorspace = "hsl"
			break;
		case "cctSlider":
			colorspace = "cct"
			break;
	}

	// Mitä valoja ohjataan
	var light1 = lomake.elements["light1"].checked ? "on" : "off";
	var light2 = lomake.elements["light2"].checked ? "on" : "off";
	var light3 = lomake.elements["light3"].checked ? "on" : "off";
	var light4 = lomake.elements["light4"].checked ? "on" : "off";

	// Tallenna data ohjaimille "2"
	var command = 0x33;
	
	// Valmistele ja lähetä käsky rajapinnalle
	var requestURL = "lib/engine.php?red=" + ch1 + "&green=" + ch2 + "&blue=" + ch3 + "&red2=" + ch4 + "&green2=" + ch5 + "&blue2=" + ch6 + "&hue=" + hue + "&saturation=" + saturation + "&brightness=" + brightness + "&dimmer=" + dimmer + "&colorspace=" + colorspace + "&colorPicker=" + colorPicker + "&command=" + command + "&light1=" + light1 + "&light2=" + light2 + "&light3=" + light3 + "&light4=" + light4 + "&cct=" + cct;
	$.get(requestURL);
}

/*******************************************************************************************
Functiot pikanappuloille
********************************************************************************************/
function dim(hex)
{
	var brightness = document.getElementById("himmennin").value;
	var red = Math.round((brightness * parseInt(hex.substring(0,2),16))/100);
	var green = Math.round((brightness * parseInt(hex.substring(2,4),16))/100);
	var blue = Math.round((brightness * parseInt(hex.substring(4,6),16))/100);

	if (red < 16){red = "0"+red.toString(16)}
	else {red = red.toString(16)};
	if (green < 16){green = "0"+green.toString(16)}
	else {green = green.toString(16)};
	if (blue < 16){blue = "0"+blue.toString(16)}
	else {blue = blue.toString(16)};
	 
	return (red+green+blue);
}

function setBrightness() 
{	
	var color1 = document.getElementById("light1").value.substring(1,7);
	var color2 = document.getElementById("light2").value.substring(1,7);
	var color3 = document.getElementById("light3").value.substring(1,7);
	var color4 = document.getElementById("light4").value.substring(1,7);
	//var color5 = document.getElementById("light5").value.substring(1,7);
	//var color6 = document.getElementById("light6").value.substring(1,7);
	//var color7 = document.getElementById("light7").value.substring(1,7);
	
	color5 = color6 = color7 = "000000";
	
	setColour(1, color1, color2, color3, color4, color5, color6, color7);
}

function change(colorpicker)
{
	var lamp = colorpicker.name.substring(5,6);
	var colour = colorpicker.value.substring(1,7);

	var url = "lib/setLamp.php?lamp="+lamp+"&color="+dim(colour);
	
	$.get(url);
	return false;
}

function random()
{
	setColour(0,
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16),
	
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16),
	
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16), "000000", 
	
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16),
	
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16), 
	
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16)+
	Number(Math.floor((Math.random()*255)+0)).toString(16), "000000" 
	);
}

function setColour(brightness, color1, color2, color3, color4, color5, color6, color7)
{
	if(brightness == 0)
	{
		document.getElementById("light1").value = "#"+color1;
		document.getElementById("light2").value = "#"+color2;
		document.getElementById("light3").value = "#"+color3;
		document.getElementById("light4").value = "#"+color4;
		//document.getElementById("light5").value = "#"+color5;
		//document.getElementById("light6").value = "#"+color6;
		//document.getElementById("light7").value = "#"+color7;
		//document.getElementById("light8").value = "#"+color8;
	}

	setAutoLight("disable")
	
	var url = "lib/setAll.php?color1="+dim(color1)+"&color2="+dim(color2)+"&color3="+dim(color3)+"&color4="+dim(color4)+"&color5="+dim(color5)+"&color6="+dim(color6)+"&color7="+dim(color7)+"&color8=000000";
	$.get(url);
	return false;
}

function setAutoLight(state)
{
	var enable = 0
	if(state == "enable"){enable = 1}
	else if(state == "disable"){enable = 0}

	var url = "lib/autoMode.php?set="+enable;
	$.get(url);
	return false;
}

function lightsOn()
{
	$.get("lib/timeOfDay.php?i=1");
    return false;
}

function setPreset(name)
{
	loader("set");
	var buttontext = document.getElementById("lightsOn");
	
	switch (name)
	{
		case "auto":
			setAutoLight("enable");
			buttontext.textContent = "Automaatti";
			break;
		case "daylight":
			var color = "color1=EBD9D1&color2=FFE8AC&color3=A7C6D2&color4=4AFF00&color5=40FFFF&color6=FF0000&color7=FFEFAB&color8=000000";
			buttontext.textContent = "Päivänvalo";
			setAutoLight("disable");
			break;
		case "off":
			var color = "color1=000000&color2=000000&color3=000000&color4=000000&color5=000000&color6=000000&color7=000000&color8=000000";
			buttontext.textContent = "Päälle";
			setAutoLight("disable");
			break;
		case "night":
			var color = "color1=1A1005&color2=1A0E03&color3=000000&color4=000000&color5=1A1005&color6=1A0E03&color7=1A1005&color8=1A1005";
			buttontext.textContent = "Iltavalo";
			setAutoLight("disable");
			break;
	}
	var url = "http://192.168.0.200/lib/setAll.php?"+color;
	$.get(url);
	
	var delay=5000;

	setTimeout(function(){
	  loader("clear");
	}, delay); 
	
	return false;
}

function loader(state) {
	var element = document.getElementById("loaderScreen");
	
	if(state === "set")
	{
		
		var op = 0.1;  // initial opacity
		element.style.opacity = op;
		element.style.display = 'block';
		var timer = setInterval(function () {
			if (op >= 1){
				clearInterval(timer);
			}
			element.style.opacity = op;
			element.style.filter = 'alpha(opacity=' + op * 100 + ")";
			op += op * 0.1;
			}, 25);
	}
	
	if(state === "clear")
	{
		var op = 1;  // initial opacity
		var timer = setInterval(function () {
        if (op <= 0.1){
            clearInterval(timer);
            element.style.display = 'none';
        }
        element.style.opacity = op;
        element.style.filter = 'alpha(opacity=' + op * 100 + ")";
        op -= op * 0.1;
		}, 10);
	}	
}

function logUser(user, direction)
{
		
}