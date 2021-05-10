jQuery(document).ready(function($){

  $("#apply-button").click(function(){
        console.log("hi")
        $("#applicantsform").show();
    });
  //programe for deleting the post
  $("#delete-button").click(function() {
        var id = $("#post_id").val();
        var nonce = $("#app_nonce").val();
        var post = $(this).parents('.post:first');

        $.ajax({
            type: 'post',
            url: settings.ajaxurl,
            data: {
                action: 'my_delete_post',
                nonce: nonce,
                id: id
            },
            success: function( result ) {
              // alert('Deleted succesfully ');
              $(location).attr('href','http://localhost/basic_jobs/')
            }
        });
        return false;
    });

  // Variable to hold request
  var request;


  // Bind to the submit event of our form
  $("#applicantsform").submit(function(event){

      // Prevent default posting of form - put here to work in case of errors
      event.preventDefault();

      $("#applicantsform").hide();

      var name = $("#applicant-name").val();
      var email = $("#applicant-email").val();
      var phone = $("#applicant-phone").val();
      var exp = $("#applicant-exp").val();

      if(name && email && phone && exp){
        $("#application-status").append("Your Application is Submitted. Good Luck.");
        $("#name-data").append(name);
        $("#email-data").append(email);
        $("#phone-data").append(phone);
        $("#exp-data").append(exp);
        $("#details-container").show();


        $("#data-container").show();

        // Abort any pending request
        if (request) {
            request.abort();
        }

        // setup some local variables
        var $form = $(this);

        var form_data = {};
        $('#applicantsform').find('input').each(function () {
            form_data[this.name] = $(this).val();
        });
        console.log(form_data);

        $.ajax({
              type: 'POST',
              url: settings.ajax_url,
              data: form_data,
              success : function() {
                $.post($form.attr('action'), form_data, function(data) {
                     alert('Applied succesfully ' + form_data);
                }, 'json');

              }
          });

        $('#apply-button').click(function() {
            $("#applicantsform").hide();
            location.reload();
        });
      }else{
        alert("All fields are required, Try again");
        // $("#application-status").append("All fields are required, Try again");
      }
  });
});
