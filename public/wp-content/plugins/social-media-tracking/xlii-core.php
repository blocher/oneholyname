<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */
include_once('buttons/Default.php');

// Load all buttons
xlii_smt_supported_buttons();

// Register front page CSS
// Should normaly convert to: http://domain.example/wp-content/plugins/xlii-social-media-tracker

define('XLII_SMT_BASE', WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/');

function xlii_smt_load_resources()
{
    wp_register_style('xlii_smt_front', XLII_SMT_BASE.'resource/xlii_front.css', array());
    wp_register_script('xlii_smt_front', XLII_SMT_BASE.'resource/xlii_front.js', array('jquery'), 1.0, true);
    wp_register_script('xlii_smt_tracking', XLII_SMT_BASE.'resource/xlii_tracking.js', array('jquery'), 1.0, true);

    // Register button dependencies
    wp_register_script('xlii_smt_google', 'http://apis.google.com/js/plusone.js', array(), 1.0, true);
    wp_register_script('xlii_smt_twitter', 'http://platform.twitter.com/widgets.js', array(), 1.0, true);
    wp_register_script('xlii_smt_facebook', 'http://connect.facebook.net/en_US/all.js#xfbml=1', array(), 1.0, true);
}

add_action('init', 'xlii_smt_load_resources');

// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// CUSTOM METHODS
// ------------------------------------------------------------------------------------------------------------------------------------------------------------

/** GA OPTIONS ------------------------------------------------------------------------------------------------------------------------------------------------
 * Fetch the plugin ga options.
 * 
 * @return  array
 */
function _xlii_smt_ga_options()
{
	return get_option('xlii_smt_ga', array('enabled' => false, 'tracking' => true));
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// BUTTON METHODS
// ------------------------------------------------------------------------------------------------------------------------------------------------------------
/** SUPPORTED BUTTONS -----------------------------------------------------------------------------------------------------------------------------------------
 * Returns an array containing a list of supported button objects.
 * 
 * @filter  xlii_smt_supported_buttons
 * @return  array
 */
function xlii_smt_supported_buttons()
{
    $buttons = array('Facebook', 'Google', 'Twitter');
    foreach($buttons as &$button)
    {
        include_once('buttons/'.$button.'.php');
        $button = 'XLII_SMT_Button_'.$button;
        $button = new $button;
    }
    
    return apply_filters('xlii_smt_supported_buttons', $buttons);
}

/** GET BUTTONS -----------------------------------------------------------------------------------------------------------------------------------------------
 * Returns the buttons stored in a data section.
 * 
 * @param   string $section = null The section to retrive the buttons from, leave empty to return all.
 * @return  array
 */
function xlii_smt_get_buttons($section = null)
{
    $data = get_option('xlii_smt_buttons', array());
    if($section)
        return isset($data[$section]) ? $data[$section] : array();
    else
        return $data;
}

/** SET BUTTONS -----------------------------------------------------------------------------------------------------------------------------------------------
 * Set the buttons for a particulair section.
 * 
 * @param   string $section The section to store the buttons for.
 * @param   array $buttons The buttons and there values to add to the section.
 */
function xlii_smt_set_buttons($section, array $buttons)
{
    $data = xlii_smt_get_buttons();
    if(count($buttons))
        $data[$section] = $buttons;
    else if(isset($data[$section]))
        unset($data[$section]);
    else
        return;
    
    update_option('xlii_smt_buttons', $data);
}

/** TO BUTTON -------------------------------------------------------------------------------------------------------------------------------------------------
 * Convert a dataset to a button object.
 * 
 * @filter  xlii_smt_to_button
 * @param   array $data An array containing the buttons data.
 * @return  XLII_SMT_Button
 */
function xlii_smt_to_button(array $data)
{
    if(!is_array(apply_filters('xlii_smt_to_button', $data)))
        return $data;
    
    $class = 'XLII_SMT_Button_'.$data['type'];    
    return new $class($data);
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// ENABLED METHODS
// ------------------------------------------------------------------------------------------------------------------------------------------------------------
/** GET ENABLED ----------------------------------------------------------------------------------------------------------------------------------------------
 * Returns a list of keys where the plugin should be enabled on.
 * 
 * @return array
 */
function xlii_smt_get_enabled()
{
    return get_option('xlii_smt_enabled', array());
}

/** SET ENABLED -----------------------------------------------------------------------------------------------------------------------------------------------
 * Set the pages where the plugin is enabled.
 * 
 * @param   array $enabled An array containing the keys where the plugin is enabled.
 */
function xlii_smt_set_enabled(array $enabled)
{
    update_option('xlii_smt_enabled', $enabled);
}

/** IS ENABLED ------------------------------------------------------------------------------------------------------------------------------------------------
 * Returns wether the plugin is enabled on this page.
 * 
 * @filter  xlii_smt_is_enabled
 * @return  bool
 */
function xlii_smt_is_enabled()
{
    foreach(xlii_smt_get_enabled() as $key => $enabled)
    {
        $method = 'is_'.$key;
        if($enabled && function_exists($method) && $method())
            return true;
    }
    
    return apply_filters('xlii_smt_is_enabled', false);
}