var ns4 = (document.layers)? true:false;
var ns6 = (document.getElementById)? true:false;
var ie4 = (document.all)? true:false;
var ie5 = false;
if (ie4) { if (navigator.userAgent.indexOf('MSIE 5')>0) ie5 = true; if (ns6) ns6 = false; }
var fmodus = false;
var erstklick = new kobjekt();
var kartenfenster, zoomfenster;

// Eventhandler müssen noch verändert werden (NS6/7 IE6)
function initialisierung(bildname)
{
 kartenfenster = bilddaten(document.images[bildname]);
 window.objContainer = layerbez('kartenlayer');
 window.objZoomSquare = layerbez('zoomlayer');
 setfobjekt(objContainer, kartenfenster);
 setVisibility(objContainer, true);
 if (ns4)
 {
  objContainer.captureEvents(Event.MOUSEDOWN)
  document.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
 }
 objContainer.onmousedown = klicken;
 document.onmousemove = ziehen;
 document.onmouseup = senden;
}

function kobjekt(x,y)
{
 this.x=(!x)?0:x; this.y=(!y)?0:y;
// this.toString=objToString; this.equals=equalskobjekt;
}

function equalskobjekt(c)
{
 return(this.x == c.x && this.y == c.y);
}

function fobjekt(x, y, breite, hoehe)
{
 this.width=(!breite)?0:breite; this.height=(!hoehe)?0:hoehe;
 this.kobjekt=kobjekt; this.kobjekt(x, y);
// this.equalskobjekt=this.equals; this.equals=equalsfobjekt;
}

function equalsfobjekt(c)
{
 return (this.equalskobjekt == c.equalskobjekt && this.width == c.width && this.height == c.height);
}

function objToString()
{
 var ret = "{";
 for(p in this)
 {
  if (typeof this[p] == "function" || typeof this[p] == "object") continue;
  if(ret.length > 1) ret += ",";
  ret += p + ":" + this[p];
 }
 return ret + "}";
}

function layerbez(bildid, document)
{
 if (!document) document = window.document;
 if (ns4) 
 {
  for (var l = 0; l < document.layers.length; l++)
   if (document.layers[l].id == bildid) return document.layers[l];
  for (var l = 0; l < document.layers.length; l++) 
  {
   var result = layerbez(bildid, document.layers[l].document);
   if (result) return result;
  }
  return null;
 }
 else if (ie4) return document.all[bildid];
 else if (ns6) return document.getElementById(bildid);
 else return null;
}

function setPosition(objLayer, coords)
{
 if (ns4) 
 {
  objLayer.top = coords.y;
  objLayer.left = coords.x;
 }
 else if (window.opera) 
 {
  objLayer.style.top = coords.y;
  objLayer.style.left = coords.x;
 }
 else if (ie4) 
 {
  objLayer.style.top = coords.y;
  objLayer.style.pixelLeft = coords.x;
 }
 else if (ns6) 
 {
  objLayer.style.top = coords.y + 'px';
  objLayer.style.left = coords.x + 'px';
 }
}

function setVisibility(objLayer, visible) 
{
 if(ns4) objLayer.visibility  = (visible == true) ? 'show' : 'hide';
 else objLayer.style.visibility = (visible == true) ? 'visible' : 'hidden';
}

function xyholen(evt) 
{
 e = evt || window.event;
 if(!e) return null;
 if(ns4) return new kobjekt(e.pageX, e.pageY);
 else if(window.opera) return new kobjekt(e.clientX, e.clientY);
 else if(ie4)
 {
  if(ie5) return new kobjekt(e.x + document.body.scrollLeft, e.y + document.body.scrollTop);
  else return new kobjekt(e.x, e.y);
 }
 else if(ns6) return new kobjekt(e.pageX , e.pageY);
}


function setfobjekt(objLayer, canvas)
{
 if (ns4) 
 {
  objLayer.top = canvas.y;
  objLayer.left = canvas.x;
  objLayer.clip.width = canvas.width;
  objLayer.clip.height = canvas.height;
 }
 else if (window.opera) 
 {
  objLayer.style.top = canvas.y;
  objLayer.style.left = canvas.x;
  objLayer.style.height = canvas.height;
  objLayer.style.width = canvas.width;
 }
 else if (ie4) 
 {
  objLayer.style.top = canvas.y ;
  objLayer.style.pixelLeft = canvas.x ;
  objLayer.style.height = canvas.height;
  objLayer.style.width = canvas.width;
 }
 else if (ns6) 
 {
  objLayer.style.top = canvas.y + 'px'; 
  objLayer.style.left = canvas.x + 'px';
  objLayer.style.height = canvas.height;
  objLayer.style.width = canvas.width;
 }
}

function klicken(evt) 
{
 var modstatus = window.document.mapserv.mode.value;
 e = evt || window.event;
 window.erstklick = xyholen(e);
 if (modstatus == 'browse')
 {
  var zoomstatus = window.document.mapserv.zoomdir.value;
  if (zoomstatus == 1)
  {
   setfobjekt(objZoomSquare, new fobjekt(erstklick.x, erstklick.y, 1, 1));
   window.fmodus = true;
  }
  else
  {
   var x1 = window.erstklick.x - kartenfenster.x;
   var y1 = window.erstklick.y - kartenfenster.y;
   bildladen('ladestatus','visible');
   window.document.mapserv.imgbox.value = "";
   window.document.mapserv.imgxy.value = x1 + " " + y1;
   window.document.mapserv.submit();
  }
 }
 else if (modstatus == 'query')
 {
  var x1 = window.erstklick.x - kartenfenster.x;
  var y1 = window.erstklick.y - kartenfenster.y;
  bildladen('ladestatus','visible');
  window.document.mapserv.imgbox.value = "";
  window.document.mapserv.savequery.value = "true";
  window.document.mapserv.imgxy.value = x1 + " " + y1;
  window.document.mapserv.submit();
 }
}

function ziehen(evt)
{
 var modstatus = window.document.mapserv.mode.value;
 var zoomstatus = window.document.mapserv.zoomdir.value;
 if (modstatus == 'browse' && zoomstatus == 1)
 {
  e = evt || window.event;
  if(window.fmodus)
  {
   var xyziehen =  xyholen(e);
   var diffx = xyziehen.x - window.erstklick.x;
   var diffy = xyziehen.y - window.erstklick.y;
   if(Math.abs(diffx) > 10 && Math.abs(diffy) > 10)
   {
    setVisibility(objZoomSquare, true);
    if(ns4) objZoomSquare.background.src="../graphics/s.gif";
   }
   else
   {
    setVisibility(objZoomSquare, false);
    if(ns4) objZoomSquare.background.src="";
   }
   zoomfenster = new fobjekt(window.erstklick.x, window.erstklick.y);
   if((diffx) < 0) zoomfenster.x = (window.erstklick.x + diffx);
   if((diffy) < 0) zoomfenster.y = (window.erstklick.y + diffy );
   zoomfenster.height = Math.abs(diffy);
   zoomfenster.width = Math.abs(diffx);
   if(zoomfenster.x < kartenfenster.x || zoomfenster.y < kartenfenster.y || kartenfenster.width < (zoomfenster.width + zoomfenster.x - kartenfenster.x) || kartenfenster.height < (zoomfenster.height + zoomfenster.y - kartenfenster.y)) return; 
   else setfobjekt(objZoomSquare, zoomfenster);
  }
 }
}

function senden()
{
 var modstatus = window.document.mapserv.mode.value;
 if (modstatus == 'browse')
 {
  var x1, y1, x2, y2;
  if(fmodus == true)
  {
   if(!zoomfenster || (zoomfenster.width < 10 && zoomfenster.height < 10))
   {
    x1 = window.erstklick.x - kartenfenster.x;
    y1 = window.erstklick.y - kartenfenster.y;
    window.document.mapserv.imgbox.value = "";
    window.document.mapserv.imgxy.value = x1 + " " + y1;
   }
   else
   {
    x1 = zoomfenster.x - kartenfenster.x; y1 = zoomfenster.y - kartenfenster.y;
    x1 = x1 < 0 ? 0 : x1; y1 = y1 < 0 ? 0 : y1;
    x2 = x1 + zoomfenster.width; y2 = y1 + zoomfenster.height;
    x2 = x2 > (kartenfenster.width + kartenfenster.x) ? kartenfenster.width + kartenfenster.x : x2; y2 = y2 > (kartenfenster.height + kartenfenster.y) ? kartenfenster.height + kartenfenster.y : y2;
    window.document.mapserv.imgbox.value = x1 + " " + y1 + " " + x2 + " " + y2;
    window.document.mapserv.imgxy.value = "";
   }
   bildladen('ladestatus','visible');
   document.onmousemove = dummy;
   window.document.mapserv.submit();
  }
 }
}

function dummy()
{
 var pseudo =1;
}

function bildladen(ladung, zustand)
{
 var statuslayer = layerbez(ladung);
 if (ns4) statuslayer.visibility = zustand;
 else statuslayer.style.visibility = zustand;
}

//function aktion(mod,wert)
//{
// window.document.mapserv.mode.value = mod;
// window.document.mapserv.zoomdir.value = wert;
//}

function aktion(mod, wert)
{
         window.document.mapserv.mode.value = mod;
         window.document.mapserv.zoomdir.value = wert;
     if (document.mapserv.mode.value == "query") {
         document.mapserv.target = "_self";
//         this.document.reload;
        }
else if (document.mapserv.mode.value == "browse") {
         document.mapserv.target = "_self";
     }
}

function neuladen()
{
 bildladen('ladestatus','visible');
 window.document.mapserv.mode.value = "browse";
 window.document.mapserv.zoomdir.value = 1;
 window.document.mapserv.mapext.value = document.mapserv.imgext.value;
 window.document.mapserv.submit();
}

function startansicht()
{ 
 bildladen('ladestatus','visible');
 window.document.mapserv.mode.value = "browse";
 window.document.mapserv.zoomdir.value = 1;
 window.document.mapserv.imgbox.value = "";
 window.document.mapserv.imgxy.value = "";
 window.document.mapserv.mapext.value = "";
 window.document.mapserv.submit()
}

function move(fx, fy)
{
 if (ns4)    //???
 {
 bildladen('ladestatus','visible');
 s = document.mapserv.imgext.value;
//document.write(s);
 x1 = parseInt(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 y1 = parseInt(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 x2 = parseInt(s.substr(0, s.indexOf(" ")));
 y2 = parseInt(s.substr(s.indexOf(" ")+1));
 dx = (x2-x1)*fx;
 dy = (y2-y1)*fy;
 s = (" "+(x1+dx)+" "+(y1+dy)+" "+(x2+dx)+" "+(y2+dy)).substr(1);
// document.write(s);
 neujustieren(s);
 }
 else if (window.opera) // OK!!!
 {
 bildladen('ladestatus','visible');
 s = document.mapserv.imgext.value;
//document.write(s);
 x1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 y1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 x2 = parseFloat(s.substr(0, s.indexOf(" ")));
 y2 = parseFloat(s.substr(s.indexOf(" ")+1));
 dx = (x2-x1)*fx;
 dy = (y2-y1)*fy;
 s = (" "+(x1+dx)+" "+(y1+dy)+" "+(x2+dx)+" "+(y2+dy)).substr(1);
// document.write(s);
 neujustieren(s);
 }
 else if (ie4) // OK!!!
 {  
bildladen('ladestatus','visible');
 s = document.mapserv.imgext.value;
//document.write(s);
 x1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 y1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 x2 = parseFloat(s.substr(0, s.indexOf(" ")));
 y2 = parseFloat(s.substr(s.indexOf(" ")+1));
 dx = (x2-x1)*fx;
 dy = (y2-y1)*fy;
 s = (" "+(x1+dx)+" "+(y1+dy)+" "+(x2+dx)+" "+(y2+dy)).substr(1);
// document.write(s);
 neujustieren(s);
 }
 else
 {
 bildladen('ladestatus','visible');
 s = document.mapserv.imgext.value;
//document.write(s);
 x1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 y1 = parseFloat(s.substr(0, s.indexOf(" ")));
 s  = s.substr(s.indexOf(" ")+1);
 x2 = parseFloat(s.substr(0, s.indexOf(" ")));
 y2 = parseFloat(s.substr(s.indexOf(" ")+1));
 dx = (x2-x1)*fx;
 dy = (y2-y1)*fy;
 s = (" "+(x1+dx)+" "+(y1+dy)+" "+(x2+dx)+" "+(y2+dy)).substr(1);
// document.write(s);
 neujustieren(s);
 }
}

function neujustieren(bildext)
{
 window.document.mapserv.mapext.value = bildext;
 window.document.mapserv.mode.value = "browse";
 window.document.mapserv.imgbox.value = "";  
 window.document.mapserv.imgxy.value = "";
 window.document.mapserv.submit();
}

function MM_openBrWindow(theURL,winName,features) 
{ //v2.0
  window.open(theURL,winName,features);
}

function MM_reloadPage(init) {
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
