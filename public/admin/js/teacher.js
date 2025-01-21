(function ($) {
    "use strict";

    // multiple-select js 
    $(document).ready(function () {
        $( '.multiple-select-clear-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
            allowClear: true,
        });

        // get country wise state
        $(document).on('change', '.getCountryState', function() {
            commonAjaxRequest('GET', $('#get-state-route').val(), dataResponse, dataResponse, { id: $(this).val() });
        });
        function dataResponse(response) {
            $(".addState").html(response.responseText);
        }
    });
        
    // teacher active api call
    // function teacherActiveCheck() {
    //     commonAjaxRequest('GET', $('#teacher-list-route').val(), activeDataResponse, activeDataResponse, {});
    // }
    // setTimeout(() => {
    //     teacherActiveCheck(); 
    //     setInterval(teacherActiveCheck, 180000);
    // }, 180000);

    // function activeDataResponse(response) {
        $("#teacherDataTable").DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            searching: true,
            ajax: $('#teacher-list-route').val(),
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
                {"data": "image", "name": "image"},
                {"data": "name", "name": "name"},
                {"data": "email", "name": "email"},
                {"data": "phone", "name": "phone"},
                {"data": "action", searchable: false, responsivePriority:2},
            ],
        });
    // }

    // $(document).ready(function () {
    //     function teacherActiveCheck() {
    //         commonAjaxRequest('GET', $('#teacher-list-route').val(), activeDataResponse, activeDataResponse, {});
    //     }
        
    //     // Run `teacherActiveCheck` every 3 minutes
    //     setTimeout(() => {
    //         teacherActiveCheck(); 
    //         setInterval(teacherActiveCheck, 180000);
    //     }, 180000);
    // });
    
    // function activeDataResponse(response) {
    //     if ($.fn.DataTable.isDataTable("#teacherDataTable")) {
    //         $("#teacherDataTable").DataTable().ajax.reload();
    //     } else {
    //         $("#teacherDataTable").DataTable({
    //             pageLength: 10,
    //             ordering: false,
    //             serverSide: true,
    //             processing: true,
    //             searching: true,
    //             ajax: {
    //                 url: $('#teacher-list-route').val(),
    //                 type: 'GET'
    //             },
    //             language: {
    //                 paginate: {
    //                     previous: "<i class='fa-solid fa-angles-left'></i>",
    //                     next: "<i class='fa-solid fa-angles-right'></i>"
    //                 },
    //                 searchPlaceholder: "Search...",
    //                 search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>"
    //             },
    //             dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"tableSearch float-start"f>><"col-sm-6"<"tableLengthInput float-end"l>>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
    //             columns: [
    //                 {"data": "image", "name": "image"},
    //                 {"data": "name", "name": "name"},
    //                 {"data": "email", "name": "email"},
    //                 {"data": "phone", "name": "phone"},
    //                 {"data": "action", searchable: false, responsivePriority: 2}
    //             ]
    //         });
    //     }
    // }
    
    // teacherActiveCheck();

})(jQuery)
