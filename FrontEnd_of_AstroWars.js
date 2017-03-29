/**
 * Created by DeMoc on 17-12-2016.
 */
var loop;
var obj;
var loopTimer;
var spaceShipDim={"x_length":40, "y_length":40};
var missileGunDim={"x_length":25,"y_length":10};
var missileDim={"x_length":5,"y_length":7};
var fireBallDim={"x_length":6,"y_length":7};
var arenaDim={"x_length":500,"y_length":300};
var shield={"x_length":5,"y_length":5};
var missile=[{},{}];
var fireBall=[{},{}];
var missilesDiv;
var fireBallDiv;

//*****************************************************************************
var finishGame= function(){
  if(obj.winner==0){
    alert("Player 1 Won!");
  }
  else if(obj.winner==1){
    alert("Player 2 Won!");
  }
  else {
    alert("Draw!")
  }
}
//*****************************************************************************
var showGame=function (gameData) {
  var frameTime=100;
  obj=JSON.parse(gameData);
  delete gameData;
  loop=0;
  missilesDiv=document.getElementById("missile");
  fireBallDiv=document.getElementById("fireBall");
  shield=document.getElementById("shield");
  loopTimer=setInterval(renderFrame,frameTime);
}
//*****************************************************************************
var renderFrame=function () {
   if(loop >= obj.loopCount){
     clearInterval(loopTimer);
     finishGame();
   }
   renderSpaceShip(obj[loop].gs.spaceShip[0],"spaceShip1");
   renderSpaceShip(obj[loop].gs.spaceShip[1],"spaceShip2");
   var i = 0;
   for(i=0;i<2;i++){
     var mArray = Object.keys(missile[i]);
     var x;
     for(x=0;x<mArray.length;x++){
       if (isUndefined(obj[loop].gs.missile[i][mArray[x]])){
         delete missile[i][mArray[x]];
         var temp = document.getElementById("missile_"+ i + "_" + mArray[x]);
         if(temp != null){
           temp.outerHTML="";
         }
       }
     }
       mArray = Object.keys(obj[loop].gs.missile[i]);
       for(x=0;x<mArray.length;x++){
           var temp = document.getElementById("missile_" + i +"_"+ mArray[x]);
           if (temp ===null){
               var newM = newMissile(i,mArray[x]);
               renderMissile(newM,obj[loop].gs.missile[i][mArray[x]]);
               missilesDiv.appendChild(newM);
               missile[i][mArray[x]] = newM;

           }
           else{
               renderMissile(temp,obj[loop].gs.missile[i][mArray[x]]);
           }

       }
       renderHealth(obj[loop].gs.spaceShip[i].h,i);
   }
   loop++;
}
//*****************************************************************************
var renderHealth = function(health,player){
    if (health<0){
        health=0;

    }
    $("#player_"+ player + "_health").css("width",health+"px");
}
//*****************************************************************************
var renderSpaceShip = function(spaceShipObj,spaceShipEle){
    var x = spaceShipObj.x;
    var y = arenaDim.y_length - spaceShipObj.y;
    var sA = 360 -spaceShipObj.sA;
    var mgA = 360 - spaceShipObj.mgA;
    x= x-(spaceShipDim.x_length)/2;
    y= y-(spaceShipDim.y_length)/2;

    $($("#" + spaceShipEle + " > .spaceShipBody")[0]).css("left",x);
    $($("#" + spaceShipEle + " > .spaceShipBody")[0]).css("top",y);
    $($("#" + spaceShipEle + " > .spaceShipBody")[0]).css("transform","rotate(" + sA + "deg)");

    var x = spaceShipObj.x;
    var y = arenaDim.y_length-spaceShipObj.y;
    y=y- ( missileGunDim.y_length)/2;

    $($("#" + spaceShipEle + " > .missileGun")[0]).css("left",x);
    $($("#" + spaceShipEle + " > .missileGun")[0]).css("top",y);
    $($("#" + spaceShipEle + " > .missileGun")[0]).css("transform","rotate(" + mgA + "deg)");



}
//*****************************************************************************

var renderMissile = function(b,renMissile){
  
  var x = renMissile.x;
  var y = arenaDim.y_length - renMissile.y;
  var a = 360 - renMissile.a;
  x = x - (missileDim.x_length)/2;
  y = y - (missileDim.y_length)/2;
  
  $(b).css("left",x);
  $(b).css("top",y);
  $(b).css("transform","rotate(" + a + "deg)");
  
  
}


//***********************************************************************************
var renderfireBall = function(b,renfireBall){
  
  var x = renfireBall.x;
  var y = arenaDim.y_length - renfireBall.y;
  var a = 360 - renfireBall.a;
  x = x - (fireBallDim.x_length)/2;
  y = y - (fireBallDim.y_length)/2;
  
  $(b).css("left",x);
  $(b).css("top",y);
  $(b).css("transform","rotate(" + a + "deg)");
  
  
}




var createShield =function(){
     var s=document.createElement("div");
}
//*****************************************************************************
var createMissile=function () {
  var m=document.createElement("div");
}

var createfireBall=function () {
  var f=document.createElement("div");
}
//*****************************************************************************
var newshield = function(pId,mId){
  var s= document.createElement("div");
  s.className="shield";
  s.id="shield_"+pId+"_"+mId;
  return s;
}
//*****************************************************************************
var newMissile=function (pId,mId) {
  var m= document.createElement("div");
  m.className="missile";
  m.id="missile_"+pId+"_"+mId;
  return m;
}


var newfireBall=function (pId,mId) {
  var f= document.createElement("div");
  f.className="fireball";
  f.id="fireball_"+pId+"_"+mId;
  return f;
}
//*****************************************************************************
var getGameRecord=function () {
  $.ajax({
    url:"getgamedata.php?fight_id="+this_fight_id,
    gameData:{},
    sync:false
  }).done(function (gameData) {
    showGame(gameData);
  });
}
//*****************************************************************************
var isUndefined=function (obj) {
  return (typeof obj==="undefined");
}
//*****************************************************************************
getGameRecord();
/*..................Render Functions..................*/
