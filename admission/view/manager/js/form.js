function application_begins() {
    let surname = document.getElementById("surname").value;
    let firstname = document.getElementById("firstname").value;
    let othername = document.getElementById("othername").value;
    let pros_class = document.getElementById("pros_class").value;
    let action = 'application_begin';

    if (surname != "") {
    if (firstname != "") {   
    if (othername != "") {
        
    if (pros_class != "") {
       
    

    $.ajax({
        url: '../../app/formhandler.php',
        method: 'POST',
        data: {
            firstname: firstname,
            othername: othername,
            surname: surname,
            pros_class: pros_class,
            action: action
        },
        success: function (data) {
            $("#feedback").html(data);
        }
    })
}else{
    alert("Prospective Class is required. Select Class");
}
}else{
    alert("Othername is required");
}
    }else{
        alert("Firstname is required");
    }
}else{
    alert("Surname is required");
}
}
