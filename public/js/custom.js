$(function () {
      $('#msgError').hide();
      $('#msgSuccess').hide();
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
      $('.sel').click(function(){
        $('.next_serv').show();
      })
      $('.ctn-btn').click(function(){
        activaTab($(this).data('tab'));
      });

      //get user details if booking page and auto fill sesion fields
      if($('#bookingForm') && $('#bookingForm').length) {
        $.ajax({
          url: "/user/me",
          type: "GET",
          dataType: 'JSON',
          success: function(user) {
            if(user.loggedIn) {
              console.log('user data ', user.data);
            }
          },
          error: function(error) {
            console.log('Can\'t get current user ', error);
          }
        })
      }


      var totalPrice = 0;
      var basicPrice = 0;
      //change price when service category checked
      $("input[name='service_categories[]']").change(function() {
        var category = $(this).val().toUpperCase() + '_PRICE';
        var envPrice = parseFloat($('#'+category).val()).toFixed(2);
        var quantity = parseFloat($('#service_quantity').val()).toFixed(2);
        var checkPrice = parseFloat(envPrice * quantity);
        if($(this).is(":checked")) {
          totalPrice = parseFloat(parseFloat(totalPrice) + parseFloat(checkPrice)).toFixed(2);          
          basicPrice = parseFloat(parseFloat(basicPrice) + parseFloat(envPrice)).toFixed(2);
        } else {
          totalPrice = parseFloat(parseFloat(totalPrice) - parseFloat(checkPrice)).toFixed(2);
          basicPrice = parseFloat(parseFloat(basicPrice) - parseFloat(envPrice)).toFixed(2);
        }

        $('.total_price').val(totalPrice);
        $('#main_price').val(basicPrice);
      });


      //signup from booking page
      $('#bookingSubmit').click(function(e) {
          e.preventDefault();
          var btn = $(this);
          var form = $('#bookingForm');
          var url = form.attr('action');
          btn.text('Please wait..');
          $.ajax({
                 type: "POST",
                 url: url,
                 data: form.serialize(), // serializes the form's elements.
                 success: function(response){
                    btn.text('Submit Payment');
                    console.log(response); // show response from the php script.
                    $('#msgSuccess').html(response.message);
                    $('#msgSuccess').show();
                    scrollToDiv('msgSuccess');
                    setTimeout(function() {
                      window.location.href = "{{ route('user_dashboard') }}";
                    },3000);
                 },
                 error: function(error) {
                  scrollToDiv('msgError');
                  btn.text('Submit Payment');
                  $('#msgError').html(error.responseJSON.message);
                  $('#msgError').show();
                  setTimeout(function() {
                    $('#msgError').fadeOut('slow');
                  },3000);
                 }
               });


      });
      //change total price
      $('#service_quantity').on('input', function() {
        $('.total_price').val($(this).val() * $('#main_price').val())
      })

      //open profile image modal
      $(".choose-file-btn").click(function() { 
          $("#upload").trigger('click');
      });

      //profile image upload
      var $uploadCrop = "";
      $('#upload').on('change', function() {

          var value = $(this).val(),
              file = value.toLowerCase(),
              extension = file.substring(file.lastIndexOf('.') + 1);

          $(".err").html("")
          let allowedExtensions = ['jpg', 'jpeg', 'png']
          if ($.inArray(extension, allowedExtensions) == -1) {
              $(".err").html("<p style='color:red;'>Please select only: jpg, jpeg, png format.</p>");
              return false;
          }

          $('#upload-demo').croppie('destroy');
          $('.upload-result').show();
          $uploadCrop = $('#upload-demo').croppie({
              enableExif: true,
              viewport: {
                  width: 200,
                  height: 200
              },
              boundary: {
                  width: 300,
                  height: 300
              }
          });

          var reader = new FileReader();
          reader.onload = function(e) {
              $uploadCrop.croppie('bind', {
                  url: e.target.result
              }).then(function() {
                  console.log('jQuery bind complete');
              });
          }
          reader.readAsDataURL(this.files[0]);
      });
      $('.upload-result').on('click', function(ev) {
          $uploadCrop.croppie('result', {
              type: 'canvas',
              size: 'viewport'
          }).then(function(resp) {
              $.ajax({
                  url: "{{ route('upload_profile_picture') }}",
                  type: "POST",
                  data: {
                      "image": resp
                  },
                  success: function(data) {
                      $("#edit-photo").modal("hide");
                      $(".profile-img img").attr('src', resp)
                      $('#upload-demo').croppie('destroy');
                      $('.upload-result').hide();
                      $('#edit-photo').modal('hide');
                  },
                  error: function(err) {
                      swal("Error!", "Please try again", "error");
                  }
              });
          });
      });
      //
      $('#service_laundress').change(function(){
        if($(this).val()) {
          $('#service_laundress_selected').val($('#service_laundress option:selected').html());
        }
      });

      $('#service_time').change(function(){
        if($(this).val()) {
          $('#service_time_selected').val($('#service_time option:selected').html());
        }
      });

      $('#service_day').change(function(){
        if($(this).val()) {
          $('#service_day_selected').val($('#service_day option:selected').html());
        }
      });
    });
    function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        scrollToDiv(tab);
    };

    function scrollToDiv(divId) {
      $('html, body').animate({
          scrollTop: $("#" + divId).offset().top - 300
      }, 500);
    }