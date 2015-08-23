/** 
 * @author 42functions | Ferdy Perdaan
 * @version 1.0
 */
 
(function($)
{
    // METHODS ----------------------------------------------------------------------------------------------------------------------------
    var _xlii_smt_counter = 0;
    function _xlii_smt_parsetemplate(form)
    {
        _xlii_smt_counter++;
        // Replace name and id
        $.each($('input,select,textarea', form), function(key, field){
            field = $(field);
            field.attr('name', field.attr('name').replace(/^button\[template\]/, 'button[' + _xlii_smt_counter + ']'));
            field.attr('id', field.attr('id').replace(/^button\_template/, 'button_' + _xlii_smt_counter));
        });
    
        // Replace labels
        $.each($('label', form), function(key, field){ $(field).attr('for', $(field).attr('for').replace(/^button\_template/, 'button\_' + _xlii_smt_counter)); });

        // Set order
        $('input.order', form).val(_xlii_smt_counter);

        // Recheck elements
        $('input[type="radio"][checked="checked"]', form).attr('checked', true);
    }
    
    // ONLOAD -----------------------------------------------------------------------------------------------------------------------------
    $(function(){
        $('#xlii-admin .buttons ul li').draggable({
            connectToSortable : '#xlii-admin .location .xlii-smt-location ul', 
            handle : '> .name',
            cursor : 'pointer',
            helper : 'clone'
        }); 
        
        $('#xlii-admin .location .xlii-smt-location ul').sortable({
            connectWith : '#xlii-admin .location .xlii-smt-location ul', 
            placeholder : 'placeholder',
            cancel : '.form'
        });
        
        $('#recyclebin').droppable({ hoverClass : 'active' });
        $('#recyclebin').bind('drop', function(event, ui){ $('#xlii-admin li.options .remove').trigger('click'); });
        
        // DRAGGING ITEMS
        $('#xlii-admin .location .xlii-smt-location ul').bind('sortstart', function(event, ui){ $('#xlii-admin li.options').hide(); $('#xlii-admin .form.active').removeClass('active').hide(); $('.form', ui.item).addClass('active').hide(); if(!ui.helper.hasClass('template')) $('#recyclebin').show(); });
        $('#xlii-admin .location .xlii-smt-location ul').bind('sortstop', function(event, ui){ $('.icon', ui.item).trigger('click'); $('#recyclebin').hide(); });
        $('#xlii-admin .location .xlii-smt-location ul').bind('sortreceive', function(event, ui){ 
            $(this).removeClass('empty');
            $('.form input.position', ui.item.hasClass('template') ? $('li.template', this) : ui.item).val(
                ($(this).parent().hasClass('top') ? 'top' : 'bottom') + ' ' +
                ($(this).hasClass('xlii-smt-left') ? 'left' : 'right') 
            );
        
            if(ui.item.hasClass('template'))
            {
                _xlii_smt_parsetemplate($('li.template .form', this));
                $('li.template', this).removeClass('template');
            }
        });
        
        // Replace templating prefix
        $.each($('#xlii-admin .location .form'), function(key, element){
            _xlii_smt_parsetemplate(element);
        });
    });
    
    // LIVE TRIGGERS ----------------------------------------------------------------------------------------------------------------------
    // Fix dragging of elements by moving the button to the mouse position
    $('#xlii-admin .buttons ul li').live('drag', function(event, ui){
        ui.position = {top : ui.position.top, left : event.pageX};
    });
    
    // Open option form 
    $('#xlii-admin .xlii-smt-location .icon').live('click', function(){
        // Refresh meta button information
        $('#xlii-admin li.options .meta').html(
            $(this).siblings('.name').html() + ' ' + $('input.position', $(this).siblings('.form')).val()
        );
        
        // Hide active form and show new form
        $('#xlii-admin li.options').show();
        $('#xlii-admin .form.active').removeClass('active').hide();
        $(this).siblings('.form').addClass('active').show().offset($('#xlii-admin li.options .container').height($(this).siblings('.form').height()).offset());
    });
    
    // Support remove button
    $('#xlii-admin li.options .remove').live('click', function(){
        var option = $('#xlii-admin .form.active').parents('li:eq(0)'); 
        if(option.length)
        {
            if(option.siblings('li').not('.placeholder').length == 0)
                option.parent().addClass('empty');
            
            option.remove();
        }
        $('#recyclebin').hide();
        $('#xlii-admin li.options').hide();
    });
    
})(jQuery);