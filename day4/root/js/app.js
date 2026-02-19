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

          const uname = this.uname.value.trim();
          const psw = this.psw.value;

          let users = JSON.parse(localStorage.getItem("users") || "[]");

          const found = users.find(
            (u) => (u.uname === uname || u.email === uname) && u.psw === psw,
          );

          if (!found) {
            alert("Invalid username/email or password");
            return;
          }

          localStorage.setItem("sessionUser", JSON.stringify(found));

          location.hash = "#/home";
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
          const user = {};

          for (const [k, v] of fd.entries()) user[k] = v.trim();

          let users = JSON.parse(localStorage.getItem("users") || "[]");

          if (users.find((u) => u.uname === user.uname)) {
            alert("Username already exists");
            return;
          }

          users.push(user);
          localStorage.setItem("users", JSON.stringify(users));

          alert("Signup successful!");
          location.hash = "#/login";
        });
    });

    /* HOME */
    Path.map("#/home").to(function () {
      const session = localStorage.getItem("sessionUser");

      if (!session) {
        location.hash = "#/login";
        return;
      }

      const user = JSON.parse(session);

      $("#canvas").html($.Mustache.render("homePage", user));

      $("#logoutBtn").click(function (e) {
        e.preventDefault();
        localStorage.removeItem("sessionUser");
        location.hash = "#/login";
      });
    });

    Path.root("#/login");
    Path.listen();
  });
});
