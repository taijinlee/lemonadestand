var body = $(document.body);

$(document).addEvent('domready', function() {
  
  if ((body).hasClass('login')) {
    $('fb_login').addEvent('click', function(e) {
      e.stop();
      window.open(this.href,'','scrollbars=no,menubar=no,height=326,width=627,resizable=no,toolbar=no,location=yes,status=no');
    });
  }
  
  if ((body).hasClass('fb_logged_in')) {
    window.opener.location.reload();
    window.close();
  }

});