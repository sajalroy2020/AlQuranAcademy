$(document).ready(function () {

    function teacherActiveCheck() {
        commonAjaxRequest('GET', $('#teacher-active-check').val(), activeDataResponse, activeDataResponse, {});
    }
    function activeDataResponse(response) {
        if (response.data.is_active == 0) {
            $('.fullscreen-popup').removeClass('d-none');
        }
    }
    // Run `teacherActiveCheck` every 2 minutes
    setTimeout(() => {
        teacherActiveCheck(); 
        setInterval(teacherActiveCheck, 120000);
    }, 120000);

    // Activate data save teacher
    $(document).on('click', '.teacher-active-btn', function () {
        const route = $('#teacher-active-route').val();
        commonAjaxRequest('GET', route,
            function teacherActiveResponse(response) {
                if (response.status === "success") {
                    $('.fullscreen-popup').addClass('d-none');
                }
            },
        );
    });
    
    
});
