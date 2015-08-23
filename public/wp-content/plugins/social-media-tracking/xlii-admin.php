<?php
/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */
 
function _xlii_smt_notify($append = null)
{
    global $queue;
    if($append !== null)
    {
        if(!$queue)
            $queue = array();
        
        $queue[] = !is_array($append) ? array('content' => $append, 'type' => 'updated') : $append;
    }
    else if($queue)
    {
        foreach($queue as &$data)
            echo '<div class = "'.$data['type'].' below-h2">'.__($data['content']).'</div>';
    }
} 

function _xlii_smt_router()
{
	include_once('guifactory/xlii_guifactory.php');
	wp_enqueue_style('xlii_smt_admin', XLII_SMT_BASE.'resource/xlii_admin.css', array('xlii_smt_front'));
 	wp_enqueue_script('xlii_smt_admin', XLII_SMT_BASE.'resource/xlii_admin.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-draggable'));

    switch(isset($_GET['nav']) ? $_GET['nav'] : 'default')
    {
        case 'ga':
            xlii_smt_adminpage_ga();
            break;
        case 'placeholder':
            xlii_smt_adminpage_placeholder();
            break;
        default:
            xlii_smt_adminpage_main();
    }
}

function _xlii_smt_navigation($active = null)
{
    if($active == null)
        $active = isset($_GET['nav']) ? $_GET['nav'] : 'default';
    
    $items = array('default' => 'Buttons', /*'placeholder' => 'Placeholders',*/ 'ga' => 'Analytics');
    
    echo '<ul id = "xlii-smt-nav">';
    foreach($items as $key => $value)
        echo '<li'.($active == $key ? ' class = "active"' : '').'><a href = "?page='.$_GET['page'].'&nav='.$key.'">'.$value.'</a></li>';
    echo '</ul><br class = "_clear" />';
}
 
// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// MAIN PAGE 
// ------------------------------------------------------------------------------------------------------------------------------------------------------------

// Helper methods
function _xlii_smt_renderbutton(XLII_SMT_Button $button, $template = false)
{
    echo '<li'.($template ? ' class = "template"' : '').'>
            <span class = "name">'.$button->getName().'</span>
            <span class = "icon"><div class = "xlii-icon '.$button->getType().'"></div></span>
            <span class = "form '.$button->getType().'">'.$button->getForm().'</span>
          </li>';
}

function _xlii_smt_rendersection($primairy, $sub, array &$buttons)
{
    if(isset($buttons[$primairy]) && isset($buttons[$primairy][$sub]))
    {
        echo '<ul class = "xlii-smt-'.$sub.'">';
        foreach($buttons[$primairy][$sub] as &$button)
        {
            $button['position'] = $primairy.' '.$sub;
            _xlii_smt_renderbutton(xlii_smt_to_button($button));
        }
        echo '</ul>';
    }
    else
    {
        echo '<ul class = "xlii-smt-'.$sub.' empty"></ul>';
    }
}

function xlii_smt_adminprocess_main()
{
    $data = array();
    if(isset($_POST['order']) && isset($_POST['button']))
    {
        foreach($_POST['order'] as $key => &$value)
        {
            if($value != 'template' && isset($_POST['button'][$value]))
            {
                $button = $_POST['button'][$value];
                $pos = explode(' ', $button['position']);
                
                unset($button['position']);
                
                if(!isset($data[$pos[0]]))           $data[$pos[0]] = array();
                if(!isset($data[$pos[0]][$pos[1]]))  $data[$pos[0]][$pos[1]] = array();
                
                $data[$pos[0]][$pos[1]][] = apply_filters('xlii_smt_submit_'.$button['type'], $button);
            }
            else
            {
                unset($data[$key]);
            }
        }
    }
    
    xlii_smt_set_enabled(isset($_POST['enabled']) ? (array) $_POST['enabled'] : array());
    xlii_smt_set_buttons('default', $data);
    
    
    _xlii_smt_notify('State saved');
}

function xlii_smt_adminpage_main()
{
    if(isset($_POST['save']))
        xlii_smt_adminprocess_main();
    
    $template = xlii_smt_supported_buttons();
    $enabled  = xlii_smt_get_enabled();
    $buttons  = xlii_smt_get_buttons('default');
    ?>
    <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" method="post">
        <div class="wrap">
    	    <?php screen_icon(); ?>
    	    <h2><?php _e('Social Media Tracker'); ?></h2>
    	    <?php _xlii_smt_notify(); _xlii_smt_navigation(); ?>
            <div id = "xlii-admin" class = "main">
                <div class = "wrapper">
                    <ul class = "left">
                        <li class = "location">
                            <div class = "xlii-smt-location top"> 
                                <?php _xlii_smt_rendersection('top', 'left', $buttons); _xlii_smt_rendersection('top', 'right', $buttons); ?>
                                <br class = "_clear" />
                            </div>
                            <img src = "<?php echo XLII_SMT_BASE.'media/blogpost.png'; ?>" alt = "blogpost" title = "blogpost" />
                            <div class = "xlii-smt-location bottom">
                                <?php _xlii_smt_rendersection('bottom', 'left', $buttons); _xlii_smt_rendersection('bottom', 'right', $buttons); ?>
                                <br class = "_clear" />
                            </div>
                        </li>
                        <li class = "options">
                            <strong>Button options</strong>
                            <ul class = "right">
                                <li class = "meta"></li>
                                <li class = "remove"><?php _e('Remove'); ?></li>
                            </ul>
                            <div class = "container"></div>
                        </li>
                        <div id = "recyclebin"><?php _e('Drag the button here to remove it'); ?></div>
                    </ul>
                    <ul class = "right">
                        <li class = "buttons">
                            <strong>Buttons</strong>
                            <div class = "description"><?php _e('Drag buttons to the dary gray areas in blog post or append them to other buttons within the area.'); _e(' Dragging activation is temporarily limited to the name, we\'re working on this.') ?></div>
                            <ul>
                                <?php
                                $source = XLII_GUIFactory::setSource(XLII_GUIFactory::SOURCE_NONE);
                                foreach($template as &$button)
                                    _xlii_smt_renderbutton($button, true);
                                XLII_GUIFactory::setSource($source);
                                ?>
                            </ul>
                        </li>
                        <li class = "displayoptions">
                            <strong><?php _e('Display options'); ?></strong>
                            <div class = "description"><?php _e('Indicates on wich pages and archives the plugin should be loaded and append the buttons.'); ?></div>
                            <ul>
                                <?php
                                $sections = array('home' => 'Front page of the blog', 'single' => 'Individual blog posts', 'page' => 'Individual WordPress "Pages"', 'category' => 'Category archives', 'tag' => 'Tag listings', 'date' => 'Date-based archives', 'author' => 'Author archives', 'search' => 'Search results', /*'feed' => 'RSS feed items'*/);
                                foreach($sections as $type => $label)
                                    echo '<li>'.XLII_GUIFactory::checkbox(array('label' => __($label), 'name' => 'enabled['.$type.']', 'id' => 'xlii_smt_enabled_'.$type, 'value' => isset($enabled[$type]))).'</li>';
                                ?>
			                </ul>
                        </li>
                        <li><?php echo XLII_GUIFactory::submit(array('name' => 'save', 'label' => __('Save state'))); ?></li>
                    </ul>
                    <br class = "_clear" />
                </div>
            </div>
        </div>
    </form>
    <?php
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// PLACEHOLDER PAGE 
// ------------------------------------------------------------------------------------------------------------------------------------------------------------
function xlii_smt_adminpage_placeholder()
{    
    
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------
// GA PAGE 
// ------------------------------------------------------------------------------------------------------------------------------------------------------------
function xlii_smt_adminprocess_ga()
{
    if(!($enabled = isset($_POST['enabled']) && $_POST['enabled']) || strpos($_POST['code'], 'XX-XXXXXXXX-X') === false)
    {
        update_option('xlii_smt_ga', array(
            'enabled' => $enabled,
            'tracking' => isset($_POST['tracking']),
            'code' => $_POST['code']
        ));
        
    	_xlii_smt_notify('The code is now '.($enabled ? 'added to' : 'removed from').' your website');
    }
    else
    {
    	_xlii_smt_notify(array('type' => 'error', 'content' => 'In order for this to work you\'re required to replace the Google Analytics code.'));
    }
}

function xlii_smt_adminpage_ga()
{
    if(isset($_POST['save']))
        xlii_smt_adminprocess_ga();
    $settings = _xlii_smt_ga_options();
    
    if(!$settings['code'])
    {
        $settings['code'] = "var _gaq = _gaq || [];
         _gaq.push(['_setAccount', 'XX-XXXXXXXX-X']);
         _gaq.push(['_trackPageview']);

         (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();";
    }
    
    ?>
    <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>" method="post">
        <div class="wrap">
    	    <?php screen_icon(); ?>
    	    <h2><?php _e('Social Media Tracker'); ?></h2>
    	    <?php _xlii_smt_notify(); _xlii_smt_navigation(); ?>
            <div id = "xlii-admin" class = "analytics">
                <?php 
                echo '<div class = "form">
                        <div class = "row">'.XLII_GUIFactory::textarea(array('label' => __('Analytics code'), 'name' => 'code', 'value' => $settings['code'], 'style' => 'width:600px;height:250px;')).'</div>
                        <div class = "row">'.XLII_GUIFactory::checkbox(array('label' => __('Track button interaction'), 'value' => $settings['tracking'], 'name' => 'tracking')).'
                            <p class = "description">'.__('You can check this box if you\'d like to track the interaction with the social media buttons.').'</p>
                        </div>
                        <div class = "row">'.XLII_GUIFactory::checkbox(array('label' => __('Append code to website'), 'value' => $settings['enabled'], 'name' => 'enabled')).'
                            <p class = "description">'.
                                __('In order to work propperly the Social Media Tracking plugin requires a valid Google Analytics code to be implemented on your website. In case you didn\'t implemented this 
                                   code already our plugin can do it for you, the only thing you have to do is to').' <label for = "enabled" class = "link">click here</label> '.__('and replace XX-XXXXXXXX-X in the sample
                                   by your own code.').'
                            </p>
        	                <p class = "description">
        	                    <span style = "margin-top:-10px;">'.__('Your tracking code can be found in your Google Analytics panel.').'</span>
        	                    <img src = "'.XLII_SMT_BASE.'media/gacode.png" alt = "Tracking code" title = "Tracking code" style = "vertical-align:baseline;" />
        	                </p>
                        </div>
                        '.XLII_GUIFactory::submit(array('label' => __('Save state'), 'name' => 'save')).'
                      </div>';
                ?>
            </div>
        </div>
    </form>
    <?php 
}
