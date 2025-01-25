(function ($) {
    "use strict";

    // Handle button clicks
    let all_day = [];
    $('.btn[data-day]').on('click', function () {
        const day = $(this).data('day');
        const index = all_day.indexOf(day);
        if (index === -1) {
            all_day.push(day);
            $(this).addClass('btn-primary').removeClass('btn-outline-primary');
        } else {
            all_day.splice(index, 1);
            $(this).addClass('btn-outline-primary').removeClass('btn-primary');
        }
        $('#all-day').val(all_day);
        console.log($('#all-day').val());
        
    });

    // get teacher wise course
    $(document).ready(function () {
        $(document).on('change', '.getTeacherCourse', function() {
            commonAjaxRequest('GET', $('#get-filter-course-route').val(), dataResponse, dataResponse, { id: $(this).val() });
        });
    });
    function dataResponse(response) {
        $(".addCourse").html(response.responseText);
    }

    $("#classScheduleDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        ajax: $('#class-schedule-list-route').val(),
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
            {"data": "subject", "name": "courses.subject_name"},
            {"data": "day", "name": "day"},
            {"data": "time", "name": "time"},
            {"data": "status", "name": "status"},
            {"data": "action", searchable: false, responsivePriority:2},
        ],
      });

    //   add more time
    $(document).ready(function () {
        // Add More Button Click
        $(document).on('click', '.btn-add-more', function () {            
            // Create a new row with a delete button
            var newRow = `
                <div class="row align-items-end time-row">
                    <input type="hidden" name="class_slot_id[]" value="0">
                    <div class="col-5">
                        <div class="primary-form-group mt-2 pt-2">
                            <div class="primary-form-group-wrap">
                                <label class="form-label">Start Time<span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="start_time[]">
                            </div>
                            <div class="start_time"></div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="primary-form-group mt-2 pt-2">
                            <div class="primary-form-group-wrap">
                                <label class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="end_time[]">
                            </div>
                            <div class="end_time"></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="text-danger border-0 bg-transparent btn-delete-row mb-2" data-slotid="0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1h4a.5.5 0 0 1 0 1h-1v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2h-1a.5.5 0 0 1 0-1h4zm1-1h1a1 1 0 0 1 1 1v1H6V1a1 1 0 0 1 1-1zm-5 3h10v11a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3zM6 7.5a.5.5 0 0 1 1 0v4a.5.5 0 0 1-1 0v-4zm3 .5a.5.5 0 0 1 1 0v3a.5.5 0 0 1-1 0v-3z"/>
                            </svg>
                        </button>
                    </div>
                </div>`;
            
            // Append the new row to the container
            $('.time-container').append(newRow);
        });

        // Delete Row Button Click
        $(document).on('click', '.btn-delete-row', function () {
            const slotId = $(this).data('slotid');
            const $row = $(this).closest('.time-row');
            const message = "Slot deleted successfully.";
        
            if (slotId != 0) {
                commonAjaxRequest('GET', $('#check-slot-data').val(), 
                    function dataSlotResponse(response) {
                        if (response.status === 200) {
                            $row.remove();
                            alertCommonAjaxMessage('success', message);
                        } else if (response.status === 400) {
                            const errorMessage = response.message || 'An error occurred.';
                            alertCommonAjaxMessage('error', errorMessage);
                        }
                    },
                    function errorCallback(error) {
                        const errorMessage = error.responseJSON?.message || 'An error occurred.';
                        alertCommonAjaxMessage('error', errorMessage);
                    }, 
                    { slot_id: slotId }
                );
            } else {
                $row.remove(); 
            }
        });

    });

    // check already schedule 
    $(document).ready(function () {
        $(document).on('change', '.select-day', function() {
            const selected_day = $(this).val();
            const old_day = $('.select-day').data('day');
            const teacherId = $('.select-day').data('teacherid');   
            
            $('.save-button').prop('disabled', false);
            $('.error-message').html('');

            if (selected_day != old_day) {
                commonAjaxRequest('GET', $('#check-day-list-route').val(), dataResponseDay, dataResponseDay, {selected_day: selected_day, teacher_id: teacherId});
            }
        });
        function dataResponseDay(response) {
            const errorMessage = response.responseJSON?.message;
            if (response.status == 400) {
                alertCommonAjaxMessage('error', errorMessage);
                $('.save-button').prop('disabled', true);
                $('.error-message').html(errorMessage);
            }
        }
    });

})(jQuery)
