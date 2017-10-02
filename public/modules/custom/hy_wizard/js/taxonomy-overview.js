(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.hy_wizard_add = {
    attach: function (context, settings) {
      $('.wizard-add-child').on('click', addChild);
    }
  };

  function addChild() {
    var tid = $(this).data('tid');
    var $row = $('tr[data-drupal-selector=edit-terms-tid' + tid + '0]');
    $row.after();
  }
})(jQuery, Drupal);

// https://www.previousnext.com.au/blog/understanding-drupal-8s-modal-api-and-dialog-controller
