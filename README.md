
# EPFL STI WordPress Theme Framework

Based on [UnderStrap](https://understrap.com) — Bootstrap 4, jQuery, child-theme-friendly

Travis build: [![Build Status](https://travis-ci.org/holger1411/understrap.svg?branch=master)](https://travis-ci.org/holger1411/understrap) | Start talking: [![Gitter](https://img.shields.io/gitter/room/holger1411/understrap.svg?maxAge=2592000?style=flat-square)](https://gitter.im/holger1411/understrap)

#### See: [Official Demo](https://understrap.com/understrap) | Read: [Official Docs Page](https://understrap.github.io/)

## License

Like UnderStrap, the EPFL-STI theme is released under the terms of the GPL version 2 or (at your option) any later version.

http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

## Installation

- Download folder from GitHub
- Upload it into your WordPress installation subfolder here: `wp-content/themes/epfl-sti`
- Run a terminal, cd to the `wp-content/themes/epfl-sti` and type `npm install` to build all the things
- Login to your WordPress backend
- Go to Appearance → Themes
- Activate the EPFL-STI theme

## Development

### Installing Dependencies
- Make sure you have installed Node.js and npm on your computer globally
- Then open your terminal and browse to the location of your UnderStrap copy
- Run: `$ npm install`

### Running
To work and compile your Sass files on the fly start:

- `$ npm run watch`

Or, to run with Browser-Sync:

- First change the browser-sync options to reflect your environment in the file `/gulpfile.js` in the beginning of the file:
```javascript
var browserSyncOptions = {
    proxy: "localhost/theme_test/", // <----- CHANGE HERE
    notify: false
};
```
- then run: `$ npm run watch watch-bs`

### Directory Layout

Some basics about the Sass and CSS files that come with UnderStrap:
- The theme itself uses the `/style.css`file just to identify the theme inside of WordPress. The file is not loaded by the theme and does not include any styles.
- The `/css/theme.css` and its minified little brother `/css/theme.min.css` file(s) provides all styles. It is composed of five different SCSS sets and one variable file at `/sass/theme.scss`:

                  - 1 "theme/theme_variables";  // <--------- Add your variables into this file. Also add variables to overwrite Bootstrap or UnderStrap variables here
                  - 2 "../src/bootstrap-sass/assets/stylesheets/bootstrap";  // <--------- All the Bootstrap stuff - Don´t edit this!
                  - 3 "understrap/understrap"; // <--------- Some basic WordPress stylings and needed styles to combine Boostrap and Underscores
                  - 4 "../src/fontawesome/scss/font-awesome"; // <--------- Font Awesome Icon styles

                  // Any additional imported files //
                  - 5 "theme/theme";  // <--------- Add your styles into this file

- Don’t edit the files no. 2-4 files/filesets or you won’t be able to update it without overwriting your own work!
- Your design goes into: `/sass/theme`. Add your styles to the `/sass/theme/_theme.scss` file and your variables to the `/sass/theme/_theme_variables.scss`. Or add other .scss files into it and `@import` it into `/sass/theme/_theme.scss`.

## How to Use the Build-In Widget Slider

The front-page slider is widget driven. Simply add more than one widget to widget position “Hero”.
- Click on Appearance → Widgets.
- Add two, or more, widgets of any kind to widget area “Hero”.
- That’s it.

## RTL styles?
Just add a new file to the themes root folder called rtl.css. Add all alignments to this file according to this description:
https://codex.wordpress.org/Right_to_Left_Language_Support

## Page Templates

### Blank Template

The `blank.php` template is useful when working with various page builders and can be used as a starting blank canvas.

### Empty Template

The `empty.php` template displays a header and a footer only. A good starting point for landing pages.

### Full Width Template

The `fullwidthpage.php` template has full width layout without a sidebar.

[1] Visit [http://browsersync.io](http://browsersync.io) for more information on Browser Sync

Licenses & Credits
=
- Font Awesome: http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
- Bootstrap: http://getbootstrap.com | https://github.com/twbs/bootstrap/blob/master/LICENSE (Code licensed under MIT documentation under CC BY 3.0.)
and of course
- jQuery: https://jquery.org | (Code licensed under MIT)
- WP Bootstrap Navwalker by Edward McIntyre: https://github.com/twittem/wp-bootstrap-navwalker | GNU GPL
- Bootstrap Gallery Script based on Roots Sage Gallery: https://github.com/roots/sage/blob/5b9786b8ceecfe717db55666efe5bcf0c9e1801c/lib/gallery.php


[![Analytics](https://ga-beacon.appspot.com/UA-139292-31/chromeskel_a/readme)](https://github.com/igrigorik/ga-beacon)
