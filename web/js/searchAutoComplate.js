

function doquick_search( ev, keywords ) {
    if( ev.keyCode == 38 || ev.keyCode == 40 ) {
        return false;
    }   
    $('#ajax_search_results').remove();
     updown = -1;
    if( keywords == '' || keywords.length < 1 ) {
        return false;
    }   
    //keywords = encodeURI(keywords);
    $.ajax({url : './searchAction!getUsualSearchWord.go?keyword=' + keywords, dataType: 'json', success: function(result) { 
        if( result.length > 0 ) {
            var html, i;
            html = '<div id="ajax_search_results"><div id="ajax_search_results_body">';
            for(i=0;i<result.length;i++) {
                //html += '<div class="live_row search_word_item_wrapper" style="width:100%;">';
                html += '<div class="live_row search_word_item" style="width:100%;" code="' + result[i].text + '"><span class="name">' + result[i].text + '</span>';
                html += '</div>';
                //html += '</div>';
            }
            html += '</div></div>';   
            if( $('#ajax_search_results').length > 0 ) {
                $('#ajax_search_results').remove();
            }
            $('#search').append(html);
            
            $('#ajax_search_results .search_word_item').click(function(){
				var searchNameText = $(this).attr('code');
				if(!searchNameText){
					searchNameText = this.outText;
				}
				$('#search_input').val(searchNameText);
				gotoSearch();  
			});
            	
        }
    }});
    return true;
}

function upDownEvent( ev ) {
    var elem = document.getElementById('ajax_search_results_body');
    var fkey = $('#search').find('[name=search]').first();
    if( elem ) {
        var length = elem.childNodes.length - 1;
        if( updown != -1 && typeof(elem.childNodes[updown]) != 'undefined' ) {
            $(elem.childNodes[updown]).removeClass('selected');
        }
        if( ev.keyCode == 38 ) {
            updown = ( updown > 0 ) ? --updown : updown;    
        }
        else if( ev.keyCode == 40 ) {
            updown = ( updown < length ) ? ++updown : updown;
        }
        if( updown >= 0 && updown <= length ) {
            $(elem.childNodes[updown]).addClass('selected');
            var text = $(elem.childNodes[updown]).find('.name').html();
            $('#search').find('[name=search]').first().val(text);
        }
    }
    return false;
}
var updown = -1;


$(document).ready(function(){
    $('[name=search]').keyup(function(ev){   
        doquick_search(ev, this.value);
    }).focus(function(ev){
        doquick_search(ev, this.value);
    }).keydown(function(ev){
        upDownEvent( ev );
    }).blur(function(){
        window.setTimeout("$('#ajax_search_results').remove();updown=0;", 15000);
    });
});


