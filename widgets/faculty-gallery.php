<?php

/**
 * "Trombinoscope" (in French) of Professors
 */

namespace EPFL\STI\Theme\Widgets;

if (! class_exists('WP_Widget')) {
  die('Access denied.');
}

require_once(dirname(dirname(__FILE__)) . "/inc/i18n.php");
use function \EPFL\STI\Theme\___;

class FacultyGallery extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'EPFL_STI_Theme_Widget_FacultyGallery', // unique id
      ___('EPFL Faculty Gallery'), // widget title
      // additional parameters
      array(
        'description' => ___('Shows mugshots of faculty members')
      )
    );
  }

  public function widget($args, $config)
  {
    # TODO: Need to be smarter than this to support multiple faculty
    # galleries per page.
    $div_id = "faculty-gallery";
    ?>
    <directory>
<script type="text/javascript">

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
		  test+="<a class='pandalink' href='" + people_listing[x].labwebsite + "'>" + people_listing[x].labname + "</a></div><br/>";
			test+="<br><br></div>\n\
";
  count++;
      
     }
    }
   }
  }
  if (count==0) {
   test+= "<span style='width:82px' class=pandalink><a style='color:white' href=#>THERE ARE NO PROFESSORS IN THIS CATEGORY</a></span>"; 
  }
  document.getElementById('<?php echo $div_id; ?>').innerHTML = test;
}

function printOuter(which, lang, level) {
    $.ajax({
        url: "https://stisrv13.epfl.ch/cgi-bin/whoop/faculty-and-teachers.pl",
        dataType: "json",
}).done(function(people_listing) {
    _doPrintOuter(people_listing, which, lang, level);
});
}
</script>

<center>
	<h4><img src="https://stisrv13.epfl.ch/js/faculty.png" usemap="#facultybar" alt="" border="0"></h4>
</center>

<map name="facultybar">
	<area title="Tenure Track Assistant Professors" shape="poly" coords="304,112,390,114,306,115,303,99,445,96,473,113,475,128,397,125" href="#" onclick="javascript:printOuter('','en','PATT');" alt="" />

	<area title="Associate Professors" shape="rect" coords="158,96,282,110" href="#" onclick="javascript:printOuter('','en','PA');" alt="" />
	<area title="Senior Scientists" shape="rect" coords="155,120,302,139" href="#" onclick="javascript:printOuter('','en','MER');" alt="" />
	<area title="Adjunct Professors" shape="rect" coords="11,121,148,139" href="#" onclick="javascript:printOuter('','en','PT');" alt="" />
	<area title="Full Professors" shape="rect" coords="9,94,148,117" href="#" onclick="javascript:printOuter('','en','PO');" alt="" />
	<area title="Bioengineering" href="#" onclick="javascript:printOuter('IBI2','en','');" coords="375,39,471,79" shape="rect">
	<area title="Microengineering" href="#" onclick="javascript:printOuter('IMT','en','');" coords="164,60,292,91" shape="rect">
	<area title="Materials Science and Engineering" href="#" onclick="javascript:printOuter('IMX','en','');" coords="158,40,366,60" shape="rect">
	<area title="Mechanical Engineering" onclick="javascript:printOuter('IGM','en','');" href="#" coords="12,61,157,81" shape="rect">
	<area title="Electrical Engineering" href="#" onclick="javascript:printOuter('IEL','en','');" coords="12,40,157,60" shape="rect">

	<area title="Show all faculty members of STI" onclick="javascript:printOuter('all','en','');" href="#" coords="12,0,374,39" shape="rect">
</map>
</p>
<div id="<?php echo $div_id; ?>" style='padding: 15px 0px 0px 25px; display: inline-block; background-color:#444;border:2px solid black;'>&nbsp;</div>
<script>printOuter('all','en','all');</script>

</directory>
<?php

  }  // public function widget
}

register_widget(FacultyGallery::class);
