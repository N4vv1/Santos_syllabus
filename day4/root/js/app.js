$(document).ready(function() {
    var templateData = {
        name: 'Ivan'
    };
    $.Mustache.options.warnOnMissingTemplates = true;
    $.Mustache.load('/templates/templates.html').done(function() {
        function clearPanel() {
            // You can put some code in here to do fancy DOM transitions, such as fade-out or slide-in.
        }

        Path.map("#/login").to(function() {
            $('#canvas').html('').append($.Mustache.render('loginPage', templateData));
            alert("Login!");
        });

        Path.map("#/signup").to(function() {
            alert("signup!");
        }).enter(clearPanel);

        Path.map("#/home").to(function() {
            alert("Home!");
        }).enter(clearPanel);

        Path.root("#/login");

        Path.listen();
    });
});