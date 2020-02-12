$(document).ready(function() {
  $('.dataTable').DataTable();

    $('.verify-user').click(function() {
	  //verify user
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
		  	console.log(id);
		  }
		});
	});
});