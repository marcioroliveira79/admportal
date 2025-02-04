
var req;
function loadXMLDoc1(url){
 req = null;

if (window.XMLHttpRequest) {
 req = new XMLHttpRequest();
 req.onreadystatechange = processReqChange1;
 req.open("GET", url, true); 
 req.send(null);

} else if (window.ActiveXObject) {
try {
req = new ActiveXObject("Msxml2.XMLHTTP.4.0");
} catch(e) {
try {
req = new ActiveXObject("Msxml2.XMLHTTP.3.0");
} catch(e) {
try {
req = new ActiveXObject("Msxml2.XMLHTTP");
} catch(e) {
try {
req = new ActiveXObject("Microsoft.XMLHTTP");
} catch(e) {
req = false;
}
}
}
}
if (req) {
 req.onreadystatechange = processReqChange1;
 req.open("GET", url, true);
 req.send();
}
}
}


function processReqChange1(){

if (req.readyState == 4) {
if (req.status == 200) {

document.getElementById("atualiza_item").innerHTML = req.responseText;
} else {
alert("Houve um problema ao obter os dados:\n" + req.statusText);
}
}
}



function atualiza_item(valor){
alert(valor);
loadXMLDoc1("usuario_menu.php?ID="+valor);

}
