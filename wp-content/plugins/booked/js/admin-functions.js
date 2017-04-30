;(function($, window, document, undefined) {

	var $win = $(window);

	$.fn.spin.presets.booked = {
	 	lines: 10, // The number of lines to draw
		length: 7, // The length of each line
		width: 5, // The line thickness
		radius: 11, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color: '#555', // #rgb or #rrggbb or array of colors
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'booked-spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: '50%', // Top position relative to parent
		left: '50%' // Left position relative to parent
	}

	$win.on('load', function() {

		var currentlySaving = false;

		//$('body').bookedCalendarPopup();

		// Custom Time Slots
		var timeslotsContainter = $('#customTimeslotsContainer');

		timeslotsContainter.find('.booked-customTimeslot').each(function(){

			var thisTimeslot = $(this);
			var rand = Math.floor((Math.random() * 100000000) + 1);
			init_custom_timeslot_block(thisTimeslot,rand)

		});

		$('body').on('click','.addCustomTimeslot',function(e){

			e.preventDefault();
			var thisTimeslot = $('.booked-customTimeslotTemplate').clone().appendTo(timeslotsContainter).removeClass().addClass('booked-customTimeslot').show();
			var rand = Math.floor((Math.random() * 100000000) + 1);
			init_custom_timeslot_block(thisTimeslot,rand);

		});

		$('body').on('click','form.booked-form input[type=submit]',function(e){
			e.preventDefault();

			var customerType 		= $('#newAppointmentForm input[name=customer_type]:checked').val(),
				customerID			= $('#newAppointmentForm select[name=user_id]').val(),
				firstName			= $('#newAppointmentForm input[name=first_name]').val(),
				firstNameDefault	= $('#newAppointmentForm input[name=first_name]').attr('title'),
				lastName			= $('#newAppointmentForm input[name=last_name]').val(),
				email				= $('#newAppointmentForm input[name=email]').val(),
				emailDefault		= $('#newAppointmentForm input[name=email]').attr('title'),
				phone				= $('#newAppointmentForm input[name=phone]').val(),
				mobile				= $('#newAppointmentForm input[name=mobile]').val(),
				calendar_id			= $('#newAppointmentForm').attr('data-calendar-id'),
				showRequiredError	= false,
				ajaxRequests 		= [];
	
			$(this).parents('form.booked-form').find('input,textarea,select').each(function(i,field){

				var required = $(this).attr('required');

				if (required && $(field).attr('type') == 'hidden'){
					var fieldParts = $(field).attr('name');
					fieldParts = fieldParts.split('---');
					fieldName = fieldParts[0];
					fieldNumber = fieldParts[1].split('___');
					fieldNumber = fieldNumber[0];

					if (fieldName == 'radio-buttons-label'){
						var radioValue = false;
						$('input:radio[name="single-radio-button---'+fieldNumber+'[]"]:checked').each(function(){
							if ($(this).val()){
								radioValue = $(this).val();
							}
						});
						if (!radioValue){
							showRequiredError = true;
						}
					} else if (fieldName == 'checkboxes-label'){
						var checkboxValue = false;
						$('input:checkbox[name="single-checkbox---'+fieldNumber+'[]"]:checked').each(function(){
							if ($(this).val()){
								checkboxValue = $(this).val();
							}
						});
						if (!checkboxValue){
							showRequiredError = true;
						}
					}

				} else if (required && $(field).attr('type') != 'hidden' && $(field).val() == ''){
		            showRequiredError = true;
		        }

		    });

		    if (showRequiredError){
			    alert(i18n_fill_out_required_fields);
			    return false;
		    }
		    
		    if (customerType == 'guest'){

				$('form.booked-form input').each(function(){
					thisDefault = $(this).attr('title');
					thisVal = $(this).val();
					if (thisDefault == thisVal){ $(this).val(''); }
				});

				$(this).val('Please wait...').attr('disabled',true);
				$(this).parents('form').find('button.cancel').attr('disabled',true);

				var booked_ajaxURL	= $('#data-ajax-url').html(),
					$activeTD		= $('td.active');

				ajaxRequests.push = $.ajaxQueue({
					type	: 'post',
					url 	: booked_ajaxURL,
					data	: $('form.booked-form').serializeArray(),
					success: function(date) {
						//alert(date);
						$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
							$('tr.entryBlock').find('.booked-appt-list').show();
							$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
							$('.bookedAppointmentTab.active').fadeIn(300);
						});
						$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':date,'calendar_id':calendar_id},function(){
							var self = $(this);
							self.replaceWith(self.children());
							adjust_calendar_boxes();
						});
						close_booked_modal();
					}
				});

				return false;

			}

			if (customerType == 'current' && customerID){

				$('form.booked-form input').each(function(){
					thisDefault = $(this).attr('title');
					thisVal = $(this).val();
					if (thisDefault == thisVal){ $(this).val(''); }
				});

				$(this).val('Please wait...').attr('disabled',true);
				$(this).parents('form').find('button.cancel').attr('disabled',true);

				var booked_ajaxURL	= $('#data-ajax-url').html(),
					$activeTD		= $('td.active');

				ajaxRequests.push = $.ajaxQueue({
					type	: 'post',
					url 	: booked_ajaxURL,
					data	: $('form.booked-form').serializeArray(),
					success: function(date) {
						$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
							$('tr.entryBlock').find('.booked-appt-list').show();
							$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
							$('.bookedAppointmentTab.active').fadeIn(300);
						});
						$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':date,'calendar_id':calendar_id},function(){
							var self = $(this);
							self.replaceWith(self.children());
							adjust_calendar_boxes();
						});
						close_booked_modal();
					}
				});

				return false;

			} else if (customerType == 'current' && !customerID){

				alert(i18n_choose_customer);

			}

			if (customerType == 'new' && firstName != firstNameDefault && email != emailDefault){

				$('form.booked-form input').each(function(){
					thisDefault = $(this).attr('title');
					thisVal = $(this).val();
					if (thisDefault == thisVal){ $(this).val(''); }
				});

				$thisButton = $(this);

				$thisButton.val('Please wait...').attr('disabled',true);
				$thisButton.parents('form').find('button.cancel').attr('disabled',true);

				var booked_ajaxURL	= $('#data-ajax-url').html(),
					$activeTD		= $('td.active');

				ajaxRequests.push = $.ajaxQueue({
					type	: 'post',
					url 	: booked_ajaxURL,
					data	: $('form.booked-form').serialize(),
					success: function(data) {

						data = data.split('###');

						if (data[0] != 'success'){

							console.log(data[0]);

							// There was an error!
							$thisButton.val('Create Appointment').attr('disabled',false);
							$thisButton.parents('form').find('button.cancel').attr('disabled',false);

							$('form.booked-form input').each(function(){
								thisDefault = $(this).attr('title');
								thisVal = $(this).val();
								if (!thisVal){ $(this).val(thisDefault); }
							});

							alert(data[1]);

						} else {

							$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':data[1],'calendar_id':calendar_id},function(){
								$('tr.entryBlock').find('.booked-appt-list').show();
								$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
								$('.bookedAppointmentTab.active').fadeIn(300);
							});
							$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':data[1],'calendar_id':calendar_id},function(){
								var self = $(this);
								self.replaceWith(self.children());
								adjust_calendar_boxes();
							});
							close_booked_modal();

						}
					}
				});

				return false;

			} else if (customerType == 'new' && firstName == firstNameDefault || customerType == 'new' && email == emailDefault){

				alert(i18n_appt_required_fields);

			}

		});

		function init_custom_timeslot_block(thisTimeslot,rand){

			hideAddCustomTimeslotsForm();
			thisTimeslot.find('#vacationDayCheckbox').attr('id','vacationDayCheckbox-'+rand);
			thisTimeslot.find('label[for="vacationDayCheckbox"]').attr('for','vacationDayCheckbox-'+rand);

			var disableApptsValue = thisTimeslot.find('#vacationDayCheckbox-'+rand).is(':checked');
			if (disableApptsValue){
				hideAddCustomTimeslotsForm();
				thisTimeslot.find('button.addSingleTimeslot,button.addBulkTimeslots,.customTimeslotsList').hide();
			} else {
				$('button.addBulkTimeslots').attr('disabled',false);
				$('button.addSingleTimeslot').attr('disabled',false);
				$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').show();
			}

			thisTimeslot.on('change','#vacationDayCheckbox-'+rand,function(){
				thisCheckbox = $(this);
				if (thisCheckbox.is(':checked')){
					hideAddCustomTimeslotsForm();
					thisTimeslot.find('button.addSingleTimeslot,button.addBulkTimeslots,.customTimeslotsList').hide();
				} else {
					$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').show();
					thisTimeslot.find('button.addSingleTimeslot,button.addBulkTimeslots,.customTimeslotsList').show();
				}
			});

			thisTimeslot.find('.booked_custom_start_date').datepicker({
		        beforeShow: function(input, inst) {
					$('#ui-datepicker-div').removeClass();
					$('#ui-datepicker-div').addClass('booked_custom_date_picker');
			    },
		        onSelect: function(selected) {
		        	thisTimeslot.find('.booked_custom_end_date').datepicker("option","minDate", selected);
		        	updateCustomTimeslotEncodedField();
		        }
		    });
		    thisTimeslot.find('.booked_custom_end_date').datepicker({
		        beforeShow: function(input, inst) {
					$('#ui-datepicker-div').removeClass();
					$('#ui-datepicker-div').addClass('booked_custom_date_picker');
			    },
		        onSelect: function(selected) {
		           thisTimeslot.find('.booked_custom_start_date').datepicker("option","maxDate", selected);
		           updateCustomTimeslotEncodedField();
		        }
		    });

			thisTimeslot.on('click','button.addSingleTimeslot',function(e){
				e.preventDefault();
				$(this).attr('disabled',true);
				$('button.addBulkTimeslots').attr('disabled',false);
				$('#booked-saveCustomTimeslots').attr('disabled',true);
				$('#booked-customTimePickerTemplates').find('select,input').val('').attr('checked',false);
				$('#booked-customTimePickerTemplates').appendTo(thisTimeslot).show();
				$('#booked-customTimePickerTemplates').find('.customBulk').hide();
				$('#booked-customTimePickerTemplates').find('.customSingle').hide().fadeIn(200);
			});

			thisTimeslot.on('click','button.addBulkTimeslots',function(e){
				e.preventDefault();
				$(this).attr('disabled',true);
				$('button.addSingleTimeslot').attr('disabled',false);
				$('#booked-saveCustomTimeslots').attr('disabled',true);
				$('#booked-customTimePickerTemplates').find('select,input').val('').attr('checked',false);
				$('#booked-customTimePickerTemplates').appendTo(thisTimeslot).show();
				$('#booked-customTimePickerTemplates').find('.customBulk').hide().fadeIn(200);
				$('#booked-customTimePickerTemplates').find('.customSingle').hide();
			});

			$('#booked-customTimePickerTemplates').on('click','button.cancel',function(e){
				e.preventDefault();
				hideAddCustomTimeslotsForm();
			});

			thisTimeslot.on('click','.deleteCustomTimeslot',function(e){
				e.preventDefault();
				var confirmDelete = confirm(i18n_confirm_cts_delete);
				if (confirmDelete){
					$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').hide();
					$('#booked-customTimePickerTemplates').hide().appendTo('#booked-custom-timeslots');
					$('button.addBulkTimeslots').attr('disabled',false);
					$('button.addSingleTimeslot').attr('disabled',false);
					$('#booked-saveCustomTimeslots').attr('disabled',false);
					$(this).parents('.booked-customTimeslot').remove();
					updateCustomTimeslotEncodedField();
				}
			});

			thisTimeslot.on('change','> input, > select',function(){
				updateCustomTimeslotEncodedField();
			});

			thisTimeslot.on('change','input#all_day_custom',function(){

				var thisCheckbox = $(this);

				if (thisCheckbox.is(':checked')){
					thisCheckbox.parents('.customSingle').find('select[name="startTime"]').val('0000').hide();
					thisCheckbox.parents('.customSingle').find('select[name="endTime"]').val('2400').hide();
				} else {
					thisCheckbox.parents('.customSingle').find('select[name="startTime"]').val('').show();
					thisCheckbox.parents('.customSingle').find('select[name="endTime"]').val('').show();
				}

			});

			thisTimeslot.on('click','.customTimeslotsList .delete',function(e){
				e.preventDefault();

				var confirmDelete = confirm(i18n_confirm_cts_delete);
				if (confirmDelete){

					var booked_ajaxURL  	= $('#data-ajax-url').html(),
						thisButton			= $(this),
						thisTimeslot		= thisButton.parent();
						deleteTimeslot 		= thisButton.parent().attr('data-timeslot'),
						currentTimesBox 	= thisButton.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]'),
						currentArray 		= thisButton.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]').val(),
						timeslotList		= thisButton.parents('.booked-customTimeslot').find('.customTimeslotsList');

					ajaxRequests.push = $.ajaxQueue({
						type	: 'post',
						url 	: booked_ajaxURL,
						data	: {
							'action'     	: 'delete_custom_timeslot',
							'timeslot'     	: deleteTimeslot,
							'currentArray'	: currentArray,
						},
						beforeSend: function(){
							savingState(true);
						},
						success: function(data) {
							currentTimesBox.val(data);
							$('#booked-customTimePickerTemplates').find('select,input').val('').attr('checked',false);
							$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').hide();
							$('#booked-customTimePickerTemplates').hide().appendTo('#booked-custom-timeslots');
							$('button.addBulkTimeslots').attr('disabled',false);
							$('button.addSingleTimeslot').attr('disabled',false);
							$('#booked-saveCustomTimeslots').attr('disabled',false);
							$('.customSingle').find('select[name="startTime"]').val('').show();
							$('.customSingle').find('select[name="endTime"]').val('').show();
							updateCustomTimeslotEncodedField();
							loadCustomTimeSlots(booked_ajaxURL,timeslotList,data);
							thisTimeslot.slideUp(200);
							$('#booked-saveCustomTimeslots').trigger('click');
						}
					});

				}

			});

			var preventMultiClicks;

			thisTimeslot.on('click','.changeCount',function(e){
				e.preventDefault();

				if (!currentlySaving){

					var $button      	= $(this),
						$countText	 	= $button.parent().find('.count'),
						countAdjust  	= $button.attr('data-count'),
						currentTimesBox = $button.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]'),
						currentArray 	= $button.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]').val(),
						timeslot 		= $button.parents('.timeslot').attr('data-timeslot'),
						booked_ajaxURL  = $('#data-ajax-url').html(),
						currentCount 	= $countText.find('em').text();

					clearTimeout(preventMultiClicks);

					newCount = parseInt(currentCount) + parseInt(countAdjust);
					if (newCount < 1) {

						newCount = 1;

					} else {

						if (newCount != 1) { slot_text = i18n_slots; } else { slot_text = i18n_slot; }
						$countText.html('<em>' + newCount + '</em> ' + slot_text);

						preventMultiClicks = setTimeout(function(){

							savingState(true);
							currentlySaving = true;

							ajaxRequests.push = $.ajaxQueue({
								type	: 'post',
								url 	: booked_ajaxURL,
								data	: {
									'action'     	: 'adjust_custom_timeslot_count',
									'newCount'		: newCount,
									'timeslot'     	: timeslot,
									'currentArray'	: currentArray,
								},
								success: function(data) {
									currentTimesBox.val(data);
									updateCustomTimeslotEncodedField();
									$('#booked-saveCustomTimeslots').trigger('click');
									currentlySaving = false;
								}
							});

						},400);

					}

				}

			});

			// Single add
			thisTimeslot.on('click','.addSingleTimeslot_button',function(e){
				e.preventDefault();

				var $button = $(this);
				$button.attr('disabled',true);

				var addTimeslotsFormWrapper = $('#booked-customTimePickerTemplates .customSingle');

				var booked_ajaxURL  = $('#data-ajax-url').html(),
					startTime 		= addTimeslotsFormWrapper.find('select[name=startTime]').val(),
					startTimeText	= addTimeslotsFormWrapper.find('select[name=startTime] :selected').text(),
					endTime 		= addTimeslotsFormWrapper.find('select[name=endTime]').val(),
					endTimeText		= addTimeslotsFormWrapper.find('select[name=endTime] :selected').text(),
					count 			= addTimeslotsFormWrapper.find('select[name=count]').val(),
					countText		= addTimeslotsFormWrapper.find('select[name=count] :selected').text(),
					currentTimesBox	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]'),
					currentTimes 	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]').val(),
					timeslotList	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('.customTimeslotsList');

				if (startTime && endTime && count){

					if (endTime <= startTime){
						$button.attr('disabled',false);
						alert(i18n_time_error);
						return false;
					}

					if (startTime == '0000' && endTime == '2400'){
						appt_add_confirm = confirm(i18n_single_add_confirm + ':\n'+i18n_all_day+' x'+count);
					} else {
						appt_add_confirm = confirm(i18n_single_add_confirm + ':\n'+startTimeText+' '+i18n_to+' '+endTimeText+' x'+count);
					}

					if (appt_add_confirm == true){
						ajaxRequests.push = $.ajaxQueue({
							type	: 'post',
							url 	: booked_ajaxURL,
							data	: {
								'action'     			: 'add_custom_timeslot',
								'startTime'     		: startTime,
								'endTime'     			: endTime,
								'count'     			: count,
								'currentTimes'			: currentTimes,
							},
							beforeSend: function(){
								savingState(true);
							},
							success: function(data) {
								currentTimesBox.val(data);
								$button.attr('disabled',false);
								$('#booked-customTimePickerTemplates').find('select,input').val('').attr('checked',false);
								$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').hide();
								$('#booked-customTimePickerTemplates').hide().appendTo('#booked-custom-timeslots');
								$('button.addBulkTimeslots').attr('disabled',false);
								$('button.addSingleTimeslot').attr('disabled',false);
								$('#booked-saveCustomTimeslots').attr('disabled',false);
								$('.customSingle').find('select[name="startTime"]').val('').show();
								$('.customSingle').find('select[name="endTime"]').val('').show();
								updateCustomTimeslotEncodedField();
								loadCustomTimeSlots(booked_ajaxURL,timeslotList,data);
								$('#booked-saveCustomTimeslots').trigger('click');
							}
						});

					}
				} else {
					$button.attr('disabled',false);
					alert(i18n_all_fields_required);
					return false;
				}

			});

			// Bulk add
			thisTimeslot.on('click','.addBulkTimeslots_button',function(e){
				e.preventDefault();

				var $button = $(this);
				$button.attr('disabled',true);

				var addTimeslotsFormWrapper = $('#booked-customTimePickerTemplates .customBulk');

				var booked_ajaxURL  = $('#data-ajax-url').html(),
					startTime 		= addTimeslotsFormWrapper.find('select[name=startTime]').val(),
					startTimeText	= addTimeslotsFormWrapper.find('select[name=startTime] :selected').text(),
					endTime 		= addTimeslotsFormWrapper.find('select[name=endTime]').val(),
					endTimeText		= addTimeslotsFormWrapper.find('select[name=endTime] :selected').text(),
					interval 		= addTimeslotsFormWrapper.find('select[name=interval]').val(),
					time_between 	= addTimeslotsFormWrapper.find('select[name=time_between]').val(),
					intervalText	= addTimeslotsFormWrapper.find('select[name=interval] :selected').text(),
					count 			= addTimeslotsFormWrapper.find('select[name=count]').val(),
					countText		= addTimeslotsFormWrapper.find('select[name=count] :selected').text(),
					currentTimesBox	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]'),
					currentTimes 	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('input[name=booked_this_custom_timelots]').val(),
					timeslotList	= addTimeslotsFormWrapper.parents('.booked-customTimeslot').find('.customTimeslotsList');

				if (startTime && endTime && interval && count){

					if (endTime <= startTime){
						$button.attr('disabled',false);
						alert(i18n_time_error);
						return false;
					}

					appt_add_confirm = confirm(i18n_bulk_add_confirm);
					if (appt_add_confirm == true){

						ajaxRequests.push = $.ajaxQueue({
							type	: 'post',
							url 	: booked_ajaxURL,
							data	: {
								'action'     			: 'add_custom_timeslots',
								'startTime'     		: startTime,
								'endTime'     			: endTime,
								'interval'     			: interval,
								'time_between'     		: time_between,
								'count'     			: count,
								'currentTimes'			: currentTimes,
							},
							beforeSend: function(){
								savingState(true);
							},
							success: function(data) {
								currentTimesBox.val(data);
								$button.attr('disabled',false);
								$('#booked-customTimePickerTemplates').find('select,input').val('').attr('checked',false);
								$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').hide();
								$('#booked-customTimePickerTemplates').hide().appendTo('#booked-custom-timeslots');
								$('button.addBulkTimeslots').attr('disabled',false);
								$('button.addSingleTimeslot').attr('disabled',false);
								$('#booked-saveCustomTimeslots').attr('disabled',false);
								updateCustomTimeslotEncodedField();
								loadCustomTimeSlots(booked_ajaxURL,timeslotList,data);
								$('#booked-saveCustomTimeslots').trigger('click');
							}
						});

					}
				} else {
					$button.attr('disabled',false);
					alert(i18n_all_fields_required);
					return false;
				}

			});

		}

		function hideAddCustomTimeslotsForm(){

			$('#booked-customTimePickerTemplates').find('.customBulk,customSingle').hide();
			$('#booked-customTimePickerTemplates').hide().appendTo('#booked-custom-timeslots');
			$('button.addBulkTimeslots').attr('disabled',false);
			$('button.addSingleTimeslot').attr('disabled',false);
			$('#booked-saveCustomTimeslots').attr('disabled',false);
			updateCustomTimeslotEncodedField();

		}

		function loadCustomTimeSlots(booked_ajaxURL,timeslotsBlock,json_array){
			timeslotsBlock.load(booked_ajaxURL, {'load':'custom_timeslots_list','json_array':json_array});
		}

		$('#booked-custom-timeslots').on('click','#booked-saveCustomTimeslots',function(e){
			e.preventDefault();
			var booked_ajaxURL				= $('#data-ajax-url').html(),
				custom_timeslots_encoded	= $('#custom_timeslots_encoded').val();

			ajaxRequests.push = $.ajaxQueue({
				type	: 'post',
				url 	: booked_ajaxURL,
				data	: {
					'action'     				: 'save_custom_time_slots',
					'custom_timeslots_encoded'  : custom_timeslots_encoded
				},
				beforeSend: function(){
					$('#booked-saveCustomTimeslots').attr('disabled',true);
					savingState(true);
				},
				success: function(data) {
					//alert(data);
					$('#booked-saveCustomTimeslots').attr('disabled',false);
					$('#customTimeslotsContainer .booked-customTimeslot').css('border-color','#ddd');
				}
			});

		});
		/* END Custom Time Slots */



		if ($('.booked-color-field').length){
			$('.booked-color-field').wpColorPicker();
		}

		// Upload Image Button
		var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;

		$('#booked_email_logo_button').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
			var id = button.attr('id').replace('_button', '');
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					$("#"+id).val(attachment.url);
					$("#"+id+"-img").attr('src',attachment.url);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}

			wp.media.editor.open(button);
			return false;
		});
		// END Upload Image Button
		
		// Custom Fields
		var CF_SortablesForm				= $('#booked-cf-sortables-form'),
			CF_SortablesContainer			= $('#booked-cf-sortables'),
			CF_SortablesTemplatesContainer	= $('#booked-cf-sortable-templates'),
			CF_SingleLineTextTemplate		= $('#bookedCFTemplate-single-line-text'),
			CF_ParagraphTextTemplate		= $('#bookedCFTemplate-paragraph-text'),
			CF_CheckboxesTemplate			= $('#bookedCFTemplate-checkboxes'),
			CF_RadioButtonsTemplate			= $('#bookedCFTemplate-radio-buttons'),
			CF_DropDownTemplate				= $('#bookedCFTemplate-drop-down');

		init_booked_custom_fields();
		
		$('body').on('keyup','#booked-cf-sortables input',function() {
			update_CF_Data(CF_SortablesForm);
		});

		$('body').on('click','.booked-cf-block .cfButton',function(e){

			e.preventDefault();
			var CF_ButtonType = $(this).attr('data-type');
			appendLocation = $(this).parent().find('ul:first');

			var newSortable = CF_SortablesTemplatesContainer.find('#bookedCFTemplate-'+CF_ButtonType).clone().appendTo(appendLocation);

			// Assign this field a random number
			var thisInput = newSortable.find('input[name="'+CF_ButtonType+'"]');
			var thisRequiredCheckbox = newSortable.find('input[name="required"]');
			if (CF_ButtonType == 'single-radio-button'){
				var thisTextField = $(this).parents('li').find('input[type=text]:first').attr('name');
				thisTextField = thisTextField.split('---');
				var randomNumber = thisTextField[1];
				randomNumber = randomNumber.split('___');
				randomNumber = randomNumber[0];
			} else if (CF_ButtonType == 'single-checkbox'){
				var thisTextField = $(this).parents('li').find('input[type=text]:first').attr('name');
				thisTextField = thisTextField.split('---');
				var randomNumber = thisTextField[1];
				randomNumber = randomNumber.split('___');
				randomNumber = randomNumber[0];
			} else {
				var randomNumber = Math.floor((Math.random() * 9999999) + 1000000);
			}
			thisInput.attr('name',CF_ButtonType+'---'+randomNumber);
			thisRequiredCheckbox.attr('name','required---'+randomNumber).attr('id','required---'+randomNumber).parent().find('label').attr('for','required---'+randomNumber);
			thisInput.css('border-color','#FFBA00');

			/*
			 * allow other script to apply their modification before initializing the sortables
			 * $(document).on("booked-on-cbutton-click", function(event, params) { code goes here });
			 */
			$(document).trigger("booked-on-cbutton-click", {
				button_object: $(this),
				this_input: thisInput,
				button_type: CF_ButtonType,
				random_number: randomNumber
			});

			CF_SortablesContainer.show();
			init_booked_cf_sortables();

		});

		$('body').on('change','.booked-cf-block .cf-required-checkbox',function(e){
			var thisCheckboxVal = $(this).attr('checked');
			var thisTextField = $(this).parents('li').find('input[type=text]:first');
			var currentValue = thisTextField.attr('name');
			if (thisCheckboxVal == 'checked'){
				thisTextField.attr('name',currentValue+'___required');
			} else {
				currentValue = currentValue.split('___');
				currentValue = currentValue[0];
				thisTextField.attr('name',currentValue);
			}
			update_CF_Data(CF_SortablesForm);
		});

		$('body').on('click','.booked-cf-block .cf-delete',function(e){
			var confirm_delete = confirm("Are you sure you want to delete this field?");
			if (confirm_delete){
				$(this).parent().remove();
				if ($('#booked-cf-sortables').is(':empty')){
					$('#booked-cf-sortables').hide();
				}
				update_CF_Data(CF_SortablesForm);
			}
		});

		function update_CF_Data(CF_SortablesForm){
			var sortableContent = JSON.stringify(CF_SortablesForm.serializeArray());
			$('#booked_custom_fields').val(sortableContent);
		}

		function init_booked_custom_fields(){
			
			CF_SortablesForm				= $('#booked-cf-sortables-form');
			CF_SortablesContainer			= $('#booked-cf-sortables');
			CF_SortablesTemplatesContainer	= $('#booked-cf-sortable-templates');
			CF_SingleLineTextTemplate		= $('#bookedCFTemplate-single-line-text');
			CF_ParagraphTextTemplate		= $('#bookedCFTemplate-paragraph-text');
			CF_CheckboxesTemplate			= $('#bookedCFTemplate-checkboxes');
			CF_RadioButtonsTemplate			= $('#bookedCFTemplate-radio-buttons');
			CF_DropDownTemplate				= $('#bookedCFTemplate-drop-down');
	
			if (CF_SortablesContainer.length){
	
				if (!CF_SortablesContainer.is(':empty')){
					CF_SortablesContainer.show();
				}
	
				var CF_SortingObject = CF_SortablesContainer.sortable({
					handle: ".main-handle",
					stop: function(){
						update_CF_Data(CF_SortablesForm);
					}
				});
	
				init_booked_cf_sortables();
	
			}
			
		}

		function init_booked_cf_sortables(){
			
			CF_SortablesForm = $('#booked-cf-sortables-form');
			
			$('#booked-cf-checkboxes').sortable({
				handle: ".sub-handle",
				stop: function(){
					update_CF_Data(CF_SortablesForm);
				}
			});
			$('#booked-cf-radio-buttons').sortable({
				handle: ".sub-handle",
				stop: function(){
					update_CF_Data(CF_SortablesForm);
				}
			});
			$('#booked-cf-drop-down').sortable({
				handle: ".sub-handle",
				stop: function(){
					update_CF_Data(CF_SortablesForm);
				}
			});

			update_CF_Data(CF_SortablesForm);
		}

		$('#booked-custom-fields').on('click','#booked-cf-saveButton',function(e){
			e.preventDefault();
			var booked_ajaxURL			= $('#data-ajax-url').html(),
				booked_custom_fields	= $('#booked_custom_fields').val(),
				booked_cf_calendar_id	= $('#booked-cfSwitcher select').val();

			ajaxRequests.push = $.ajaxQueue({
				type	: 'post',
				url 	: booked_ajaxURL,
				data	: {
					'action'     			: 'save_custom_fields',
					'booked_custom_fields'  : booked_custom_fields,
					'booked_cf_calendar_id'	: booked_cf_calendar_id
				},
				beforeSend: function(){
					$('#booked-cf-saveButton').attr('disabled',true);
					savingState(true);
				},
				success: function(data) {
					//alert(data);
					$('#booked-cf-saveButton').attr('disabled',false);
					$('#booked-cf-sortables input[type=text]').css('border-color','#ccc');
				}
			});

		});
		// END Custom Fields



		var checkedClass = 'custom-input-checked';
		var disabledClass = 'custom-input-disabled';
		var ajaxRequests = [];

		/* Main Admin Tabs */
		if ($('.booked-admin-tabs').length){

			// Tabs
			$('.tab-content').hide();

			var adminTabs 	= $('.booked-admin-tabs');
			var tabHash 	= window.location.hash;

			if (tabHash){
				var activeTab = tabHash;
				activeTab = activeTab.split('#');
				activeTab = activeTab[1];
				adminTabs.find('li').removeClass('active');
				adminTabs.find('a[href="'+tabHash+'"]').parent().addClass('active');
				$('#booked-'+activeTab).show();
			} else {
				var activeTab = adminTabs.find('.active > a').attr('href');
				activeTab = activeTab.split('#');
				activeTab = activeTab[1];
				$('#booked-'+activeTab).show();
			}

			adminTabs.find('li > a').on('click', function(e) {

				//e.preventDefault();
				$('.tab-content').hide();
				adminTabs.find('li').removeClass('active');

				$(this).parent().addClass('active');
				var activeTab = $(this).attr('href');
				activeTab = activeTab.split('#');
				activeTab = activeTab[1];

				if (activeTab == 'import_export_uninstall'){
					$('.submit-section').hide();
				} else {
					$('.submit-section').show();
				}

				$('#booked-'+activeTab).show();

			});

		}

		/* Click the Cancel button */
		$('#bookedTimeslotsWrap').on('click','td.addTimeslot .cancel',function(e){
			e.preventDefault();
			$('td.addTimeslot').find('select').val('');
			$('td.addTimeslot').removeClass('active');
			$('td.addTimeslot a.button').html(i18n_add + ' ...').removeClass('button-primary');
			$('#timepickerTemplate').appendTo('#booked-defaults').hide();
		});

		$('#bookedTimeslotsWrap').on('change','input[name="all_day"]',function(){

			var thisCheckbox = $(this);

			if (thisCheckbox.is(':checked')){
				thisCheckbox.parents('.tsTabContent').find('select[name="startTime"]').val('0000').hide();
				thisCheckbox.parents('.tsTabContent').find('select[name="endTime"]').val('2400').hide();
			} else {
				thisCheckbox.parents('.tsTabContent').find('select[name="startTime"]').val('').show();
				thisCheckbox.parents('.tsTabContent').find('select[name="endTime"]').val('').show();
			}

		});

		/* Click the Add button */
		$('#bookedTimeslotsWrap').on('click','td.addTimeslot a.button',function(e){
			e.preventDefault();
			parentBlock = $(this).parent();
			allTimeslotParents = $('#bookedTimeslotsWrap td.addTimeslot');

			if (parentBlock.hasClass('active')){

				var activeTab = $('.addTimeslotTab.active').attr('href');
				if (activeTab == '#Bulk'){

					// Bulk add
					var $button      	= $(this),
						day	 	 	 	= $button.parents('td').attr('data-day'),
						booked_ajaxURL  = $('#data-ajax-url').html(),
						startTime 		= $('.tsBulk').find('select[name=startTime]').val(),
						startTimeText	= $('.tsBulk').find('select[name=startTime] :selected').text(),
						endTime 		= $('.tsBulk').find('select[name=endTime]').val(),
						endTimeText		= $('.tsBulk').find('select[name=endTime] :selected').text(),
						interval 		= $('.tsBulk').find('select[name=interval]').val(),
						time_between 	= $('.tsBulk').find('select[name=time_between]').val(),
						intervalText	= $('.tsBulk').find('select[name=interval] :selected').text(),
						count 			= $('.tsBulk').find('select[name=count]').val(),
						calendar_id		= $('table.booked-timeslots').attr('data-calendar-id'),
						countText		= $('.tsBulk').find('select[name=count] :selected').text();

					if (startTime && endTime && interval && count){

						if (endTime <= startTime){
							alert(i18n_time_error);
							return false;
						}

						appt_add_confirm = confirm(i18n_bulk_add_confirm);
						if (appt_add_confirm == true){

							ajaxRequests.push = $.ajaxQueue({
								'url' : booked_ajaxURL,
								'data': {
									'action'     			: 'add_timeslots',
									'day'     				: day,
									'calendar_id'			: calendar_id,
									'startTime'     		: startTime,
									'endTime'     			: endTime,
									'interval'     			: interval,
									'time_between'     		: time_between,
									'count'     			: count,
								},
								beforeSend: function(){
									savingState(true);
								},
								success: function(data) {
									$('input[name="all_day"]').attr('checked',false);
									$('td.addTimeslot').find('select').val('').show();
									$('td.addTimeslot').removeClass('active');
									$('td.addTimeslot a').html(i18n_add + ' ...').removeClass('button-primary');
									$('#timepickerTemplate').appendTo('#booked-defaults').hide();
									$('td.dayTimeslots[data-day="'+day+'"]').load(booked_ajaxURL, {'load':'load_timeslots','day':day,'calendar_id':calendar_id});
								}
							});

						}
					} else {
						alert(i18n_all_fields_required);
					}

				} else {

					// Single add
					var $button      	= $(this),
						day	 	 	 	= $button.parents('td').attr('data-day'),
						booked_ajaxURL  = $('#data-ajax-url').html(),
						startTime 		= $('.tsSingle').find('select[name=startTime]').val(),
						startTimeText	= $('.tsSingle').find('select[name=startTime] :selected').text(),
						endTime 		= $('.tsSingle').find('select[name=endTime]').val(),
						endTimeText		= $('.tsSingle').find('select[name=endTime] :selected').text(),
						count 			= $('.tsSingle').find('select[name=count]').val(),
						calendar_id		= $('table.booked-timeslots').attr('data-calendar-id'),
						countText		= $('.tsSingle').find('select[name=count] :selected').text();

					if (startTime && endTime && count){

						if (endTime <= startTime){
							alert(i18n_time_error);
							return false;
						}

						if (startTime == '0000' && endTime == '2400'){
							appt_add_confirm = confirm(i18n_single_add_confirm + ':\n'+i18n_all_day+' x'+count);
						} else {
							appt_add_confirm = confirm(i18n_single_add_confirm + ':\n'+startTimeText+' '+i18n_to+' '+endTimeText+' x'+count);
						}

						if (appt_add_confirm == true){

							ajaxRequests.push = $.ajaxQueue({
								'url' : booked_ajaxURL,
								'data': {
									'action'     			: 'add_timeslot',
									'day'     				: day,
									'calendar_id'			: calendar_id,
									'startTime'     		: startTime,
									'endTime'     			: endTime,
									'count'     			: count,
								},
								beforeSend: function(){
									savingState(true);
								},
								success: function(data) {
									$('input[name="all_day"]').attr('checked',false);
									$('td.addTimeslot').find('select').val('').show();
									$('td.addTimeslot').removeClass('active');
									$('td.addTimeslot a').html(i18n_add + ' ...').removeClass('button-primary');
									$('#timepickerTemplate').appendTo('#booked-defaults').hide();
									$('td.dayTimeslots[data-day="'+day+'"]').load(booked_ajaxURL, {'load':'load_timeslots','day':day,'calendar_id':calendar_id});
								}
							});

						}
					} else {
						alert(i18n_all_fields_required);
					}

				}

			} else {
				allTimeslotParents.find('select').val('');
				allTimeslotParents.removeClass('active');
				allTimeslotParents.find('a.button').html(i18n_add + ' ...').removeClass('button-primary');
				$('#timepickerTemplate').appendTo('#booked-defaults').hide();
				parentBlock.addClass('active');
				$(this).html(i18n_add).addClass('button-primary');
				init_timeslot_tabs();
				$('#timepickerTemplate').prependTo(parentBlock).slideDown('fast');
			}
		});

		$('#bookedTimeslotsWrap').on('change','select[name=startTime]',function(e) {
			var endTimeSelect = $(this).parent().find('select[name=endTime]');
			var startTimeVal = $(this).val();
			endTimeSelect.find('option').removeAttr('disabled');
			endTimeSelect.find('option').each(function() {
				var thisVal = $(this).val();
				if (thisVal <= startTimeVal){
					$(this).attr('disabled',true);
				}
			});
		});

		// Change Timeslot Count
		$('#bookedTimeslotsWrap').on('click', '.slotsBlock .changeCount', function(e) {

			e.preventDefault();

			var $button      	= $(this),
				$countText	 	= $button.parent().find('.count'),
				day	 	 	 	= $button.parents('td').attr('data-day'),
				timeslot	 	= $button.parents('.timeslot').attr('data-timeslot'),
				countAdjust  	= $button.attr('data-count'),
				booked_ajaxURL  = $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-timeslots').attr('data-calendar-id'),
				currentCount 	= $countText.find('em').text();

			newCount = parseInt(currentCount) + parseInt(countAdjust);
			if (newCount < 1) {

				newCount = 1;

			} else {

				if (newCount != 1) { slot_text = i18n_slots; } else { slot_text = i18n_slot; }
				$countText.html('<em>' + newCount + '</em> ' + slot_text);

				savingState(true);

				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'adjust_default_timeslot_count',
						'countAdjust'	: countAdjust,
						'calendar_id'	: calendar_id,
						'day'     		: day,
						'timeslot'     	: timeslot
					}
				});
			}

			return false;

		});

		// Delete Timeslot
		$('#bookedTimeslotsWrap').on('click', '.timeslot .delete', function(e) {

			e.preventDefault();

			var $button      	= $(this),
				$timeslot	 	= $button.parents('.timeslot'),
				day	 	 	 	= $button.parents('td').attr('data-day'),
				timeslot	 	= $button.parents('.timeslot').attr('data-timeslot'),
				booked_ajaxURL  = $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-timeslots').attr('data-calendar-id'),
				startText	 	= $timeslot.find('.start').html(),
				endText	 	 	= $timeslot.find('.end').html();

			confirm_ts_delete = confirm(i18n_confirm_ts_delete);
			if (confirm_ts_delete == true){

		    	$timeslot.slideUp('fast',function(){
					$(this).remove();
				});

				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'delete_timeslot',
						'day'     		: day,
						'calendar_id'	: calendar_id,
						'timeslot'     	: timeslot
					},
					beforeSend: function(){
						savingState(true);
					},
					success: function(data) {
						// Do nothing
					}
				});

			}

		});

		// Time Slots Calendar Switcher
		$('#booked-timeslotsSwitcher').on('change','select[name="bookedTimeslotsDisplayed"]',function(e){

			var calendar_id 		= $(this).val(),
				allTimeslotParents 	= $('#bookedTimeslotsWrap td.addTimeslot'),
				booked_ajaxURL 		= $('#data-ajax-url').html();

			allTimeslotParents.find('select').val('');
			allTimeslotParents.removeClass('active');
			allTimeslotParents.find('a.button').html(i18n_add + ' ...').removeClass('button-primary');
			$('#timepickerTemplate').appendTo('#booked-defaults').hide();

			savingState(true);
			$('table.booked-timeslots tbody').addClass('faded');
			$('#bookedTimeslotsWrap').load(booked_ajaxURL, {'load':'load_full_timeslots','calendar_id':calendar_id}, function(){
				init_timeslot_tabs();
				$('table.booked-timeslots tbody').removeClass('faded');
			});

		});
		
		// Custom Fields Calendar Switcher
		$('#booked-cfSwitcher').on('change','select[name="bookedCustomFieldsDisplayed"]',function(e){

			var calendar_id 		= $(this).val(),
				booked_ajaxURL 		= $('#data-ajax-url').html();

			$('#booked_customFields_Wrap').addClass('faded');
			$('#booked_customFields_Wrap').load(booked_ajaxURL, {'load':'load_full_customfields','calendar_id':calendar_id}, function(){
				$('#booked_customFields_Wrap').removeClass('faded');
				init_booked_custom_fields();
			});

		});

		// Calendar Switcher
		$('.booked-calendarSwitcher').on('change','select[name="bookedCalendarDisplayed"]',function(e){

			var calendar_id = $(this).val(),
				booked_ajaxURL = $('#data-ajax-url').html(),
				currentMonth = $('table.booked-calendar').attr('data-monthShown');

			savingState(true);
			$('.booked-admin-calendar-wrap').load(booked_ajaxURL, {'load':'calendar_month','gotoMonth':currentMonth,'calendar_id':calendar_id}, function(){
				adjust_calendar_boxes();
			});

		});

		// Calendar Next/Prev Click
		$('body').on('click', '.booked-admin-calendar-wrap .page-right, .booked-admin-calendar-wrap .page-left, .booked-admin-calendar-wrap .monthName a', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				gotoMonth		= $button.attr('data-goto'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-calendar').attr('data-calendar-id');

			savingState(true);
			$('.booked-admin-calendar-wrap').load(booked_ajaxURL, {'load':'calendar_month','gotoMonth':gotoMonth,'calendar_id':calendar_id}, function(){
				adjust_calendar_boxes();
			});

			return false;

		});

		// Calendar Date Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.week td', function(e) {

			e.preventDefault();

			var $thisDate 		= $(this),
				$thisRow		= $thisDate.parent(),
				date			= $thisDate.attr('data-date'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-calendar').attr('data-calendar-id'),
				colspanSetting	= $thisRow.find('td').length;

			if ($thisDate.hasClass('blur')){

				// Do nothing.

			} else if ($thisDate.hasClass('active')){

				$thisDate.removeClass('active');
				$('tr.entryBlock').remove();

			} else {

				$('tr.week td').removeClass('active');
				$thisDate.addClass('active');

				$('tr.entryBlock').remove();
				$thisRow.after('<tr class="entryBlock loading"><td colspan="'+colspanSetting+'"></td></tr>');
				$('tr.entryBlock').find('td').spin('booked');

				$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
					$('tr.entryBlock').removeClass('loading');
					$('tr.entryBlock').find('.booked-appt-list').fadeIn(300);
					$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
					$('.bookedAppointmentTab.active').fadeIn(300);
				});

			}

			return false;

		});

		// Delete Appointment Click from Calendar
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .delete', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				$thisParent		= $button.parents('.timeslot'),
				$thisTimeslot	= $button.parents('.timeslot'),
				$activeTD		= $('td.active'),
				$addlParent		= $thisTimeslot.parents('.additional-timeslots'),
				appt_id			= $thisTimeslot.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				date			= $activeTD.attr('data-date'),
				calendar_id		= $button.attr('data-calendar-id');


			if (!appt_id){
				appt_id			= $button.parents('.appt-block').attr('data-appt-id');
				$thisParent		= $button.parents('.appt-block');
			}

			confirm_appt_delete = confirm(i18n_confirm_appt_delete);
	  		if (confirm_appt_delete == true){

		    	$thisParent.slideUp('fast',function(){
					$(this).remove();
					if ($addlParent.length){
		  				if (!$addlParent.find('.timeslot').length){
			  				$addlParent.remove();
		  				}
		  			} else {
			  			if (!$thisTimeslot.find('.appt-block').length){
				  			$thisTimeslot.find('strong').remove();
			  			}
		  			}
				});

				$thisTimeslot.addClass('faded');

				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'delete_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
							$('tr.entryBlock').find('.booked-appt-list').show();
							$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
							$('.bookedAppointmentTab.active').fadeIn(300);
						});
						$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':date,'calendar_id':calendar_id},function(){
							var self = $(this);
							self.replaceWith(self.children());
							adjust_calendar_boxes();
						});
					}
				});

			}

			return false;

		});

		// Approve Appointment in Calendar
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .approve', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				$thisParent		= $button.parents('.timeslot'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();

			if (!appt_id){
				$thisParent		= $button.parents('.appt-block');
				appt_id			= $button.attr('data-appt-id');
			}

			confirm_appt_approve = confirm(i18n_confirm_appt_approve);
			if (confirm_appt_approve == true){

				$(document).trigger("booked-on-calendar-approve", appt_id);

		    	$button.remove();
		    	$thisParent.find('.pending-text').remove();

				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'approve_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						// Do nothing
					}
				});

			}

			return false;

		});

		// User Info Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .user', function(e) {

			e.preventDefault();

			var $thisLink 		= $(this),
				user_id			= $thisLink.attr('data-user-id'),
				appt_id			= $thisLink.parent().attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();

			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'user_info_modal','user_id':user_id,'appt_id':appt_id},function(){
				// Loaded!
			});

			return false;

		});

		// User Info Click
		$('.booked-pending-appt-list').on('click', '.user', function(e) {

			e.preventDefault();

			var $thisLink 		= $(this),
				user_id			= $thisLink.attr('data-user-id'),
				appt_id			= $thisLink.parent().attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();

			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'user_info_modal','user_id':user_id,'appt_id':appt_id},function(){
				// Loaded!
			});

			return false;

		});

		$('.booked-admin-calendar-wrap').on('click', '#bookedAppointmentTabs li a', function(e) {

			e.preventDefault();

			var $thisTab = $(this);
			var tabName	= $thisTab.attr('href').split('#calendar-');
			tabName = tabName[1];

			$('#bookedAppointmentTabs li').removeClass('active');
			$('.bookedAppointmentTab').hide();
			$('.bookedAppointmentTab').removeClass('active');

			$thisTab.parent().addClass('active');

			$('#bookedCalendarAppointmentsTab-'+tabName).fadeIn(100);
			$('#bookedCalendarAppointmentsTab-'+tabName).addClass('active');

			return false;

		});

		// New Appointment Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock button.new-appt', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				timeslot		= $button.attr('data-timeslot'),
				date			= $button.attr('data-date'),
				$thisTimeslot	= $button.parents('.timeslot'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $button.attr('data-calendar-id');

			booked_load_calendar_date_booking_options = {'load':'new_appointment_form','date':date,'timeslot':timeslot,'calendar_id':calendar_id};

			/*
			 * $(document).on("booked-before-loading-calendar-booking-options", function(event, params) { code goes here });
			 */
			$(document).trigger("booked-before-loading-calendar-booking-options");

			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, booked_load_calendar_date_booking_options, function (responseText) {
				$('select#userList').chosen();

				/*
				 * allow other script to apply modification on content loaded
				 * $(document).on("booked-on-new-app", function(event) { code goes here });
				 */
				$(document).trigger("booked-on-new-app");
			});


			return false;

		});

		// Delete Appointment from Pending List
		$('.booked-pending-appt-list').on('click', '.pending-appt .delete', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				$thisParent		= $button.parents('.pending-appt'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();

			confirm_appt_delete = confirm(i18n_confirm_appt_delete);
			if (confirm_appt_delete == true){

	  			var currentPendingCount = parseInt($('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html());
				currentPendingCount = parseInt(currentPendingCount - 1);
				if (currentPendingCount < 1){
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-plugins').remove();
					$('.no-pending-message').slideDown('fast');
				} else {
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html(currentPendingCount);
				}

	  			$thisParent.slideUp('fast',function(){
					$(this).remove();
				});

	  			savingState(true);

				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'delete_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						savingState(false);
					}
				});

			}

			return false;

		});

		// Approve Appointment from Pending List
		$('.booked-pending-appt-list').on('click', '.pending-appt .approve', function(e) {

			e.preventDefault();

			var $button 		= $(this),
				$thisParent		= $button.parents('.pending-appt'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL			= $('#data-ajax-url').html();

			confirm_appt_approve = confirm(i18n_confirm_appt_approve);
			if (confirm_appt_approve == true){

				var currentPendingCount = parseInt($('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html());
				currentPendingCount = parseInt(currentPendingCount - 1);
				if (currentPendingCount < 1){
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-plugins').remove();
					$('.no-pending-message').slideDown('fast');
				} else {
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html(currentPendingCount);
				}

				$thisParent.slideUp('fast',function(){
					$(this).remove();
				});

	  			savingState(true);

		  		ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'approve_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						savingState(false);
					}
				});

			}

			return false;

		});

		$('body').on('click','.bm-overlay, .bm-window .close, form.booked-form .cancel',function(e){
			e.preventDefault();
			close_booked_modal();
			return false;
		});

		$('body').on('change','form.booked-form input,form.booked-settings-form select',function(){

			var condition = $(this).attr('data-condition'),
				thisVal = $(this).val();

			if (condition && $('.condition-block').length) {
				$('.condition-block.'+condition).hide();
				$('#condition-'+thisVal).fadeIn(200);
			}

		});

		$('body')
		.on('focusin', 'form.booked-form input', function() {
			if(this.title==this.value) {
				$(this).addClass('hasContent');
				this.value = '';
			}
		}).on('focusout', 'form.booked-form input', function(){
			if(this.value==='') {
				$(this).removeClass('hasContent');
				this.value = this.title;
			}
		});

		// Adjust the calendar sizing when resizing the window
		$win.resize(function(){
			adjust_calendar_boxes();
		});

		// Adjust the calendar sizing on load
		adjust_calendar_boxes();

		// Saving state updater
		function savingState(show,savingStateObj){
			if (typeof savingStateObj === 'undefined'){
				var savingStateObj = $('li.active .savingState, .topSavingState.savingState, .calendarSavingState, .cf-updater.savingState, .cts-updater.savingState, .cal-updater.savingState');
			}
			var $stuffToHide = $('.monthName');
			var $stuffToTransparent = $('table.booked-calendar tbody');
			if (show){
				savingStateObj.fadeIn(200);
				$stuffToHide.hide();
				$stuffToTransparent.animate({'opacity':0.2},100);
			} else {
				savingStateObj.hide();
				$stuffToHide.show();
				$stuffToTransparent.animate({'opacity':1},0);
			}
		}

		function init_timeslot_tabs(){
			/* Add Timeslot Tabs */
			if ($('.addTimeslotTab').length){

				// Tabs
				var timeslotTabs = $('.timeslotTabs');
				$('.tsTabContent').hide();
				var activeTab = timeslotTabs.find('.active').attr('href');
				activeTab = activeTab.split('#');
				activeTab = activeTab[1];
				$('.tsTabContent.ts'+activeTab).show();

				timeslotTabs.find('a').on('click', function(e) {

					e.preventDefault();
					$('.tsTabContent').hide();
					timeslotTabs.find('a').removeClass('active');

					$(this).addClass('active');
					var activeTab = $(this).attr('href');
					activeTab = activeTab.split('#');
					activeTab = activeTab[1];

					$('.tsTabContent.ts'+activeTab).show();

				});

			}
		}

		function updateCustomTimeslotEncodedField(){
			var formData = JSON.stringify($('form#customTimeslots').serializeObject());
			$('#custom_timeslots_encoded').val(formData);
		}

		$(document).ajaxStop(function() {
			savingState(false);
		});

	});

})(jQuery, window, document);

// Create Booked Modal
function create_booked_modal(){
	jQuery('body *').blur();
	jQuery('body').css({'overflow':'hidden'});
	jQuery('<div class="booked-modal"><div class="bm-overlay"></div><div class="bm-window"><div style="height:100px"></div></div></div>').appendTo('body');
	jQuery('.booked-modal .bm-window').spin('booked');
}

function close_booked_modal(){
	jQuery('.booked-modal').fadeOut(200);
	jQuery('.booked-modal').addClass('bm-closing');
	jQuery('body').css({'overflow':'auto'});
	setTimeout(function(){
		jQuery('.booked-modal').remove();
	},300);
}

// Function to adjust calendar sizing
function adjust_calendar_boxes(){
	var boxesWidth = jQuery('.booked-calendar tbody tr.week td').width();
	boxesHeight = boxesWidth * 0.8;
	jQuery('.booked-calendar tbody tr.week td').height(boxesHeight);
	jQuery('.booked-calendar tbody tr.week td .date').css('line-height',boxesHeight+'px');
}

// Serialize Function
(function($) {

	// jQuery on an empty object, we are going to use this as our Queue
	var ajaxQueue = $({});

	$.ajaxQueue = function( ajaxOpts ) {
	    var jqXHR,
	        dfd = $.Deferred(),
	        promise = dfd.promise();

	    // queue our ajax request
	    ajaxQueue.queue( doRequest );

	    // add the abort method
	    promise.abort = function( statusText ) {

	        // proxy abort to the jqXHR if it is active
	        if ( jqXHR ) {
	            return jqXHR.abort( statusText );
	        }

	        // if there wasn't already a jqXHR we need to remove from queue
	        var queue = ajaxQueue.queue(),
	            index = $.inArray( doRequest, queue );

	        if ( index > -1 ) {
	            queue.splice( index, 1 );
	        }

	        // and then reject the deferred
	        dfd.rejectWith( ajaxOpts.context || ajaxOpts, [ promise, statusText, "" ] );
	        return promise;
	    };

	    // run the actual query
	    function doRequest( next ) {
	        jqXHR = $.ajax( ajaxOpts )
	            .done( dfd.resolve )
	            .fail( dfd.reject )
	            .then( next, next );
	    }

	    return promise;
	};

})(jQuery);