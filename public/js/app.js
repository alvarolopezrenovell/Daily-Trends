$(document).ready(function () {

  // Event to execute link on confirm modal
  $('.modal button.btn_accept').click(function () {
    var modal_id = $(this).closest('.modal').attr('id');

    if ($('[data-target="#'+modal_id+'"]').length === 1) {
      var href = $('[data-target="#'+modal_id+'"]').attr('href');
      window.location.href = href;
    }
  });

});