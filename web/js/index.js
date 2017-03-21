var scriptsWithInit = {'dashboard' : new Dashboard(),
                       'landing' : new Landing(),
                       'menu' : new Menu(),
                       'order' : new Order(),
                       'cook' : new Cook(),
                       'search' : new Search(),
                       'payment' : new Payment()
                      };
var loadedScripts = Object.keys(scriptsWithInit);

$(window).on('load', function () {
  // Back to top button
  $(window).scroll(function() {

    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }

  });
  $('.back-to-top').click(function(){
    $('html, body').animate({scrollTop : 0}, 750, 'easeInOutExpo');
    return false;
  });
  // TODO: look at the URL and load the appropriate page (GET?)
  if (isManager()) {
    loadPage('dashboard');
  } else {
    loadPage('landing');
  }
});

function loadPage(name) {
  showPreloader(function () {
    // After the pre-loader image is shown, load the page's html into the
    // page_contents div (on index).
    $('#page_contents').load('html/' + name + '.html', function () {
      // Load the page's JS it (if not already loaded) and initialise it,
      // then hide the pre-loader, otherwise hide the pre-loader 'straight
      // away'.

      // Ensure that the script isn't loaded twice.
      if ($.inArray(name, loadedScripts) == -1) {
        // Script not loaded.
        $.getScript('js/' + name + '.js', function () {
          loadedScripts.push(name);
          // Shouldn't be necessary (all scripts with init included in
          // index.html), but just in case.
          if (scriptsWithInit[name] != undefined) {
            scriptsWithInit[name].init();
          }
          hidePreloader();
        });
      } else {
        if (scriptsWithInit[name] != undefined) {
          scriptsWithInit[name].init();
        }
        hidePreloader();
      }
    });
  });
}

function showPreloader(callback) {
  // Hide the scroll bar.
  $('html, body').css({
    'overflow': 'hidden',
    'height': '100%'
  });
  // Check whether the pre-loader image is already shown (when the user first
  // visits the site), or not.
  var preloader = $('#preloader');
  if (preloader.css('display') == 'none') {
    // If it isn't, trigger the slide-down animation.
    preloader.slideDown(300, function () {
      // Once the animation is complete, call the function that defines what
      // to do next.
      callback();
    });
  } else {
    // The pre-loader image is already shown, so call the function that
    // defines what to do next.
    callback();
  }
}

function hidePreloader() {
  // Trigger the slide-up animation after a delay of 200ms.
  $('#preloader').delay(200).slideUp(400, function () {
    // Once complete, hide the pre-loader and allow the scroll bar to be shown.
    $(this).hide();
    $('html, body').css({
      'overflow': 'auto',
      'height': 'auto'
    });
  });
}
