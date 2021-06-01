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
use \EPFL\WS\Persons\Person;

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

<directory class="container">
<script type="text/javascript">
var PO = 0;
var PA = 0;
var PATT = 0;
var PT = 0;
var MER = 0;
var IBI2 = 0;
var IGM = 0;
var IMX = 0;
// New Institute (IEL + IMT)
var IEM = 0;

function findString(tstring, text) {
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

var _all_persons = {<?php
   // Thanks to the post_meta cache described e.g. at
   // http://www.dansmart.co.uk/2016/01/wordpress-post-meta-caching/,
   // even the naïve implementation below will perform only a constant
   // number of database requests.
   Person::foreach(function ($person) {
     $sciper = $person->get_sciper();
     printf("%d: %s,\n", $sciper, json_encode(array(
         img    => get_the_post_thumbnail($person->wp_post())
     )));
   });
?> "_last_key_unused": {}};

function img_of_person (person) {
    var this_person = _all_persons[person.sciper];
    if (! this_person) return "";
    return this_person.img;
}

function _templateCard(person) {
    function _h(s, person) {
         // Poor man's handlebars
         return s.replace(/{{person.([a-zA-Z_]+)}}/g,
                          function(unused, k) {return person[k]});

    }

     var html = "<div class=\"faculty-titre-card col-6 col-md-4 col-lg-3 col-xl-2\">\n";

     person = JSON.parse(JSON.stringify(person));  // Unalias
     person["fullname"] = person.firstname + " " + person.lastname;

     html += "<a href=\"" + person.link + "\">" + img_of_person(person) + "</a>\n";
     html += " <div class=\"faculty-rouge\"></div>\n";
     html += _h(
             " <div class=\"faculty-titre-id\"><h4><a href=\"{{person.link}}\">{{person.lastname}} {{person.firstname}}</a></h4>\n", person);

     html += _h(
             "  <a class=\"faculty-lab\" href=\"{{person.labwebsite}}\">{{person.mylabname}}</a>\n", person);
     html +=  " </div>\n"; // id="faculty-titre-id"
     html +=  "</div>\n";  // class="faculty-titre-card" etc.
     return html;
}

function _doPrintOuter(people_listing, lang) {
  var test="";

  var count=0;
  var result=0;
  for(var x=0; x < people_listing.length; x++) {
   if (people_listing[x]) {

	result++;
        count++;
        people_listing[x].link = "https://people.epfl.ch/" + people_listing[x].sciper;
        test += _templateCard(people_listing[x]);
   }
  }
  if (count==0) {
   test+= "<span><a style='color:white' href=#>THERE ARE NO PROFESSORS IN THIS CATEGORY</a></span>";
  }
  document.getElementById('<?php echo $div_id; ?>').innerHTML = test;
}

function resetDirectoryForm() {
    $(".sti-faculty-sort a").data("active", false);
    $(".sti-faculty-sort a#all").data("active", true);
    $(".sti-faculty-sort a").children('input').each(function () {
     $(this).prop('checked', false);
    });
    redraw();
    return false;  // For the onClick() handler to suppress the event
}

function redraw () {
    var $institute = <?php echo ($institute ? "\"$institute\"" : undefined); ?>;
    $(".sti-faculty-sort a").map(function (unused_index, e) {
        if ($(e).data("active")) {
            $(e).addClass('sti-toggled');
        } else {
            $(e).removeClass('sti-toggled');
        }
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
        IEM = anchor2cgiparam("IEM"),
        IGM = anchor2cgiparam("IGM"),
        IMX = anchor2cgiparam("IMX")
      url = url+"&IBI2="+IBI2+"&IEM="+IEM+"&IGM="+IGM+"&IMX="+IMX;
    }
    $.ajax({
        url: url,
        dataType: "json",
}).done(function(people_listing) {
    _doPrintOuter(people_listing, "en");
});
}

function toggle ($link) {
    $(".sti-faculty-sort a#all").data("active", false);
    var oldState = $link.data("active");
    $link.data("active", ! oldState);
    $(function() {  // Poor man's nextTick()
        $link.children("input").prop("checked", ! oldState);
    });
    redraw();
}

$(function() {
    $("#all").data("active", true);
    $("input, a.togglable").on("click", function(event) {
        this_link = $(this).closest("a");
        toggle(this_link);
        event.preventDefault();
        event.stopPropagation();
    });
    redraw();
});

</script>

<style>
  .chkBox {
    float: left;
    margin-top: 0.4rem;
  }
  .chkBox-label {
    margin-left: 1.2rem;
    display: block;
  }
</style>

  <div class="container">
    <div class="row row-offcanvas row-offcanvas-right">
      <div class="sti-faculty-trombinoscope col-12 col-md-9">
        <div class="row" id="<?php echo $div_id; ?>">&nbsp;</div>
      </div>
      <div class="sti-faculty-sort d-none d-md-block col-md-3">
        <div class="toggles-rank">
          <a href="#" onClick="javascript:return resetDirectoryForm();" id="all"><?php echo ___("All Faculty"); ?></a>
          <br />
          <br />
          <a href="#" class="togglable" id="PO"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Full Professors"); ?> </span></a>
          <a href="#" class="togglable" id="PA"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Associate Professors"); ?> </span></a>
          <a href="#" class="togglable" id="PATT"><input type="checkbox"class="chkBox"> <span class="chkBox-label"> <?php echo ___("Assistant Professors"); ?> </span></a>
          <a href="#" class="togglable" id="PT"><input type="checkbox" class="chkBox"> <span class="chkBox-label"> <?php echo ___("Adjunct Professors"); ?> </span></a>
          <a href="#" class="togglable" id="MER"><input type="checkbox"class="chkBox" > <span class="chkBox-label"> <?php echo ___("Senior Scientists"); ?> </span></a>
        </div>
        <br />
        <br />
        <?php if (! $institute): ?>
        <div class="toggles-institute">
          <a href="#" class="togglable" id="IBI2"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Bioengineering"); ?> </span></a>
          <a href="#" class="togglable" id="IEM"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Electrical and Micro Engineering"); ?> </span></a>
          <a href="#" class="togglable" id="IMX"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Materials Science"); ?> </span></a>
          <a href="#" class="togglable" id="IGM"><input type="checkbox" class="chkBox"><span class="chkBox-label"> <?php echo ___("Mechanical Engineering"); ?> </span></a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
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
    $config["institute"] = wp_strip_all_tags(strtoupper($new_config["institute"]));
    return $config;
  }
}

register_widget(FacultyGallery::class);
