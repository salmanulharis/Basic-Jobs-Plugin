jQuery(document).ready(function($){

  $("#apply-button").click(function(){
        console.log("hi")
        $("#applicantsform").show();
    });

  $("#delete-button").click(function() {
        var names = $("#post_id").text();
        console.log(names);
        var id = $("#post_id").text();
        // var nonce = $(this).data('nonce');
        // var post = $(this).parents('.post:first');
        // console.log(post);
        $.ajax({
            type: 'post',
            url: settings.ajaxurl,
            data: {
                action: 'my_delete_post',
                // nonce: nonce,
                id: id
            },
            success: function( result ) {
                if( result == 'success' ) {
                    post.fadeOut( function(){
                        post.remove();
                    });
                }
            }
        });
        return false;
    });

  // Variable to hold request
  var request;


  // Bind to the submit event of our form
  $("#applicantsform").submit(function(event){

      $('#apply-button').click(function() {
          $("#applicantsform").hide();
          location.reload();
      });

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
      }else{
        $("#application-status").append("All fields are required, Try again");
      }
      $("#data-container").show();


      // Abort any pending request
      if (request) {

          request.abort();
      }
      // setup some local variables
      var $form = $(this);


      console.log($form);
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
                   alert('This is data returned from the server ' + data);
              }, 'json');

            }
        });


  });



});
