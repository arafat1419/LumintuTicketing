$('.btn-email').click(function() {
    $('.email-form').addClass('d-none')
    $('.password-form').removeClass('d-none')
})

$('.btn-reset').click(function() {
    $('.email-form').removeClass('d-none')
    $('.password-form').addClass('d-none')
})

$('.password').on('input', function(){
    if($('.password').val().length > 8) {
        $('#passwordHelpBlock').addClass('d-none')
    } else {
        $('#passwordHelpBlock').removeClass('d-none')
    }
})

$('.confirm').on('input', function(){
    if($('.confirm').val().length > 8) {
        $('#confirmHelpBlock').addClass('d-none')
    } else {
        $('#confirmHelpBlock').removeClass('d-none')
    }
})

// Function untuk ShowHide Password
function password_show_hide() {
    var x = document.querySelector(".password");
    var show_eye = document.getElementById("show_eye_password");
    var hide_eye = document.getElementById("hide_eye_password");
    hide_eye.classList.remove("d-none");
    if (x.type === "password") {
      x.type = "text";
      show_eye.style.display = "none";
      hide_eye.style.display = "block";
    } else {
      x.type = "password";
      show_eye.style.display = "block";
      hide_eye.style.display = "none";
    }
}

// Function untuk ShowHide Confirm Password
function confirm_show_hide() {
    var x = document.querySelector(".confirm");
    var show_eye = document.getElementById("show_eye");
    var hide_eye = document.getElementById("hide_eye");
    hide_eye.classList.remove("d-none");
    if (x.type === "password") {
      x.type = "text";
      show_eye.style.display = "none";
      hide_eye.style.display = "block";
    } else {
      x.type = "password";
      show_eye.style.display = "block";
      hide_eye.style.display = "none";
    }
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validate() {
    const result = $("#emailHelpBlock");
    const email = $("#email").val();
  
    if (validateEmail(email)) {
        result.text("Your email is valid")
    } else {
        result.text("Your email is not valid")
    }
    return false;
  }