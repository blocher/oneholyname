<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */
add_action('wp_print_styles', '_xlii_smt_load');

function _xlii_smt_load()
{
	if(!is_admin())
	{
		include_once('guifactory/xlii_guifactory.php');
		
		// Implement tracking code
		$settings = _xlii_smt_ga_options();
		if($settings['enabled'] && $settings['code']) 
		  	echo '<script type="text/javascript">'.stripslashes($settings['code']).'</script>';
	
		// Add filter if plugin is enabled for this page
	    if(xlii_smt_is_enabled() && count($buttons = xlii_smt_get_buttons('default')))
	    {
	        add_filter('the_content', 'xlii_smt_display');
	        add_filter('the_excerpt', 'xlii_smt_display');  
	    }
	}
}

function xlii_smt_getsection($primairy, $sub, array &$buttons)
{
    if(isset($buttons[$primairy]) && isset($buttons[$primairy][$sub]))
    {
        $content = '<ul class = "xlii-smt-'.$sub.'">';
        foreach($buttons[$primairy][$sub] as &$button)
            $content .= '<li>'.xlii_smt_to_button($button).'</li>';
        return $content.'</ul>';
    }
    return '';
}

function xlii_smt_display($content)
{
    // Render top
    $buttons = xlii_smt_get_buttons('default');
    $append  = array('top' => '', 'bottom' => '');
    foreach($append as $section => &$html)
    {
        $html = xlii_smt_getsection($section, 'left', $buttons).
                xlii_smt_getsection($section, 'right', $buttons);
                
        if($html)
            $html = '<div class = "xlii-smt-location">'.$html.' <br class = "_clear"/></div>';
    }
    
    return $append['top'].$content.$append['bottom'];
}