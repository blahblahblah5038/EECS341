/*
Functions to alternately display/hide sections of the inventory forms
*/

function swapDisplay(type) {
	switch (type)
	{
		case '': plainForm(); break;
		case 'S': dispStabilizer(); break;
		case 'L': dispLimb(); break;
		case 'A': dispArrow(); break;
		case 'R': dispRiser(); break;
	}
}

function plainForm() {
	document.getElementById("stabilizerform").style.display = "none";
	document.getElementById("limbform").style.display = "none";
	document.getElementById("arrowform").style.display = "none";
	document.getElementById("riserform").style.display = "none";
}

function dispStabilizer() {
	document.getElementById("stabilizerform").style.display = "display";
	document.getElementById("limbform").style.display = "none";
	document.getElementById("arrowform").style.display = "none";
	document.getElementById("riserform").style.display = "none";
}

function dispLimb() {
	document.getElementById("stabilizerform").style.display = "none";
	document.getElementById("limbform").style.display = "display";
	document.getElementById("arrowform").style.display = "none";
	document.getElementById("riserform").style.display = "none";
}

function dispArrow() {
	document.getElementById("stabilizerform").style.display = "none";
	document.getElementById("limbform").style.display = "none";
	document.getElementById("arrowform").style.display = "display";
	document.getElementById("riserform").style.display = "none";
}

function dispRiser() {
	document.getElementById("stabilizerform").style.display = "none";
	document.getElementById("limbform").style.display = "none";
	document.getElementById("arrowform").style.display = "none";
	document.getElementById("riserform").style.display = "display";
}o