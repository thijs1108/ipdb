/*
Copyright 2015 Topicus Onderwijs bv (http://www.topicus.nl)
Author: Thijs Beltman <t.beltman@hotmail.nl>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


var timer = undefined;


/* Initialize */
$(function () {
	ajaxify();
	settimeout();
});


/* Set timeout */
function settimeout() {
	if ((typeof timeout != 'undefined') && (timeout!=0)) {
		if (timer!=undefined) {
			clearTimeout(timer);
		}
		timer = setTimeout('timeoutdummy();', 1050*timeout);
	}
}


/* Do dummy call after timeout */
function timeoutdummy() {
	ajaxrequest({ dummy: 'dummy'});
}


/* escape() does not escape '+' */
function escapeplus(str) {
	return escape(str).replace(/\+/, '%2B');
}


/* AJAXify the anchors and forms */
function ajaxify() {
	$('#tree a').off('click').click(clicktree);
	$('#tree li[id^="a_"]').off('click').click(clicktree);
	$('#menu a[remote="remote"]').off('click').click(clicka);
	$('#menu form[remote="remote"]').off('submit').submit(submitform);
	$('#menu form[remote="remote"] input[type="submit"]').click(function() { $(this).attr('clicked', 'clicked'); });
	$('#menu form[remote="remote"] input[name="cancel"]').off('click').click(function() { ajaxrequest(location.href.replace(/.*\?/, '')); return false; });
	$('#content a[remote="remote"]').off('click').click(clicka);
	$('#content form[remote="remote"]').off('submit').submit(submitform);
	$('#content form[remote="remote"] input[type="submit"]').click(function() { $(this).attr('clicked', 'clicked'); });
	$('#content form[remote="remote"] input[name="cancel"]').off('click').click(function() { ajaxrequest(location.href.replace(/.*\?/, '')); return false; });
}


/* Click on an anchor */
function clicka(event) {
	ajaxrequest($(event.target).attr('href').replace(/.*\?/, ''));
	return false;
}


/* Submit a form */
function submitform(event, button) {
	var vars = { };
	$(event.target).find('input,select,textarea').each(function() {
		if ($(this).is('input[type=checkbox]'))
			vars[$(this).attr('name')] = this.checked ? 'on' : 'off';
		else if ($(this).is('input[type=radio]'))
			vars[$(this).attr('name')] = this.checked ? this.value : '';
		else if ($(this).is('input[type=submit]') && ($(this).attr('name')!='cancel') && ($(this).attr('clicked')=='clicked'))
			vars['submit'] = $(this).attr('name');
		else
			vars[$(this).attr('name')] = this.value;
	});
	ajaxrequest($.param(vars));
	return false;
}


/* Handle click on the tree */
function clicktree(event) {
	if ($(event.target).is('a')) {
		document.location.href = target.href.replace(/.*\?/, '?');
	} else if ($(event.target).is('div')) {
		if ($(this).hasClass('expanded'))
			collapse($(this).attr('id').replace(/^a_/, ''));
		else if ($(this).hasClass('collapsed'))
			expand($(this).attr('id').replace(/^a_/, ''));
	}
	event.stopPropagation();
	return false;
}


/* Expand a tree node */
function expand(address) {
	ajaxrequest({ action: 'getsubtree', leaf: address });
	return false;
}
function expandtree(address, content) {
	$('#tree li[id="a_'+address+'"] ul').remove();
	$('#tree li[id="a_'+address+'"]').append(unescape(content)).addClass('expanded').removeClass('collapsed');
}


/* Collapse a tree node */
function collapse(address) {
	$('#tree li[id="a_'+address+'"]').addClass('collapsed').removeClass('expanded');
	$('#tree li[id="a_'+address+'"] ul').remove();
}


/* Send an AJAX request */
function ajaxrequest(vars) {
	if (typeof vars == 'string')
		vars = vars.replace(/^[\?]?/, 'remote=remote&');
	else
		vars['remote'] = 'remote';
	$.ajax(location.href.replace(/\?.*/, ''), { data: vars }).done(function(json) {
		if (json.notify)
			notify($.extend({}, json.notify, { container: 'main' }));
		if (json.tree)
			$('#tree').html(json.tree);
		if (json.content)
			$('#content').html(json.content);
		if (json.title)
			document.title = json.title;
		if (json.commands)
			$.each(json.commands, function(index, command) { eval(command); });
		if (json.debug)
			$('#debug span').html(json.debug);
		ajaxify();
		settimeout();
	});
}


/* Display notification */
notify = function(options) {

	var defaults = {
		type: 'notify',
		message: 'Default message',
	}

	options = $.extend({}, defaults, options);

	var element = $('.notify');
	if (!element.length) {
		element = $('<div class="notify"></div>');
		$('body').append(element);
	}
	element.removeClass('error').removeClass('success').addClass(options.type);
	element.html(options.message);
	element.slideDown(300);
	setTimeout(function() {
		element.slideUp(300);
	}, 3000);

};


/* Toggle showing unused blocks */
function toggleunused(node, showunused) {
	ajaxrequest({ page: 'main', node: node, showunused: showunused });
}
