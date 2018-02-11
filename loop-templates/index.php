        <style>           
			.link { text-decoration: underline; color: #0b94b1; cursor: pointer; }
                        #note { text-align: left;
                                font: 11px verdana, Helvetica, Arial, Geneva, Swiss, SunSans-Regular, sans-serif;
                                margin:0;padding-bottom:2px;padding-left:4px;padding-top:0px;
                                width: 630px;
                        }
                        #cy {
                                width: 100%;
				height : 700px;
                        }
                        #cytocontent{
                                width:100%;
                        }
                        h1 {    font: 18px verdana; }
                        h2 {
                                font: 14px verdana;
                                font-weight: bold;
                        }
                        a:active,a:link,a:visited {
                         text-decoration: none;
                         font-weight: normal;
                         color: #039;
                        }
                        .holding {
                         display: inline-block;
                        }
                        .willey {
                         float:left;
                         display: inline;
                         clear: none;
                        } 
.embolden {
 font-weight: bold;
}
#note, .note {
 font: 12px verdana, Helvetica, Arial, Geneva, Swiss, SunSans-Regular, sans-serif;
}


        </style>
        <script src="https://stisrv13.epfl.ch/node_modules/cytoscape/dist/cytoscape.js"></script>
   
<div id="cytocontent" class=holding>
<h1>School of Engineering Research Clusters</h1>
        <div id="cy" class=willey></div>
           <div style="visibility:hidden"><h2>No STI publications have been recorded for this professor 
 between 2009 and 2016

        </div>
        <div id="note" class=willey>
         <p>
<br><br>
        </p>
        </div>
        <div>
        </div>
     </div>

<script>

var cy = cytoscape({
 container: document.getElementById('cy'),
 elements: [

<?php

$array=["{ data: { id: 'p-data', label: 'Data & Network Sciences', image:'../img/clusters/data.jpg', weight: '130px' } },",
"{ data: { id: 'p-energy', label: 'Energy', image:'../img/clusters/energy.jpg', weight: '130px' } },",
" { data: { id: 'p-health', label: 'Personalized Health', image:'../img/clusters/health.jpg', weight: '130px' } },",
"{ data: { id: 'p-imaging', label: 'Imaging & Vision', image:'../img/clusters/imaging.jpg', weight: '130px' } },",
"{ data: { id: 'p-iot', label: 'Internet-of-Things', image:'../img/clusters/iot.jpg', weight: '130px' } },",
"{ data: { id: 'p-learning', label: 'Learning & Decision Systems', image:'../img/clusters/learning.jpg', weight: '130px' } },",
"{ data: { id: 'p-manufacturing', label: 'Advanced Manufacturing', image:'../img/clusters/manufacturing.jpg', weight: '130px' } },",
"{ data: { id: 'p-nano', label: 'Nanotechnology', image:'../img/clusters/nano.jpg', weight: '130px' } },",
"{ data: { id: 'p-neural', label: 'Neural Engineering & Prosthetics', image:'../img/clusters/neural.jpg', weight: '130px' } },",
"{ data: { id: 'p-photonics', label: 'Photonics', image:'../img/clusters/photonics.jpg', weight: '130px' } },",
"{ data: { id: 'p-quantum', label: 'Quantum materials and devices', image:'../img/clusters/quantum.jpg', weight: '130px' } },",
"{ data: { id: 'p-robotics', label: 'Robotics and Autonomous Systems', image:'../img/clusters/robotics.jpg', weight: '130px' } },",
"{ data: { id: 'p-soft', label: 'Soft Electronics & Bio-Inspired Systems', image:'../img/clusters/soft.jpg', weight: '130px' } },"];
shuffle($array);
foreach ($array as $arg) {
 echo $arg;
}
?>
        ],

        style: [
         {
           selector: 'node',
           style: {
            shape: 'circle',
            label: 'data(label)',
            'text-valign': 'bottom',
            'background-image': 'data(image)',
            'background-fit': 'contain',
            'width': 'data(weight)',
            'height': 'data(weight)',
	    'text-wrap': 'wrap',
           }
         },
         {
           selector:'edge',
                style:{
                'curve-style': 'bezier',
                 'line-color': 'data(shade)',
                 'width': 'data(width)',
                 label: ''
                }
         }
        ],

        zoom: 1,
        pan: { x: 0, y: 0 },

  // interaction options:
  minZoom: -1,
  maxZoom: 3,
  zoomingEnabled: true,
  userZoomingEnabled: true,
  panningEnabled: true,
  userPanningEnabled: true,
  boxSelectionEnabled: false,
  selectionType: 'single',
  touchTapThreshold: 8,
  desktopTapThreshold: 4,
  autolock: false,
  autoungrabify: false,
  autounselectify: false,

  // rendering options:
  headless: false,
  styleEnabled: true,
  hideEdgesOnViewport: false,
  hideLabelsOnViewport: false,
  textureOnViewport: false,
  motionBlur: false,
  motionBlurOpacity: 0.2,
  wheelSensitivity: 1,
  pixelRatio: 'auto' 
      });

var breadthfirst = {
  name: 'breadthfirst',

  fit: true, // whether to fit the viewport to the graph
  directed: false, // whether the tree is directed downwards (or edges can point in any direction if false)
  padding: 10, // padding on fit
  circle: true, // put depths in concentric circles if true, put depths top down if false
  spacingFactor: 1.5, // positive spacing factor, larger => more space between nodes (N.B. n/a if causes overlap)
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  avoidOverlap: true, // prevents node overlap, may overflow boundingBox if not enough space
  nodeDimensionsIncludeLabels: false, // Excludes the label when calculating node bounding boxes for the layout algorithm
  roots: undefined, // the roots of the trees
  maximalAdjustments: 0, // how many times to try to position the nodes in a maximal way (i.e. no backtracking)
  animate: true, // whether to transition the node positions
  animationDuration: 3500, // duration of animation in ms if enabled
  animationEasing: undefined, // easing of animation if enabled,
  animateFilter: function ( node, i ){ return true; }, // a function that determines whether the node should be animated.  All nodes animated by default on animate enabled.  Non-animated nodes are positioned immediately when the layout starts
  ready: undefined, // callback on layoutready
  stop: undefined, // callback on layoutstop
  transform: function (node, position ){ return position; } // transform a given node position. Useful for changing flow direction in discrete layouts
};

cy.layout( breadthfirst );

var pdata=cy.$('#p-data'); pdata.on('tap',function(){ document.location='research.php?cluster=data';});
var penergy=cy.$('#p-energy'); penergy.on('tap',function(){ document.location='research.php?cluster=energy';});
var phealth=cy.$('#p-health'); phealth.on('tap',function(){ document.location='research.php?cluster=health';});
var pimaging=cy.$('#p-imaging'); pimaging.on('tap',function(){ document.location='research.php?cluster=imaging';});
var piot=cy.$('#p-iot'); piot.on('tap',function(){ document.location='research.php?cluster=iot';});
var plearning=cy.$('#p-learning'); plearning.on('tap',function(){ document.location='research.php?cluster=learning';});
var pmanufacturing=cy.$('#p-manufacturing'); pmanufacturing.on('tap',function(){ document.location='research.php?cluster=manufacturing';});
var pnano=cy.$('#p-nano'); pnano.on('tap',function(){ document.location='research.php?cluster=nano';});
var pneural=cy.$('#p-neural'); pneural.on('tap',function(){ document.location='research.php?cluster=neural';});
var pphotonics=cy.$('#p-photonics'); pphotonics.on('tap',function(){ document.location='research.php?cluster=photonics';});
var pquantum=cy.$('#p-quantum'); pquantum.on('tap',function(){ document.location='research.php?cluster=quantum';});
var probotics=cy.$('#p-robotics'); probotics.on('tap',function(){ document.location='research.php?cluster=robotics';});
var psoft=cy.$('#p-soft'); psoft.on('tap',function(){ document.location='research.php?cluster=soft';});



</script>

