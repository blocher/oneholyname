<aside id="sidebar">
	<!-- Begin Social Icons -->
	<section id="socialIcons">
	<ul>
		<?php if(get_option('diary_twitter_user')!=""){ ?>
		<li><a href="http://twitter.com/<?php echo get_option('diary_twitter_user'); ?>" class="twitter <?php if(get_option('diary_latest_tweet')!="no"):?>tip<?php endif?>" title="Follow Us on Twitter!">Follow Us on Twitter!</a></li>
		<?php }?>
		<?php if(get_option('diary_facebook_link')!=""){ ?>
		<li><a href="<?php echo get_option('diary_facebook_link'); ?>" class="facebook" title="Join Us on Facebook!">"Join Us on Facebook!</a></li>
		<?php }?>
		<li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS" class="rss">RSS</a></li>
	</ul>
	<?php if(get_option('diary_contact_page')):?>
	<a href="<?php echo get_page_link(get_option('diary_contact_page')); ?>" id="butContact">Contact</a>
	<?php endif;?>
	</section>
	<!-- End Social Icons -->
	<?php // Widgetized sidebar 
			if ( ! dynamic_sidebar( 'sidebar' ) ) :?>
			<div class="sideBox">
				<h3>WIDGETS NEEDED!</h3>
				<p>Go ahead and add some widgets here! Admin > Appearance > Widgets</p>
			</div>
			<?php endif; ?>
</aside>
