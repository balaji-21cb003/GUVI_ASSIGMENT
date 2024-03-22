function hit() {
  $(document).ready(function () {
    var data = {
      name: $("#name").val(),
      email: $("#email").val(),
      password1: $("#password1").val(),
      password2: $("#password2").val(),
    };
    console.log(data.name);
    $.ajax({
      url: "php/register.php",
      type: "post",
      data: data,
      success: function (response) {
        console.log(data);
        location.replace("login.html");
        alert(response);
        if (response == "Login Successful") {
          window.location.reload();
        }
      },
    });
  });
}
