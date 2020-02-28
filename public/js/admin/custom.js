$(document).ready(function() {
	$.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  	});
  $('.dataTable').DataTable();

    $('.verify-user').click(function() {
	  //verify user
	  var btn = $(this);
	  swal({
	  	  title: 'Confirm User',
	 	  text: "This user will be confirmed.",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
		   	let id = $(this).data('id');
		  	$.ajax({
		  		url: "/user/verify/" + id,
		  		type: 'GET',
		  		success: function(resposne) {
		  			if(resposne.success) {
		  				btn.removeClass('btn-danger');
		  				btn.addClass('btn-success');
		  				btn.html('<i class="fas fa-check"></i>');
		  			} else {
		  				swal("Error!", resposne.message, "error");	
		  			}
		  		},
		  		error: function(error) {
		  			console.log('error ', error);
		  			swal("Error!", error, "error");
		  		}
		  	})
		  }
		});
	});

	$('#confirmPayment').click(function() {
	  //verify user
	  var btn = $(this);
	  btn.attr('disabled', 'true');
	  swal({
	  	  title: 'Confirm Payment',
	 	  text: "You are going to pay $" + btn.data('amount'),
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((pay) => {
		  if (pay) {
		  	let id = btn.data('id');
		  	$.ajax({
		  		url: "/admin/confirm-payment/" + id,
		  		type: 'GET',
		  		success: function(resposne) {
		  			if(resposne.success) {
		  				btn.html('Confirm Payment - $0');
		  				swal("Paid", "You have paid the amount to provider,", "success");	
		  			} else {
		  				swal("Error!", resposne.message, "error");	
		  			}
		  		},
		  		error: function(error) {
		  			swal(error.status.toString(), error.responseJSON.message, "error");
		  		}
		  	})
		  }
		});
	});
});