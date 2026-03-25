function identify(e) {
	getImageXY(e);
}

function makeQueryURL() {
	if(insideMap) {
	refreshMap('query');
	URLString = new String;	
	URLString = "http://www.ischiamappe.it/querymap.phtml?";
	URLString = URLString+'&minx='+document.mapserv.tminx.value;
	URLString = URLString+'&miny='+document.mapserv.tminy.value;
	URLString = URLString+'&maxx='+document.mapserv.tmaxx.value;
	URLString = URLString+'&maxy='+document.mapserv.tmaxy.value;	
	URLString = URLString+'&mainmap_x='+document.mapserv.mainmap_x.value;
	URLString = URLString+'&mainmap_y='+document.mapserv.mainmap_y.value;
	URLString = URLString+'&imagewidth='+document.mapserv.imagewidth.value;
	URLString = URLString+'&imageheight='+document.mapserv.imageheight.value;
	URLString = URLString+'&imgext='+document.mapserv.imgext.value;
	URLString = URLString+'&CMD=QUERY_POINT';
 
/*  URLString = "http://www.ischiamappe.it/querymap.phtml?mainmap_x=484&mainmap_y=160&CMD=QUERY_POINT&imgext=-10500+-500+-500+7900&minx=-3226.433959&miny=5023.468021&maxx=-2263.361959&maxy=5793.481131&imagewidth=500&imageheight=400"; 

URLString = "http://www.ischiamappe.it/querymap.phtml?imgxy=484+160&mainmap_x=484&mainmap_y=160&savequery=&mapext=&mode=browse&zoomdir=1&minx=&maxx=&miny=&maxy=&zoomsize=&imgbox=-1+-1+-1+-1&INPUT_TYPE=&INPUT_COORD=&CMD=QUERY_POINT&tool=zoomin&imgext=-10500+-500+-500+7900&minx=-3226.433959&miny=5023.468021&maxx=-2263.361959&maxy=5793.481131&imagewidth=500&imageheight=400&KEYMAPXSIZE=147&KEYMAPYSIZE=118&ViewRegion=Viste+predefinite&ppubbl=Y&distrib=Y"; */

	showQuery(URLString);

	}
}



function showQuery(URL) {
	var Parameters = "scrollbars=no,status=no,width=395,height=208";
	myFloater = window.open('','MapQuery',Parameters);
	myFloater.location.href = URLString;
	myFloater.focus();
//	parent.QueryFrame.location.href = URL;
}

