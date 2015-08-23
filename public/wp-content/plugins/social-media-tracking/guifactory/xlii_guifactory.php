<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 * 
 * GUIFactory used to easy create form elements.
 * 
 * The following options can be used on every element unless otherwise is indicated:
 * - string id The identification of the element.
 * - string name The name of the element.
 * - string label The label attached to the element.
 * - string class Additional classes to append to the element.
 * - string style The style of the element.
 * - string|int|bool value The default value of the element.
 */

if(defined('XLII_GUIFACTORY'))
	return;

define('XLII_GUIFACTORY', true);

XLII_GUIFactory::init();

class XLII_GUIFactory
{
    const SOURCE_NONE = 'none';
    const SOURCE_BOTH = 'both';
    const SOURCE_POST = 'post';
    const SOURCE_GET = 'get';
    
    private static $_source = null;
    private static $_subcounter = array();
    
	/** INIT --------------------------------------------------------------------------------------------------------------------------------------------------
     * Initialize the guifactory.
     */
	public static function init()
	{
		// Abuse the source to check for initialization
		if(self::$_source === null)
		{
			self::$_source = self::SOURCE_POST;
			
			// Fix for lower wordpress	
			if(version_compare(get_bloginfo('wp_version'), '3.3', '<' ))
			{
				add_action('wp_footer', array(__CLASS__, '_fixFooterScriptStyles'), 1);
				add_action('admin_footer', array(__CLASS__, '_fixFooterScriptStyles'), 1);
			}
		}
	}
    
    /** _ATTRIBUTES -------------------------------------------------------------------------------------------------------------------------------------------
     * Return the attributes stored within the options.
     *
     * @param   array &$options The options to fetch the shared data from.
     * @param   array $include = null An array containing the allowed fields.
     * @return  string
     */
    protected static function _attributes(array &$options, array $include = null)
    {
		// Most probably drawing a 
		self::queueResources();
	
        if(isset($options['appendClass']))
        {
            $options['class'] = (isset($options['class']) ? $options['class'].' ' : '').$options['appendClass'];
            unset($options['appendClass']);
        }
            
        if(isset($options['name']) && !isset($options['id']))
            $options['id'] = $options['name'];
            
        $options['id'] = str_replace('[]', '', $options['id']);
        $options['id'] = str_replace(array('][', ']', '['), '_', $options['id']);
            
        $attr = array();
        foreach(array('name', 'id', 'class', 'style', 'onchange', 'onclick') as $type)
        {
            if(isset($options[$type]) && (!$include || in_array($type, $include)))
                $attr[] = $type.' = "'.$options[$type].'"';
        }
        
        return count($attr) ? ' '.implode(' ', $attr) : '';
    }
    
    /** CALENDAR ----------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a default calendar selector.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function calendar($options)
    {
        $options['class'] = (isset($options['class']) ? $options['class'].' ' : '').' xlii-calendar';
        
        return self::text($options);
    }
    
    /** CHECKBOX ----------------------------------------------------------------------------------------------------------------------------------------------
     * Return a checkbox.
     *
     * @param   array $options An array containing element specific options.
     *          Allowed options
     *          - bool value Indicate wether the checkbox is checked or not.
     * @return  string
     */
    public static function checkbox(array $options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-checkbox';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : false);

        return '<input type = "checkbox"'.self::_attributes($options).($options['value'] ? ' checked = "checked"' : '').' />'.self::label($options);
    }

    /** ELEMENT -----------------------------------------------------------------------------------------------------------------------------------------------
     * Global render method, specify a type for fast dynamic rendering.
     *
     * @param   enum $type The type of element to render.
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function element($type, array $options)
    {
        if(method_exists(__CLASS__, $type) && $type[0] != '_' && $type != 'element')
            return self::$type($options);
        else
            return '';
    }
	
	/** _FIX FOOT SCRIPT STYLES  ------------------------------------------------------------------------------------------------------------------------------
     * Fix to print footer styles in lower wordpress.
     */
	public static function _fixFooterScriptStyles()
	{
		global $wp_styles, $wp_scripts;
		
		// Fix render queue
		if($wp_styles && get_class($wp_styles) == 'WP_Styles')
			$wp_styles->to_do = array_merge($wp_styles->to_do, array_diff($wp_styles->queue, $wp_styles->done));
			
		if($wp_scripts && get_class($wp_scripts) == 'WP_Scripts')
		{
			$wp_scripts->all_deps(array_merge($wp_scripts->to_do, array_diff($wp_scripts->queue, $wp_scripts->done)));
			$wp_scripts->in_footer = 
			$wp_scripts->to_do = array_unique(array_merge($wp_scripts->to_do, array_diff($wp_scripts->queue, $wp_scripts->done)));
		}
		
		// Render style
		if (is_a($wp_styles, 'WP_Styles') )
			$wp_styles->do_items( false );
	}
    
    /** HIDDEN ------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a default hidden element.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function hidden($options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-hidden';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        
        return '<input type = "hidden"'.self::_attributes($options).($options['value'] ? ' value = "'.self::_protect($options['value']).'"' : '').' />';
    }
    
    /** LABEL -------------------------------------------------------------------------------------------------------------------------------------------------
     * Return a label for an element.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function label(array $options)
    {
        if(isset($options['label']))
            return '<label for = "'.(isset($options['id']) ? $options['id'] : $options['name']).'"'.self::_attributes($options, array('class', 'style')).'>'.$options['label'].'</label>';
        else
            return '';
    }
    
    /** _PROTECT ----------------------------------------------------------------------------------------------------------------------------------------------
     * Parse user given data to make sure its safe for output.
     *
     * @param   string $text The text to parse.
     * @return  string
     */
    protected function _protect($text)
    {
        return htmlentities($text);
    }

    /** QUEUE RESOURCES ---------------------------------------------------------------------------------------------------------------------------------------
     * Enqueue all the resources 
     */
    public static function queueResources()
    {
		if(empty($GLOBALS['wp_scripts']->registered['xlii_guifactory']))
		{
			$base = substr(dirname(__FILE__), strlen(ABSPATH) - 1).'/assets/';
	        wp_enqueue_script('xlii_guifactory', $base.'guifactory.js', array('jquery', 'jquery-ui-datepicker'));

	        wp_register_style('xlii_guifactory_ui', $base.'ui.css');
	        wp_enqueue_style('xlii_guifactory', $base.'guifactory.css', array('xlii_guifactory_ui')); 
		}
    }
    
    /** RADIO -------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a radiobutton.
     *
     * @param   array $options An array containing element specific options.
     *          Allowed options
     *          - array options An array containing values => labels used to create entire radio groups at once. 
     * @return  string
     */
    public static function radio(array $options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-radio';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        
        if(isset($options['options']))
        {   
            $html = '<div class = "radiogroup">';
			if(!empty($options['label']))
				$html .= '<strong>'.$options['label'].'</strong>';
            $i = 0;
            foreach($options['options'] as $value => $label)
            {
                $options['id'] = $options['name'].'_'.(++$i);
                $options['label'] = $label;
                $html .= '<div class = "entity"><input type = "radio"'.self::_attributes($options).' value = "'.$value.'"'.($options['value'] == $value ? ' checked = "checked"' : '').' />'.self::label($options).'</div>';
            }
                
                
            return $html.'</div>';
        }
        else
        {
            return '<input type = "radio"'.self::_attributes($options).' value = "'.$options['value'].'"'.(self::_value($options['name'], $options['value']) == $options['value'] ? ' checked = "checked"' : '').' />'.self::label($options);
        }
    }   
    
    /** SELECT ------------------------------------------------------------------------------------------------------------------------------------------------
     * Return a selectbox.
     *
     * @param   array $options An array containing element specific options.
     *          Allowed options
     *          - bool multi = false Indicate wether multiple values are allowed to be selected.
     *          - array options The options within the selectbox value => label.
     * @return  string
     */
    public static function select(array $options)
    {   
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-select';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        $options['value'] = !is_array($options['value']) ? array($options['value']) : $options['value'];
     
        
        if(isset($options['multi']) && $options['multi'])
        {
            $options['appendClass'] .= ' multi';
            if(substr($options['name'], -2) != '[]')
            {
                if(!isset($options['id']))
                    $options['id'] = $options['name'];
                $options['name'] .= '[]';
            }
        }
        
        $html = self::label($options).'<select'.self::_attributes($options).(isset($options['multi']) && $options['multi'] ? 'multiple="multiple"' : '').'>';
        foreach($options['options'] as $value => $label)
            $html .= '<option value = "'.$value.'"'.(in_array($value, $options['value']) ? 'selected = "selected"' : '').'>'.$label.'</option>';
        
        return $html.'</select>';
    }
    
    /** SELECT ------------------------------------------------------------------------------------------------------------------------------------------------
     * Return a selectbox.
     *
     * @param   array $options An array containing element specific options.
     *          Allowed options
     *          - bool multi = true Indicate wether multiple values are allowed to be selected.
     *          - bool linked = false Only allow to select linked media.
     *          - bool popup = true Indicate wether the files are selected trough a popupbox
     *          - array mime_type The allowed mime types that may be selected.
     * @return  string
     */
    public static function selectMedia(array $options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        $options['value'] = array_filter(!is_array($options['value']) ? array($options['value']) : $options['value']);
       
        $media = get_posts(array(
            'post_type' => 'attachment', 
            'numberposts' => -1, 
            'order' => 'ASC', 
            'orderby' => 'title', 
            'post_mime_type' => isset($options['mime_type']) ? $options['mime_type'] : null,
            'post_parent' => isset($options['linked']) && $options['linked'] ? get_the_ID() : null
        ));
        
        $type = !isset($options['multi']) || $options['multi'] ? 'checkbox' : 'radio';
        $tabs = array(
            'Active' => '',
        );

        // Collect tabs
        foreach($media as $post)
        {
            $url = wp_get_attachment_image_src($post->ID, array(50, 50));
            $row = '<li class = "tab '.sanitize_title($post->post_mime_type).(in_array($post->ID, $options['value']) ? ' active' : '').'"><input type = "'.$type.'" id = "'.$options['id'].'_'.$post->ID.'" name = "'.$options['name'].'[]" value = "'.$post->ID.'" class = "xlii-'.$type.' xlii-input" '.(in_array($post->ID, $options['value']) ? 'checked="checked"' : '').' /><span class = "image"><img src = "'.$url[0].'" width = "'.$url[1].'" height = "'.$url[2].'" /></span><span class = "title">'.get_the_title($post->ID).'</span><span class = "full">'.wp_get_attachment_url($post->ID).'</span></li>';
        
            if(!isset($tabs[$post->post_mime_type]))
                $tabs[$post->post_mime_type] = $row;
            else
                $tabs[$post->post_mime_type] .= $row;
        }
        
        // Gather content
        $navigation = $container = '';
        foreach($tabs as $title => $content)
        {
            $navigation .= '<li class = "nav" rel = "'.sanitize_title($title).'">'.$title.'</li>';
            $container  .= $content;
        }

        if($popup = !isset($options['popup']) || $options['popup'])
        {
            $reload = '';
            foreach($options['value'] as $post)
            {
                $url = wp_get_attachment_image_src($post, array(50, 50));
                $reload .= '<li class = "tab '.sanitize_title(get_post_mime_type($post)).' active" rel = "'.$post.'"><span class = "image"><img src = "'.$url[0].'" width = "'.$url[1].'" height = "'.$url[2].'" /></span><span class = "title">'.get_the_title($post).'</span><span class = "full">'.wp_get_attachment_url($post).'</span></li>';
            }
        }
		ob_start();
        echo '<div class = "xlii-mediaselector'.($popup ? ' popup' : '').'">
                <div id = "xlii-mediaselector-'.($options['id']).'" class = "element '.($popup ? 'xlii-popup"' : '"'.self::_attributes($options, array('style'))).'>
                    <ul class = "navigation">'.$navigation.'<li class = "searchbox"><input type = "text" class = "xlii-search" /></li></ul>
                    <ul class = "content">'.$container.'</ul>
                    <div class = "controlls">
                        <span class = "prev item">'.__('Previous').'</span>
                        <span class = "next item">'.__('Next').'</span>
                        <input type = "hidden" class = "page" value = "0" />
                    </div>
                </div>
                '.($popup ? '<div class = "frontend"'.self::_attributes($options, array('style', 'class')).'><ul class = "active">'.$reload.'</ul><a class = "change xlii-showPopup" href = "xlii-mediaselector-'.($options['id']).'">'.__('Select media files').'</a></div>' : '').'
              </div>';
        
        if($popup)
            self::supportPopup(true);
		return ob_get_clean();
    }
    
    /** SET SOURCE --------------------------------------------------------------------------------------------------------------------------------------------
     * Set the source used for fetching the elements values.
     *
     * @param   enum $type The source type to fetch values from.
     */
    public static function setSource($type)
    {
        self::$_source = $type;
    }
    
    /** SUBMIT ------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a default submission element.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function submit($options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-submit button';
        $options['value'] = isset($options['value']) ? $options['value'] : 'Submit';
        $options['label'] = isset($options['label']) ? $options['label'] : $options['value'];
        
        return '<input type = "submit"'.self::_attributes($options).' value = "'.self::_protect($options['label']).'" />';
    }
    
    /** SUPPORT POPUP ----------------------------------------------------------------------------------------------------------------------------------------
     * Support popup functionality for the guifactory.
     */
    public static function supportPopup($add = false)
    {
        static $added;
        if($add && !$added)
        {
            $added = true;
            add_action('admin_footer', array(__CLASS__, __FUNCTION__));
        }
        else
        {
            echo '<div id = "xlii-popupOverlay"></div>';
        }   
    }
    
    /** TEXT --------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a default text element.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function text($options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-text';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        
        return self::label($options).'<input type = "text"'.self::_attributes($options).($options['value'] ? ' value = "'.self::_protect($options['value']).'"' : '').' />';
    }
    
    /** TEXTAREA -----------------------------------------------------------------------------------------------------------------------------------------------
     * Returns a default textarea element.
     *
     * @param   array $options An array containing element specific options.
     * @return  string
     */
    public static function textarea($options)
    {
        if(!isset($options['name']))    $options['name'] = $options['id'];
        
        $options['appendClass'] = 'xlii-textarea';
        $options['value'] = self::_value($options['name'], isset($options['value']) ? $options['value'] : '');
        
        return self::label($options).'<br /><textarea'.self::_attributes($options).'>'.stripslashes($options['value'] ? $options['value'] : '').'</textarea>';
    }
    
    /** _VALUE ------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns the value of an element.
     *
     * @param   string $name The name of the element.
     * @param   void $default The default value.
     * @return  void|null
     */
    protected static function _value($name, $default = null)
    {
        if(strpos($name, '[') !== false && self::$_source == self::SOURCE_NONE)
        {
            $name   = explode('[', str_replace(']', '', $name));
            $chain  = '';
            if(self::$_source == self::SOURCE_BOTH)
                $source = array_merge($_POST, $_GET);
            else if(self::$_source == self::SOURCE_POST)
                $source = $_POST;
            else
                $ousrce = $_GET;
            
            foreach($name as &$key)
            {
                // Make sure we have a valid key
                if(!$key)
                {
                    if(!isset(self::$_subcounter[$chain]))
                        $key = self::$_subcounter[$chain] = 0;
                    else
                        $key = self::$_subcounter[$chain];
                        
                    self::$_subcounter[$chain]++;
                }
                
                if(!isset($source[$key]))
                    return $default;
                
                $source = $source[$key];
            }    
            return $source;
        }
        else
        {
            if(isset($_POST[$name]) && (self::$_source == self::SOURCE_BOTH || self::$_source == self::SOURCE_POST))
                return $_POST[$name];
            else if(isset($_GET[$name]) && (self::$_source == self::SOURCE_BOTH || self::$_source == self::SOURCE_GET))
                return $_GET[$name];
        }
        return $default;    
    }
}
