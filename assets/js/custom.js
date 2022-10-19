<script type="text/javascript">
   $("#id").unbind('click').on("click", function () {

          uservalidate();
          passvalidate();

         if (uservalidate() === true
          && passvalidate() === true

          ) {

   };


   });


   function uservalidate() {
   if ($('#val1').val() == '') {
    $('#val1').css('border-color', '#dc3545');
    return false;
     } else {
      $('#val1').css('border-color', '#28a745');
       return true;
   }

   };

   function passvalidate() {
   if ($('#val5').val() == '') {
    $('#val5').css('border-color', '#dc3545');
    return false;
     } else {
      $('#val5').css('border-color', '#28a745');
       return true;
   }

   };


</script>
<script type="text/javascript">
   $(document).ready (function(){
               $("#success-alerts").fadeOut(15000);

               $("#id").unbind('click').on("click", function () {
                   $("#success-alerts").fadeTo(1000, 0).slideUp(5000, function(){
                    //$(this).remove();
                   });
               }, 5000);

               $("#success-alert").fadeOut(15000);
               $("#id").unbind('click').on("click", function () {
                   $("#success-alert").fadeTo(1000, 0).slideUp(5000, function(){
                  // $(this).remove();
                   });
               }, 5000);
    });

</script>
<script type="text/javascript">
   $(document).ready (function(){
               $("#danger-alert").fadeOut(15000);
               $("#id").unbind('click').on("click", function () {
                   $("#danger-alert").fadeTo(1000, 0).slideUp(5000, function(){
                    //$(this).remove();
                   });
               }, 5000);

               $("#danger-alerts").fadeOut(15000);
               $("#id").unbind('click').on("click", function () {
                   $("#danger-alerts").fadeTo(1000, 0).slideUp(5000, function(){
                  // $(this).remove();
                   });
               }, 5000);
    });

</script>
<script type="text/javascript" defer="defer">
<!--
var enableDisable = function(){
    var UTC_hours = new Date().getUTCHours() +1;
    if (UTC_hours > 4 && UTC_hours < 6){
        document.getElementById('punchin').disabled = false;
    }
    else
    {
        document.getElementById('punchin').disabled = true;
        //alert("Punch In Time Is Over");
    }
};
setInterval(enableDisable, 1000*60);
enableDisable();
// -->
</script>
<script type="text/javascript" defer="defer">
<!--
var enableDisable = function(){
    var UTC_hours = new Date().getUTCHours() +1;
    if (UTC_hours >= 12 && UTC_hours <= 13){
        document.getElementById('punchout').disabled = false;
    }
    else
    {
        document.getElementById('punchout').disabled = true;
        alert("Punch Out Time Is Over ");
    }
};
setInterval(enableDisable, 1000*60);
enableDisable();
// -->
</script>

<script type="text/javascript">
$(document).ready(function() {
setInterval(runningTime, 1000);
});
function runningTime() {
 $.ajax({
   url: 'assets/css/timeScript.php',
   success: function(data) {
      $('#runningTime').html(data);
    },
 });
}
</script>
