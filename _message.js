function message(text){
    document.getElementById('message').innerHTML = text;
    $('#overlay').modal('show');
  
    setTimeout(function() {
        $('#overlay').modal('hide');
    }, 2000);
  }