(function ($) {
    "use strict";

    $(document).ready(function () {

        let getDate = '';
        let courseId = 0;
        let teacherId = 0;

        // get student wise course
        $(document).on('change', '.getTeacherCourse', function() {
            commonAjaxRequest('GET', $('#get-filter-course-route').val(), dataResponse, dataResponse, { id: $(this).val() });
        });
        function dataResponse(response) {
            $(".addCourse").html(response.responseText);
        }

        // get date
        $(document).on('change', '.filterTeachersDate', function() {
            getDate = $(this).val();
            if (getDate != '' && courseId != 0) {
                commonAjaxRequest('GET', $('#get-filter-teacher').val(), teacherDataResponse, teacherDataResponse, { date: getDate, course_id: courseId });
            }
        });
        //get course wise teacher
        $(document).on('change', '#getFilterCourse', function() {
            courseId = $(this).val();
            if (getDate != '' && courseId != 0) {
                commonAjaxRequest('GET', $('#get-filter-teacher').val(), teacherDataResponse, teacherDataResponse,{ date: getDate, course_id: courseId });
            }
        });
        function teacherDataResponse(response) {
            $(".showCourseTeacher").html(response.responseText);            
        }

         //get teacher class list
         $(document).on('change', '#getTeacherId', function() {
            teacherId = $(this).val();
            if (getDate != '' && courseId != 0 && teacherId != 0) {
                // commonAjaxRequest('GET', $('#get-teacher-class-list').val(), teacherClassDataResponse, teacherClassDataResponse,{ date: getDate, teacher_id: teacherId });
            }
        });

    });
    

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
            {"data": "teacher_name", "name": "users.teacher_name"},
            {"data": "date", "name": "date"},
            {"data": "start_time", "name": "start_time"},
            {"data": "end_time", "name": "end_time"},
            {"data": "status", "name": "status"},
            {"data": "action", searchable: false, responsivePriority:2},
        ],
      });

})(jQuery)
