'use strict';

import $ from 'jquery';

$(document).on(
  'click',
  '[data-action="back"]',
  (event) => $(event.currentTarget).parent().find('input[type="hidden"]').attr('value', 'back')
);

const send = (form) => {
  const actionArea = form.find('.snow-monkey-form__action');

  form.on(
    'submit',
    (event) => {
      event.preventDefault();

      $.post(
        snow_monkey_forms.view_json_url,
        form.serialize()
      ).done(
        (response) => {
          response = JSON.parse(response);
          const method = response.data._method;

          console.log(response);

          actionArea.html(response.action);

          if ('' === method || 'back' === method || 'error' === method || 'confirm' === method) {
            $.each(
              response.controls,
              (key, control) => {
              const placeholder = form.find(`.snow-monkey-form__placeholder[data-name="${key}"]`);
                placeholder.html( '' ).append( control );
              }
            );
          } else if ('complete' === method) {
            form.html('').append(response.message);
          } else {
            form.html('');
          }
        }
      );
    }
  );
};

$('.snow-monkey-form').each((i, e) => send($(e)));
