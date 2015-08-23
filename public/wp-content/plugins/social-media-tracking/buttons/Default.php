<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */

abstract class XLII_SMT_Button 
{
	private static $_queue = array();
    private $_data;
    
    /** __CONSTRUCT -------------------------------------------------------------------------------------------------------------------------------------------
     * Set up a default button object.
     */
    public function __construct(array $options = array())
    {
        $default = $this->_defaultOptions();
        $default['position'] = null;
        
        $this->_data = array_merge($default, $options);
    }
    
    /** _DEFAULT OPTIONS ---------------------------------------------------------------------------------------------------------------------------------------
     * Return an array containing the default options of this button.
     * 
     * @return  array
     */
    protected function _defaultOptions()
    {
        return array();
    }

	/** _ENQUEUE RESOURCES ------------------------------------------------------------------------------------------------------------------------------------
     * Enqueue the (tracking) resources to the page.
     *
	 * @param	bool $tracking Indicates wether tracking is enabled or not.
     * @return  XLII_SMT_Button
     */
	protected function _enqueueResources($tracking)
	{
		wp_enqueue_script('xlii_smt_'.$this->getType());

		return $this;
	}
	
    /** _FIELD ------------------------------------------------------------------------------------------------------------------------------------------------
     * Returns the name of a field generated in the form.
     *
     * @param   string $name The name the field should have.
     * @return  string
     */
    protected function _field($name)
    {
        return 'button[template]['.$name.']';
    }
    
    /** GET FORM ----------------------------------------------------------------------------------------------------------------------------------------------
     * Render a form to give this button custom options.
     *
     * @return string
     */
    public final function getForm()
    {
        return XLII_GUIFactory::hidden(array('name' => $this->_field('position'), 'id' => '', 'value' => $this->option('position'), 'class' => 'position')).
               XLII_GUIFactory::hidden(array('name' => $this->_field('type'), 'id' => '', 'value' => $this->getType())).
               XLII_GUIFactory::hidden(array('name' => 'order[]', 'id' => '', 'value' => 'template', 'class' => 'order')).
               $this->_getForm();
    }
    
    /** _GET FORM ---------------------------------------------------------------------------------------------------------------------------------------------
     * Render a form to give this button custom options, inner helper method of @see form. This is the only way a form can be overloaded.
     *
     * @return string
     */
    protected function _getForm()
    {
        return __('There arn\'t any custom options availible for this button.');
    }

    /** GET OPTION --------------------------------------------------------------------------------------------------------------------------------------------
     * Return a stored option.
     * 
     * @param   string $key The key the option can be referred by.
     * @return  void|null
     */
    public function getOption($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }
    
    /** GET TYPE ----------------------------------------------------------------------------------------------------------------------------------------------
     * Return the type the button can be identified with.
     * 
     * @return  string
     */
    public function getType()
    {
        return sanitize_title($this->getName());
    }
    
    /** OPTION ------------------------------------------------------------------------------------------------------------------------------------------------
     * Quick access option getter/setter, leave value empty to use it as a getter.
     * 
     * @param   string $key The key the option can be referred by.
     * @param   void $value = null The value to store within the option.
     * @return  void
     */
    public function option($key, $value = null)
    {
        return !$value ? $this->getOption($key) : $this->setOption($key, $value);
    }
    
    /** SET OPTION --------------------------------------------------------------------------------------------------------------------------------------------
     * Set the value of an option.
     * 
     * @param   string $key The key the option can be referred by.
     * @param   void $value The value to store within the option.
     * @return  XLII_SMT_Button
     */
    public function setOption($key, $value)
    {
        if($value !== null)
            $this->_data[$key] = $value;
        else
            unset($this->_data[$key]);
        return $this;
    }

    /** RENDER ------------------------------------------------------------------------------------------------------------------------------------------------
     * Convert the object to a string.
     * 
     * @return  string
     */
    public function render()
    {
	    if(!isset(self::$_queue[$this->getType()]))
	   	{
			$settings = _xlii_smt_ga_options();
			$this->_enqueueResources($settings['tracking']);
			self::$_queue[$this->getType()] = true;
		}
		if(count(self::$_queue) == 1)
		{
        	// Enqueue styling and smt
	        wp_enqueue_style('xlii_smt_front');
	        wp_enqueue_script('xlii_smt_front');
        
	        // Append tracking script
	        if($settings['tracking'])
	            wp_enqueue_script('xlii_smt_tracking');
		}
		
        return '<span class = "xlii-smt-button '.$this->getType().'">'.$this->_render().'</span>';
    }

    /** __TO STRING -------------------------------------------------------------------------------------------------------------------------------------------
     * Convert the object to a string.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }

    // ABSTRACT METHODS
    public abstract function getName();
    protected abstract function _render();
}