var translator = ReactDOM.render(React.createElement(Translator), document.getElementById('translator'));

$(function() {
  $('#btnShow').click(function() {
    $('#output').text('');
    
    translator.show('Hola Mundo! Este es el control de Translator.', 'en', 'es', function(text) {
      $('#output').text(text);
    });
  });
  
  $('#btnHide').click(function() {
    translator.hide();
  });
});