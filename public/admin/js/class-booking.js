(function ($) {
    "use strict";

    // data table 
    $("#classBookingDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        ajax: $('#class-booking-list-route').val(),
        language: {
			paginate: {
				previous: "<i class='fa-solid fa-angles-left'></i>",
				next: "<i class='fa-solid fa-angles-right'></i>",
			},
			searchPlaceholder: "Search...",
			search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
		},
		dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"tableSearch float-start"f>><"col-sm-6"<"tableLengthInput float-end"l>>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
		columns: [
            {"data": "student_name", "name": "users.student_name"},
            {"data": "teacher_name", "name": "users.teacher_name"},
            {"data": "subject", "name": "courses.subject"},
            {"data": "day", "name": "class_schedules.day"},
            {"data": "time", "name": "time"},
            {"data": "status", "name": "status"},
            {"data": "action", searchable: false, responsivePriority:2},
        ],
      });

    $(document).ready(function () {

        let getDay = '';
        let courseId = 0;
        let teacherId = 0;

        // get student wise course
        $(document).on('change', '.getTeacherCourse', function() {
            commonAjaxRequest('GET', $('#get-filter-course-route').val(), dataResponse, dataResponse, { id: $(this).val() });
        });
        function dataResponse(response) {
            $(".addCourse").html(response.responseText);
            $(".showClassSlot").html('');    
        }

        // get date
        $(document).on('change', '.filterTeachersDate', function() {
            getDay = $(this).val();
            if (getDay != '' && courseId != 0) {
                commonAjaxRequest('GET', $('#get-filter-teacher').val(), teacherDataResponse, teacherDataResponse, { day: getDay, course_id: courseId });
            }
            $(".showClassSlot").html('');    
        });
        //get course wise teacher
        $(document).on('change', '#getFilterCourse', function() {
            courseId = $(this).val();
            if (getDay != '' && courseId != 0) {
                commonAjaxRequest('GET', $('#get-filter-teacher').val(), teacherDataResponse, teacherDataResponse,{ day: getDay, course_id: courseId });
            }
        });
        function teacherDataResponse(response) {
            $(".showCourseTeacher").html(response.responseText);            
        }

         //get teacher class list
         $(document).on('change', '#getTeacherId', function() {
            teacherId = $(this).val();
            if (getDay != '' && courseId != 0 && teacherId != 0) {
                commonAjaxRequest('GET', $('#get-teacher-class-list').val(), teacherClassDataResponse, teacherClassDataResponse,{ day: getDay, teacher_id: teacherId });
            }
        });
        function teacherClassDataResponse(response) {
            $(".showClassSlot").html(response.responseText);          
        }

        // ============= Slot Id push In input value =============
        $(document).on('click', '.class-slot-button', function () {
            const button = $(this);
            const slotId = button.data('id');
            const scheduleId = button.data('scheduleid');

                $('.class-slot-button')
                .removeClass('btn-warning')
                .addClass('btn-outline-primary');

                button.removeClass('btn-outline-primary').addClass('btn-warning');
    
            $('#class_slot_id').val(slotId);
            $('#class_schedule_id').val(scheduleId);
        });

    });

})(jQuery)
