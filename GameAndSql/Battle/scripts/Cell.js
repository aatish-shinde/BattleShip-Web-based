
function Cell(parent, id, size, row, col, iswatercell) {
    this.parent = parent;
    this.id = id;
    this.size = size;
    this.row = row;
    this.col = col;
    this.iswatercell = iswatercell;

    //now initialize other instance vars
    this.occupied = '';
    this.bomb = '';
    this.x = this.col * this.size;
    this.y = this.row * this.size;
    this.color = (((this.row+this.col)%2) == 0)? 'white':'white'
	this.droppable = (((this.row+this.col)%2) == 0)? true:false

    this.object = this.create();
    this.parent.appendChild(this.object);
    this.myBBox = this.getMyBBox();

	this.object=null;
	
}

Cell.prototype.create = function() {
    var rect = document.createElementNS(svgns,'rect');
    rect.setAttributeNS(null, 'x', this.x + 'px');
    rect.setAttributeNS(null, 'y', this.y + 'px');
    rect.setAttributeNS(null, 'width', this.size + 'px');
    rect.setAttributeNS(null, 'height', this.size + 'px');
    rect.setAttributeNS(null, 'id', this.id);
    rect.setAttributeNS(null, 'class', 'cell_' + this.color);
    var row = this.row;
    var col = this.col;
    if(this.iswatercell) {
        rect.setAttributeNS(null, 'style', 'cursor:pointer');
        rect.onclick = function() {
			
			//alert(this.id);
            dropBombOnTheShip(row, col);
        };
        //rect.onmouseover
        //rect.onmouseout little thing telling them the row/col
    }
    return rect;
}

//get my Bbox
Cell.prototype.getMyBBox = function() {
   return this.object.getBBox();
}

//get my center x (abs)
Cell.prototype.getCenterX = function(board) {
    if(board == 'ships') {
        return (BOARDX + this.x + (this.size/2));
    } else if (board == 'guess') {
        return (BOARD2X + this.x + (this.size/2));
    } else {
        return (BOARDX + this.x + (this.size/2));
    }
}

//get my center y (abs)
Cell.prototype.getCenterY = function(board){
    return (BOARDY+this.y+(this.size/2));
}

Cell.prototype.getTopLeftX = function() {
    return (BOARDX + this.x);
}

Cell.prototype.getTopLeftY = function() {
    return (BOARDY + this.y);
}

//set cell to occupied
Cell.prototype.isOccupied = function(pieceID){
    this.occupied = pieceID;
}

//empty cell
Cell.prototype.notOccupied = function(){
    this.occupied = '';
}

Cell.prototype.givebomb = function(bombID) {
    this.bomb = bombID;
}