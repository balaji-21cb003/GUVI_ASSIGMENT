$(document).ready(function () {
  $("#profileForm").submit(function (event) {
    event.preventDefault();
    var formData = {
      name: $("#name").val(),
      email: $("#email").val(),
      dob: $("#dob").val(),
      phone: $("#phone").val(),
      address: $("#address").val(),
    };

    $.ajax({
      type: "POST",
      url: "php/profile.php", 
      data: formData,
      success: function (response) {
        alert(response);
        window.location.href = "display.html";
      },
      error: function (error) {
        alert("Failed to save profile. Please try again.");
      },
    });
  });
});
