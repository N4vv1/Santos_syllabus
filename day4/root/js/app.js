$(document).ready(function () {
  var templateData = { name: "Ivan" };

  $.Mustache.options.warnOnMissingTemplates = true;

  $.Mustache.load("./templates/templates.html").done(function () {
    function clearPanel() {}

    /* LOGIN */
    Path.map("#/login").to(function () {
      $("#canvas").html($.Mustache.render("loginPage"));

      $("#loginForm").off().on("submit", function (e) {
          e.preventDefault();

          $.ajax({
            url: "../api/functions/login.php",
            type: "POST",
            data: {
              uname: this.uname.value.trim(),
              psw: this.psw.value
            },
            success: function(res){

              res = res.trim();

              if(res === "ok"){
                location.hash = "#/home";
              }else{
                alert("Invalid username/email or password");
              }
            },
            error:function(){
              alert("Server error");
            }
          });
        });
    });

    /* SIGNUP */
    Path.map("#/signup").to(function () {
      $("#canvas").html($.Mustache.render("signupPage"));

      $("#myForm").off().on("submit", function (e) {
          e.preventDefault();

          const fd = new FormData(this);

          $.ajax({
            url: "../api/functions/signup.php",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,

            success: function(res){
              res = res.trim();

              if(res === "ok"){
                alert("Signup successful!");
                location.hash = "#/login";
              }
              else if(res === "exists"){
                alert("Username or email already exists");
              }
              else{
                alert("Signup failed: " + res);
                console.log(res);
              }
            },

            error: function(){
              alert("Server error");
              console.log(xhr.responseText);
            }
          });

        });

    });

    /* HOME */
    Path.map("#/home").to(function () {

      $.get("../api/functions/session.php", function(res){

        if(res.trim() !== "ok"){
          location.hash = "#/login";
          return;
        }

        $("#canvas").html($.Mustache.render("homePage"));

        $("#logoutBtn").off().click(function(e){
          e.preventDefault();

          $.get("../api/functions/logout.php", function(){
            location.hash="#/login";
          });
        });
      });
    });

  /* ✅ ACCOUNT ROUTE MUST BE INSIDE HERE */
  Path.map("#/account").to(function () {

    $.get("../api/functions/session.php", function(res){

      if(res.trim()!=="ok"){
        location.hash="#/login";
        return;
      }

      $("#canvas").html($.Mustache.render("accountPage"));

      $.get("../api/functions/get_user.php", function(user){

        let u = JSON.parse(user);

        $("#acc_fullname").val(u.fullname);
        $("#acc_nickname").val(u.nickname);
        $("#acc_address").val(u.address);
        $("#acc_birthday").val(u.birthday);
        $("#acc_phone").val(u.phone);
        $("#acc_username").val(u.username);
        $("#acc_email").val(u.email);

      });

      $("#accountForm").on("submit", function(e){
        e.preventDefault();

        $.post("../api/functions/update_user.php",
          $(this).serialize(),
          function(res){
            $("#accountMsg").html(
              "<div class='alert alert-info'>"+res+"</div>"
            );
          }
        );
      });

      $("#deleteAccountBtn").click(function(){

        if(!confirm("Delete your account permanently?")) return;

        $.post("../api/functions/delete_user.php", function(){
          location.hash="#/signup";
        });

      });

    });

  });


  /* START ROUTER AFTER ALL MAPS */
  Path.root("#/login");
  Path.listen();

});
});