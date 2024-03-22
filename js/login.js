
function hit(){
  $(document).ready(function(){
    var data = {
      name: $("#name").val(),
      password1: $("#password1").val(),
    };
console.log(data);
    $.ajax({
      url: 'php/login.php',
      type: 'post',
      data: data,
      success:function(response){
        localStorage.setItem('name', data.name);
        localStorage.setItem('email', data.email);
        location.replace("profile.html");
        alert(response);
      }
    });
  });
}