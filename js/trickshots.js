
var PO=0;
var PA=0;
var PATT=0;
var PT=0;
var MER=0;
var IBI2=0;
var IEL=0;
var IGM=0;
var IMT=0;
var IMX=0;

function findString(tstring,text) {
    // Replaces text with by in string
    var strLength = tstring.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)){
        return false;
    }
    var i = tstring.indexOf(text);
    if (i == -1) {
        return false;
    }
    else{
        return true;
    } 
}

function _doPrintOuter(people_listing, lang) {
  var img_dir="https://stisrv13.epfl.ch/profs/img/";
  var test="";
  
  var count=0;
  var result=0;
  for(var x=0; x < people_listing.length; x++) {
   if (people_listing[x]) {
   
    //(findString(people_listing[x].title,'PATT') 
    
	result++;
	test+="<div style='width:140px; height:285px; float:left; display: inline; clear: none; background-color:#eee; margin: 5px; padding:10px' class='' valign=top>";
	test+=people_listing[x].link + "<img width='118' border='0' src='";
	test+=img_dir+people_listing[x].image + "' title='" + people_listing[x].firstname + " " + people_listing[x].lastname;				
	test+="'/></a>\n\ <br>\n" + people_listing[x].link + people_listing[x].lastname + "<br>" + people_listing[x].firstname;				
	test+="</a>\n\ <div style='width: 110px; font-size:10px'>\n\ ";
	test+="<a href=" + people_listing[x].labwebsite + ">" + people_listing[x].mylabname + "</a></div></div>";
        count++;
      
   }
  }
  if (count==0) {
   test+= "<span style='width:82px' class=pandalink><a style='color:white' href=#>THERE ARE NO PROFESSORS IN THIS CATEGORY</a></span>"; 
  }
  document.getElementById('smackittome').innerHTML = test;
}

function resetDirectoryForm() {
    $(".sti_faculty_sort a").data("active", false);
    $(".sti_faculty_sort a#all").data("active", true);
    redraw();
    return false;  // For the onClick() handler to suppress the event
}

function redraw () {
    $(".sti_faculty_sort a").map(function (unused_index, e) {
        $(e).removeClass('blacklinkinverted');
        $(e).removeClass('blacklink');
        var activeClass = $(e).data("active") ? 'blacklinkinverted' : 'blacklink';
        $(e).addClass(activeClass);
    })

    function anchor2cgiparam (id) {
        return ($("#" + id).data("active") ? "1" : "0");
    }

    var PO = anchor2cgiparam("PO"),
        PA = anchor2cgiparam("PA"),
        PATT = anchor2cgiparam("PATT"),
        PT = anchor2cgiparam("PT"),
        MER = anchor2cgiparam("MER"),
        IBI2 = anchor2cgiparam("IBI2"),
        IEL = anchor2cgiparam("IEL"),
        IGM = anchor2cgiparam("IGM"),
        IMT = anchor2cgiparam("IMT"),
        IMX = anchor2cgiparam("IMX");
    var url = "https://stisrv13.epfl.ch/cgi-bin/whoop/faculty-and-teachers2.pl?PO="+PO+"&PA="+PA+"&PATT="+PATT+"&PT="+PT+"&MER="+MER+"&IBI2="+IBI2+"&IEL="+IEL+"&IGM="+IGM+"&IMT="+IMT+"&IMX="+IMX;
    console.log(url);
    $.ajax({
        url: url,
        dataType: "json",
}).done(function(people_listing) {
    _doPrintOuter(people_listing, "en");
});
}

function toggle (this_link) {
    $(".sti_faculty_sort a#all").data("active", false);
    var oldState = $(this_link).data("active");
    $(this_link).data("active", ! oldState);
    redraw();
    return false;  // For the onClick() handler to suppress the event
}

$(function() {
    $("#all").data("active", true);
    redraw();
});
