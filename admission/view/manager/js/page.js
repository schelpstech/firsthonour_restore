function load_signup_page() {
    $.ajax({
        url: '../../include/user/signup.php',
        success: function (data) {

            $("#section").html(data);
        }
    })
};

function load_signin_page() {
    $.ajax({
        url: '../../include/user/signin.php',
        success: function (data) {

            $("#section").html(data);
        }
    })
};

function load_recovery_page() {
    $.ajax({
        url: '../../include/user/recovery.php',
        success: function (data) {

            $("#section").html(data);
        }
    })
};


function checkpin() {
    var pina = document.getElementById("password").value;
    var pinb = document.getElementById("confirm").value;

    if (pina !== pinb) {
        alert("Password do not match");
        $("#password").val("");
        $("#confirm").val("");
    }
}

function deleteconfirm() {
    $("#confirm").val("");
}
