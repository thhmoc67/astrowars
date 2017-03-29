var xyz = function(){
	this.collision = function(robot){
		robot.rotatemissileGun(360);
	}
	this.inView = function(robot){
		robot.firefireball();
		robot.firemissile();
		robot.firemissile();
	}
	this.missileHit = function(robot){
		robot.forward(200);
		robot.backward(200);
	}
	this.wallHit = function(robot){
		robot.rotatespaceShip(180);
		robot.forward(100);
	}
	this.idle = function(robot){
		robot.rotatespaceShip(360);
		robot.forward(200);
	}
}