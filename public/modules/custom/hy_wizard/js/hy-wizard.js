(function ($, Drupal) {
  'use strict';

  // jQuerified variables.
  // var $wizard               = $('.wizard');
  // var $wizardContent = $('.wizard__content');
  var $wizardHeaderTitle = $('.wizard__header__title');
  var $wizardHeaderContent = $('.wizard__header__content');
  var $wizardSelections = $('.wizard__selections');
  var $wizardServices = $('.wizard__services');
  var $wizardFooterContent = $('.wizard__footer__content');

  // Default values
  var page_title = $wizardHeaderTitle.text();
  var page_content = $wizardHeaderContent.html();

  // This should be injected from JS.
  // @todo inject this from JS.
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
  function renderWizard(data) {
    $wizardHeaderTitle.text(data.name ? data.name : page_title);
    $wizardHeaderContent.html((data.parents) ? data.description : page_content);

    var $selections = $('<div class="wizard__selections"></div>');

    // Remember that this is MVP.
    var terms = data.children ? data.children : data;

    // @todo Add front end sorting.
    for (var tid in terms) {
      if (terms.hasOwnProperty(tid)) {
        var term = terms[tid];
        var $selection = $('<div class="wizard__selection"></div>');
        var $link = $('<a data-tid="' + term.tid + '">' + term.name + '</a>').addClass('wizard-button button--action icon--arrow-right');

        $selection.append($link);
        $selections.append($selection);
      }
    }

    $wizardSelections.html($selections);

    // Append services if there are any. First clear any services
    // that are applied to page.
    $wizardServices.html('');

    if (data.services && data.services.length > 0) {
      $wizardServices.append('<h2>Recommended services</h2>');
      for (var i = 0; i < data.services.length; i++) {
        // Drupal returns these horrific array monstrosities.
        var service = data.services[i];

        var $serviceContainer = $('<div class="wizard__service textarea-infobox"></div>');
        $serviceContainer.append('<h4>' + service.title[0].value + '</h4>');
        $serviceContainer.append(service.body[0].value);
        $serviceContainer.append('<a href="#" class="button--action icon--arrow-right theme-transparent-alt">Read more</a>');
        $wizardServices.append($serviceContainer);
      }
    }

    // Add back button and link to it.
    if (data.parents) {
      $wizardFooterContent.html(
        $('<a class="wizard-button button--action-before icon--arrow-left theme-transparent" data-tid="' + data.parents[0] + '">Back</a>')
      );
    }
    else {
      $wizardFooterContent.html('');
    }

    attachHandlers();

  }
})(jQuery, Drupal);
