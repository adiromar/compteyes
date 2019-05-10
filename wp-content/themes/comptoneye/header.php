<html>
<head>
<?php wp_head(); 
?>

<!-- 
<link href ="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.3.0/fullcalendar.min.css" rel ='stylesheet'/>
<link href ="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.3.0/fullcalendar.print.css" rel ='stylesheet'/>
<script src ="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.3.0/fullcalendar.min.js" rel ='stylesheet'> </script> -->
<link href='<?php echo get_template_directory_uri() ?>/fullcalendar.css' rel='stylesheet' />
<script src='<?php echo get_template_directory_uri() ?>/fullcalendar.js'></script>
<script type="text/javascript">
	if ('serviceWorker' in navigator) {
  	navigator.serviceWorker.register('/firebase-messaging-sw.js', {scope: './compteyes/'});
}

</script>
</head>