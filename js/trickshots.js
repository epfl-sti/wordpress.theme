
var PO=0;
var PA=0;
var PATT=0;
var PT=0;
var MER=0;
var IBI2=0;
var IEL=0;
var IMX=0;
var IMT=0;
var IMX=0;

function set_personal_info(id, lastname, firstname, institute, title, mylabname, labname, labwebsite, phone, office, image, link, keywords) {
 this.id = id;
 this.lastname = lastname;
 this.firstname = firstname;
 this.institute = institute;
 this.title = title;
 this.mylabname = mylabname;
 this.labname = labname;
 this.labwebsite = labwebsite;
 this.phone = phone;
 this.office = office;
 this.image = image;
 this.link = link;
 this.keywords= keywords;

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
	test+="<div style='width:140px; height:285px; float:left; display: inline; clear: none; background-color:#eee; margin: 5px; padding:10px' class='' valign=top>";
	test+=people_listing[x].link + "<img width='118' border='0' src='";
	test+=img_dir+people_listing[x].image + "' title='" + people_listing[x].firstname + " " + people_listing[x].lastname;				
	test+="'/></a>\n\ <br>\n" + people_listing[x].link + people_listing[x].lastname + "<br>" + people_listing[x].firstname;				
	test+="</a>\n\ <div style='width: 110px; font-size:10px'>\n\ ";
	test+="<a href=" + people_listing[x].labwebsite + ">" + people_listing[x].mylabname + "</a></div></div>";
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
 var target=window['level'];
 if (target==0) {
  document.getElementById(level).classList.remove('blacklinkinverted');
  document.getElementById(level).classList.add('blacklink');
  window['level']=1;
 }
 else {
  document.getElementById(level).classList.remove('blacklink');
  document.getElementById(level).classList.add('blacklinkinverted');
  window['level']=0;
 }

 $.ajax({
        url: "https://stisrv13.epfl.ch/cgi-bin/whoop/faculty-and-teachers.pl",
        dataType: "json",
}).done(function(people_listing) {
    _doPrintOuter(people_listing, which, lang, level);
});

}

/*(function kickit(incoming) {  
 if (level == 'PO') {
  if (PO==0) {
   document.getElementById("PO").classList.remove('blacklinkinverted');
   document.getElementById("PO").classList.add('blacklink');
   PO=1;
  }
  else {
   document.getElementById("PO").classList.remove('blacklink');
   document.getElementById("PO").classList.add('blacklinkinverted');
   PO=0;
  }
 }
 if (level == 'PA') {
  if (PA==0) {
   document.getElementById("PA").classList.remove('blacklinkinverted');
   document.getElementById("PA").classList.add('blacklink');
   PA=1;
  }
  else {
   document.getElementById("PA").classList.remove('blacklink');
   document.getElementById("PA").classList.add('blacklinkinverted');
   PA=0;
  }
 }
 if (level == 'PATT') {
  if (PATT==0) {
   document.getElementById("PATT").classList.remove('blacklinkinverted');
   document.getElementById("PATT").classList.add('blacklink');
   PATT=1;
  }
  else {
   document.getElementById("PATT").classList.remove('blacklink');
   document.getElementById("PATT").classList.add('blacklinkinverted');
   PATT=0;
  }
 }
 if (level == 'PT') {
  if (PT==0) {
   document.getElementById("PT").classList.remove('blacklinkinverted');
   document.getElementById("PT").classList.add('blacklink');
   PT=1;
  }
  else {
   document.getElementById("PT").classList.remove('blacklink');
   document.getElementById("PT").classList.add('blacklinkinverted');
   PT=0;
  }
 }
 if (level == 'MER') {
  if (MER==0) {
   document.getElementById("MER").classList.remove('blacklinkinverted');
   document.getElementById("MER").classList.add('blacklink');
   MER=1;
  }
  else {
   document.getElementById("MER").classList.remove('blacklink');
   document.getElementById("MER").classList.add('blacklinkinverted');
   MER=0;
  }
 }
}*/

