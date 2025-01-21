$(document).ready(function () {
    let all_day = [];

    // Handle button clicks
    $('.btn[data-day]').on('click', function () {
        const day = $(this).data('day'); // Get the value of data-day
        const index = all_day.indexOf(day);

        if (index === -1) {
            // If the day is not in the array, add it
            all_day.push(day);
            $(this).addClass('btn-primary').removeClass('btn-outline-primary'); // Highlight the button
        } else {
            // If the day is in the array, remove it
            all_day.splice(index, 1);
            $(this).addClass('btn-outline-primary').removeClass('btn-primary'); // Reset the button style
        }        

        // Call the commonAjaxRequest function with updated day_list 
        commonAjaxRequest('GET', $('#get-filter-schedule').val(), classDataResponse, classDataResponse, { day_list: all_day } );
        $(".showClassSlot").html('');          
        function classDataResponse(response) {
            console.log('response', response);
            $(".showClassSlot").html(response.responseText);          
        }
    });
});
