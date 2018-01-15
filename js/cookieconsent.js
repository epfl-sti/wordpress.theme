// https://github.com/insites/cookieconsent
window.addEventListener("load", function(){
  window.cookieconsent.initialise({
    "palette": {
      "popup": {
        "background": "#444444",
        "text": "#cfcfe8"
      },
      "button": {
        "background": "#D10016"
      }
    },
    "theme": "classic",
    "content": {
      "message": "By continuing your browsing on this site, you agree to the use of cookies to improve your user experience and to make statistics of visits.",
      "link": "Read the legal notice",
      "href": "https://mediacom.epfl.ch/disclaimer"
    }
  })
});
