function RetornoAjax(id, url, container)
{
var xmlHttp=GetXmlHttpObject()

if (xmlHttp==null)
{
     alert ("Este browser não suporta HTTP Request")
     return
}

var url=url;
alert(id);
url=url+"?id="+id;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=function()
{
     if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
     {
         document.getElementById(container).innerHTML=xmlHttp.responseText
     }
}
xmlHttp.open("GET",url,true)
xmlHttp.send(null)
}

function GetXmlHttpObject()
{
var objXMLHttp=null

     if (window.XMLHttpRequest)
         objXMLHttp=new XMLHttpRequest()
     else if (window.ActiveXObject)
         objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")

return objXMLHttp
}
