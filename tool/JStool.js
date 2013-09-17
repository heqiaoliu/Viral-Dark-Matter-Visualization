//------------------following is  the methods for JQuery Quick implement
//unique jQuery selector x, and the class want to toggle y.
//class y will be add if the target has it, otherwise removed.
function classTog(x,y){
  if($(x).hasClass(y))
    $(x).removeClass(y);
  else
    $(x).addClass(y);
}

function classTogTwo(varx,vary,classx,classy){
  if($(varx).hasClass(classx)){
    $(varx).removeClass(classx);
    $(vary).removeClass(classy);
  }
  else{
    $(varx).addClass(classx);
    $(vary).addClass(classy);
  }
}

$(function(){
function twoClassTog(varx,classOne,classTwo){
  if($(varx).hasClass(classOne)){
    if($(varx).hasClass(classTwo)){
    $(varx).removeClass(classOne);
    $(varx).removeClass(classTwo);}
    else
    $(varx).addClass(classTwo);
  }
  else
    $(varx).addClass(classOne);
    
}
});


$(function(){
function htmlChange(varx,message){
   $(varx).html(message);
}
});

function quickAppend(identifier, type, classSet, id, value){
	$(identifier).append("<"+type+" class=\""+classSet+"\" id=\""+id+"\">"+value+"</"+type+">");
}

function classUniAdd(varAll,target,className){
	$(varAll).removeClass(className);
	target.addClass(className);
}
