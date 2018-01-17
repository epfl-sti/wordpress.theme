//var viewportwidth;
 
//if (typeof window.innerWidth != 'undefined') {
 //viewportwidth = window.innerWidth;
//}
 
//else if (typeof document.documentElement != 'undefined'
 //    && typeof document.documentElement.clientWidth !=
  //   'undefined' && document.documentElement.clientWidth != 0) {
 //viewportwidth = document.documentElement.clientWidth;
//}
 
//else {
// viewportwidth = document.getElementsByTagName('body')[0].clientWidth;
//}

//var viewportwidth2 = parseFloat(viewportwidth);
//var numperrow = 6;
//if (viewportwidth2 < 1050) { numperrow=4; }

var numperrow = 5;

function set_personal_info(id, lastname, firstname, institute, title, lab, labsite, phone, office, image, link) {
 this.id = id;
 this.lastname = lastname;
 this.firstname = firstname;
 this.institute = institute;
 this.title = title;
 this.lab = lab;
 this.labsite = labsite;
 this.phone = phone;
 this.office = office;
 this.image = image;
 this.link = link;
}

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

function _doPrintOuter(people_listing, which, lang, level) {
  var img_dir="https://stisrv13.epfl.ch/profs/img/";
  var test="";
  
  var count=0;
  var result=0;
  for(var x=0; x < people_listing.length; x++) {
   if (people_listing[x]) {
   
    //(findString(people_listing[x].title,'PATT') 
    if (((findString(people_listing[x].institute,which))||(which=='all'))||(people_listing[x].title==level)) {
    
     if (people_listing[x].institute!='IBI1') {
	result++;
	test+="\
            <div style='width:118px; height:190px; float:left; display: inline; clear: none;' class='pandalink' valign=top>\n\
";
	 test+=people_listing[x].link + "<img width='78' height='100' border='0' src='";
				
test+=img_dir+people_listing[x].image + "' title='" + people_listing[x].firstname + " " + people_listing[x].lastname;				
		  test+="'/></a>\n\ <br>\n" + people_listing[x].link + people_listing[x].lastname + "<br>" + people_listing[x].firstname;				
		  test+="</a>\n\ <div style='width: 78px' align=right>\n\ ";
		  test+="<a class='pandalink' href='\n\
				";
test+= people_listing[x].labsite + "'>" + people_listing[x].lab + "</a></div><br/>";
			test+="\
			<br><br></div>\n\
";
  count++;
      
     }
    }
   }
  }
  if (count==0) {
   test+= "<span style='width:82px' class=pandalink><a style='color:white' href=#>THERE ARE NO PROFESSORS IN THIS CATEGORY</a></span>"; 
  }
  document.getElementById('smackittome').innerHTML = test;
}

function printOuter(which, lang, level) {
    $.ajax({
        url: "https://stisrv13.epfl.ch/cgi-bin/whoop/faculty-and-teachers.pl",
        dataType: "json",
}).done(function(people_listing) {
    _doPrintOuter(people_listing, which, lang, level);
});
}
