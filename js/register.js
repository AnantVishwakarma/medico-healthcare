const displayError = document.querySelector(".display-error");

const sex = document.getElementById("registration-form").sex;
sex.style.color = "gray";
sex.options[1].style.color = "black";
sex.options[2].style.color = "black";

sex.addEventListener("change", function (event) {
    if (sex.value != "") sex.style.color = "black";
});

const dateOfBirth = document.getElementById("registration-form").dob;

dateOfBirth.addEventListener("focus", function (event) {
    if (this.value == "") {
        this.type = "date";
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, "0");
        let mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
        let yyyy = today.getFullYear();
        today = yyyy + "-" + mm + "-" + dd;
        //console.log(today);
        this.max = today;
    }
});

dateOfBirth.addEventListener("focusout", function (event) {
    if (this.value == "") {
        this.type = "text";
    }
});

function validateForm(form) {
    let name = form.name;
    let dob = form.dob;
    let sex = form.sex;
    let phno = form.phno;
    let email = form.email;
    let password = form.password;
    let confirm_password = form.confirm_password;

    if (
        validateName(name, 1, 50) &&
        validateDOB(dob) &&
        validateSex(sex) &&
        validateNumber(phno) &&
        validateEmail(email) &&
        validatePassword(password, confirm_password)
    ) {
        return true;
    }

    return false;
}

function validateName(name, min_length, max_length) {
    if (min_length <= name.value.length && name.value.length <= max_length) {
        return true;
    } else {
        displayError.innerHTML = `Name should be between ${min_length} and ${max_length} characters`;
        name.focus();
        return false;
    }
}

function validateDOB(dob) {
    if (dob.value) {
        return true;
    } else {
        displayError.innerHTML = "Please enter your date of birth";
        dob.focus();
        return false;
    }
}

function validateSex(sex) {
    if (sex.value == "") {
        displayError.innerHTML = "Please enter your sex";
        sex.focus();
        return false;
    } else {
        return true;
    }
}

function validateNumber(num) {
    const numberPattern = /^(\+91|0)?[7-9]{1}[0-9]{9}$/;
    if (num.value.match(numberPattern)) return true;
    else {
        displayError.innerHTML = `Not a valid Phone Number`;
        num.focus();
        return false;
    }
}

function validateEmail(email) {
    const emailPattern = /[a-z0-9.]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    if (email.value.match(emailPattern)) return true;
    else {
        displayError.innerHTML = "Not a valid email";
        email.focus();
        return false;
    }
}

function validatePassword(password, confirm_password) {
    //Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character:
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (password.value == "" && confirm_password.value == "") {
        displayError.innerHTML = "Please type the password";
        password.focus();
        return false;
    }
    
    if (password.value.match(passwordPattern)) {
        if(password.value == confirm_password.value)
        {
            return true;
        }
        else
        {
            displayError.innerHTML = "Passwords do not match";
            confirm_password.focus();
            return false;
        }
    } else {
        displayError.innerHTML = "Password must contain<br>minimum eight characters<br>an uppercase letter<br>a lowercase letter<br>a digit<br>a special character";
        password.focus();
        return false;
    }
}
