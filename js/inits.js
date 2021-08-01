$(document).ready(function(){
    
    
    
    
      $('.parallax').parallax();      
      $(".button-collapse").sideNav();
      $('.modal').modal();
      $('select').material_select();
     
      $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 80, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        closeOnSelect: false,
        language: 'es'// Close upon selecting a date,
        });                   
        Materialize.updateTextFields();
    });