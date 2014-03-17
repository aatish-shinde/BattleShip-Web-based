var xhtmlns = "http://www.w3.org/1999/xhtml";
var svgns = "http://www.w3.org/2000/svg";
var BOARDX = 150;				//starting pos of board
var BOARDY = 50;				//look above
var boardArr = new Array();		//2d array [row][col]
var pieceArr = new Array();		//2d array [player][piece] (player is either 0 or 1)
var BOARDWIDTH = 10;				//how many squares across
var BOARDHEIGHT = 10;			//how many squares down


function init(){
	//create a parent to stick board in...
	var gEle=document.createElementNS(svgns,'g');
	gEle.setAttributeNS(null,'transform','translate('+BOARDX+','+BOARDY+')');
	gEle.setAttributeNS(null,'id','someIDsetByTheServer');
	//stick g on board
	document.getElementsByTagName('svg')[0].insertBefore(gEle,document.getElementsByTagName('svg')[0].childNodes[5]);
	//create the board...
	//var x = new Cell(document.getElementById('someIDsetByTheServer'),'cell_00',75,0,0);
	for(i=0;i<BOARDWIDTH;i++){
		boardArr[i]=new Array();
		for(j=0;j<BOARDHEIGHT;j++){
			boardArr[i][j]=new Cell(document.getElementById('someIDsetByTheServer'),'cell_'+j+i,45,j,i);
		}
	}

}
		