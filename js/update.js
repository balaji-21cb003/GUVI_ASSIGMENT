$(document).ready(function () {
  $("#updateForm").submit(function (event) {
    event.preventDefault(); // Prevent form submission

    // Get form data
    var id = $("#id").val();
    var name = $("#name").val();
    var email = $("#email").val();
    var dob = $("#dob").val();
    var phone = $("#phone").val();
    var address = $("#address").val();

    // Send AJAX request to update_data.php
    $.ajax({
      type: "POST",
      url: "update_data.php",
      data: {
        id: id,
        name: name,
        email: email,
        dob: dob,
        phone: phone,
        address: address,
      },
      success: function (response) {
        alert(response); // Show response message
      },
    });
  });
});
