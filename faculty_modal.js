// READ records
function readRecords() {


 var $container = $("#studentInfoDiv");
   $container.load("FacultyExcel.php");
    console.log("success");
}

// Add Record
function addRecord() {
    // get values
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var email = $("#email").val();
    var mobile = $("#mobile").val();
    var address = $("#address").val();
    var department = $("#department").val();

    // Add record


    tail=email.substring(email.length-8,email.length)
    if (tail=="@gsu.edu"){
    $.post("addFaculty.php", {
        first_name: first_name,
        last_name: last_name,
        email: email,
      	mobile: mobile,
      	address: address,
      	department: department
    }, function (data, status) {
        // close the popup
        $("#add_new_record_modal").modal("hide");
        console.log(data);
	console.log(status);
        // clear fields from the popup
        $("#first_name").val("");
        $("#last_name").val("");
        $("#email").val("");
	$("#mobile").val("");
	$("#address").val("");
	$("#department").val("");
    });
    readRecords();
    }
    else{
      $("#add_new_record_modal").modal("hide");
    }

}




function DeleteUser(id) {
    var conf = confirm("Are you sure, do you really want to delete a faculty? ");
    if (conf == true) {
        $.post("deleteFaculty.php", {
                id: id
            },
            function (data, status) {
                // reload Users by using readRecords();
                readRecords();
            }
        );
    }
}

function GetUserDetails(id) {
    // Add User ID to the hidden field for furture usage
    $("#hidden_user_id").val(id);
    $.post("readFacultyDetails.php", {
            id: id
        },
        function (data, status) {
            // PARSE json data
            var user = JSON.parse(data);
            // Assing existing values to the modal popup fields
            $("#update_first_name").val(user.FirstName);
            $("#update_last_name").val(user.LastName);
            $("#update_email").val(user.email);
            $("#update_mobile").val(user.mobile);
            $("#update_address").val(user.location);
            $("#update_department").val(user.department);

        }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}

function UpdateUserDetails() {
    // get values
    var first_name = $("#update_first_name").val();
    var last_name = $("#update_last_name").val();
    var email = $("#update_email").val();
    var mobile = $("#update_mobile").val();
    var address = $("#update_address").val();
    var department = $("#update_department").val();

    // get hidden field value
    var id = $("#hidden_user_id").val();

    // Update the details by requesting to the server using ajax
    $.post("updateFacultyDetails.php", {
            id: id,
            first_name: first_name,
            last_name: last_name,
            email: email,
	    mobile: mobile,
	    address:  address,
	    department: department
        },
        function (data, status) {
            // hide modal popup
            $("#update_user_modal").modal("hide");
            // reload Users by using readRecords();
            readRecords();
        }
    );
}

$(document).ready(function () {
    // READ recods on page load
    //readRecords();

});
