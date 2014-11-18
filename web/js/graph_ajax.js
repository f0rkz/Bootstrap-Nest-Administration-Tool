jQuery.ajaxSetup({
  beforeSend: function() {
     $('#loading').show();
  },
  complete: function(){
     $('#loading').hide();
  },
  success: function() {}
});