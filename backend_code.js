var game = function () {
  this.frames = {};
  this.players = players;
  this.loopCount;
  this.robots = [];
  this.missile = [{}, {}];
  this.fireBall = [{}, {}];
  this.arena;
  this.gameLength = 1000;
  this.loadedScripts = 0;
  this.winner = -1;
  this.init = function () {

    this.arena = new arena();
    this.arena.init();
    this.robots[0].init(0);
    this.robots[1].init(1);
	
    this.loopCount = 0;
    var i;
    for (i = 0; i < this.gameLength; i++) {
      if (this.gameLoop()) {
        break;
      }
    }
    this.frames.loopCount = this.loopCount;
    this.frames.winner = this.winner;
    this.saveGame();    
  }
  this.saveGame = function(){
	//  console.log(JSON.stringify(this.frames));
    sendObj = {
      "fightid":fight_id,
      "winner":this.winner,
      "savedata":JSON.stringify(this.frames)
    };
    //console.log(this.frames);
    console.log(sendObj);
    $.post("savegame.php",sendObj,function(data){});
  }
  
  
  
  this.gameLoop = function () {
    if (this.robots[0].health <= 0 && this.robots[1].health <= 0) {
      this.winner = 2;
      return 1;
    } else if (this.robots[0].health <= 0) {
      this.winner = 1;
      return 1;
    } else if (this.robots[1].health <= 0) {
      this.winner = 0;
      return 1;
    }

    if (checkCollisionPoly(this.robots[0].toCoods(), this.robots[1].toCoods())) {
      
      var robStates = [{}, {}];

      robStates[0] = this.robots[0].state();
      robStates[0].enemy = this.robots[1].enemystate();

      robStates[1] = this.robots[1].state();
      robStates[1].enemy = this.robots[0].enemystate();
      


        if(!isUndefined(this.robots[0].funcs.collision)){
          this.robots[0].clearCmds();
          try{
            this.robots[0].funcs.collision(robStates[0]);
          }
          catch(e){
            this.robots[0].errorcode(e,0);
          }
          
        }
        
        if(!isUndefined(this.robots[1].funcs.collision)){
          this.robots[1].clearCmds();
          try{
            this.robots[1].funcs.collision(robStates[1]);
          }
          catch(e){
            this.robots[1].errorcode(e,1);
          }
        }
    }
    
    
    var i = 0;

    for (i = 0; i < 2; i++) {

      var enemy = (i + 1) % 2;

      var robStates = this.robots[i].state();
      

      if (checkCollisionPoly(this.robots[enemy].toCoods(), this.robots[i].fieldOfView())) {
        //Viewed enemy
        try{
          if(!isUndefined(this.robots[i].funcs.inView)){
            this.robots[i].clearCmds();
            robStates.enemy = this.robots[enemy].enemystate();
            this.robots[i].funcs.inView(robStates);
          }
        }
        catch(e){
			
          this.robots[i].errorcode(e,i);
        }
        
      } else if (this.robots[i].wallCollision()) {

        try{
            
          if(!isUndefined(this.robots[i].funcs.wallHit)){
            this.robots[i].clearCmds();
            this.robots[i].funcs.wallHit(robStates);
          }
        }
        catch(e){
			
          this.robots[i].errorcode(e,i);
          
        }
      }
      
      var bArray = Object.keys(this.missile[enemy]);
      var it = 0;
      for(it = 0;it<bArray.length;it++){
        
        var emissile;
        emissile = bArray[it];
        
        if (checkCollisionPoly(this.missile[enemy][emissile].toCoods(), this.robots[i].toCoods())) {
          
          
          
          this.robots[i].gotHit(this.missile[enemy][emissile].damage);
          delete this.missile[enemy][emissile];
          robStates.health = this.robots[i].health;
          try{
            if(!isUndefined(this.robots[i].funcs.missileHit)){
              this.robots[i].clearCmds();
              this.robots[i].funcs.missileHit(robStates);
            }
          }
          catch(e){
          this.robots[i].errorcode(e,i);
            
          }
          break;
        }
      }
    
     var bArray = Object.keys(this.fireBall[enemy]);
      var it = 0;
      for(it = 0;it<bArray.length;it++){
        
        var efireBall;
        efireBall = bArray[it];
        
        if (checkCollisionPoly(this.fireBall[enemy][efireBall].toCoods(), this.robots[i].toCoods())) {
          
          
          
          this.robots[i].gotHit(this.fireBall[enemy][efireBall].damage);
          delete this.fireBall[enemy][efireBall];
          robStates.health = this.robots[i].health;
          try{
            if(!isUndefined(this.robots[i].funcs.fireBallHit)){
              this.robots[i].clearCmds();
              this.robots[i].funcs.fireBallHit(robStates);
            }
          }
          catch(e){
          this.robots[i].errorcode(e,i);
            
          }
          break;
        }
      }
      
      
      if (this.robots[i].countQ() == 0) {
        
        if(!isUndefined(this.robots[i].funcs.idle)){
          try{
            this.robots[i].clearCmds();
            this.robots[i].funcs.idle(robStates);
          }
          catch(e){
            this.robots[i].errorcode(e,i);
            
          }
        }
      }

      
    }
      
      

      
    

    for (i = 0; i < 2; i++) {
      this.robots[i].rotate();
      this.robots[i].rotatemissileGunExec();
      this.robots[i].move();
      this.firemissile(i);
      this.firefireBall(i);
      
        //TODO remove comments
        for (b in this.missile[i]) {
          
        if (!this.missile[i][b].move()) {
          delete this.missile[i][b];
        }
      }

        for (c in this.fireBall[i]) {
          
        if (!this.fireBall[i][c].move()) {
          delete this.fireBall[i][c];
        }
      }


    }
    this.frames[this.loopCount] = new gameState();
    this.frames[this.loopCount].init(this.robots, this.missile,this.fireBall);
    this.loopCount++;
    return 0;
  }
  this.firemissile = function (p) {
    if (this.robots[p].missileQ.count == 0) {
      return;
    }
    if (this.loopCount < this.robots[p].lastmissile + this.robots[p].reloadTime) {
      return;
    }
    this.robots[p].lastmissile = this.loopCount;
    this.robots[p].missileQ.deq();
    
    
    i = 0;
    while (true){
      if(isUndefined(this.missile[p][i])){
        break;
      }
      else{
        i++;
      }
    }
    
    
    
    this.missile[p][i] = new missile();
    this.missile[p][i].angle = this.robots[p].missileGunangle;
    
    
    var argh = this.missile[p][i].toCoods();
    
        
    this.missile[p][i].x = Math.round(this.robots[p].x + (argh[0].x + argh[3].x)/2);
    this.missile[p][i].y = Math.round(this.robots[p].y + (argh[0].y + argh[3].y)/2);
    
    
    this.missile[p][i].firedBy = p;
    
  }

//fireBall
this.firefireBall = function (p) {
    if (this.robots[p].fireBallQ.count == 0) {
      return;
    }
    if (this.loopCount < this.robots[p].lastfireBall + this.robots[p].reloadTime) {
      return;
    }
    this.robots[p].lastfireBall = this.loopCount;
    this.robots[p].fireBallQ.deq();
    
    
    i = 0;
    while (true){
      if(isUndefined(this.fireBall[p][i])){
        break;
      }
      else{
        i++;
      }
    }
    
    
    
    this.fireBall[p][i] = new fireBall();
    this.fireBall[p][i].angle = this.robots[p].missileGunangle;
    
    
    var argh = this.fireBall[p][i].toCoods();
    
        
    this.fireBall[p][i].x = Math.round(this.robots[p].x + (argh[0].x + argh[3].x)/2);
    this.fireBall[p][i].y = Math.round(this.robots[p].y + (argh[0].y + argh[3].y)/2);
    
    
    this.fireBall[p][i].firedBy = p;
    
  }




}


var gameState = function () {
  this.gs = {
    "spaceShip" : [{}, {}

    ],
    "missile" : [{}, {}

    ],
    "fireBall" : [{}, {}

    ]

  };
  this.init = function (r, b,f) {
    var i = 0;
    var j = 0;
    var k = 0;
    var x;
    for (i = 0; i < 2; i++) {
      this.gs.spaceShip[i].x = r[i].x;
      this.gs.spaceShip[i].y = r[i].y;
      this.gs.spaceShip[i].sA = r[i].spaceshipangle;
      this.gs.spaceShip[i].mgA = r[i].missileGunangle;
      this.gs.spaceShip[i].h = r[i].health;
      
      
      j = Object.keys(b[i]);
      
      for(k = 0;k<j.length;k++){
        this.gs.missile[i][j[k]] = {};
        this.gs.missile[i][j[k]].x = b[i][j[k]].x;
        this.gs.missile[i][j[k]].y = b[i][j[k]].y;
        this.gs.missile[i][j[k]].a = b[i][j[k]].angle;
      }

       j = Object.keys(f[i]);
       for(k = 0;k<j.length;k++){
        this.gs.fireBall[i][j[k]] = {};
        this.gs.fireBall[i][j[k]].x = f[i][j[k]].x;
        this.gs.fireBall[i][j[k]].y = f[i][j[k]].y;
        this.gs.fireBall[i][j[k]].a = f[i][j[k]].angle;
      }

        
      
    }
  }
  this.getStr = function () {
    return JSON.stringify(this.gs);
  }
};


var n_states = Object.freeze({
        "idle": 1,
        "collision": 2,
         "hit": 3,
          "seen": 4,
          "hitwall":5
});

var arena =function(){
        this.height= 300;
        this.width = 500;
        this.wall = [{
               x : 0,
               y : 0
        },
        {
               x : 0,
               y : this.height
        },
        {
                x : 0,
                y : this.width
        },
        {
                 x : this.width,
                 y : this.height
        }];
        	this.init = function () {}

}


var missile = function () {
  this.firedBy = -1;
  this.movespeed = 10;
  this.x = 0;
  this.y = 0;
  this.xlen = 10;
  this.ylen = 6;
  this.angle = 0;
  this.arenaWidth = 500;
  this.arenaHeight = 300;
  this.damage = 6;

  this.toCoods = function () {
    var a = [{x : 0,y : 0}, {x : 0,y : 0}, {  x : 0,y : 0 }, {x : 0,y : 0}];

    var x1,y1,x2,y2;

    x1 = this.x + this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[0].x = x2 * Math.cos(this.angle * Math.PI / 180) + y2 * Math.sin(this.angle * Math.PI / 180) + this.x;
    a[0].y = x2 * Math.sin(this.angle * Math.PI / 180) + y2 * Math.cos(this.angle * Math.PI / 180) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[1].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[1].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[2].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[2].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    x1 = this.x + this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[3].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[3].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    return a;

  }
  this.move = function () {
    
    
    
    this.x = Math.round(Math.cos(this.angle * Math.PI / 180) * this.movespeed + this.x);
    this.y = Math.round(Math.sin(this.angle * Math.PI / 180) * this.movespeed + this.y);
    
    
    
    
    if (this.x > (this.arenaWidth + this.xlen) || this.x < -this.xlen || this.y < -this.ylen || this.y > (this.arenaHeight + this.ylen)) {
      return 0;
    }

    return 1;
  }
}



var fireBall = function () {
  this.firedBy = -1;
  this.movespeed = 10;
  this.x = 0;
  this.y = 0;
  this.xlen = 10;
  this.ylen = 6;
  this.angle = 0;
  this.arenaWidth = 500;
  this.arenaHeight = 300;
  this.damage = 6;

  this.toCoods = function () {
    var a = [{x : 0,y : 0}, {x : 0,y : 0}, {  x : 0,y : 0 }, {x : 0,y : 0}];

    var x1,y1,x2,y2;

    x1 = this.x + this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[0].x = x2 * Math.cos(this.angle * Math.PI / 180) + y2 * Math.sin(this.angle * Math.PI / 180) + this.x;
    a[0].y = x2 * Math.sin(this.angle * Math.PI / 180) + y2 * Math.cos(this.angle * Math.PI / 180) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[1].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[1].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[2].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[2].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    x1 = this.x + this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[3].x = x2 * Math.cos(this.angle) + y2 * Math.sin(this.angle) + this.x;
    a[3].y = x2 * Math.sin(this.angle) + y2 * Math.cos(this.angle) + this.y;

    return a;

  }
  this.move = function () {
    
    
    
    this.x = Math.round(Math.cos(this.angle * Math.PI / 180) * this.movespeed + this.x);
    this.y = Math.round(Math.sin(this.angle * Math.PI / 180) * this.movespeed + this.y);
    
    
    
    
    if (this.x > (this.arenaWidth + this.xlen) || this.x < -this.xlen || this.y < -this.ylen || this.y > (this.arenaHeight + this.ylen)) {
      return 0;
    }

    return 1;
  }
}

var queue = function(){
   this.front = -1;
   this.rear = -1;
   this.count = 0;
   this.q =[];
   
   this.enq = function(cmd){
	   this.enqueue(cmd);
   }
   
   this.deq = function(cmd){
	   this.dequeue(cmd);
   }
   
    this.enqueue= function(cmd){
        if(this.front==-1){
          this.front=this.rear=0;
        }
          
        else{
          this.rear=this.rear+1;

        }
        this.count=this.count+1;
         this.q[this.rear]=cmd;

    };
    this.dequeue =function(cmd){
         if(this.count==0){
          return;
         }
         this.front=this.front+1;
         this.count =this.count-1;
         if(this.count==0){
          this.front=this.rear=-1;
         }

    };

    this.get =function () {
      if(this.count==0){
        return 0;
      }
      return this.q[this.front];
    };

    this.set =function(cmd){
        if(this.count==0){
          return 0;
        }
        this.q[this.rear]=cmd;
        return 1;
    };

    this.clear =function(){
          this.count=0;
          this.front=this.rear=-1;
          q=[];
    };
}

var robot =function(){
  this.xlen =50;
  this.ylen =20;
  this.x = 0;
  this.y =0;
  this.spaceshipangle =0;
  //this.fireBall  =0;
  //this.missile =0;
  this.missileGunangle =0;
  this.health =0;
  this.moveQ = new queue();
  this.missileQ = new queue();
  this.fireBallQ =new queue();
  this.rotateQ = new queue();
  this.rotatemissileGunQ = new queue();
  
   this.movespeed = 5;
   this.rotateSpeed = 5;
   this.fireBallspeed = 7;
   this.missilespeed = 7;
   this.state = n_states.idle;
    this.reloadTime = 5;
  this.arenaWidth = 500;
  this.arenaHeight = 300;
  this.stateFlag;
  this.funcs = {};
   this.missileGunxlen = 25;
  this.missileGunylen = 10;

	this.clearCmds = function () {
		this.clrcmd();
	}

  this.errorcode = function(e,i){
        
    sendObj = {
      "fightid":fight_id,
      "winner":-9,
      "savedata":player_ids[i],
      "player_id":player_ids[i],
      "e_msg":e.message
      
    };
	console.log(sendObj);
    $.post("savegame.php",sendObj,function(data){});
    g.loopCount = 9999;
    
    
  }
  
   this.state =function(){
		var obj = {};
        obj.x= this.x;
        obj.y =this.y;
        obj.spaceshipangle =this.spaceshipangle;
        obj.missileGunangle =this.missileGunangle;
        obj.health =this.health;

        obj.firefireBall =this.firefireBall;
        obj.firemissile = this.firemissile;
        obj.forward = this.forward;
        obj.backward = this.backward;
        obj.rotatespaceShip = this.rotatespaceShip;
		obj.rotatemissileGun = this.rotatemissileGun;
		
		
        obj.moveQ = this.moveQ;
        obj.missileQ = this.missileQ;
        obj.fireBallQ =this.fireBallQ;
        obj.rotateQ =this.rotateQ;
        obj.rotatespaceShipQ =  this.rotatespaceShipQ;
        obj.rotatemissileGunQ = this.rotatemissileGunQ;
        return obj;


   }

    this.enemystate =function(){
       var obj = {};
       obj.x = this.x;
       obj.y = this.y;
       obj. spaceshipangle = this.spaceshipangle;
       obj. missileGunangle = this.missileGunangle;
       obj.health = this.health;
       return obj;

   }

   this.gotHit =function(damage){
	   this.health =this.health -damage;

   }

   this.clrcmd =function(){
     this.moveQ.clear();
     this.missileQ.clear();
     this.fireBallQ.clear();    //change
    // this.rotateQ.clear();
     this.rotatemissileGunQ.clear();
     
   }

  this.fieldOfView = function () {
    var a = [{  x : 0,y : 0}, {x : 0,y : 0}, {x : 0,y : 0}, {x : 0,y : 0}];
    var x1,   y1,   x2,   y2;
    
    
    x1 = this.x;
    y1 = this.y;
    
    var xd,yd,hypo;
    
    xd = Math.round(Math.cos(this.missileGunangle * Math.PI / 180) * 1000  + x1);
    yd = Math.round(Math.sin(this.missileGunangle * Math.PI / 180) * 1000 + y1);
    
    
    
    a[0].x = Math.round(Math.cos(Math.PI / 2) * 3  + x1);
    a[0].y = Math.round(Math.sin(Math.PI / 2) * 3  + y1);
    
    
    a[3].x = Math.round(Math.cos(Math.PI / 2) * 3  + xd);
    a[3].y = Math.round(Math.sin(Math.PI / 2) * 3  + yd);
    
    
    a[2].x = Math.round(Math.cos(3 * Math.PI / 2) * 3  + x1);
    a[2].y = Math.round(Math.sin(3 * Math.PI / 2) * 3  + y1);
    
    
    a[1].x = Math.round(Math.cos(3 * Math.PI / 2) * 3  + x1);
    a[1].y = Math.round(Math.sin(3 * Math.PI / 2) * 3  + y1);

    
    return a;
  }
this.toCoods = function () {
  var a = [{x : 0,y : 0 }, {x : 0,y : 0}, {x : 0, y : 0}, {x : 0,y : 0  }];

    var x1,
    y1,
    x2,
    y2;

    x1 = this.x + this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[0].x = x2 * Math.cos(this.spaceshipangle * Math.PI / 180) + y2 * Math.sin(this.spaceshipangle * Math.PI / 180) + this.x;
    a[0].y = x2 * Math.sin(this.spaceshipangle * Math.PI / 180) + y2 * Math.cos(this.spaceshipangle * Math.PI / 180) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y + this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[1].x = x2 * Math.cos(this.spaceshipangle * Math.PI / 180) + y2 * Math.sin(this.spaceshipangle * Math.PI / 180) + this.x;
    a[1].y = x2 * Math.sin(this.spaceshipangle * Math.PI / 180) + y2 * Math.cos(this.spaceshipangle * Math.PI / 180) + this.y;

    x1 = this.x - this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[2].x = x2 * Math.cos(this.spaceshipangle * Math.PI / 180) + y2 * Math.sin(this.spaceshipangle * Math.PI / 180) + this.x;
    a[2].y = x2 * Math.sin(this.spaceshipangle * Math.PI / 180) + y2 * Math.cos(this.spaceshipangle * Math.PI / 180) + this.y;

    x1 = this.x + this.xlen / 2;
    y1 = this.y - this.ylen / 2;
    x2 = x1 - this.x;
    y2 = y1 - this.y;
    a[3].x = x2 * Math.cos(this.spaceshipangle * Math.PI / 180) + y2 * Math.sin(this.spaceshipangle * Math.PI / 180) + this.x;
    a[3].y = x2 * Math.sin(this.spaceshipangle * Math.PI / 180) + y2 * Math.cos(this.spaceshipangle * Math.PI / 180) + this.y;
    return a;
}

this.countQ = function () {
    return this.moveQ.count + this.rotateQ.count + this.rotatemissileGunQ.count + this.missileQ.count+this.fireBallQ.count;
  }

this.wallCollision = function () {
    //Concern:Verify
    var sangleMod = (this.spaceshipangle + 45) % 90;

    if (sangleMod > 45) {
      sangleMod = 90 - sangleMod;
    }

    var effXLen = Math.cos((Math.PI * sangleMod) / 180) * Math.sqrt(2) * (this.xlen / 2);
    var effYLen = Math.cos((Math.PI * sangleMod) / 180) * Math.sqrt(2) * (this.ylen / 2);

    if (this.arenaWidth - effXLen <= this.x) {
      this.x = this.arenaWidth - effXLen;
      this.wc = 1;
    } else if (this.x <= effXLen) {
      this.x = effXLen;
      this.wc = 1;
    } else {
      this.wc = 0;
    }

    if (this.arenaHeight - effYLen <= this.y) {
      //Wall collision on Y Axis
      this.y = this.arenaHeight - effYLen;
      this.wc = 1;
    } else if (this.y <= effYLen) {
      this.y = effYLen;
      this.wc = 1;
    } else {
      this.wc = 0;
    }

    return !!this.wc;
  }

this.move = function () {
    var moveCmd = this.moveQ.get();
    var directionFlag = 1;
    if (moveCmd.cmd == "backward") {
      directionFlag = -1;
    }

    if (moveCmd != 0) {
      var moveQuantity = this.movespeed;
      if (moveQuantity > moveCmd.steps) {
        moveQuantity = moveCmd.steps;
      }
      this.x = Math.round(Math.cos(this.spaceshipangle * Math.PI / 180) * moveQuantity * directionFlag + this.x);
      this.y = Math.round(Math.sin(this.spaceshipangle * Math.PI / 180) * moveQuantity * directionFlag + this.y);

      this.wallCollision();

      moveCmd.steps -= moveQuantity;
      if (moveCmd.steps == 0) {
        this.moveQ.deq();
      } else {
        this.moveQ.set(moveCmd);
      }

    }
  }
  this.rotate = function () {
    var rotatespaceShipCmd = this.rotateQ.get();	
      if (rotatespaceShipCmd.rotDeg == 0) {
        this.rotateQ.deq();
		return;
      } else {
        this.rotateQ.set(rotatespaceShipCmd);
      }	
    if (rotatespaceShipCmd != 0) {
		
      var rotatespaceShipQuantity = this.rotateSpeed;
	  
      if (rotatespaceShipQuantity > Math.abs(rotatespaceShipCmd.rotDeg)) {
        rotatespaceShipQuantity = Math.abs(rotatespaceShipCmd.rotDeg);
      }
	  
	  this.spaceshipangle = this.spaceshipangle % 360;

      this.spaceshipangle += rotatespaceShipQuantity * (Math.abs(rotatespaceShipCmd.rotDeg) / rotatespaceShipCmd.rotDeg);

	  this.spaceshipangle = this.spaceshipangle % 360;
	  
      rotatespaceShipCmd.rotDeg -= rotatespaceShipQuantity * (Math.abs(rotatespaceShipCmd.rotDeg) / rotatespaceShipCmd.rotDeg);
      if (rotatespaceShipCmd.rotDeg == 0) {
        this.rotateQ.deq();
      } else {
        this.rotateQ.set(rotatespaceShipCmd);
      }
    }
  }
  this.rotatemissileGunExec = function () {
    var rotatemissileGunCmd = this.rotatemissileGunQ.get();
	
	
      if (rotatemissileGunCmd.rotDeg == 0) {
        this.rotatemissileGunQ.deq();
		return;
      } else {
        this.rotatemissileGunQ.set(rotatemissileGunCmd);
      }
	
	
    if (rotatemissileGunCmd != 0) {
      var rotatemissileGunQuantity = this.rotateSpeed;
      if (rotatemissileGunQuantity > Math.abs(rotatemissileGunCmd.rotDeg)) {
        rotatemissileGunQuantity = Math.abs(rotatemissileGunCmd.rotDeg);
      }

	  this.missileGunangle = this.missileGunangle % 360;
	  
      this.missileGunangle += rotatemissileGunQuantity * (Math.abs(rotatemissileGunCmd.rotDeg) / rotatemissileGunCmd.rotDeg);

	  this.missileGunangle = this.missileGunangle % 360;
	  
      rotatemissileGunCmd.rotDeg -= rotatemissileGunQuantity * (Math.abs(rotatemissileGunCmd.rotDeg) / rotatemissileGunCmd.rotDeg);
      if (rotatemissileGunCmd.rotDeg == 0) {
        this.rotatemissileGunQ.deq();
      } else {
        this.rotatemissileGunQ.set(rotatemissileGunCmd);
      }
    }
  }
  this.coodsToXY = function (a) {
    x = (a[0].x + a[2].x) / 2;
    y = (a[0].y + a[2].y) / 2;
    return {
      "x" : x,
      "y" : y
    };
  }
  this.init = function (index) {
    this.health = 100;
    if (index == 0) {
      this.x = 50;
      this.y = 100;
      this.spaceshipangle = 0;
      this.missileGunangle = 0;
    } else {
      this.x = 450;
      this.y = 100;
      this.spaceshipangle = 180;
      this.missileGunangle = 180;
    }
    this.stateFlag = 0;
	
  }
  this.firemissile = function () {
    this.missileQ.enq({"Fire" : 1});
  }
  this.firefireBall = function () {
    this.fireBallQ.enq({"Fire" : 1});
  }
  this.forward = function (steps) {
    if (steps == 0) {
      return;
    }
    if (steps < 0) {
      this.moveQ.enq({
        "cmd" : "backward",
        "steps" : -steps
      });
    } else {
      this.moveQ.enq({
        "cmd" : "forward",
        "steps" : steps
      });
    }
  }
  this.backward = function (steps) {
    if (steps == 0) {
      return;
    }
    if (steps < 0) {
      this.moveQ.enq({
        "cmd" : "forward",
        "steps" : -steps
      });
    } else {
      this.moveQ.enq({
        "cmd" : "backward",
        "steps" : steps
      });

    }
  }
  this.rotatemissileGun = function (rotDeg) {
    if (rotDeg == 0) {
      return;
    }
    this.rotatemissileGunQ.enq({
      "rotDeg" : rotDeg
    });
  }
  this.rotatespaceShip = function (rotDeg) {
    if (rotDeg == 0) {
      return
    }
	
	if(this.rotateQ == undefined){
	}
    this.rotateQ.enq({
      "rotDeg" : rotDeg
    });
  }




}

var checkCollisionPoly = function (a, b) {
  
  var polygons = [a, b];
  var minA,
  maxA,
  projected,
  i,
  i1,
  j,
  minB,
  maxB;

  for (i = 0; i < polygons.length; i++) {

    // for each polygon, look at each edge of the polygon, and determine if it separates
    // the two shapes
    var polygon = polygons[i];
    for (i1 = 0; i1 < polygon.length; i1++) {

      // grab 2 vertices to create an edge
      var i2 = (i1 + 1) % polygon.length;
      var p1 = polygon[i1];
      var p2 = polygon[i2];

      // find the line perpendicular to this edge
      var normal = {
        x : p2.y - p1.y,
        y : p1.x - p2.x
      };

      minA = maxA = undefined;
      // for each vertex in the first shape, project it onto the line perpendicular to the edge
      // and keep track of the min and max of these values
      for (j = 0; j < a.length; j++) {
        projected = normal.x * a[j].x + normal.y * a[j].y;
        if (isUndefined(minA) || projected < minA) {
          minA = projected;
        }
        if (isUndefined(maxA) || projected > maxA) {
          maxA = projected;
        }
      }

      // for each vertex in the second shape, project it onto the line perpendicular to the edge
      // and keep track of the min and max of these values
      minB = maxB = undefined;
      for (j = 0; j < b.length; j++) {
        projected = normal.x * b[j].x + normal.y * b[j].y;
        if (isUndefined(minB) || projected < minB) {
          minB = projected;
        }
        if (isUndefined(maxB) || projected > maxB) {
          maxB = projected;
        }
      }

      // if there is no overlap between the projects, the edge we are looking at separates the two
      // polygons, and we know there is no overlap
      if (maxA < minB || maxB < minA) {
        //console.log("polygons don't intersect!");
        return false;
      }
    }
  }
  return true;

}

var isUndefined = function (obj) {
  return (typeof obj === "undefined");
}

var loadScript = function (url, callback) {
  // Adding the script tag to the head as suggested before
  var head = document.getElementsByTagName('head')[0];
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = url;
  // Then bind the event to the callback function.
  // There are several events for cross browser compatibility.
  script.onreadystatechange = callback;
  script.onload = callback;
  // Fire the loading
  head.appendChild(script);
}

var p1exists,p2exists;
var gettingScripts0 = function () {
  pl = 0;
  g.loadedScripts++;
  
  p1exists = 1;
  if(isUndefined(eval(g.players[pl]))){
    p1exists = 0;
  }else{
    g.robots[pl].funcs = new(eval(g.players[pl]))();
  }
  
  if (g.loadedScripts == 2) {
    if(p1exists == 0 || p2exists == 0){
      
      sendObj = {
        "fightid":fight_id,
        "winner":-10,
        "savedata":"REJECTED"
      };
	 console.log(sendObj);
      $.post("savegame.php",sendObj,function(data){});
    }
    else{
      g.init();
    }
  }
}
var gettingScripts1 = function () {
  pl = 1;
  g.loadedScripts++;
  
  p2exists = 1;
  if(isUndefined(eval(g.players[pl]))){
    p2exists = 0;
  }else{
    g.robots[pl].funcs = new(eval(g.players[pl]))();
  }
  
  if (g.loadedScripts == 2) {
    
    if(p1exists == 0 || p2exists == 0){
      
      sendObj = {
        "fightid":fight_id,
        "winner":-10,
        "savedata":"REJECTED"
      };
	  
	  //console.log(sendObj);
      $.post("savegame.php",sendObj,function(data){});
    }
    else{
      g.init();
    }
  }
}


var g = new game();
g.robots[0] = new robot();
g.robots[1] = new robot();
loadScript("getjs.php?spaceShipname=" + players[0] + "&player_id=" + player_ids[0], gettingScripts0);
loadScript("getjs.php?spaceShipname=" + players[1] + "&player_id=" + player_ids[1], gettingScripts1);









