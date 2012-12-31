function getSeriesObj(){

}

//-------------------------------instruction to users
$(document).ready(function(){
  $("p.beid").click(function(){
    $("p#userGuid").html("Please click to choose plate type. ");
  });
  $("td.plate").click(function(){
    $("p#userGuid").html("Please click to choose replicate(s): click once(yellow)--to show as individual; click twice(blue)-- join into group to show average and STD. ");
  });

  $("td.replicate").click(function(){    
    $("p#userGuid").html("Please click well number to choose wells.");
  });

  $("td.sel").click(function(){
    $("p#userGuid").html("Once done with selecting wells, please click to generate the graph.");
  });
});


//----------------following is effect for view.php
$(function(){
  $("td.sel").mouseover(function(){
    var x=(this).id;
    if(x.length>1){
	getWell(x);
    }
  });
  $("#Tclones td").mouseleave(function(){
      htmlChange("p#infoBox","Information");
  });
});


$(function(){
  $("td.replicate.num").mouseover(function(){
    htmlChange("p#infoBox","Replicate"+(parseInt((this).title)+1)+" information: "+(this).getAttribute("expdate"));
  });
  $("td.replicate.num").mouseleave(function(){
    htmlChange("p#infoBox","Information");
  });
});

$(function() {
  $('.selectRow').click(function() {
    classTogTwo(".selectRow#"+(this).id,".sel."+(this).id,"Rselected","selected");
  });
});

$(function() {
  $('#Tclones td.sel').click(function() {
    var x = "td#"+this.id+".sel";
    classTog(x,"selected");
  });
});


$(function() {
  $('#clearButton').click(function() {
    clearVal = $(this).val();
    if (clearVal == "Clear") {
        $('#Tclones td.sel').removeClass('selected');
        $(this).val("Select All");
    } else {
        $('#Tclones td.sel').addClass('selected');
        $(this).val("Clear");
    }
  });
});



$(function (){
  $("td.replicate.num").click(function(){
    var item="td.replicate#"+(this).id;
    twoClassTog(item,"selected","grouped");
  });
});

function repInfo(x,y){
    $("td.replicate").hide();
    $("td.replicate#desb").html("Replicate for "+x+":");
    $("td.replicate#desb").show();
    $("td.replicate."+x+"."+y).show();
}




$(function(){
  $("p.beid").click(function(){
    if($(this).hasClass("focus")&&$(this).hasClass("selected")){
        $(this).removeClass("selected");
    }
    else{
        $("p.beid").removeClass("focus");
	$(this).addClass("selected");
	$(this).addClass("focus");
    }
  });
});

$(function (){
  $("td.plate.name").click(function(){
    var item="td.plate#"+(this).id;
    $("td.replicate").removeClass("focus");
    $(this).addClass("focus");
    classTog(item,"selected");
    repInfo((this).getAttribute("plate_id"),(this).getAttribute("bacteria_id"));
  });
});

$(function(){
function plateInfo(x){
    $("td.plate").hide();
    $("td.plate#desc").html("Plate for "+x+":");
    $("td.plate#desc").show();
    $("td.plate."+x).show();
}
});

$(function(){
  $("input#beidin").keyup(function(){
    var x=$(this).val();  
    if(x.length==4){
      $("p.beid").removeClass("focus");
      $("p.beid#"+x).addClass("focus");
      repInfo(x);
      $(this).val("");
    }
  });
});


$(function(){
  var scrollPos=0;
  $("input#beidin").keyup(function(){
    var x=$(this).val();  
    if(x.length==4){
      x="EDT"+x;
      $("p.beid").removeClass("focus");
      $("p.beid#"+x).addClass("focus");
      repInfo(x);
      $(this).val("");
      if(($("p.beid#"+x).position().top-$("p.beid:first").position().top)>225)
        $("div#cloneSelect").scrollTop(($("p.beid#"+x).position().top-$("p.beid:first").position().top)-225);
      else
	$("div#cloneSelect").scrollTop(0);
    }
  });
});

$(function(){
function minheight(number) {
    if (number > 20 ){
            var height = (600 + (number-20)*3).toString()+"px";
            $('div#container').css("min-height", height);
    }
}
});
