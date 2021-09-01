		<?php $background_image = App\Models\Setting::orderBy('s_id', 'asc')->value('s_background'); ?>
		<div id="background"><img src="{{ asset($background_image) }}"></div>
		<div class="footer-wrapper">
		    <footer id="footer">
		        <div class="container-fluid clearfix">
		            <span class="pull-left footer-text"><small><font color="black">&copy; 2015. TRACE. All Rights Reserved</font></small></span>
		            <span class="pull-right footer-text"><a href="http://region4a.dost.gov.ph/"><small>Developed by Department of Science and Technology - Region 4A · MIS Unit</small></a></span>
		        </div>
		    </footer>
		</div>
		<script src="{{ asset('extensions/bootstrap/js/bootstrap.min.js') }}"></script>        
	</body>
</html>