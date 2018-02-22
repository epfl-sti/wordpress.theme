$('a:not(' +
  '[href^="/"], ' +
  '[href^="https://sti.epfl"], ' +
  '[href^="https://sti-test.epfl"], ' +
  '[href^="https://sti-dev.epfl"], ' +
  '[href^="https://localhost"], ' +
  '[href^="\#"])').attr({target: "_blank"});
