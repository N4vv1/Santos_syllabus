$(document).ready(function () {
  var templateData = { name: "Ivan" };

  $.Mustache.options.warnOnMissingTemplates = true;

  $.Mustache.load("./templates/templates.html").done(function () {
    function clearPanel() {}

    /* LOGIN */
    Path.map("#/login").to(function () {
      $("#canvas").html($.Mustache.render("loginPage"));

      $("#loginForm")
        .off()
        .on("submit", function (e) {
          e.preventDefault();

          $.ajax({
            url: "/Santos_syllabus/day4/api/login",
            type: "POST",

            data: {
              username: this.uname?.value.trim(),
              password: this.psw?.value,
            },
            success: function (res) {
              res = res.trim();

              if (res === "ok") {
                location.hash = "#/home";
              } else {
                alert("Invalid username/email or password");
              }
            },
            error: function () {
              alert("Server error");
            },
          });
        });
    });

    /* SIGNUP */
    Path.map("#/signup").to(function () {
      
      $("#canvas").html($.Mustache.render("signupPage"));

      $("#myForm")
        .off()
        .on("submit", function (e) {
          e.preventDefault();

          const fd = new FormData(this);

          $.ajax({
            url: "/Santos_syllabus/day4/api/signup",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,

            success: function (res) {
              res = res.trim();

              if (res === "ok") {
                alert("Signup successful!");
                location.hash = "#/login";
              } else if (res === "exists") {
                alert("Username or email already exists");
              } else {
                alert("Signup failed: " + res);
                console.log(res);
              }
            },

            error: function (xhr) {
              alert("Server error");
              console.log(xhr.status);
              console.log(xhr.responseText);
            },
          });
        });
    });

    /* HOME */
    Path.map("#/home").to(function () {
      $.get("/Santos_syllabus/day4/api/session").done(function (res) {
        if (res.trim() !== "ok") {
          location.hash = "#/login";
          return;
        }

        $("#canvas").html($.Mustache.render("homePage"));

        const fullHash = window.location.hash;   // ex: #/home/foods
        const parts = fullHash.split("/");
        
        if (parts.length === 2 && parts[1] === "home") {
          window.scrollTo({ top: 0, behavior: "smooth" });
        }

        if (parts.length === 3) {
          const section = parts[2];

          setTimeout(function () {
            const el = document.getElementById(section);
            if (el) el.scrollIntoView({ behavior: "smooth" });
          }, 100);
        }

        $("#logoutBtn")
          .off()
          .click(function (e) {
            e.preventDefault();

            $.get("/Santos_syllabus/day4/api/logout", function () {
              location.hash = "#/login";
            });
          });
      });
    });

    Path.map("#/home/:section").to(function () {
      Path.dispatch("#/home");
    });

    /* ✅ ACCOUNT ROUTE MUST BE INSIDE HERE */
    Path.map("#/account").to(function () {
      $.get("/Santos_syllabus/day4/api/session").done(function (res) {
        if (res.trim() !== "ok") {
          location.hash = "#/login";
          return;
        }

        // ✅ GET USER DATA FIRST
        $.getJSON("/Santos_syllabus/day4/api/user", function (u) {
          if (u.error) {
            alert(u.error);
            location.hash = "#/login";
            return;
          }

          // ✅ RENDER TEMPLATE WITH DATA
          $("#canvas").html($.Mustache.render("accountPage", u));

          // ✅ ATTACH EVENTS AFTER RENDER
          $("#accountForm")
            .off()
            .on("submit", function (e) {
              e.preventDefault();

              $.post(
                "/Santos_syllabus/day4/api/update-user",
                $(this).serialize(),
                function (res) {
                  alert(res);
                },
              );
            });

          $("#deleteAccountBtn")
            .off()
            .click(function () {
              if (!confirm("Delete your account permanently?")) return;

              $.post("/Santos_syllabus/day4/api/delete-user", function () {
                location.hash = "#/login";
              });
            });
        });
      });
    });

    Path.root("#/login");
    Path.listen();
  });
});
