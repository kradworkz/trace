	<script type="text/javascript"> 
		$(document).ready(function(){
			$('.dropdown-submenu a.test').on("click", function(e){
			    $(this).next('ul').toggle();
		    	e.stopPropagation();
		    	e.preventDefault();
		  	});
		});
	</script>
	
	<footer id="footer">
	    <div class="container-fluid">
	        <span class="pull-left footer-text"><small>&copy; 2017. TRACE. All Rights Reserved</small></span>
	        <span class="pull-right footer-text"><a href="http://region4a.dost.gov.ph/"><small>Developed by Department of Science and Technology - Region 4A · MIS Unit</small></a></span>
	    </div>
	</footer>
	</body>
</html>