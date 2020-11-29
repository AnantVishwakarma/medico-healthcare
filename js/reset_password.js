let displayError = document.getElementById("error_message");

function validateForm(form)
{
    let password = form.password;
    let confirm_password = form.confirm_password;

    if(password.value === "")
    {
        displayError.innerHTML = "Please type the password";
        password.focus();
        return false;
    }
    else if(password.value !== confirm_password.value)
    {
        displayError.innerHTML = "Passwords do not match";
        confirm_password.focus();
        return false;
    }
    else
    {
        return true;
    }
}