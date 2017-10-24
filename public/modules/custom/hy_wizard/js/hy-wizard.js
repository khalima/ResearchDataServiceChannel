(function ($, Drupal, settings) {
  'use strict';

  // jQuerified variables.
  // var $wizard = $('.wizard');
  // var $wizardContent = $('.wizard__content');
  var $wizardHeaderTitle = $('.wizard__header__title');
  var $wizardHeaderContent = $('.wizard__header__content');
  var $wizardSelections = $('.wizard__selections');
  var $wizardServices = $('.wizard__services');
  // var $wizardFooterContent = $('.wizard__footer__content');
  var $wizardFooterLinks = $('.wizard__footer__links');
  var $wizardBreadcrumbs = $('.wizard__breadcrumbs');

  // Default values
  var page_title = $wizardHeaderTitle.text();
  var page_content = $wizardHeaderContent.html();
  var $consultationLink = '';

  // If consultation settings are not set, do not show link.
  if (settings.consult_target && settings.consult_text) {
    $consultationLink = $('<a class="button--action icon--arrow-right theme-transparent right" href="' + settings.consult_target + '">' + settings.consult_text + '</a>');
  }

  // This should be injected from JS.
  // @todo inject this from settings.
  var api_url = '/api/wizard-questions/';

  Drupal.behaviors.hy_wizard_controller = {
    attach: function (context, settings) {
      attachHandlers();
    }
  };

  /**
   * Helper to attach click handlers
   */
  function attachHandlers() {
    $('.wizard-button').once().on('click', wizardClickHandler);
  }

  /**
   * Click handler to set stage for second batch of questions.
   */
  function wizardClickHandler() {
    var selected_id = $(this).data('tid');

    if (selected_id === 0) {
      selected_id = 'all';
    }

    var url = api_url + selected_id + '?_format=json';

    // @todo Add error handling.
    $.ajax({
      url: url,
      method: 'GET',
      success: renderWizard
    });
  }

  /**
   * Helper to render wizard.
   */
  function renderWizard(output) {
    // All data is coming from the backend. We just need
    // to refresh page with newly acquired data.
    var breadcrumb = output.breadcrumb;
    var data = output.terms;

    // Empty up current breadcrumbs.
    $wizardBreadcrumbs.html('');

    $(breadcrumb).each(function (index, value) {
      var $elem = $('<span class="breadcrumbs__item"></span>');
      $elem.append('<a class="wizard-button" data-tid="' + value.tid + '">' + value.name + '</a>');
      $wizardBreadcrumbs.append($elem);
      $wizardBreadcrumbs.append('<span class="breadcrumbs__divider">/</span>');
    });

    $wizardHeaderTitle.text(data.name ? data.name : page_title);
    $wizardHeaderContent.html((data.parents) ? data.description : page_content);

    var $selections = $('<div class="wizard__selections"></div>');

    // Remember that this is MVP.
    var terms = data.children ? data.children : data;

    // @todo Add front end sorting.
    // @todo Convert terms to be an array.
    for (var tid in terms) {
      if (terms.hasOwnProperty(tid)) {
        var term = terms[tid];
        var $selection = $('<div class="wizard__selection"></div>');
        var $link = $('<a data-tid="' + term.tid + '">' + term.name + '</a>').addClass('wizard-button button--action icon--arrow-right');

        $selection.append($link);
        $selections.append($selection);
      }
    }

    // Prevent empty page and add default 'not found' text. Should
    // very rarely happen.
    // @todo Add this from Drupal settings!
    // Check if terms is an array
    if (terms.length === 0 && (data.services && data.services.length === 0)) {
      $selections.append('<p>No values found.</p>');
    }

    // This is always triggered so that selections are visible or
    // empty.
    $wizardSelections.html($selections);

    // Append services if there are any. First clear any services
    // that are applied to page.
    $wizardServices.html('');

    if (data.services && data.services.length > 0) {

      // @todo Add this as a value that is obtained from admin.
      $wizardServices.append('<h2>Recommended services</h2>');

      for (var i = 0; i < data.services.length; i++) {
        // 'service' is already preformatted to a specific format.
        // Drupal naming conventions (like 'body') are still used.
        var service = data.services[i];

        var $serviceContainer = $('<div class="wizard__service textarea-infobox"></div>');
        $serviceContainer.append('<h4>' + service.title + '</h4>');
        $serviceContainer.append(service.body);
        $serviceContainer.append('<a href="' + service.path + '" class="button--action icon--arrow-right theme-transparent-alt">Read more</a>');
        $wizardServices.append($serviceContainer);
      }
    }

    // Add back button and link to it.
    if (data.parents) {
      $wizardFooterLinks.html(
        $('<a class="wizard-button button--action-before icon--arrow-left theme-transparent left" data-tid="' + data.parents[0] + '">Back</a>')
      );

      $wizardFooterLinks.append($consultationLink);
    }
    else {
      $wizardFooterLinks.html('');
    }

    attachHandlers();

  }
})(jQuery, Drupal, drupalSettings);
