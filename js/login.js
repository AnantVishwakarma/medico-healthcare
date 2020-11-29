const displayError = document.querySelector(".display-error");
// if(displayError.innerHTML="")
// {
//     displayError.style.display = "none";
// }

function validateForm(form)
{
    let email = form.email;
    let password = form.password;
    if(validateEmail(email) && validatePassword(password))
    {        
        return true;
    }
    
    return false;
}

function validateEmail(email)
{
    const emailPattern = /[a-z0-9.]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    if(email.value.match(emailPattern)) return true;
    else {
        displayError.innerHTML =  "Not a valid email";
        email.focus();
        return false;
    }
}

function validatePassword(password) {
    if(password.value == "")
    {
        displayError.innerHTML =  "Please type the password";
        password.focus();
        return false;
    }
    else
    {
        return true;
    }
}