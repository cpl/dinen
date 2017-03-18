$(function () {
  var currentTheme
    = $('<link href="' + themes['default'] + '" rel="stylesheet" />');
  currentTheme.appendTo('head');
  $('.theme-link').click(function () {
    var themeURL = themes[$(this).attr('data-theme')];
    currentTheme.attr('href', themeURL);
  });
  loadPage('landing', true);
});

function loadPage(name, hasJS) {
  showPreloader(function () {
    $('#page_contents').load('html/' + name + '.html', function () {
      $.getScript('imperial/js/custom.js', function () {
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
  $('html, body').css({
    'overflow': 'hidden',
    'height': '100%'
  });
  if ($('#preloader').css('display') == 'none') {
    $('#preloader').slideDown(300, function () {
      callback();
    });
  } else {
    callback();
  }
}

function hidePreloader() {
  $('#preloader').delay(400).slideUp(400, function () {
    $(this).hide();
    $('html, body').css({
      'overflow': 'auto',
      'height': 'auto'
    });
  });
}
