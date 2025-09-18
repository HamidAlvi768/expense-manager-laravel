$(document).ready(function() {
    "use strict";
    $(document).on('click', '#credTable tr', function () {
        $('#email').val($(this).find("td").eq(0).html());
        $('#password').val($(this).find("td").eq(1).html());
    });

    $('#login-button').on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        var $this = $(this);
        var $form = $this.closest('form');

        // Trigger validation
        if ($form.parsley().validate()) {
            // Disable the button to prevent multiple clicks
            $this.prop('disabled', true);
            $this.html('<i class="fas fa-spinner fa-spin mr-2"></i> Logging in...');

            // Submit the form after validation passes
            $form.submit();
        } else {
            // If validation fails, do nothing
            console.log('Validation failed. Spinner not triggered.');
        }
    });
});
