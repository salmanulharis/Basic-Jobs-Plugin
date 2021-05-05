jQuery(document).ready(function($){
  // datepicker jQuery script
  $('#job-date-picker').focus(function() {
    $( "#job-date-picker" ).datepicker();
    $( "#job-date-picker" ).datepicker("show");
  });

  $( "#company_email_field" ).focusout(function() {
    var email = $('#company_email_field').val();
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
      alert( "Enter a valid Email Address" );
       return false;
    }else{
       return true;
    }

  });
  //jquery to add datepicker
  $('#expiry_datepicker').focus(function() {
    // alert( "date" );
    $( "#expiry_datepicker" ).datepicker();
    $( "#expiry_datepicker" ).datepicker("show");
  });

});

//colorpicker javascript using Pickr library
//'https://github.com/Simonwep/pickr.git' refer this site for more details

const savedColor = document.getElementById("job_color_picker").value;
const pickr = Pickr.create({

    el: '.color-picker',
    theme: 'classic', // or 'monolith', or 'nano'
    defaultRepresentation: 'HEX',
    default: savedColor,
    swatches: [
        'rgba(244, 67, 54, 1)',
        'rgba(233, 30, 99, 0.95)',
        'rgba(156, 39, 176, 0.9)',
        'rgba(103, 58, 183, 0.85)',
        'rgba(63, 81, 181, 0.8)',
        'rgba(33, 150, 243, 0.75)',
        'rgba(3, 169, 244, 0.7)',
        'rgba(0, 188, 212, 0.7)',
        'rgba(0, 150, 136, 0.75)',
        'rgba(76, 175, 80, 0.8)',
        'rgba(139, 195, 74, 0.85)',
        'rgba(205, 220, 57, 0.9)',
        'rgba(255, 235, 59, 0.95)',
        'rgba(255, 193, 7, 1)'
    ],

    components: {

        // Main components
        preview: true,
        opacity: true,
        hue: true,

        // Input / output Options
        interaction: {
            hex: true,
            rgba: true,
            input: true,
            save: true
        }
    }
});
//pickr script to get hex value of color
pickr.on('change', (color, instance) => {
    const hexColor = color.toHEXA().toString();
    document.getElementById("job_color_picker").value = hexColor;
})
