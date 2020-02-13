$(document).ready(function() {
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
});