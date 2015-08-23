<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */

class XLII_SMT_Button_Twitter extends XLII_SMT_Button 
{
    /** _DEFAULT OPTIONS ---------------------------------------------------------------------------------------------------------------------------------------
     * Return an array containing the default options of this button.
     * 
     * @return  array
     */
    protected function _defaultOptions()
    {
        return array(
            'button' => 'horizontal'
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
                            'none' => '<span class = "xlii-icon twitter none"></span>',
                            'horizontal' => '<span class = "xlii-icon twitter horizontal"></span>',
                            'vertical' => '<span class = "xlii-icon twitter vertical"></span>'
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
        return 'Twitter';
    }
    
    /** _RENDER -----------------------------------------------------------------------------------------------------------------------------------------------
     * Convert the object to a string.
     * 
     * @return  string
     */
    protected function _render()
    {   
        return '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.get_permalink().'" data-text="'.get_the_title().'" data-count="'.$this->option('button').'">Tweet</a>';
    }
}