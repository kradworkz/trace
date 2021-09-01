  <script type="text/javascript">
    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert").slideUp(500);
    });
  </script>
   
  <!-- nice scroll -->
  <script src="{{ asset('extensions/NiceAdmin/js/jquery.scrollTo.min.js') }}"></script>
  <script src="{{ asset('extensions/NiceAdmin/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
  
  <!-- charts scripts -->
  <script src="{{ asset('extensions/NiceAdmin/assets/jquery-knob/js/jquery.knob.js') }}"></script>  
  <script src="{{ asset('extensions/NiceAdmin/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>  
  
  <!-- jQuery full calendar -->
  <script src="{{ asset('extensions/NiceAdmin/js/fullcalendar.min.js') }}"></script> <!-- Full Google Calendar - Calendar -->
  <script src="{{ asset('extensions/NiceAdmin/assets/fullcalendar/fullcalendar/fullcalendar.js') }}"></script>
  
  <!--script for this page only-->
  <script src="{{ asset('extensions/NiceAdmin/js/calendar-custom.js') }}"></script>
  <!-- <script src="{{ asset('extensions/NiceAdmin/js/jquery.rateit.min.js') }}"></script> -->
   
  <!--custome script for all page-->
  <script src="{{ asset('extensions/NiceAdmin/js/scripts.js') }}"></script>
  
  <!-- custom script for this page-->
  <script src="{{ asset('extensions/NiceAdmin/js/morris.min.js') }}"></script>
  </body>
</html>