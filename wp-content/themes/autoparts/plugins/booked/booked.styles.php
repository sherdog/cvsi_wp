<?php

// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('autoparts_booked_get_css')) {
	add_filter('autoparts_filter_get_css', 'autoparts_booked_get_css', 10, 4);
	function autoparts_booked_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button,
body #booked-profile-page input[type="submit"],
body #booked-profile-page button,
body .booked-list-view input[type="submit"],
body .booked-list-view button,
body table.booked-calendar input[type="submit"],
body table.booked-calendar button,
body .booked-modal input[type="submit"],
body .booked-modal button {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
div.booked-calendar-wrap.small table.booked-calendar thead tr th .monthName,
div.booked-calendar-wrap table.booked-calendar thead tr th .monthName {
    {$fonts['h1_font-family']};
}
div.booked-calendar-wrap.small table.booked-calendar thead tr th .monthName .backToMonth,
div.booked-calendar-wrap table.booked-calendar thead tr th .monthName .backToMonth {
    {$fonts['p_font-family']};
}
CSS;
		}
		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Calendar */
table.booked-calendar th .monthName a {
	color: {$colors['extra_link']};
}
table.booked-calendar th .monthName a:hover {
	color: {$colors['extra_hover']};
}
.booked-calendar-wrap .booked-appt-list h2 {
	color: {$colors['text_dark']};
}
.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-title {
	color: {$colors['text_link']};
}
.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-time {
	color: {$colors['text_dark']};
}
.booked-calendar-wrap .booked-appt-list .timeslot .spots-available {
	color: {$colors['inverse_link']};
}

/* Form fields */
#booked-page-form {
	color: {$colors['text']};
	border-color: {$colors['bd_color']};
}

#booked-profile-page .booked-profile-header {
	background-color: {$colors['bg_color']} !important;
	border-color: transparent !important;
	color: {$colors['text']};
}
#booked-profile-page .booked-user h3 {
	color: {$colors['text_dark']};
}
#booked-profile-page .booked-profile-header .booked-logout-button:hover {
	color: {$colors['text_link']};
}

#booked-profile-page .booked-tabs {
	border-color: {$colors['alter_bd_color']} !important;
}

.booked-modal .bm-window p.booked-title-bar {
	background-color: {$colors['text_link2']} !important;
}
.booked-modal .bm-window .close i {
	color: {$colors['alter_bg_color']};
}

.booked-calendarSwitcher.calendar,
.booked-calendarSwitcher.calendar select,
#booked-profile-page .booked-tabs {
	background-color: {$colors['alter_bg_color']} !important;
}
#booked-profile-page .booked-tabs li a {
	background-color: {$colors['extra_bg_hover']};
	color: {$colors['extra_dark']};
}
table.booked-calendar thead,
table.booked-calendar thead th,
table.booked-calendar tr.days,
table.booked-calendar tr.days th,
#booked-profile-page .booked-tabs li.active a,
#booked-profile-page .booked-tabs li.active a:hover,
#booked-profile-page .booked-tabs li a:hover {
	color: {$colors['extra_dark']} !important;
	background-color: {$colors['extra_bg_color']} !important;
}
table.booked-calendar tr.days,
table.booked-calendar tr.days th {
	border-color: {$colors['extra_bd_color']} !important;
}
table.booked-calendar thead th i {
	color: {$colors['extra_dark']} !important;
}
table.booked-calendar td.today .date span {
	border-color: {$colors['text_hover2']};
}
table.booked-calendar td:hover .date span {
	color: {$colors['text_dark']} !important;
}
table.booked-calendar td.today:hover .date span {
	background-color: {$colors['text_link']} !important;
	color: {$colors['inverse_link']} !important;
}
#booked-profile-page .booked-tab-content {
	background-color: {$colors['bg_color']};
	border-color: {$colors['alter_bd_color']};
}
table.booked-calendar td,
table.booked-calendar td+td {
	border-color: {$colors['alter_bd_color']};
}


/* Booked */
    .booked-calendar-shortcode-wrap .booked-calendar-wrap.small table.booked-calendar {
        background-color: {$colors['extra_bg_color']};
    }
    .booked-calendar-wrap.small table.booked-calendar thead,
    .booked-calendar-wrap table.booked-calendar thead {
        background: {$colors['text_link']};
        border-color: {$colors['text_link']};
        color: {$colors['inverse_link']};
    }
    .booked-calendar-wrap.small table.booked-calendar thead th,
    .booked-calendar-wrap table.booked-calendar thead th {
        background: {$colors['text_link']};
        border-color: {$colors['text_link']};
        color: {$colors['inverse_link']};
    }
    .booked-calendar-wrap.small table.booked-calendar thead tr.days th,
    .booked-calendar-wrap table.booked-calendar thead tr.days th {
        color: {$colors['alter_light_05']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar thead tr th .monthName .backToMonth,
    .booked-calendar-wrap table.booked-calendar thead tr th .monthName .backToMonth {
        color: {$colors['text']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar thead tr th .monthName .backToMonth:hover,
    .booked-calendar-wrap table.booked-calendar thead tr th .monthName .backToMonth:hover {
        color: {$colors['alter_light']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar thead tr th,
    .booked-calendar-wrap table.booked-calendar thead tr th {
        background-color: {$colors['alter_link3']}!important;
        color: {$colors['alter_light']}!important;
        border-color: {$colors['alter_link3']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar tbody tr td .date {
        background-color: {$colors['extra_bg_color']}!important;
        color: {$colors['text_link2']};
    }
    .booked-calendar-wrap.small table.booked-calendar td,
    .booked-calendar-wrap.small table.booked-calendar td + td {
        border-color: {$colors['text_link3_01']};
    }
    .booked-calendar-wrap.small table.booked-calendar tbody tr td.prev-date:not(.today) .date {
        background-color: {$colors['inverse_dark']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar tbody tr td.prev-month.prev-date:not(.today) .date,
    .booked-calendar-wrap.small table.booked-calendar tbody tr td.next-month.prev-date:not(.today) .date {
        background-color: {$colors['extra_bg_color']}!important;
    }
    body table.booked-calendar td.prev-date:hover .date span,
    body table.booked-calendar td:hover .date.tooltipster span,
    table.booked-calendar tbody td.today .date span,
    .booked-calendar-wrap.small table.booked-calendar tbody tr td.prev-date.today .date  {
        background-color: {$colors['text_link2']} !important;
        color: {$colors['inverse_link']}!important;
    }
    .booked-calendar-wrap.small table.booked-calendar tbody tr td.prev-date.today .date .number {
        color: {$colors['inverse_link']}!important;
        background-color: transparent !important;
    }
    .booked-calendar-wrap table.booked-calendar td.next-month .date span,
    .booked-calendar-wrap table.booked-calendar td.prev-month .date span {
        color: {$colors['text']} ;
    }
    .booked-calendar-wrap.small table.booked-calendar td.next-month .date span,
    .booked-calendar-wrap.small table.booked-calendar td.prev-month .date span {
        color: {$colors['text_light']} ;
    }
    body .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button {
        background-color: {$colors['text_link2']}!important;
        color: {$colors['inverse_link']}!important;
        border-color: {$colors['text_link2']}!important;
    }
    body .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button:hover {
        background-color: {$colors['text_hover2']}!important;
        color: {$colors['inverse_link']}!important;
        border-color: {$colors['text_hover2']}!important;
    }
    .booked-calendar-wrap .booked-appt-list .timeslot .spots-available.empty {
        color: {$colors['text']}!important;
    }
    body .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button[disabled],
    body .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button[disabled]:hover {
        background-color: {$colors['text_light']}!important;
        color: {$colors['text']}!important;
        border-color: {$colors['text_light']}!important;
    }
    body table.booked-calendar td.booked .date,
    body table.booked-calendar td.booked .date span,
    body table.booked-calendar td.booked:hover .date,
    body table.booked-calendar td.booked:hover .date span {
        background-color: {$colors['text_light']}!important;
        color: {$colors['text']}!important;
    }
    
    body .booked-modal input[type="submit"].button:hover {
        background-color: {$colors['text_hover2']} !important;
        color: {$colors['inverse_link']};
        border-color: {$colors['text_hover2']} !important;
    }
    .booked-calendar-wrap .booked-appt-list h2 {
        color: {$colors['alter_light']} !important;
    }
    .booked-calendar-shortcode-wrap .booked-calendar-wrap.small table.booked-calendar tbody tr.week,
    .booked-calendar-shortcode-wrap .booked-calendar-wrap.small table.booked-calendar tbody tr td {
        border-color: {$colors['inverse_text_01']} !important;
    }
    
    body .booked-modal .bm-window a {
        color: {$colors['text_link']};
    }
     body .booked-modal .bm-window a:hover {
            color: {$colors['text_hover']};
     }
CSS;
		}

		return $css;
	}
}
?>