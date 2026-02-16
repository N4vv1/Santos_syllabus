$('#myForm').submit(function(e) {
    e.preventDefault();

    const fullname = $('#fullname').val();
    const nname = $('#nname').val();
    const address = $('#address').val();
    const bdate = $('#bdate').val();
    const tel = $('#tel').val();
    const uname = $('#uname').val();
    const email = $('#email').val();
    const psw = $('#psw').val();

    const outputDiv = $('#outputContainer');
    outputDiv.html(`
    <div style="border: 1px solid #ccc; padding: 10px; margin-top10px;">
        <strong>Fullname: </strong> ${fullname} <br>
        <strong>Nickname: </strong> ${nname} <br>
        <strong>Address: </strong> ${address} <br>
        <strong>Birthday: </strong> ${bdate} <br>
        <strong>Contact no: </strong> ${tel} <br>
        <strong>Username: </strong> ${uname} <br>
        <strong>Email: </strong> ${email} <br>
        <strong>Password: </strong> ${psw} <br>
    </div>
    `);
    
});