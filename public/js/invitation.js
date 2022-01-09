const INPUT_PHONE = document.querySelector("#phone");
const RESULT = $("#emailHelpBlock");
const NAME_HELPBLOCK = $('#nameHelpBlock')
const PHONE_HELPBLOCK = $('#phoneHelpBlock')
const NAME_INPUT = $(".name-input")
const PHONE_INPUT = $(".phone-input")
const REGEX_LETTER = /^[a-zA-Z\s]*$/;
const REGEX_NUMBER = /^[0-9]*$/;
const REGEX_EMAIL = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

let check = [true, false, false, false]

let getDialCode = () => {
    $("#phone").val(`${iti.getSelectedCountryData().dialCode}`);
}

let validate = () => {
    const EMAIL_VALUE = $("#email").val();
    if (validateEmail(EMAIL_VALUE)) {
        RESULT.text("Your email is valid")
        check[0] = true
        checkStatus()
    } else {
        RESULT.text("Your email is not valid")
        check[0] = false
        checkStatus()
    }
    return false;
}

let validateEmail = (email) => {
    return REGEX_EMAIL.test(email);
}

let showPopUp = () => {
    Swal.fire({
        icon: 'success',
        title: 'Registration Success!',
        showConfirmButton: true,
        confirmButtonColor: '#3085d6',
        text: "Make sure to check your email to verify your account.",
    })
}

let showMailExist = () => {
    Swal.fire({
        icon: 'success',
        title: 'Registration Failed!',
        showConfirmButton: true,
        confirmButtonColor: '#3085d6',
        text: `Your Email "${EMAIL_VALUE}" already exists!"`,
    })
}

let allLetter = (inputtxt) => {
    if (inputtxt.value.match(REGEX_LETTER)) {
        NAME_HELPBLOCK.text = "Numbers Not Allowed"
        NAME_HELPBLOCK.addClass('d-none')
        check[1] = true
        checkStatus()
        return true
    }
    else {
        NAME_HELPBLOCK.text = "Numbers Not Allowed"
        NAME_HELPBLOCK.removeClass('d-none')
        Swal.fire({
            icon: 'error',
            title: 'Numbers Not Allowed!',
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            text: " Please Check Your Input!",
        })
        NAME_INPUT.val("")
        check[1] = false
        checkStatus()
        return false;
    }

}

let allNumber = (inputnbr) => {
    if (inputnbr.value.match(REGEX_NUMBER)) {
        PHONE_HELPBLOCK.text = "Numbers Not Allowed"
        $('#phoneHelpBlock').addClass('d-none')
        check[2] = true
    }
    else {
        PHONE_HELPBLOCK.text = "Numbers Not Allowed"
        PHONE_HELPBLOCK.removeClass('d-none')
        Swal.fire({
            icon: 'error',
            title: 'Letter Not Allowed!',
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            text: " Please Check Your Input!",
        })
        PHONE_INPUT.val("")
        check[2] = false
        return false;
    }
    checkStatus()
}

let checkStatus = () => {
    if (check.indexOf(false) == -1) {
        $('.btn-registrasi').prop('disabled', false)
    } else {
        $('.btn-registrasi').prop('disabled', true)
    }

}

let iti = intlTelInput(INPUT_PHONE, {
    utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js",
});

$(document).ready(() => {
    iti.setCountry("id");
    getDialCode();
});

INPUT_PHONE.addEventListener("countrychange", function () {
    getDialCode();
});




$("#selectAgree").change(function () {
    if (this.checked) {
        check[3] = true;
    } else {
        check[3] = false;
    }
    validate()
});
