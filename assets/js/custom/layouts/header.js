$(document).ready(function () {
    $('#header-logout').on('click', function () {
        var $logoutButton = $(this); // Cache the logout button

        // Disable the button to prevent multiple clicks
        $logoutButton.addClass('disabled');
        $logoutButton.css('pointer-events', 'none'); // Prevent interaction
        $logoutButton.text('Logging Out...'); // Optional: Provide feedback

        // Submit the form
        $('#logout-form').submit();
    });
});
