</div>
	</div>
	<!-- End Content -->
	<footer id="page-footer">
	<div id="site5bottom">Theme by Site5. <br /><a href="http://www.site5.com/p/php-hosting/">PHP Web Hosting</a>.</div>
	<div id="html5">
		<a href="http://www.w3.org/html/logo/" title="HTML5 Inside">
		<img src="<?php bloginfo('template_directory'); ?>/images/html5_logo.png" alt="HTML5 Inside" title="HTML5 Inside"></a><br />
  </div>
  
	<?php echo get_option("diary_copyright");?>
	</footer>
</div>
<?php if (get_option('diary_analytics') <> "") { 
		echo stripslashes(stripslashes(get_option('diary_analytics'))); 
	} ?>
</body>
<?php wp_footer();?>
</html>
