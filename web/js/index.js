$(function () {
  // TODO: look at the URL and load the appropriate page (GET?)
  loadPage('landing', true);
});

function loadPage(name, hasJS) {
  showPreloader(function () {
    // After the pre-loader image is shown, load the page's html into the
    // page_contents div (on index).
    $('#page_contents').load('html/' + name + '.html', function () {
      // Load the JS used by the theme (for e.g. the changing text on the
      // landing page). Must be loaded after the DOM is so that its elements
      // can be referred to.
      // TODO: check if this needs to be loaded more than once
      $.getScript('imperial/js/custom.js', function () {
        // If the page has any additional JS, load it and then hide the
        // pre-loader, otherwise hide the pre-loader 'straight away'.
        if (hasJS) {
          $.getScript('js/' + name + '.js', function () {
            hidePreloader();
          });
        } else {
          hidePreloader();
        }
      });
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
  if ($('#preloader').css('display') == 'none') {
    // If it isn't, trigger the slide-down animation.
    $('#preloader').slideDown(300, function () {
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
