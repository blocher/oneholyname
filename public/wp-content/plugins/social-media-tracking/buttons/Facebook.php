<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */

add_filter('xlii_smt_submit_facebook', 'XLII_SMT_Button_Facebook::_process');

class XLII_SMT_Button_Facebook extends XLII_SMT_Button 
{
    /** _PROCESS ----------------------------------------------------------------------------------------------------------------------------------------------
     * Helper method used for processing this button (when submitted by admin).
     * 
     * @param   array $button The button data.
     * @return  array
     */
    public static function _process(array $button)
    {
        if(!isset($button['send']))
            $button['send'] = false;
        return $button;
    }
    
    /** _DEFAULT OPTIONS ---------------------------------------------------------------------------------------------------------------------------------------
     * Return an array containing the default options of this button.
     * 
     * @return  array
     */
    protected function _defaultOptions()
    {
        return array(
            'button' => 'standard',
            'send' => true
         );
    }
    
    /** _GET FORM ---------------------------------------------------------------------------------------------------------------------------------------------
     * Render a form to give this button custom options, inner helper method of @see form. This is the only way a form can be overloaded.
     *
     * @return string
     */
    protected function _getForm()
    {
        return '<strong>Layout</strong>'.
               '<div class = "buttonselect">'.
                    XLII_GUIFactory::radio(array(
                        'name' => $this->_field('button'),
                        'value' => $this->option('button'),
                        'options' => array(
                            'standard' => '<span class = "xlii-icon facebook plain"></span><span class = "icontext">... people like this. Be the first of your friends.</span>',
                            'button_count' => '<span class = "xlii-icon facebook horizontal"></span>',
                            'box_count' => '<span class = "xlii-icon facebook vertical"></span>'
                        )
                    )).
                '</div>'.
                XLII_GUIFactory::checkbox(array('name' => $this->_field('send'), 'value' => $this->option('send'), 'label' => __('Include send button')));
    }
    
    /** GET NAME ----------------------------------------------------------------------------------------------------------------------------------------------
     * Return the name this button can be identified with.
     * 
     * @return  string
     */
    public function getName()
    {
        return 'Facebook';
    }
    
    /** _RENDER -----------------------------------------------------------------------------------------------------------------------------------------------
     * Convert the object to a string.
     * 
     * @return  string
     */
    protected function _render()
    {       
        return '<div class="fb-like" data-href="'.get_permalink().'" data-send="'.($this->option('send') ? 'true' : 'false').'" data-width="0" data-layout="'.$this->option('button').'" data-show-faces="false"></div>';
    }
}