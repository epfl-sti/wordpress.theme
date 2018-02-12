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
use function \EPFL\STI\Theme\__x;

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

  public function widget ($args, $config)
  {
    $institute = $config["institute"];
    if ($institute) {
      $div_id = "faculty-gallery-$institute";
    } else {
      $div_id = "faculty-gallery";
    }

    ?>
    <directory>
<script type="text/javascript">
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
  document.getElementById('<?php echo $div_id; ?>').innerHTML = test;
}

function resetDirectoryForm() {
    $(".sti_faculty_sort a").data("active", false);
    $(".sti_faculty_sort a#all").data("active", true);
    redraw();
    return false;  // For the onClick() handler to suppress the event
}

function redraw () {
    var $institute = <?php echo ($institute ? "\"$institute\"" : undefined); ?>;
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
        MER = anchor2cgiparam("MER");
    var url = "https://stisrv13.epfl.ch/cgi-bin/whoop/faculty-and-teachers2.pl?PO="+PO+"&PA="+PA+"&PATT="+PATT+"&PT="+PT+"&MER="+MER;
    if ($institute) {
      url = url + "&" + $institute + "=1";
    } else {
      var IBI2 = anchor2cgiparam("IBI2"),
        IEL = anchor2cgiparam("IEL"),
        IGM = anchor2cgiparam("IGM"),
        IMT = anchor2cgiparam("IMT"),
        IMX = anchor2cgiparam("IMX")
      url = url+"&IBI2="+IBI2+"&IEL="+IEL+"&IGM="+IGM+"&IMT="+IMT+"&IMX="+IMX;
    }
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

</script>

<div class="sti_faculty_sort">
 <div class="sti_sort_box">
  <a class="blacklink" href="#" onClick="javascript:return resetDirectoryForm();" id=all class="sti_sort_button">All Faculty</a><br><br>
  <!---a href=# onClick='alert(PO + " " + PA + " " + PATT + " " + PT + " " + MER + " " + IBI2 + " " + IEL + " " + IGM + " " + IMX + " " + IMT);'>report</a--->
 </div>
 <div class="sti_sort_box">
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=PO>Full Professors</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=PA>Associate Professors</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=PATT>Assistant Professors</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=PT>Adjunct Professors</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=MER>Senior Scientists</a>
 </div> 
<?php if (! $institute): ?>
 <div class="sti_sort_box">
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=IBI2>Bioengineering</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=IEL>Electrical Engineering</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=IMX>Materials Science</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=IGM>Mechanical Engineering</a>
  <a class="blacklink" href="#" onClick="javascript:return toggle(this);" id=IMT>Microengineering</a>
 </div>
<?php endif; ?>
</div>

<div id="<?php echo $div_id; ?>" style='padding: 15px 0px 0px 25px; display: inline-block; background-color:white;'>&nbsp;</div>

</directory>
<?php

  }  // public function widget

  public function form ($config)
  {
    $title_id   = $this->get_field_id  ('institute');
    $title_name = $this->get_field_name('institute');
    printf("<label for=\"%s\">%s</label>", $title_id,
           __x('Institute:', 'faculty-gallery wp-admin'));
    printf("<input type=\"text\" id=\"$title_id\" name=\"$title_name\" value=\"%s\">", esc_html($config["institute"]));
  }

  public function update( $new_config, $old_config )
  {
    $config = $old_config;
    $config["institute"] = $new_config["institute"];
    return $config;
  }

}

register_widget(FacultyGallery::class);
