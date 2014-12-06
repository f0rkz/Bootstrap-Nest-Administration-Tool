jQuery.ajaxSetup({
  beforeSend: function() {
     $('#loading').show();
  },
  complete: function(){
     $('#loading').hide();
  },
  success: function() {}
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})