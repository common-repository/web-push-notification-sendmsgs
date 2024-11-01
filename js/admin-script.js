/*
* SendMsgs - Admin Script
*/

function sendmsgsIsValidURL(url)
{
    var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
} 

function sendmsgsShowError(field, msg, focusSet)
{
	if (jQuery(field).parent().find(".error").length == 0 ) // only add if not added
	{
		jQuery(field).parent().append("<span class='error' >"+msg+"</span>");
	}
	else if(jQuery(field).parent().find(".error").text() != msg)
	{
		jQuery(field).parent().find(".error").text(msg);
	}
	if(!focusSet)
	{
	    jQuery(field).focus();
	}
}

function sendmsgsCleanError(field)
{
	if (jQuery(field).closest(".error").length > 0 ) // only add if not added
	{
		jQuery(field).closest(".error").text('');;
	}
}

jQuery(function($){

	//Validate the form and urls
	$('#sendmsgs-form').submit(function(){
		var url1 = $('#sendmsgs-scripturl-1');
		var url2 = $('#sendmsgs-scripturl-2');
		var url3 = $('#sendmsgs-scripturl-3');
		var focusSet = false;
		var isError	=	false;

		if(url1.val().trim() != '')
		{
			if(!sendmsgsIsValidURL(url1.val().trim()))
			{
				sendmsgsShowError(url1, 'Please enter a valid url', focusSet);
				focusSet = true;
				isError = true;
        	}
		}
		else
		{
			sendmsgsCleanError(url1);
		}

		if(url2.val().trim() == '')
		{
			sendmsgsShowError(url2, 'Please enter a url', focusSet);
			focusSet = true;
			isError = true;
		}
		else if(url2.val().trim() != '' && !sendmsgsIsValidURL(url2.val().trim()))
		{
			sendmsgsShowError(url2, 'Please enter a valid url', focusSet);	
			focusSet = true;
			isError = true;		
		}
		else
		{
			sendmsgsCleanError(url2);
		}

		if(url3.val().trim() == '')
		{
			sendmsgsShowError(url3, 'Please enter a url', focusSet);
			focusSet = true;
			isError = true;
		}
		else if(url3.val().trim() != '' && !sendmsgsIsValidURL(url3.val().trim()))
		{
			sendmsgsShowError(url3, 'Please enter a valid url', focusSet);
			focusSet = true;
			isError = true;
		}
		else
		{
			sendmsgsCleanError(url3);
		}
		return (!isError);
	});

	//Hide Show page options
	$('input:radio[name="sendmsgs_include"]').change(function(){
        if ($(this).is(':checked') && $(this).val() == 'all')
		{
			$('.sendmsgs_exclude_option').slideDown();
			jQuery('.sendmsgs-page-options_inc_box')
                .addClass('disabled')
                .find('input[type="checkbox"]').addClass('disabled');
		}
		else
		{
			$('.sendmsgs_exclude_option').slideUp();
			jQuery('.sendmsgs-page-options_inc_box')
                .removeClass('disabled')
                .find('input[type="checkbox"]').removeClass('disabled');
		}
    });

    $('#sendmsgs_exclude').change(function(){
        if ($(this).is(':checked'))
        {
            //$('.exclude_checkbox, #sendmsgs_page_options_exc_box').slideDown();
            jQuery('.sendmsgs-page-options_exc_box')
                .removeClass('disabled')
                .find('input[type="checkbox"]').removeClass('disabled');
        }
        else
        {
            //$('.exclude_checkbox, #sendmsgs_page_options_exc_box').slideUp();
            jQuery('.sendmsgs-page-options_exc_box')
                .addClass('disabled')
                .find('input[type="checkbox"]').addClass('disabled');
        }
    });

	//hide initialy
	if(!$('#sendmsgs_include_all').is(':checked'))
	{
		$('.sendmsgs_exclude_option').slideUp();
	}

	if (!$('#sendmsgs_exclude').is(':checked'))
    {
        //$('.exclude_checkbox, #sendmsgs_page_options_exc_box').slideDown();
        jQuery('.sendmsgs-page-options_exc_box')
            .addClass('disabled')
            .find('input[type="checkbox"]').addClass('disabled');
    }

    if(!$('#sendmsgs_include_specific').is(':checked'))
    {
        jQuery('.sendmsgs-page-options_inc_box')
            .addClass('disabled')
            .find('input[type="checkbox"]').addClass('disabled');
    }

	$('.sendmsgs-page-options input.checkbox').click(function(){
		if($(this).hasClass('disabled'))
		{
			return false;
		}
	});
});
