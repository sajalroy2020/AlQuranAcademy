$(document).ready(function () {

    function teacherActiveCheck() {
        commonAjaxRequest('GET', $('#teacher-active-route').val(), activeDataResponse, activeDataResponse, {});
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
    
});
