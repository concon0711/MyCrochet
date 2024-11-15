jQuery(document).ready(function($) {
    // DataTables initialization
    $('#membership-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true
    });

    // Ajax form submission
    $('#add-row-form').on('submit', function(e) {
        e.preventDefault();

        var rowNumber = $('#row_number').val(); // Make sure this matches your input ID
    	var stitchType = $('#stitch_type').val(); // Make sure this matches your input ID
    	var projectName = $('#project_name').val(); // Make sure this matches your 

        $.ajax({
   		 type: 'POST',
   		 url: ajax_object.ajax_url,
   		 data: {
        		action: 'add_row',
       			 nonce: ajax_object.add_row_nonce, // Ensure this is correctly generated
       			 row_number: rowNumber,
       			 stitch_type: stitchType,
       			 project_name: projectName
   		 },
   		 success: function(response) {
       			 alert('Row added successfully!');
       			 updateTable(); // Optional function to update table
   		 },
   		 error: function(error) {
       			 console.log(error);
       			 alert('Error adding row.');
   		 }
	});

    });

    // Function to update DataTable after adding a row
    function updateTable() {
        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url,
            data: {
                action: 'get_crochet_data' // Action to retrieve updated data
            },
            success: function(response) {
                $('#membership-table').DataTable().destroy(); // Destroy old table
                $('#membership-table tbody').html(response); // Update table body
                $('#membership-table').DataTable({ // Re-initialize DataTable
                    paging: true,
                    searching: true,
                    ordering: true,
                    responsive: true
                });
            },
            error: function(error) {
                console.log(error);
                alert('Error updating table.');
            }
        });
    }
});

