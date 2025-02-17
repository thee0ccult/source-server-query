//multiple modal backdrop fix
$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});
//multiple modal scrollbar fix
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

$.ajaxSetup({
	headers: {
      'X-CSRF-Token': $("#csrf-token").attr('content')
    },
	cache:false,
	error: function (request, status, error) {
		if(request.responseJSON){
			var strErrors = request.responseJSON.message + "<br>";
			var errors = request.responseJSON.errors;
		    $.each(errors, function(index, value){
		    	/*if($.isArray(value)){
		    		$.each(value, function(subindex, subvalue){
		    			strErrors += subvalue[0] + "<br>";
		    		});
	    		}
		    	else*/
			   		strErrors += value[0] + "<br>";
		    });
			
	        Codebase.helpers('notify', {
	            align: 'right',             // 'right', 'left', 'center'
	            from: 'top',                // 'top', 'bottom'
	            type: 'danger',               // 'info', 'success', 'warning', 'danger'
	            icon: 'fa fa-danger mr-5',    // Icon class
	            message: strErrors
	        });
        }
        Codebase.layout('header_loader_off');
    }
});

$( document ).ajaxSend(function( event, request, settings ) {
	Codebase.layout('header_loader_on');
	$(".spinning_status").show();
});

$( document ).ajaxStart(function() {
	Codebase.layout('header_loader_on');
	$(".spinning_status").show();
});

$( document ).ajaxComplete(function() {
	$(".spinning_status").hide();
	Codebase.layout('header_loader_off');
});

$( document ).ajaxStop(function( event, request, settings ) {
	$(".spinning_status").hide();
	Codebase.layout('header_loader_off');
});

window.proURIDecoder = function (val)
{
	val=val.replace(/\+/g, '%20');
	var str=val.split("%");
	var cval=str[0];
	for (var i=1;i<str.length;i++)
	{
		cval+=String.fromCharCode(parseInt(str[i].substring(0,2),16))+str[i].substring(2);
	}

	return cval;
}


window.fillDropDown = function (url, dropdown, bUrlEncoded, nSelectedId)
{
	bUrlEncoded = typeof bUrlEncoded !== 'undefined' ? bUrlEncoded : false;
	nSelectedId = typeof nSelectedId !== 'undefined' ? nSelectedId : false;
		
	$.ajax({
		url: url,
		dataType: "json",
		async:false,
	}).done(function (data) {
		// Clear drop down list
		$(dropdown).find("option").empty();
		// Fill drop down list with new data
		$(data).each(function () {
			// Create option
			var $option = $("<option />");
			// Add value and text to option
			if(bUrlEncoded)
				$option.attr("value", this.value).text(proURIDecoder(this.text));
			else
				$option.attr("value", this.value).text(this.text);
			
			// Add option to drop down list
			if(nSelectedId == this.value) $option.attr("selected", true);
			$(dropdown).append($option);
		});
	});
} 			

window.fillDropDownOptgroup = function (url, dropdown, bUrlEncoded, nSelectedId)
{
	bUrlEncoded = typeof bUrlEncoded !== 'undefined' ? bUrlEncoded : false;
	nSelectedId = typeof nSelectedId !== 'undefined' ? nSelectedId : false;
		
	$.ajax({
		url: url,
		dataType: "json"
	}).done(function (data) {

		$(dropdown).append("<option selected value=>--- Select ---</option>");
		
		$(data).each(function () {

			//insert optgroup tag
			if (this.label != undefined){
				var $optgroup = $("<optgroup />");
				
				if(bUrlEncoded)
					$optgroup.attr("label", proURIDecoder(this.label)).text(this.text);
				else
					$optgroup.attr("label", this.label).text(this.text);

				$optgroup.attr("id", "opt"+this.id)
			}

			//insert option tag in specific optgroup with id
			if (this.value != undefined){
				var $option = $("<option />");

				if(bUrlEncoded)
					$option.attr("value", this.value).text(proURIDecoder(this.text));
				else
					$option.attr("value", this.value).text(this.text);

				if(nSelectedId == this.value) $option.attr("selected", true);
				$("#opt"+this.parent_id).append($option);
			}
			
			$(dropdown).append($optgroup);
		});
	});
} 		