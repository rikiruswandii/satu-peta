var $m = jQuery.noConflict();

$m(document).ready(function () {
    function initValidation() {
        var $myInput = $m("#password");
        var $retype = $m("#password_confirmation");
        var $message = $m("#message");
        var $feedback = $m("#feedback");
        var $letter = $m("#letter");
        var $capital = $m("#capital");
        var $number = $m("#number");
        var $symbol = $m("#symbol");
        var $length = $m("#length");

        if (!$myInput.length) {
            console.warn("Elemen #password tidak ditemukan");
            return;
        }

        if ($myInput.length) {
            $myInput.on("focus", function () {
                if ($message.length) $message.show();
            });

            $myInput.on("blur", function () {
                if ($message.length) $message.hide();
            });

            $myInput.on("keyup", function () {
                var value = $myInput.val();
                var lowerCaseLetters = /[a-z]/g;
                var upperCaseLetters = /[A-Z]/g;
                var numbers = /[0-9]/g;
                var symbols = /[!$#%@]/g;

                toggleValidation($letter, lowerCaseLetters.test(value));
                toggleValidation($capital, upperCaseLetters.test(value));
                toggleValidation($number, numbers.test(value));
                toggleValidation($symbol, symbols.test(value));
                toggleValidation($length, value.length >= 8);
            });
        }

        if ($retype.length) {
            $retype.on("focus", function () {
                if ($feedback.length) $feedback.show();
            });

            $retype.on("blur", function () {
                if ($feedback.length) $feedback.hide();
            });
        }

        function toggleValidation($element, condition) {
            if ($element.length) {
                $element.toggleClass("valid", condition);
                $element.toggleClass("invalid", !condition);
            }
        }
    }

    // Tunggu hingga elemen tersedia, lalu jalankan initValidation
    setTimeout(initValidation, 300);
});
