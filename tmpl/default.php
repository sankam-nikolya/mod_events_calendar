<?php
/**
 * @copyright	Copyright © 2016 - All rights reserved.
 * @license		GNU General Public License v2.0
 */
defined('_JEXEC') or die;

jimport('joomla.html.html');

$lang = JFactory::getLanguage();
$lang = $lang->getTag();
$lang = strtolower(substr($lang, 0, 2));

JHtml::_('jquery.framework');
JHtml::script('media/com_events/js/moment.min.js', false);
JHtml::script('media/com_events/js/fullcalendar.min.js', false);
JHtml::script('media/com_events/js/locale/' . $lang . '.js', false);
JHtml::stylesheet('media/com_events/css/fullcalendar.min.css');
JHtml::stylesheet('media/com_events/css/fullcalendar.print.min.css');

if($type == 0) {
	$calendar_view = "\n				defaultView: 'listDay',";
} elseif($type == 1) {
	$calendar_view = "\n				defaultView: 'listWeek',";
} else {
	$calendar_view = "";
}

if(!empty($category)) {
	$category = "\n							category: " . $category . ",";
} else {
	$category = '';
}

if($controls) {
	$controls = "
				header: {
					left: 'prev,next',
					center: 'title',
					right: 'listDay,listWeek,month'
				},

				views: {
					listDay: { buttonText: '" . JText::_('MOD_EVENTS_CALENDAR_VIEW_TYPE_DAY') . "' },
					listWeek: { buttonText: '" . JText::_('MOD_EVENTS_CALENDAR_VIEW_TYPE_WEEK') . "' },
					month: { buttonText: '" . JText::_('MOD_EVENTS_CALENDAR_VIEW_TYPE_MONTH') . "' }
				},";
} else {
	$controls = "
				header: {
					left: '',
					center: 'title',
					right: ''
				},";
}

$document = JFactory::getDocument();
$script = "
	(function($) {
		$(document).ready(function() {
			$('#calendar-".$module->id."').fullCalendar({
" . $controls . "

				locale: '" . $lang . "'," . $calendar_view . "
				defaultDate: '" . date('Y-m-d') . "',
				lazyFetching: true,
				navLinks: true,
				editable: true,
				eventLimit: true,
				events: {
						url: 'index.php?option=com_events&task=events.listing',
						type: 'POST',
						dataType: 'json',
						cache: true,
						data: {".$category."
							lang: '" . $lang . "'
						},
						error: function(data) {
							alert('" . JText::_('MOD_EVENTS_CALENDAR_ERROR_LOADING_EVENTS') . "');
						}
				},
				eventClick: function(calEvent, jsEvent, view) {
					var start_date = calEvent.start;
					var end_date = calEvent.end;
					var data = {
						id: calEvent.id,
						s: start_date.format(),
						e: end_date.format()
					};
					$.ajax({
						type: 'POST',
						cache: false,
						dataType: 'html',
						data: data,
						url: 'index.php?option=com_events&task=event.data',
						success: function (data) {
							var modal = $(data);
							modal.attr('id', 'eventModal-" . $module->id . "');
							modal = modal.outerHTML();
							$('body').append(modal);
							$('#eventModal-" . $module->id . "').modal('show');
						},
						error: function(data) {
							alert('" . JText::_('MOD_EVENTS_CALENDAR_ERROR_LOADING_EVENT') . "');
						}
					});
				}
			});
			$(document).on('hidden.bs.modal', '#eventModal-" . $module->id . "', function (event) {
				$('#eventModal-" . $module->id . "').remove();
			});
		});
		$.fn.outerHTML = function() {
			return (this[0]) ? this[0].outerHTML : '';  
		};
	})(jQuery);
";
$document->addScriptDeclaration($script);
?>
<div class="calendar-module">
	<div id="calendar-<?php echo $module->id;?>" class="calendar-block"></div>
</div>
