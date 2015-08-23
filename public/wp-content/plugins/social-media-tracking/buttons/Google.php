<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */

class XLII_SMT_Button_Google extends XLII_SMT_Button 
{
    /** _DEFAULT OPTIONS ---------------------------------------------------------------------------------------------------------------------------------------
     * Return an array containing the default options of this button.
     * 
     * @return  array
     */
    protected function _defaultOptions()
    {
        return array(
            'button' => 'bubble'
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
                            'none' => '<span class = "xlii-icon google none"></span>',
                            'bubble' => '<span class = "xlii-icon google bubble"></span>',
                            'inline' => '<span class = "xlii-icon google inline"></span><span class = "icontext">... people +1\'d this.</span>'
                        )
                    )).
                '</div>';
    }
    
    /** GET NAME ----------------------------------------------------------------------------------------------------------------------------------------------
     * Return the name this button can be identified with.
     * 
     * @return  string
     */
    public function getName()
    {
        return 'Google+';
    }
    
    /** _RENDER -----------------------------------------------------------------------------------------------------------------------------------------------
     * Convert the object to a string.
     * 
     * @return  string
     */
    protected function _render()
    {   
        return '<div class="g-plusone" data-href="'.get_permalink().'" data-annotation="'.$this->option('button').'"'.($this->option('button') == 'inline' ? 'data-width="120"' : '').'></div>';
    }
}