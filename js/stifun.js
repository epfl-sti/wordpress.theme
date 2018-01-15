(function($) {
  $.stifun = {
    defaults: {
      'code': [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
      'step': 0
    }
  };
  $.fn.stifun = function(fn, options) {
    var opts = $.extend({}, $.stifun.defaults, options);
    return this.each(function() {
      $(this).bind('stifun.' + opts.code.join(''), fn)
        .bind('keyup', function(event) {
          if (event.keyCode == opts.code[opts.step])
            opts.step++;
          else
            opts.step = 0;

          if (opts.step == opts.code.length) {
            $(this).trigger('stifun.' + opts.code.join(''));
            opts.step = 0;
          }
        });
      return this;
    });
  };
  $(document).stifun(function() {
    ['', '-ms-', '-webkit-', '-o-', '-moz-'].map(function(prefix){
      document.body.style[prefix + 'transform'] = 'rotate(180deg)';
    });
    console.log("░░░░░▄▄▄▄▀▀▀▀▀▀▀▀▄▄▄▄▄▄░░░░░░░\n░░░░░█░░░░▒▒▒▒▒▒▒▒▒▒▒▒░░▀▀▄░░░░\n░░░░█░░░▒▒▒▒▒▒░░░░░░░░▒▒▒░░█░░░\n░░░█░░░░░░▄██▀▄▄░░░░░▄▄▄░░░░█░░\n░▄▀▒▄▄▄▒░█▀▀▀▀▄▄█░░░██▄▄█░░░░█░\n█░▒█▒▄░▀▄▄▄▀░░░░░░░░█░░░▒▒▒▒▒░█\n█░▒█░█▀▄▄░░░░░█▀░░░░▀▄░░▄▀▀▀▄▒█\n░█░▀▄░█▄░█▀▄▄░▀░▀▀░▄▄▀░░░░█░░█░\n░░█░░░▀▄▀█▄▄░█▀▀▀▄▄▄▄▀▀█▀██░█░░\n░░░█░░░░██░░▀█▄▄▄█▄▄█▄████░█░░░\n░░░░█░░░░▀▀▄░█░░░█░█▀██████░█░░\n░░░░░▀▄░░░░░▀▀▄▄▄█▄█▄█▄█▄▀░░█░░\n░░░░░░░▀▄▄░▒▒▒▒░░░░░░░░░░▒░░░█░\n░░░░░░░░░░▀▀▄▄░▒▒▒▒▒▒▒▒▒▒░░░░█░\n░░░░░░░░░░░░░░▀▄▄▄▄▄░░░░░░░░█░░");
   });
})(jQuery);
