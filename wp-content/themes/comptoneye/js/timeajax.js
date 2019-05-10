jQuery(document).ready(function($) {
 
  $( "#datepicker" ).datepicker( { 
      beforeShowDay: function(date) {
        var day = date.getDay();
        return [(day != 0), ''];
    },
      onSelect: function(date, picker){
       var someNumbers = ["1:00pm", "2:00pm", "3:00pm", "4:00pm", "5:00pm"];
       var dateselect= date;
       
       //console.log("Event Triggered");
       jQuery.ajax({
            url: time_send_ajax.ajaxurl,
            type: 'GET',
            dataType: 'html',
            data: {
                action: 'cmt_time_send',
                post_date: dateselect,
            },
            success: function(data) {
                     console.log(response);
                     alert(response);
        }});
       
       for(var i=0;i<5;i++){  
        document.querySelector('.results').innerHTML += '<div class="timeholder"><a class="time"> ' + someNumbers[i] + '</a> </div>';
      }
      
     }
    } );




});