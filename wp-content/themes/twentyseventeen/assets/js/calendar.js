jQuery(document).ready(function($)
{
$(".tfdate").datepicker({
    dateFormat: 'D, M d, yy',
    showOn: 'button',
    buttonImage: '/assests/js/calendar.jpeg',
    buttonImageOnly: true,
    numberOfMonths: 1

    });
});