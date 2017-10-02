(function ($, Drupal, questions) {
  'use strict';

  // var current_terms = [];
  // var current_term = [];
  // var current_term_id = 0;
  // var previous_term_id = 0;
  // var previous_term = [];

  // // jQuerified variables.
  // var $wizard = $('.wizard');

  // Drupal.behaviors.hy_wizard_controller = {
  //   attach: function (context, settings) {
  //     attachHandlers();
  //   }
  // };

  // /**
  //  * Helper to attach click handlers
  //  */
  // function attachHandlers() {
  //   $('.wizard__selections .wizard-button').once().on('click', wizardClickHandler);
  // }

  // /**
  //  * Helper to reset wizard to default values.
  //  * @todo Implement resetting values.
  //  */
  // function resetWizard() {
  //   current_terms = questions;
  //   previous_term = [];
  //   renderWizard();
  // }

  // /**
  //  *  Helper to recurse through array of questions
  //  */

  // /**
  //  * Clickhandler to go back in the form.
  //  */
  // function goBack() {
  //   console.log( typeof questions );
  //   // Lets find the correct index for current term
  //   var term = questions.filter( question => question.tid === current_term.tid);
  //   console.log( term, "did we find it?");

  //   renderWizard();

  // }

  // /**
  //  * Click handler to set stage for second batch of questions.
  //  */
  // function wizardClickHandler() {
  //   current_term_id = $(this).data('tid');

  //   console.log( current_terms, "current terms");
  //   console.log( current_terms.length, "current terms length" );

  //   if ( current_terms.length !== 0 ) {
  //     previous_term = current_term;
  //     current_term = current_terms[ current_term_id ];
  //     current_terms = current_term.children;
  //   } else {
  //     current_term = questions[ current_term_id ];
  //     current_terms = questions[ current_term_id].children;
  //   }

  //   renderWizard();

  // }

  // /**
  //  * Helper to render wizard.
  //  */
  // function renderWizard() {
  //   // We should load these from the backend.
  //   // @todo Add rest-route for getting question data.
  //   var $content    = $('<div class="wizard"></div>');
  //   var $header     = $('<div class="wizard__header"></div>');
  //   var $selections = $('<div class="wizard__selections"></div>');
  //   var $footer     = $('<div class="wizard__footer"></div>');

  //   $header.append('<h2>' + current_term.name + '</h2>');
  //   $header.append(current_term.description__value);

  //   // Get stuff from the backend.
  //   $content.append( $header );

  //   // MVP.
  //   for ( var tid in current_terms ) {
  //     var term = current_terms[tid];
  //     var $selection = $('<div class="wizard__selection"></div>');
  //     var $link = $('<a data-tid="' + term.tid + '">' + term.name + '</a>').addClass('wizard-button button--action icon--arrow-right');

  //     $selection.append( $link );
  //     $selections.append( $selection );
  //   }

  //   $content.append( $selections );

  //   $footer.append( $('<a class="button--action-before icon--arrow-left theme-transparent">Back</a>').on('click', goBack));
  //   $content.append( $footer );

  //   // A pretty worn out fade out & in.
  //   $wizard.fadeOut( function() {
  //     $wizard.html( $content );
  //     attachHandlers();
  //   }).fadeIn();
  // }
})(jQuery, Drupal, drupalSettings.questions);
