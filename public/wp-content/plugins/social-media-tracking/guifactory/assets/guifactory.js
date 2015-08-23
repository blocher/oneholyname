/** 
 * @author 42functions
 * @version 1.0
 */
 
(function($){
    $(function(){
        $('.xlii-calendar').datepicker({ dateFormat: 'yy-mm-dd' });  
        
        $('.xlii-mediaselector li.nav:nth-child(2)').trigger('click');
    });
    
    /* MEDIA SELECTOR */
    // Append tab functionality
    $('.xlii-mediaselector li.nav').live('click', function(){
        if(!$(this).hasClass('active'))
        {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
            
            var parent = $(this).parents('.xlii-mediaselector:eq(0)');
            
            $('li.visible', parent).removeClass('visible');
            $('li.tab.' + $(this).attr('rel')).addClass('visible');
            
            $('.xlii-search', parent).trigger('keyup');
        }
    });
    
    // Append onclick change (for entire tab)
    $('.xlii-mediaselector li.tab').live('click', function(){
        var input  = $(this).find('.xlii-input');
        
        if(input.is(':checked') && input.attr('type') != 'radio')
            input.removeAttr('checked').trigger('change');
        else if(!input.is(':checked'))
            input.attr('checked', 'checked').trigger('change');
    });
    
    // Switch classes and manage active box
    $('.xlii-mediaselector li.tab .xlii-input').live('change', function(){
        var parent = $(this).parents('.xlii-mediaselector');
        var tab    = $(this).parents('li:eq(0)').toggleClass('active');
    
		if($(this).attr('type') == 'radio')
			$(this).parents().siblings('.active').removeClass('active');

        if($(this).attr('checked'))
        {
            var clone = tab.clone();
            clone.attr('rel', $(this).val());
            $('.xlii-input', clone).remove();
            $('.frontend ul.active', parent).append(clone);
        }
        else
        {
            $('.frontend .active li[rel="' + $(this).val() + '"]', parent).remove();
        }
        if($('li.nav.active[rel="active"]', parent).length)
        {
            tab.removeClass('visible');
            parent.trigger('paging');
        }
    });
    
    // Apply filter
    $('.xlii-mediaselector .xlii-search').live('keyup', function(){
        var parent = $(this).parents('.xlii-mediaselector:eq(0)');
        $('li.tab.filtered', parent).removeClass('filtered');
        if(this.value)
        {
            var search = this.value.toLowerCase().split(' ');
            $.each($('li.tab'), function(key, element){
                $.each(search, function(key, match){
                    if($('.title:eq(0)', element).html().toLowerCase().indexOf(match) < 0)
                    {
                        $(element).addClass('filtered');
                        return true;
                    }
                })
                
            });
        }
        parent.trigger('paging', [0]);
    });
    
    // Support paging
    $('.xlii-mediaselector').live('paging', function(event, page){
        page = typeof(page) != 'undefined' ? page : parseInt($('.controlls .page', this).val()); 
        amount = 20;
        
        $('li.paged', this).removeClass('paged');
        $('li.visible', this).not('.filtered').slice(page * amount, (page + 1) * amount).addClass('paged');
        
        $('.controlls .item', this).hide();
        if($('li.visible', this).not('.filtered').length > (page + 1) * amount)
            $('.controlls .next', this).show();
        
        if(page > 0)
            $('.controlls .prev', this).show();
        
        $('.controlls .page', this).val(page);
    });
    
    $('.xlii-mediaselector .controlls .next').live('click', function(){
        $(this).parents('.xlii-mediaselector').trigger('paging', [parseInt($(this).siblings('.page').val(), 10) + 1]);
    });
    
    $('.xlii-mediaselector .controlls .prev').live('click', function(){
        $(this).parents('.xlii-mediaselector').trigger('paging', [parseInt($(this).siblings('.page').val(), 10) - 1]);
    });
    
    // Support popup
    $('.xlii-showPopup').live('click', function(event){
        $('#xlii-popupOverlay').show();
        var popup = $('#' + $(this).attr('href')).show();
        
        // Set popup location
        popup.css({
            'top': ($(window).height() - popup.outerHeight()) / 2 + $(window).scrollTop(),
            'left': ($(window).width() - popup.outerWidth()) / 2 + $(window).scrollLeft()
        });
        
        event.preventDefault();
    });
    
    $('#xlii-popupOverlay').live('click', function(){
        $('.xlii-popup:visible').hide();
        $(this).hide();
    });
    
})(jQuery); 

        