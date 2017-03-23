/* Set the defaults for DataTables initialisation */
$.extend(true, $.fn.dataTable.defaults, {
  "sDom": "<'row'<'col-xs-5 col-sm-6'l><'col-xs-7 col-sm-6 text-right'f>r>t<'row'<'col-xs-3 col-sm-4 col-md-5'i><'col-xs-9 col-sm-8 col-md-7 text-right'p>>",
  "sPaginationType": "bootstrap",
  "oLanguage": {
    "sLengthMenu": "_MENU_ records per page"
  },
  "fnInitComplete": function (oSettings, json) {
    var currentId = $(this).attr('id');
    if (currentId) {
 
      var thisLength = $('#' + currentId + '_length');
      var thisLengthLabel = $('#' + currentId + '_length label');
      var thisLengthSelect = $('#' + currentId + '_length label select');
 
      var thisFilter = $('#' + currentId + '_filter');
      var thisFilterLabel = $('#' + currentId + '_filter label');
      var thisFilterInput = $('#' + currentId + '_filter label input');
 
      // Re-arrange the records selection for a form-horizontal layout
      thisLength.addClass('form-group');
      thisLengthLabel.addClass('control-label col-xs-12 col-sm-7 col-md-7').attr('for', currentId + '_length_select').css('text-align', 'left');
      thisLengthSelect.addClass('form-control input-sm').attr('id', currentId + '_length_select');
      thisLengthSelect.prependTo(thisLength).wrap('<div class="col-xs-12 col-sm-5 col-md-5" />');
      // Re-arrange the search input for a form-horizontal layout
      thisFilter.addClass('form-group');
      thisFilterLabel.addClass('control-label col-xs-4 col-sm-3 col-md-3').attr('for', currentId + '_filter_input');
      thisFilterInput.addClass('form-control input-sm').attr('id', currentId + '_filter_input');
      thisFilterInput.appendTo(thisFilter).wrap('<div class="col-xs-8 col-sm-9 col-md-9 " />');
    }
  }
});
 
$.extend($.fn.dataTableExt.oStdClasses, {
  "sWrapper": "dataTables_wrapper form-horizontal"
});
 
/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
  return {
    "iStart": oSettings._iDisplayStart,
    "iEnd": oSettings.fnDisplayEnd(),
    "iLength": oSettings._iDisplayLength,
    "iTotal": oSettings.fnRecordsTotal(),
    "iFilteredTotal": oSettings.fnRecordsDisplay(),
    "iPage": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    "iTotalPages": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
  };
};
 
 
/* Bootstrap style pagination control */
$.extend($.fn.dataTableExt.oPagination, {
  "bootstrap": {
    "fnInit": function (oSettings, nPaging, fnDraw) {
      var oLang = oSettings.oLanguage.oPaginate;
      var fnClickHandler = function (e) {
        e.preventDefault();
        if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
          fnDraw(oSettings);
        }
      };
 
      $(nPaging).append(
       '<ul class="pagination">' +
        '<li class="first disabled"><a href="#" title="' + oLang.sFirst + '"><span class="fa fa-fast-backward"></span></a></li>' +
        '<li class="prev disabled"><a href="#" title="' + oLang.sPrevious + '"><span class="fa fa-step-backward"></span></a></li>' +
        '<li class="next disabled"><a href="#" title="' + oLang.sNext + '"><span class="fa fa-step-forward"></span></a></li>' +
        '<li class="last disabled"><a href="#" title="' + oLang.sLast + '"><span class="fa fa-fast-forward"></span></a></li>' +
       '</ul>'
      );
      var els = $('a', nPaging);
      $(els[0]).bind('click.DT', { action: "first" }, fnClickHandler);
      $(els[1]).bind('click.DT', { action: "previous" }, fnClickHandler);
      $(els[2]).bind('click.DT', { action: "next" }, fnClickHandler);
      $(els[3]).bind('click.DT', { action: "last" }, fnClickHandler);
    },
 
    "fnUpdate": function (oSettings, fnDraw) {
      var iListLength = 5;
      var oPaging = oSettings.oInstance.fnPagingInfo();
      var an = oSettings.aanFeatures.p;
      var i, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);
 
        if (oPaging.iTotalPages < iListLength) { iStart = 1; iEnd = oPaging.iTotalPages; } else if (oPaging.iPage <= iHalf) { iStart = 1; iEnd = iListLength; } else if (oPaging.iPage >= oPaging.iTotalPages - iHalf) { iStart = oPaging.iTotalPages - iListLength + 1; iEnd = oPaging.iTotalPages; } else { iStart = oPaging.iPage - iHalf + 1; iEnd = iStart + iListLength - 1; }
 
        for (i = 0, iLen = an.length ; i < iLen ; i++) {
          // Remove the middle elements
          $('li:gt(1)', an[i]).filter(':not(.next,.last)').remove();
 
          // Add the new list items and their event handlers
          for (j = iStart; j <= iEnd; j++) { sClass = j == oPaging.iPage + 1 ? 'class="active"' : ""; $("<li " + sClass + '><a href="#">' + j + "</a></li>").insertBefore($(".next,.last", an[i])[0]).bind("click", function (a) { a.preventDefault(); oSettings._iDisplayStart = (parseInt($("a", this).text(), 10) - 1) * oPaging.iLength; fnDraw(oSettings) }) }
 
          // Add / remove disabled classes from the static elements
          if (oPaging.iPage === 0) $(".first,.prev", an[i]).addClass("disabled"); else $(".first,.prev", an[i]).removeClass("disabled")
 
          if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) $(".next,.last", an[i]).addClass("disabled"); else $(".next,.last", an[i]).removeClass("disabled")
        }
    }
  }
});
 
 
/*
* TableTools Bootstrap compatibility
* Required TableTools 2.1+
*/
if ($.fn.DataTable.TableTools) {
  // Set the classes that TableTools uses to something suitable for Bootstrap
  // Set the classes that TableTools uses to something suitable for Bootstrap
  $.extend(true, $.fn.DataTable.TableTools.classes, {
    "container": "DTTT btn-group",
    "buttons": {
      "normal": "btn btn-default",
      "disabled": "disabled"
    },
    "collection": {
      "container": "DTTT_dropdown dropdown-menu",
      "buttons": {
        "normal": "",
        "disabled": "disabled"
      }
    },
    "print": {
      "info": "DTTT_print_info modal"
    },
    "select": {
      "row": "active"
    }
  });
 
  // Have the collection use a bootstrap compatible dropdown
  $.extend(true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
    "collection": {
      "container": "ul",
      "button": "li",
      "liner": "a"
    }
  });
}
 
// Moved to the bottom.
if ($.fn.DataTable.defaults) {
  $.extend($.fn.dataTable.defaults, {
    'bAutoWidth': false,
    'aLengthMenu': [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    'iDisplayLength': 10,
    "bFilter": true
  });
}
/*! DataTables Bootstrap integration
 * ©2011-2014 SpryMedia Ltd - datatables.net/license
 */

/**
 * DataTables integration for Bootstrap 3. This requires Bootstrap 3 and
 * DataTables 1.10 or newer.
 *
 * This file sets the defaults and adds options to DataTables to style its
 * controls using Bootstrap. See http://datatables.net/manual/styling/bootstrap
 * for further information.
 */
(function(window, document, undefined){

var factory = function( $, DataTable ) {
"use strict";


/* Set the defaults for DataTables initialisation */
$.extend( true, DataTable.defaults, {
	dom:
		"<'row'<'col-xs-6'l><'col-xs-6'f>r>" +
		"<'row'<'col-xs-12't>>" +
		"<'row'<'col-xs-6'i><'col-xs-6'p>>",
	renderer: 'bootstrap'
} );


/* Default class modification */
$.extend( DataTable.ext.classes, {
	sWrapper:      "dataTables_wrapper form-inline dt-bootstrap",
	sFilterInput:  "form-control input-sm",
	sLengthSelect: "form-control input-sm"
} );


/* Bootstrap paging button renderer */
DataTable.ext.renderer.pageButton.bootstrap = function ( settings, host, idx, buttons, page, pages ) {
	var api     = new DataTable.Api( settings );
	var classes = settings.oClasses;
	var lang    = settings.oLanguage.oPaginate;
	var btnDisplay, btnClass;

	var attach = function( container, buttons ) {
		var i, ien, node, button;
		var clickHandler = function ( e ) {
			e.preventDefault();
			if ( !$(e.currentTarget).hasClass('disabled') ) {
				api.page( e.data.action ).draw( false );
			}
		};

		for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
			button = buttons[i];

			if ( $.isArray( button ) ) {
				attach( container, button );
			}
			else {
				btnDisplay = '';
				btnClass = '';

				switch ( button ) {
					case 'ellipsis':
						btnDisplay = '&hellip;';
						btnClass = 'disabled';
						break;

					case 'first':
						btnDisplay = lang.sFirst;
						btnClass = button + (page > 0 ?
							'' : ' disabled');
						break;

					case 'previous':
						btnDisplay = lang.sPrevious;
						btnClass = button + (page > 0 ?
							'' : ' disabled');
						break;

					case 'next':
						btnDisplay = lang.sNext;
						btnClass = button + (page < pages-1 ?
							'' : ' disabled');
						break;

					case 'last':
						btnDisplay = lang.sLast;
						btnClass = button + (page < pages-1 ?
							'' : ' disabled');
						break;

					default:
						btnDisplay = button + 1;
						btnClass = page === button ?
							'active' : '';
						break;
				}

				if ( btnDisplay ) {
					node = $('<li>', {
							'class': classes.sPageButton+' '+btnClass,
							'aria-controls': settings.sTableId,
							'tabindex': settings.iTabIndex,
							'id': idx === 0 && typeof button === 'string' ?
								settings.sTableId +'_'+ button :
								null
						} )
						.append( $('<a>', {
								'href': '#'
							} )
							.html( btnDisplay )
						)
						.appendTo( container );

					settings.oApi._fnBindAction(
						node, {action: button}, clickHandler
					);
				}
			}
		}
	};

	attach(
		$(host).empty().html('<ul class="pagination"/>').children('ul'),
		buttons
	);
};


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn btn-default",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible drop down
	$.extend( true, DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}

}; // /factory


// Define as an AMD module if possible
if ( typeof define === 'function' && define.amd ) {
	define( ['jquery', 'datatables'], factory );
}
else if ( typeof exports === 'object' ) {
    // Node/CommonJS
    factory( require('jquery'), require('datatables') );
}
else if ( jQuery ) {
	// Otherwise simply initialise as normal, stopping multiple evaluation
	factory( jQuery, jQuery.fn.dataTable );
}


})(window, document);


/**
 * By default DataTables only uses the sAjaxSource variable at initialisation time,
 * however it can be useful to re-read an Ajax source and have the table update.
 * Typically you would need to use the fnClearTable() and fnAddData() functions,
 * however this wraps it all up in a single function call.
 *
 * @param  {[type]} oSettings       [description]
 * @param  {[type]} sNewSource      [description]
 * @param  {[type]} fnCallback      [description]
 * @param  {[type]} bStandingRedraw [description]
 * @return {[type]}                 [description]
 */

$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    if ( sNewSource !== undefined && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }

    // Server-side processing should just call fnDraw
    if ( oSettings.oFeatures.bServerSide ) {
        this.fnDraw();
        return;
    }

    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];

    this.oApi._fnServerParams( oSettings, aData );

    oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );

        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }

        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

        that.fnDraw();

        if ( bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.oApi._fnCalculateEnd( oSettings );
            that.fnDraw( false );
        }

        that.oApi._fnProcessingDisplay( oSettings, false );

        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback !== null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
};
(function(g){"function"===typeof define&&define.amd?define(["jquery","datatables.net","datatables.net-buttons"],function(d){return g(d,window,document)}):"object"===typeof exports?module.exports=function(d,e){d||(d=window);if(!e||!e.fn.dataTable)e=require("datatables.net")(d,e).$;e.fn.dataTable.Buttons||require("datatables.net-buttons")(d,e);return g(e,d,d.document)}:g(jQuery,window,document)})(function(g,d,e,h){d=g.fn.dataTable;g.extend(d.ext.buttons,{colvis:function(a,b){return{extend:"collection",
text:function(a){return a.i18n("buttons.colvis","Column visibility")},className:"buttons-colvis",buttons:[{extend:"columnsToggle",columns:b.columns}]}},columnsToggle:function(a,b){return a.columns(b.columns).indexes().map(function(a){return{extend:"columnToggle",columns:a}}).toArray()},columnToggle:function(a,b){return{extend:"columnVisibility",columns:b.columns}},columnsVisibility:function(a,b){return a.columns(b.columns).indexes().map(function(a){return{extend:"columnVisibility",columns:a,visibility:b.visibility}}).toArray()},
columnVisibility:{columns:h,text:function(a,b,c){return c._columnText(a,c.columns)},className:"buttons-columnVisibility",action:function(a,b,c,f){a=b.columns(f.columns);b=a.visible();a.visible(f.visibility!==h?f.visibility:!(b.length&&b[0]))},init:function(a,b,c){var f=this,d=a.column(c.columns);a.on("column-visibility.dt"+c.namespace,function(a,b){b.bDestroying||f.active(d.visible())}).on("column-reorder.dt"+c.namespace,function(b,d,e){1===a.columns(c.columns).count()&&("number"===typeof c.columns&&
(c.columns=e.mapping[c.columns]),b=a.column(c.columns),f.text(c._columnText(a,c.columns)),f.active(b.visible()))});this.active(d.visible())},destroy:function(a,b,c){a.off("column-visibility.dt"+c.namespace).off("column-reorder.dt"+c.namespace)},_columnText:function(a,b){var c=a.column(b).index();return a.settings()[0].aoColumns[c].sTitle.replace(/\n/g," ").replace(/<.*?>/g,"").replace(/^\s+|\s+$/g,"")}},colvisRestore:{className:"buttons-colvisRestore",text:function(a){return a.i18n("buttons.colvisRestore",
"Restore visibility")},init:function(a,b,c){c._visOriginal=a.columns().indexes().map(function(b){return a.column(b).visible()}).toArray()},action:function(a,b,c,d){b.columns().every(function(a){a=b.colReorder&&b.colReorder.transpose?b.colReorder.transpose(a,"toOriginal"):a;this.visible(d._visOriginal[a])})}},colvisGroup:{className:"buttons-colvisGroup",action:function(a,b,c,d){b.columns(d.show).visible(!0,!1);b.columns(d.hide).visible(!1,!1);b.columns.adjust()},show:[],hide:[]}});return d.Buttons});

(function ($, DataTable) {
    "use strict";

    var _buildUrl = function(dt, action) {
        var url = dt.ajax.url() || '';
        var params = dt.ajax.params();
        params.action = action;

        return url + '?' + $.param(params);
    };

    DataTable.ext.buttons.excel = {
        className: 'buttons-excel',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.excel', 'Excel');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'excel');
            window.location = url;
        }
    };

    DataTable.ext.buttons.export = {
        extend: 'collection',

        className: 'buttons-export',

        text: function (dt) {
            return '<i class="fa fa-download"></i> ' + dt.i18n('buttons.export', 'Export') + '&nbsp;<span class="caret"/>';
        },

        buttons: ['csv', 'excel', 'pdf']
    };

    DataTable.ext.buttons.csv = {
        className: 'buttons-csv',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.csv', 'CSV');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'csv');
            window.location = url;
        }
    };

    DataTable.ext.buttons.pdf = {
        className: 'buttons-pdf',

        text: function (dt) {
            return '<i class="fa fa-file-pdf-o"></i> ' + dt.i18n('buttons.pdf', 'PDF');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'pdf');
            window.location = url;
        }
    };

    DataTable.ext.buttons.print = {
        className: 'buttons-print',

        text: function (dt) {
            return  '<i class="fa fa-print"></i> ' + dt.i18n('buttons.print', 'Print');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'print');
            window.location = url;
        }
    };

    DataTable.ext.buttons.reset = {
        className: 'buttons-reset',

        text: function (dt) {
            return '<i class="fa fa-undo"></i> ' + dt.i18n('buttons.reset', 'Reset');
        },

        action: function (e, dt, button, config) {
            dt.search('').draw();
        }
    };

    DataTable.ext.buttons.reload = {
        className: 'buttons-reload',

        text: function (dt) {
            return '<i class="fa fa-refresh"></i> ' + dt.i18n('buttons.reload', 'Reload');
        },

        action: function (e, dt, button, config) {
            dt.draw(false);
        }
    };

    DataTable.ext.buttons.create = {
        className: 'buttons-create',

        text: function (dt) {
            return '<i class="fa fa-plus"></i> ' + dt.i18n('buttons.create', 'Create');
        },

        action: function (e, dt, button, config) {
            window.location = window.location.href.replace(/\/+$/, "") + '/create';
        }
    };
})(jQuery, jQuery.fn.dataTable);

/*!
 Buttons for DataTables 1.2.1
 ©2016 SpryMedia Ltd - datatables.net/license
*/
(function(d){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(o){return d(o,window,document)}):"object"===typeof exports?module.exports=function(o,n){o||(o=window);if(!n||!n.fn.dataTable)n=require("datatables.net")(o,n).$;return d(n,o,o.document)}:d(jQuery,window,document)})(function(d,o,n,l){var i=d.fn.dataTable,u=0,v=0,k=i.ext.buttons,m=function(a,b){!0===b&&(b={});d.isArray(b)&&(b={buttons:b});this.c=d.extend(!0,{},m.defaults,b);b.buttons&&(this.c.buttons=b.buttons);
this.s={dt:new i.Api(a),buttons:[],listenKeys:"",namespace:"dtb"+u++};this.dom={container:d("<"+this.c.dom.container.tag+"/>").addClass(this.c.dom.container.className)};this._constructor()};d.extend(m.prototype,{action:function(a,b){var c=this._nodeToButton(a);if(b===l)return c.conf.action;c.conf.action=b;return this},active:function(a,b){var c=this._nodeToButton(a),e=this.c.dom.button.active,c=d(c.node);if(b===l)return c.hasClass(e);c.toggleClass(e,b===l?!0:b);return this},add:function(a,b){var c=
this.s.buttons;if("string"===typeof b){for(var e=b.split("-"),c=this.s,d=0,h=e.length-1;d<h;d++)c=c.buttons[1*e[d]];c=c.buttons;b=1*e[e.length-1]}this._expandButton(c,a,!1,b);this._draw();return this},container:function(){return this.dom.container},disable:function(a){a=this._nodeToButton(a);d(a.node).addClass(this.c.dom.button.disabled);return this},destroy:function(){d("body").off("keyup."+this.s.namespace);var a=this.s.buttons.slice(),b,c;b=0;for(c=a.length;b<c;b++)this.remove(a[b].node);this.dom.container.remove();
a=this.s.dt.settings()[0];b=0;for(c=a.length;b<c;b++)if(a.inst===this){a.splice(b,1);break}return this},enable:function(a,b){if(!1===b)return this.disable(a);var c=this._nodeToButton(a);d(c.node).removeClass(this.c.dom.button.disabled);return this},name:function(){return this.c.name},node:function(a){a=this._nodeToButton(a);return d(a.node)},remove:function(a){var b=this._nodeToButton(a),c=this._nodeToHost(a),e=this.s.dt;if(b.buttons.length)for(var g=b.buttons.length-1;0<=g;g--)this.remove(b.buttons[g].node);
b.conf.destroy&&b.conf.destroy.call(e.button(a),e,d(a),b.conf);this._removeKey(b.conf);d(b.node).remove();a=d.inArray(b,c);c.splice(a,1);return this},text:function(a,b){var c=this._nodeToButton(a),e=this.c.dom.collection.buttonLiner,e=c.inCollection&&e&&e.tag?e.tag:this.c.dom.buttonLiner.tag,g=this.s.dt,h=d(c.node),f=function(a){return"function"===typeof a?a(g,h,c.conf):a};if(b===l)return f(c.conf.text);c.conf.text=b;e?h.children(e).html(f(b)):h.html(f(b));return this},_constructor:function(){var a=
this,b=this.s.dt,c=b.settings()[0],e=this.c.buttons;c._buttons||(c._buttons=[]);c._buttons.push({inst:this,name:this.c.name});for(var c=0,g=e.length;c<g;c++)this.add(e[c]);b.on("destroy",function(){a.destroy()});d("body").on("keyup."+this.s.namespace,function(b){if(!n.activeElement||n.activeElement===n.body){var c=String.fromCharCode(b.keyCode).toLowerCase();a.s.listenKeys.toLowerCase().indexOf(c)!==-1&&a._keypress(c,b)}})},_addKey:function(a){a.key&&(this.s.listenKeys+=d.isPlainObject(a.key)?a.key.key:
a.key)},_draw:function(a,b){a||(a=this.dom.container,b=this.s.buttons);a.children().detach();for(var c=0,d=b.length;c<d;c++)a.append(b[c].inserter),b[c].buttons&&b[c].buttons.length&&this._draw(b[c].collection,b[c].buttons)},_expandButton:function(a,b,c,e){for(var g=this.s.dt,h=0,b=!d.isArray(b)?[b]:b,f=0,q=b.length;f<q;f++){var j=this._resolveExtends(b[f]);if(j)if(d.isArray(j))this._expandButton(a,j,c,e);else{var p=this._buildButton(j,c);if(p){e!==l?(a.splice(e,0,p),e++):a.push(p);if(p.conf.buttons){var s=
this.c.dom.collection;p.collection=d("<"+s.tag+"/>").addClass(s.className);p.conf._collection=p.collection;this._expandButton(p.buttons,p.conf.buttons,!0,e)}j.init&&j.init.call(g.button(p.node),g,d(p.node),j);h++}}}},_buildButton:function(a,b){var c=this.c.dom.button,e=this.c.dom.buttonLiner,g=this.c.dom.collection,h=this.s.dt,f=function(b){return"function"===typeof b?b(h,j,a):b};b&&g.button&&(c=g.button);b&&g.buttonLiner&&(e=g.buttonLiner);if(a.available&&!a.available(h,a))return!1;var q=function(a,
b,c,e){e.action.call(b.button(c),a,b,c,e);d(b.table().node()).triggerHandler("buttons-action.dt",[b.button(c),b,c,e])},j=d("<"+c.tag+"/>").addClass(c.className).attr("tabindex",this.s.dt.settings()[0].iTabIndex).attr("aria-controls",this.s.dt.table().node().id).on("click.dtb",function(b){b.preventDefault();!j.hasClass(c.disabled)&&a.action&&q(b,h,j,a);j.blur()}).on("keyup.dtb",function(b){b.keyCode===13&&!j.hasClass(c.disabled)&&a.action&&q(b,h,j,a)});"a"===c.tag.toLowerCase()&&j.attr("href","#");
e.tag?(g=d("<"+e.tag+"/>").html(f(a.text)).addClass(e.className),"a"===e.tag.toLowerCase()&&g.attr("href","#"),j.append(g)):j.html(f(a.text));!1===a.enabled&&j.addClass(c.disabled);a.className&&j.addClass(a.className);a.titleAttr&&j.attr("title",a.titleAttr);a.namespace||(a.namespace=".dt-button-"+v++);e=(e=this.c.dom.buttonContainer)&&e.tag?d("<"+e.tag+"/>").addClass(e.className).append(j):j;this._addKey(a);return{conf:a,node:j.get(0),inserter:e,buttons:[],inCollection:b,collection:null}},_nodeToButton:function(a,
b){b||(b=this.s.buttons);for(var c=0,d=b.length;c<d;c++){if(b[c].node===a)return b[c];if(b[c].buttons.length){var g=this._nodeToButton(a,b[c].buttons);if(g)return g}}},_nodeToHost:function(a,b){b||(b=this.s.buttons);for(var c=0,d=b.length;c<d;c++){if(b[c].node===a)return b;if(b[c].buttons.length){var g=this._nodeToHost(a,b[c].buttons);if(g)return g}}},_keypress:function(a,b){var c=function(e){for(var g=0,h=e.length;g<h;g++){var f=e[g].conf,q=e[g].node;if(f.key)if(f.key===a)d(q).click();else if(d.isPlainObject(f.key)&&
f.key.key===a&&(!f.key.shiftKey||b.shiftKey))if(!f.key.altKey||b.altKey)if(!f.key.ctrlKey||b.ctrlKey)(!f.key.metaKey||b.metaKey)&&d(q).click();e[g].buttons.length&&c(e[g].buttons)}};c(this.s.buttons)},_removeKey:function(a){if(a.key){var b=d.isPlainObject(a.key)?a.key.key:a.key,a=this.s.listenKeys.split(""),b=d.inArray(b,a);a.splice(b,1);this.s.listenKeys=a.join("")}},_resolveExtends:function(a){for(var b=this.s.dt,c,e,g=function(c){for(var e=0;!d.isPlainObject(c)&&!d.isArray(c);){if(c===l)return;
if("function"===typeof c){if(c=c(b,a),!c)return!1}else if("string"===typeof c){if(!k[c])throw"Unknown button type: "+c;c=k[c]}e++;if(30<e)throw"Buttons: Too many iterations";}return d.isArray(c)?c:d.extend({},c)},a=g(a);a&&a.extend;){if(!k[a.extend])throw"Cannot extend unknown button type: "+a.extend;var h=g(k[a.extend]);if(d.isArray(h))return h;if(!h)return!1;c=h.className;a=d.extend({},h,a);c&&a.className!==c&&(a.className=c+" "+a.className);var f=a.postfixButtons;if(f){a.buttons||(a.buttons=[]);
c=0;for(e=f.length;c<e;c++)a.buttons.push(f[c]);a.postfixButtons=null}if(f=a.prefixButtons){a.buttons||(a.buttons=[]);c=0;for(e=f.length;c<e;c++)a.buttons.splice(c,0,f[c]);a.prefixButtons=null}a.extend=h.extend}return a}});m.background=function(a,b,c){c===l&&(c=400);a?d("<div/>").addClass(b).css("display","none").appendTo("body").fadeIn(c):d("body > div."+b).fadeOut(c,function(){d(this).remove()})};m.instanceSelector=function(a,b){if(!a)return d.map(b,function(a){return a.inst});var c=[],e=d.map(b,
function(a){return a.name}),g=function(a){if(d.isArray(a))for(var f=0,q=a.length;f<q;f++)g(a[f]);else"string"===typeof a?-1!==a.indexOf(",")?g(a.split(",")):(a=d.inArray(d.trim(a),e),-1!==a&&c.push(b[a].inst)):"number"===typeof a&&c.push(b[a].inst)};g(a);return c};m.buttonSelector=function(a,b){for(var c=[],e=function(a,b,c){for(var d,g,f=0,h=b.length;f<h;f++)if(d=b[f])g=c!==l?c+f:f+"",a.push({node:d.node,name:d.conf.name,idx:g}),d.buttons&&e(a,d.buttons,g+"-")},g=function(a,b){var f,h,i=[];e(i,b.s.buttons);
f=d.map(i,function(a){return a.node});if(d.isArray(a)||a instanceof d){f=0;for(h=a.length;f<h;f++)g(a[f],b)}else if(null===a||a===l||"*"===a){f=0;for(h=i.length;f<h;f++)c.push({inst:b,node:i[f].node})}else if("number"===typeof a)c.push({inst:b,node:b.s.buttons[a].node});else if("string"===typeof a)if(-1!==a.indexOf(",")){i=a.split(",");f=0;for(h=i.length;f<h;f++)g(d.trim(i[f]),b)}else if(a.match(/^\d+(\-\d+)*$/))f=d.map(i,function(a){return a.idx}),c.push({inst:b,node:i[d.inArray(a,f)].node});else if(-1!==
a.indexOf(":name")){var k=a.replace(":name","");f=0;for(h=i.length;f<h;f++)i[f].name===k&&c.push({inst:b,node:i[f].node})}else d(f).filter(a).each(function(){c.push({inst:b,node:this})});else"object"===typeof a&&a.nodeName&&(i=d.inArray(a,f),-1!==i&&c.push({inst:b,node:f[i]}))},h=0,f=a.length;h<f;h++)g(b,a[h]);return c};m.defaults={buttons:["copy","excel","csv","pdf","print"],name:"main",tabIndex:0,dom:{container:{tag:"div",className:"dt-buttons"},collection:{tag:"div",className:"dt-button-collection"},
button:{tag:"a",className:"dt-button",active:"active",disabled:"disabled"},buttonLiner:{tag:"span",className:""}}};m.version="1.2.1";d.extend(k,{collection:{text:function(a){return a.i18n("buttons.collection","Collection")},className:"buttons-collection",action:function(a,b,c,e){var a=c.offset(),g=d(b.table().container()),h=!1;d("div.dt-button-background").length&&(h=d("div.dt-button-collection").offset(),d("body").trigger("click.dtb-collection"));e._collection.addClass(e.collectionLayout).css("display",
"none").appendTo("body").fadeIn(e.fade);var f=e._collection.css("position");h&&"absolute"===f?e._collection.css({top:h.top+5,left:h.left+5}):"absolute"===f?(e._collection.css({top:a.top+c.outerHeight(),left:a.left}),c=a.left+e._collection.outerWidth(),g=g.offset().left+g.width(),c>g&&e._collection.css("left",a.left-(c-g))):(a=e._collection.height()/2,a>d(o).height()/2&&(a=d(o).height()/2),e._collection.css("marginTop",-1*a));e.background&&m.background(!0,e.backgroundClassName,e.fade);setTimeout(function(){d("div.dt-button-background").on("click.dtb-collection",
function(){});d("body").on("click.dtb-collection",function(a){if(!d(a.target).parents().andSelf().filter(e._collection).length){e._collection.fadeOut(e.fade,function(){e._collection.detach()});d("div.dt-button-background").off("click.dtb-collection");m.background(false,e.backgroundClassName,e.fade);d("body").off("click.dtb-collection");b.off("buttons-action.b-internal")}})},10);if(e.autoClose)b.on("buttons-action.b-internal",function(){d("div.dt-button-background").click()})},background:!0,collectionLayout:"",
backgroundClassName:"dt-button-background",autoClose:!1,fade:400},copy:function(a,b){if(k.copyHtml5)return"copyHtml5";if(k.copyFlash&&k.copyFlash.available(a,b))return"copyFlash"},csv:function(a,b){if(k.csvHtml5&&k.csvHtml5.available(a,b))return"csvHtml5";if(k.csvFlash&&k.csvFlash.available(a,b))return"csvFlash"},excel:function(a,b){if(k.excelHtml5&&k.excelHtml5.available(a,b))return"excelHtml5";if(k.excelFlash&&k.excelFlash.available(a,b))return"excelFlash"},pdf:function(a,b){if(k.pdfHtml5&&k.pdfHtml5.available(a,
b))return"pdfHtml5";if(k.pdfFlash&&k.pdfFlash.available(a,b))return"pdfFlash"},pageLength:function(a){var a=a.settings()[0].aLengthMenu,b=d.isArray(a[0])?a[0]:a,c=d.isArray(a[0])?a[1]:a,e=function(a){return a.i18n("buttons.pageLength",{"-1":"Show all rows",_:"Show %d rows"},a.page.len())};return{extend:"collection",text:e,className:"buttons-page-length",autoClose:!0,buttons:d.map(b,function(a,b){return{text:c[b],action:function(b,c){c.page.len(a).draw()},init:function(b,c,d){var e=this,c=function(){e.active(b.page.len()===
a)};b.on("length.dt"+d.namespace,c);c()},destroy:function(a,b,c){a.off("length.dt"+c.namespace)}}}),init:function(a,b,c){var d=this;a.on("length.dt"+c.namespace,function(){d.text(e(a))})},destroy:function(a,b,c){a.off("length.dt"+c.namespace)}}}});i.Api.register("buttons()",function(a,b){b===l&&(b=a,a=l);return this.iterator(!0,"table",function(c){if(c._buttons)return m.buttonSelector(m.instanceSelector(a,c._buttons),b)},!0)});i.Api.register("button()",function(a,b){var c=this.buttons(a,b);1<c.length&&
c.splice(1,c.length);return c});i.Api.registerPlural("buttons().active()","button().active()",function(a){return a===l?this.map(function(a){return a.inst.active(a.node)}):this.each(function(b){b.inst.active(b.node,a)})});i.Api.registerPlural("buttons().action()","button().action()",function(a){return a===l?this.map(function(a){return a.inst.action(a.node)}):this.each(function(b){b.inst.action(b.node,a)})});i.Api.register(["buttons().enable()","button().enable()"],function(a){return this.each(function(b){b.inst.enable(b.node,
a)})});i.Api.register(["buttons().disable()","button().disable()"],function(){return this.each(function(a){a.inst.disable(a.node)})});i.Api.registerPlural("buttons().nodes()","button().node()",function(){var a=d();d(this.each(function(b){a=a.add(b.inst.node(b.node))}));return a});i.Api.registerPlural("buttons().text()","button().text()",function(a){return a===l?this.map(function(a){return a.inst.text(a.node)}):this.each(function(b){b.inst.text(b.node,a)})});i.Api.registerPlural("buttons().trigger()",
"button().trigger()",function(){return this.each(function(a){a.inst.node(a.node).trigger("click")})});i.Api.registerPlural("buttons().containers()","buttons().container()",function(){var a=d();d(this.each(function(b){a=a.add(b.inst.container())}));return a});i.Api.register("button().add()",function(a,b){1===this.length&&this[0].inst.add(b,a);return this.button(a)});i.Api.register("buttons().destroy()",function(){this.pluck("inst").unique().each(function(a){a.destroy()});return this});i.Api.registerPlural("buttons().remove()",
"buttons().remove()",function(){this.each(function(a){a.inst.remove(a.node)});return this});var r;i.Api.register("buttons.info()",function(a,b,c){var e=this;if(!1===a)return d("#datatables_buttons_info").fadeOut(function(){d(this).remove()}),clearTimeout(r),r=null,this;r&&clearTimeout(r);d("#datatables_buttons_info").length&&d("#datatables_buttons_info").remove();d('<div id="datatables_buttons_info" class="dt-button-info"/>').html(a?"<h2>"+a+"</h2>":"").append(d("<div/>")["string"===typeof b?"html":
"append"](b)).css("display","none").appendTo("body").fadeIn();c!==l&&0!==c&&(r=setTimeout(function(){e.buttons.info(!1)},c));return this});i.Api.register("buttons.exportData()",function(a){if(this.context.length){for(var b=new i.Api(this.context[0]),c=d.extend(!0,{},{rows:null,columns:"",modifier:{search:"applied",order:"applied"},orthogonal:"display",stripHtml:!0,stripNewlines:!0,decodeEntities:!0,trim:!0,format:{header:function(a){return e(a)},footer:function(a){return e(a)},body:function(a){return e(a)}}},
a),e=function(a){if("string"!==typeof a)return a;c.stripHtml&&(a=a.replace(/<[^>]*>/g,""));c.trim&&(a=a.replace(/^\s+|\s+$/g,""));c.stripNewlines&&(a=a.replace(/\n/g," "));c.decodeEntities&&(t.innerHTML=a,a=t.value);return a},a=b.columns(c.columns).indexes().map(function(a){return c.format.header(b.column(a).header().innerHTML,a)}).toArray(),g=b.table().footer()?b.columns(c.columns).indexes().map(function(a){var d=b.column(a).footer();return c.format.footer(d?d.innerHTML:"",a)}).toArray():null,h=
b.rows(c.rows,c.modifier).indexes().toArray(),h=b.cells(h,c.columns).render(c.orthogonal).toArray(),f=a.length,k=0<f?h.length/f:0,j=Array(k),m=0,l=0;l<k;l++){for(var o=Array(f),n=0;n<f;n++)o[n]=c.format.body(h[m],n,l),m++;j[l]=o}return{header:a,footer:g,body:j}}});var t=d("<textarea/>")[0];d.fn.dataTable.Buttons=m;d.fn.DataTable.Buttons=m;d(n).on("init.dt plugin-init.dt",function(a,b){if("dt"===a.namespace){var c=b.oInit.buttons||i.defaults.buttons;c&&!b._buttons&&(new m(b,c)).container()}});i.ext.feature.push({fnInit:function(a){var a=
new i.Api(a),b=a.init().buttons||i.defaults.buttons;return(new m(a,b)).container()},cFeature:"B"});return m});

(function(g){"function"===typeof define&&define.amd?define(["jquery","datatables.net","datatables.net-buttons"],function(i){return g(i,window,document)}):"object"===typeof exports?module.exports=function(i,j,t,u){i||(i=window);if(!j||!j.fn.dataTable)j=require("datatables.net")(i,j).$;j.fn.dataTable.Buttons||require("datatables.net-buttons")(i,j);return g(j,i,i.document,t,u)}:g(jQuery,window,document)})(function(g,i,j,t,u,o){function E(a,b){v===o&&(v=-1===y.serializeToString(g.parseXML(F["xl/worksheets/sheet1.xml"])).indexOf("xmlns:r"));
g.each(b,function(b,c){if(g.isPlainObject(c)){var f=a.folder(b);E(f,c)}else{if(v){var f=c.childNodes[0],e,h,l=[];for(e=f.attributes.length-1;0<=e;e--){h=f.attributes[e].nodeName;var n=f.attributes[e].nodeValue;-1!==h.indexOf(":")&&(l.push({name:h,value:n}),f.removeAttribute(h))}e=0;for(h=l.length;e<h;e++)n=c.createAttribute(l[e].name.replace(":","_dt_b_namespace_token_")),n.value=l[e].value,f.setAttributeNode(n)}f=y.serializeToString(c);v&&(-1===f.indexOf("<?xml")&&(f='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'+
f),f=f.replace(/_dt_b_namespace_token_/g,":"));f=f.replace(/<row xmlns="" /g,"<row ").replace(/<cols xmlns="">/g,"<cols>");a.file(b,f)}})}function p(a,b,d){var c=a.createElement(b);d&&(d.attr&&g(c).attr(d.attr),d.children&&g.each(d.children,function(a,b){c.appendChild(b)}),d.text&&c.appendChild(a.createTextNode(d.text)));return c}function N(a,b){var d=a.header[b].length,c;a.footer&&a.footer[b].length>d&&(d=a.footer[b].length);for(var f=0,e=a.body.length;f<e&&!(c=a.body[f][b].toString().length,c>d&&
(d=c),40<d);f++);return 5<d?d:5}var r=g.fn.dataTable,q;var h="undefined"!==typeof self&&self||"undefined"!==typeof i&&i||this.content;if("undefined"!==typeof navigator&&/MSIE [1-9]\./.test(navigator.userAgent))q=void 0;else{var w=h.document.createElementNS("http://www.w3.org/1999/xhtml","a"),O="download"in w,G=/Version\/[\d\.]+.*Safari/.test(navigator.userAgent),z=h.webkitRequestFileSystem,H=h.requestFileSystem||z||h.mozRequestFileSystem,P=function(a){(h.setImmediate||h.setTimeout)(function(){throw a;
},0)},A=0,B=function(a){setTimeout(function(){"string"===typeof a?(h.URL||h.webkitURL||h).revokeObjectURL(a):a.remove()},4E4)},C=function(a,b,d){for(var b=[].concat(b),c=b.length;c--;){var f=a["on"+b[c]];if("function"===typeof f)try{f.call(a,d||a)}catch(e){P(e)}}},I=function(a){return/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type)?new Blob(["﻿",a],{type:a.type}):a},J=function(a,b,d){d||(a=I(a));var c=this,d=a.type,f=!1,e,g,l=function(){C(c,["writestart","progress",
"write","writeend"])},n=function(){if(g&&G&&"undefined"!==typeof FileReader){var b=new FileReader;b.onloadend=function(){var a=b.result;g.location.href="data:attachment/file"+a.slice(a.search(/[,;]/));c.readyState=c.DONE;l()};b.readAsDataURL(a);c.readyState=c.INIT}else{if(f||!e)e=(h.URL||h.webkitURL||h).createObjectURL(a);g?g.location.href=e:h.open(e,"_blank")===o&&G&&(h.location.href=e);c.readyState=c.DONE;l();B(e)}},m=function(a){return function(){if(c.readyState!==c.DONE)return a.apply(this,arguments)}},
i={create:!0,exclusive:!1},s;c.readyState=c.INIT;b||(b="download");if(O)e=(h.URL||h.webkitURL||h).createObjectURL(a),setTimeout(function(){w.href=e;w.download=b;var a=new MouseEvent("click");w.dispatchEvent(a);l();B(e);c.readyState=c.DONE});else{h.chrome&&(d&&"application/octet-stream"!==d)&&(s=a.slice||a.webkitSlice,a=s.call(a,0,a.size,"application/octet-stream"),f=!0);z&&"download"!==b&&(b+=".download");if("application/octet-stream"===d||z)g=h;H?(A+=a.size,H(h.TEMPORARY,A,m(function(d){d.root.getDirectory("saved",
i,m(function(d){var e=function(){d.getFile(b,i,m(function(b){b.createWriter(m(function(d){d.onwriteend=function(a){g.location.href=b.toURL();c.readyState=c.DONE;C(c,"writeend",a);B(b)};d.onerror=function(){var a=d.error;a.code!==a.ABORT_ERR&&n()};["writestart","progress","write","abort"].forEach(function(a){d["on"+a]=c["on"+a]});d.write(a);c.abort=function(){d.abort();c.readyState=c.DONE};c.readyState=c.WRITING}),n)}),n)};d.getFile(b,{create:false},m(function(a){a.remove();e()}),m(function(a){a.code===
a.NOT_FOUND_ERR?e():n()}))}),n)}),n)):n()}},k=J.prototype;"undefined"!==typeof navigator&&navigator.msSaveOrOpenBlob?q=function(a,b,d){d||(a=I(a));return navigator.msSaveOrOpenBlob(a,b||"download")}:(k.abort=function(){this.readyState=this.DONE;C(this,"abort")},k.readyState=k.INIT=0,k.WRITING=1,k.DONE=2,k.error=k.onwritestart=k.onprogress=k.onwrite=k.onabort=k.onerror=k.onwriteend=null,q=function(a,b,d){return new J(a,b,d)})}r.fileSave=q;var x=function(a,b){var d="*"===a.filename&&"*"!==a.title&&
a.title!==o?a.title:a.filename;"function"===typeof d&&(d=d());-1!==d.indexOf("*")&&(d=g.trim(d.replace("*",g("title").text())));d=d.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g,"");return b===o||!0===b?d+a.extension:d},Q=function(a){var b="Sheet1";a.sheetName&&(b=a.sheetName.replace(/[\[\]\*\/\\\?\:]/g,""));return b},R=function(a){a=a.title;"function"===typeof a&&(a=a());return-1!==a.indexOf("*")?a.replace("*",g("title").text()||"Exported data"):a},K=function(a){return a.newline?a.newline:navigator.userAgent.match(/Windows/)?
"\r\n":"\n"},L=function(a,b){for(var d=K(b),c=a.buttons.exportData(b.exportOptions),f=b.fieldBoundary,e=b.fieldSeparator,g=RegExp(f,"g"),l=b.escapeChar!==o?b.escapeChar:"\\",h=function(a){for(var b="",c=0,d=a.length;c<d;c++)0<c&&(b+=e),b+=f?f+(""+a[c]).replace(g,l+f)+f:a[c];return b},i=b.header?h(c.header)+d:"",j=b.footer&&c.footer?d+h(c.footer):"",s=[],D=0,k=c.body.length;D<k;D++)s.push(h(c.body[D]));return{str:i+s.join(d)+j,rows:s.length}},M=function(){return-1!==navigator.userAgent.indexOf("Safari")&&
-1===navigator.userAgent.indexOf("Chrome")&&-1===navigator.userAgent.indexOf("Opera")};try{var y=new XMLSerializer,v}catch(S){}var F={"_rels/.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',"xl/_rels/workbook.xml.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
"[Content_Types].xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
"xl/workbook.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="" sheetId="1" r:id="rId1"/></sheets></workbook>',
"xl/worksheets/sheet1.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/></worksheet>',"xl/styles.xml":'<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill/><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="56"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>'};
r.ext.buttons.copyHtml5={className:"buttons-copy buttons-html5",text:function(a){return a.i18n("buttons.copy","Copy")},action:function(a,b,d,c){var a=L(b,c),f=a.str,d=g("<div/>").css({height:1,width:1,overflow:"hidden",position:"fixed",top:0,left:0});c.customize&&(f=c.customize(f,c));c=g("<textarea readonly/>").val(f).appendTo(d);if(j.queryCommandSupported("copy")){d.appendTo(b.table().container());c[0].focus();c[0].select();try{var e=j.execCommand("copy");d.remove();if(e){b.buttons.info(b.i18n("buttons.copyTitle",
"Copy to clipboard"),b.i18n("buttons.copySuccess",{1:"Copied one row to clipboard",_:"Copied %d rows to clipboard"},a.rows),2E3);return}}catch(h){}}e=g("<span>"+b.i18n("buttons.copyKeys","Press <i>ctrl</i> or <i>⌘</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.")+"</span>").append(d);b.buttons.info(b.i18n("buttons.copyTitle","Copy to clipboard"),e,0);c[0].focus();c[0].select();var l=g(e).closest(".dt-button-info"),i=function(){l.off("click.buttons-copy");
g(j).off(".buttons-copy");b.buttons.info(!1)};l.on("click.buttons-copy",i);g(j).on("keydown.buttons-copy",function(a){27===a.keyCode&&i()}).on("copy.buttons-copy cut.buttons-copy",function(){i()})},exportOptions:{},fieldSeparator:"\t",fieldBoundary:"",header:!0,footer:!1};r.ext.buttons.csvHtml5={bom:!1,className:"buttons-csv buttons-html5",available:function(){return i.FileReader!==o&&i.Blob},text:function(a){return a.i18n("buttons.csv","CSV")},action:function(a,b,d,c){a=L(b,c).str;b=c.charset;c.customize&&
(a=c.customize(a,c));!1!==b?(b||(b=j.characterSet||j.charset),b&&(b=";charset="+b)):b="";c.bom&&(a="﻿"+a);q(new Blob([a],{type:"text/csv"+b}),x(c),!0)},filename:"*",extension:".csv",exportOptions:{},fieldSeparator:",",fieldBoundary:'"',escapeChar:'"',charset:null,header:!0,footer:!1};r.ext.buttons.excelHtml5={className:"buttons-excel buttons-html5",available:function(){return i.FileReader!==o&&(t||i.JSZip)!==o&&!M()&&y},text:function(a){return a.i18n("buttons.excel","Excel")},action:function(a,b,
d,c){var f=0,a=function(a){return g.parseXML(F[a])},e=a("xl/worksheets/sheet1.xml"),h=e.getElementsByTagName("sheetData")[0],a={_rels:{".rels":a("_rels/.rels")},xl:{_rels:{"workbook.xml.rels":a("xl/_rels/workbook.xml.rels")},"workbook.xml":a("xl/workbook.xml"),"styles.xml":a("xl/styles.xml"),worksheets:{"sheet1.xml":e}},"[Content_Types].xml":a("[Content_Types].xml")},b=b.buttons.exportData(c.exportOptions),l,j,d=function(a){l=f+1;j=p(e,"row",{attr:{r:l}});for(var b=0,c=a.length;b<c;b++){for(var d=
b,i="";0<=d;)i=String.fromCharCode(d%26+65)+i,d=Math.floor(d/26)-1;d=i+""+l;if(null===a[b]||a[b]===o)a[b]="";"number"===typeof a[b]||a[b].match&&g.trim(a[b]).match(/^-?\d+(\.\d+)?$/)&&!g.trim(a[b]).match(/^0\d+/)?d=p(e,"c",{attr:{t:"n",r:d},children:[p(e,"v",{text:a[b]})]}):(i=!a[b].replace?a[b]:a[b].replace(/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g,""),d=p(e,"c",{attr:{t:"inlineStr",r:d},children:{row:p(e,"is",{children:{row:p(e,"t",{text:i})}})}}));j.appendChild(d)}h.appendChild(j);f++};g("sheets sheet",
a.xl["workbook.xml"]).attr("name",Q(c));c.customizeData&&c.customizeData(b);c.header&&(d(b.header,f),g("row c",e).attr("s","2"));for(var m=0,k=b.body.length;m<k;m++)d(b.body[m],f);c.footer&&b.footer&&(d(b.footer,f),g("row:last c",e).attr("s","2"));d=p(e,"cols");g("worksheet",e).prepend(d);m=0;for(k=b.header.length;m<k;m++)d.appendChild(p(e,"col",{attr:{min:m+1,max:m+1,width:N(b,m),customWidth:1}}));c.customize&&c.customize(a);b=new (t||i.JSZip);d={type:"blob",mimeType:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"};
E(b,a);b.generateAsync?b.generateAsync(d).then(function(a){q(a,x(c))}):q(b.generate(d),x(c))},filename:"*",extension:".xlsx",exportOptions:{},header:!0,footer:!1};r.ext.buttons.pdfHtml5={className:"buttons-pdf buttons-html5",available:function(){return i.FileReader!==o&&(u||i.pdfMake)},text:function(a){return a.i18n("buttons.pdf","PDF")},action:function(a,b,d,c){K(c);var a=b.buttons.exportData(c.exportOptions),f=[];c.header&&f.push(g.map(a.header,function(a){return{text:"string"===typeof a?a:a+"",
style:"tableHeader"}}));for(var e=0,h=a.body.length;e<h;e++)f.push(g.map(a.body[e],function(a){return{text:"string"===typeof a?a:a+"",style:e%2?"tableBodyEven":"tableBodyOdd"}}));c.footer&&a.footer&&f.push(g.map(a.footer,function(a){return{text:"string"===typeof a?a:a+"",style:"tableFooter"}}));a={pageSize:c.pageSize,pageOrientation:c.orientation,content:[{table:{headerRows:1,body:f},layout:"noBorders"}],styles:{tableHeader:{bold:!0,fontSize:11,color:"white",fillColor:"#2d4154",alignment:"center"},
tableBodyEven:{},tableBodyOdd:{fillColor:"#f3f3f3"},tableFooter:{bold:!0,fontSize:11,color:"white",fillColor:"#2d4154"},title:{alignment:"center",fontSize:15},message:{}},defaultStyle:{fontSize:10}};c.message&&a.content.unshift({text:"function"==typeof c.message?c.message(b,d,c):c.message,style:"message",margin:[0,0,0,12]});c.title&&a.content.unshift({text:R(c,!1),style:"title",margin:[0,0,0,12]});c.customize&&c.customize(a,c);b=(u||i.pdfMake).createPdf(a);"open"===c.download&&!M()?b.open():b.getBuffer(function(a){a=
new Blob([a],{type:"application/pdf"});q(a,x(c))})},title:"*",filename:"*",extension:".pdf",exportOptions:{},orientation:"portrait",pageSize:"A4",header:!0,footer:!1,message:null,customize:null,download:"download"};return r.Buttons});

(function(f){"function"===typeof define&&define.amd?define(["jquery","datatables.net","datatables.net-buttons"],function(j){return f(j,window,document)}):"object"===typeof exports?module.exports=function(j,k){j||(j=window);if(!k||!k.fn.dataTable)k=require("datatables.net")(j,k).$;k.fn.dataTable.Buttons||require("datatables.net-buttons")(j,k);return f(k,j,j.document)}:f(jQuery,window,document)})(function(f,j,k,o){function l(a,b,d){var c=a.createElement(b);d&&(d.attr&&f(c).attr(d.attr),d.children&&
f.each(d.children,function(a,b){c.appendChild(b)}),d.text&&c.appendChild(a.createTextNode(d.text)));return c}function z(a,b){var d=a.header[b].length,c;a.footer&&a.footer[b].length>d&&(d=a.footer[b].length);for(var e=0,g=a.body.length;e<g&&!(c=a.body[e][b].toString().length,c>d&&(d=c),40<d);e++);return 5<d?d:5}function v(a){n===o&&(n=-1===w.serializeToString(f.parseXML(p["xl/worksheets/sheet1.xml"])).indexOf("xmlns:r"));f.each(a,function(b,d){if(f.isPlainObject(d))v(d);else{if(n){var c=d.childNodes[0],
e,g,h=[];for(e=c.attributes.length-1;0<=e;e--){g=c.attributes[e].nodeName;var q=c.attributes[e].nodeValue;-1!==g.indexOf(":")&&(h.push({name:g,value:q}),c.removeAttribute(g))}e=0;for(g=h.length;e<g;e++)q=d.createAttribute(h[e].name.replace(":","_dt_b_namespace_token_")),q.value=h[e].value,c.setAttributeNode(q)}c=w.serializeToString(d);n&&(-1===c.indexOf("<?xml")&&(c='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'+c),c=c.replace(/_dt_b_namespace_token_/g,":"));c=c.replace(/<row xmlns="" /g,
"<row ").replace(/<cols xmlns="">/g,"<cols>");a[b]=c}})}var i=f.fn.dataTable,h={version:"1.0.4-TableTools2",clients:{},moviePath:"",nextId:1,$:function(a){"string"==typeof a&&(a=k.getElementById(a));a.addClass||(a.hide=function(){this.style.display="none"},a.show=function(){this.style.display=""},a.addClass=function(a){this.removeClass(a);this.className+=" "+a},a.removeClass=function(a){this.className=this.className.replace(RegExp("\\s*"+a+"\\s*")," ").replace(/^\s+/,"").replace(/\s+$/,"")},a.hasClass=
function(a){return!!this.className.match(RegExp("\\s*"+a+"\\s*"))});return a},setMoviePath:function(a){this.moviePath=a},dispatch:function(a,b,d){(a=this.clients[a])&&a.receiveEvent(b,d)},log:function(a){console.log("Flash: "+a)},register:function(a,b){this.clients[a]=b},getDOMObjectPosition:function(a){var b={left:0,top:0,width:a.width?a.width:a.offsetWidth,height:a.height?a.height:a.offsetHeight};""!==a.style.width&&(b.width=a.style.width.replace("px",""));""!==a.style.height&&(b.height=a.style.height.replace("px",
""));for(;a;)b.left+=a.offsetLeft,b.top+=a.offsetTop,a=a.offsetParent;return b},Client:function(a){this.handlers={};this.id=h.nextId++;this.movieId="ZeroClipboard_TableToolsMovie_"+this.id;h.register(this.id,this);a&&this.glue(a)}};h.Client.prototype={id:0,ready:!1,movie:null,clipText:"",fileName:"",action:"copy",handCursorEnabled:!0,cssEffects:!0,handlers:null,sized:!1,sheetName:"",glue:function(a,b){this.domElement=h.$(a);var d=99;this.domElement.style.zIndex&&(d=parseInt(this.domElement.style.zIndex,
10)+1);var c=h.getDOMObjectPosition(this.domElement);this.div=k.createElement("div");var e=this.div.style;e.position="absolute";e.left="0px";e.top="0px";e.width=c.width+"px";e.height=c.height+"px";e.zIndex=d;"undefined"!=typeof b&&""!==b&&(this.div.title=b);0!==c.width&&0!==c.height&&(this.sized=!0);this.domElement&&(this.domElement.appendChild(this.div),this.div.innerHTML=this.getHTML(c.width,c.height).replace(/&/g,"&amp;"))},positionElement:function(){var a=h.getDOMObjectPosition(this.domElement),
b=this.div.style;b.position="absolute";b.width=a.width+"px";b.height=a.height+"px";0!==a.width&&0!==a.height&&(this.sized=!0,b=this.div.childNodes[0],b.width=a.width,b.height=a.height)},getHTML:function(a,b){var d="",c="id="+this.id+"&width="+a+"&height="+b;if(navigator.userAgent.match(/MSIE/))var e=location.href.match(/^https/i)?"https://":"http://",d=d+('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="'+e+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="'+
a+'" height="'+b+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+h.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+c+'"/><param name="wmode" value="transparent"/></object>');else d+='<embed id="'+this.movieId+'" src="'+h.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+
a+'" height="'+b+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+c+'" wmode="transparent" />';return d},hide:function(){this.div&&(this.div.style.left="-2000px")},show:function(){this.reposition()},destroy:function(){var a=this;this.domElement&&this.div&&(f(this.div).remove(),this.div=this.domElement=null,f.each(h.clients,function(b,d){d===a&&delete h.clients[b]}))},
reposition:function(a){a&&((this.domElement=h.$(a))||this.hide());if(this.domElement&&this.div){var a=h.getDOMObjectPosition(this.domElement),b=this.div.style;b.left=""+a.left+"px";b.top=""+a.top+"px"}},clearText:function(){this.clipText="";this.ready&&this.movie.clearText()},appendText:function(a){this.clipText+=a;this.ready&&this.movie.appendText(a)},setText:function(a){this.clipText=a;this.ready&&this.movie.setText(a)},setFileName:function(a){this.fileName=a;this.ready&&this.movie.setFileName(a)},
setSheetData:function(a){this.ready&&this.movie.setSheetData(JSON.stringify(a))},setAction:function(a){this.action=a;this.ready&&this.movie.setAction(a)},addEventListener:function(a,b){a=a.toString().toLowerCase().replace(/^on/,"");this.handlers[a]||(this.handlers[a]=[]);this.handlers[a].push(b)},setHandCursor:function(a){this.handCursorEnabled=a;this.ready&&this.movie.setHandCursor(a)},setCSSEffects:function(a){this.cssEffects=!!a},receiveEvent:function(a,b){var d,a=a.toString().toLowerCase().replace(/^on/,
"");switch(a){case "load":this.movie=k.getElementById(this.movieId);if(!this.movie){d=this;setTimeout(function(){d.receiveEvent("load",null)},1);return}if(!this.ready&&navigator.userAgent.match(/Firefox/)&&navigator.userAgent.match(/Windows/)){d=this;setTimeout(function(){d.receiveEvent("load",null)},100);this.ready=!0;return}this.ready=!0;this.movie.clearText();this.movie.appendText(this.clipText);this.movie.setFileName(this.fileName);this.movie.setAction(this.action);this.movie.setHandCursor(this.handCursorEnabled);
break;case "mouseover":this.domElement&&this.cssEffects&&this.recoverActive&&this.domElement.addClass("active");break;case "mouseout":this.domElement&&this.cssEffects&&(this.recoverActive=!1,this.domElement.hasClass("active")&&(this.domElement.removeClass("active"),this.recoverActive=!0));break;case "mousedown":this.domElement&&this.cssEffects&&this.domElement.addClass("active");break;case "mouseup":this.domElement&&this.cssEffects&&(this.domElement.removeClass("active"),this.recoverActive=!1)}if(this.handlers[a])for(var c=
0,e=this.handlers[a].length;c<e;c++){var g=this.handlers[a][c];if("function"==typeof g)g(this,b);else if("object"==typeof g&&2==g.length)g[0][g[1]](this,b);else if("string"==typeof g)j[g](this,b)}}};h.hasFlash=function(){try{if(new ActiveXObject("ShockwaveFlash.ShockwaveFlash"))return!0}catch(a){if(navigator.mimeTypes&&navigator.mimeTypes["application/x-shockwave-flash"]!==o&&navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin)return!0}return!1};j.ZeroClipboard_TableTools=h;var x=function(a,
b){b.attr("id");b.parents("html").length?a.glue(b[0],""):setTimeout(function(){x(a,b)},500)},r=function(a,b){var d="*"===a.filename&&"*"!==a.title&&a.title!==o?a.title:a.filename;"function"===typeof d&&(d=d());-1!==d.indexOf("*")&&(d=f.trim(d.replace("*",f("title").text())));d=d.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g,"");return b===o||!0===b?d+a.extension:d},A=function(a){var b="Sheet1";a.sheetName&&(b=a.sheetName.replace(/[\[\]\*\/\\\?\:]/g,""));return b},s=function(a,b){var d=b.match(/[\s\S]{1,8192}/g)||
[];a.clearText();for(var c=0,e=d.length;c<e;c++)a.appendText(d[c])},y=function(a,b){for(var d=b.newline?b.newline:navigator.userAgent.match(/Windows/)?"\r\n":"\n",c=a.buttons.exportData(b.exportOptions),e=b.fieldBoundary,g=b.fieldSeparator,f=RegExp(e,"g"),h=b.escapeChar!==o?b.escapeChar:"\\",j=function(a){for(var b="",c=0,d=a.length;c<d;c++)0<c&&(b+=g),b+=e?e+(""+a[c]).replace(f,h+e)+e:a[c];return b},k=b.header?j(c.header)+d:"",m=b.footer&&c.footer?d+j(c.footer):"",l=[],u=0,B=c.body.length;u<B;u++)l.push(j(c.body[u]));
return{str:k+l.join(d)+m,rows:l.length}},t={available:function(){return h.hasFlash()},init:function(a,b,d){h.moviePath=i.Buttons.swfPath;var c=new h.Client;c.setHandCursor(!0);c.addEventListener("mouseDown",function(){d._fromFlash=!0;a.button(b[0]).trigger();d._fromFlash=!1});x(c,b);d._flash=c},destroy:function(a,b,d){d._flash.destroy()},fieldSeparator:",",fieldBoundary:'"',exportOptions:{},title:"*",filename:"*",extension:".csv",header:!0,footer:!1};try{var w=new XMLSerializer,n}catch(C){}var p=
{"_rels/.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',"xl/_rels/workbook.xml.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
"[Content_Types].xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
"xl/workbook.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="" sheetId="1" r:id="rId1"/></sheets></workbook>',
"xl/worksheets/sheet1.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/></worksheet>',"xl/styles.xml":'<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill/><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="56"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>'};
i.Buttons.swfPath="//cdn.datatables.net/buttons/1.2.0/swf/flashExport.swf";i.Api.register("buttons.resize()",function(){f.each(h.clients,function(a,b){b.domElement!==o&&b.domElement.parentNode&&b.positionElement()})});i.ext.buttons.copyFlash=f.extend({},t,{className:"buttons-copy buttons-flash",text:function(a){return a.i18n("buttons.copy","Copy")},action:function(a,b,d,c){c._fromFlash&&(a=c._flash,d=y(b,c),c=c.customize?c.customize(d.str,c):d.str,a.setAction("copy"),s(a,c),b.buttons.info(b.i18n("buttons.copyTitle",
"Copy to clipboard"),b.i18n("buttons.copySuccess",{_:"Copied %d rows to clipboard",1:"Copied 1 row to clipboard"},d.rows),3E3))},fieldSeparator:"\t",fieldBoundary:""});i.ext.buttons.csvFlash=f.extend({},t,{className:"buttons-csv buttons-flash",text:function(a){return a.i18n("buttons.csv","CSV")},action:function(a,b,d,c){a=c._flash;b=y(b,c);b=c.customize?c.customize(b.str,c):b.str;a.setAction("csv");a.setFileName(r(c));s(a,b)},escapeChar:'"'});i.ext.buttons.excelFlash=f.extend({},t,{className:"buttons-excel buttons-flash",
text:function(a){return a.i18n("buttons.excel","Excel")},action:function(a,b,d,c){var a=c._flash,e=0,g=f.parseXML(p["xl/worksheets/sheet1.xml"]),h=g.getElementsByTagName("sheetData")[0],d={_rels:{".rels":f.parseXML(p["_rels/.rels"])},xl:{_rels:{"workbook.xml.rels":f.parseXML(p["xl/_rels/workbook.xml.rels"])},"workbook.xml":f.parseXML(p["xl/workbook.xml"]),"styles.xml":f.parseXML(p["xl/styles.xml"]),worksheets:{"sheet1.xml":g}},"[Content_Types].xml":f.parseXML(p["[Content_Types].xml"])},b=b.buttons.exportData(c.exportOptions),
j,k,i=function(a){j=e+1;k=l(g,"row",{attr:{r:j}});for(var b=0,c=a.length;b<c;b++){for(var d=b,i="";0<=d;)i=String.fromCharCode(d%26+65)+i,d=Math.floor(d/26)-1;d=i+""+j;if(null===a[b]||a[b]===o)a[b]="";"number"===typeof a[b]||a[b].match&&f.trim(a[b]).match(/^-?\d+(\.\d+)?$/)&&!f.trim(a[b]).match(/^0\d+/)?d=l(g,"c",{attr:{t:"n",r:d},children:[l(g,"v",{text:a[b]})]}):(i=!a[b].replace?a[b]:a[b].replace(/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g,""),d=l(g,"c",{attr:{t:"inlineStr",r:d},children:{row:l(g,
"is",{children:{row:l(g,"t",{text:i})}})}}));k.appendChild(d)}h.appendChild(k);e++};f("sheets sheet",d.xl["workbook.xml"]).attr("name",A(c));c.customizeData&&c.customizeData(b);c.header&&(i(b.header,e),f("row c",g).attr("s","2"));for(var m=0,n=b.body.length;m<n;m++)i(b.body[m],e);c.footer&&b.footer&&(i(b.footer,e),f("row:last c",g).attr("s","2"));i=l(g,"cols");f("worksheet",g).prepend(i);m=0;for(n=b.header.length;m<n;m++)i.appendChild(l(g,"col",{attr:{min:m+1,max:m+1,width:z(b,m),customWidth:1}}));
c.customize&&c.customize(d);v(d);a.setAction("excel");a.setFileName(r(c));a.setSheetData(d);s(a,"")},extension:".xlsx"});i.ext.buttons.pdfFlash=f.extend({},t,{className:"buttons-pdf buttons-flash",text:function(a){return a.i18n("buttons.pdf","PDF")},action:function(a,b,d,c){var a=c._flash,e=b.buttons.exportData(c.exportOptions),g=b.table().node().offsetWidth,f=b.columns(c.columns).indexes().map(function(a){return b.column(a).header().offsetWidth/g});a.setAction("pdf");a.setFileName(r(c));s(a,JSON.stringify({title:r(c,
!1),message:"function"==typeof c.message?c.message(b,d,c):c.message,colWidth:f.toArray(),orientation:c.orientation,size:c.pageSize,header:c.header?e.header:null,footer:c.footer?e.footer:null,body:e.body}))},extension:".pdf",orientation:"portrait",pageSize:"A4",message:"",newline:"\n"});return i.Buttons});

/**
 * Created by rafael on 16/09/16.
 */
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });
    $('.colorbox').colorbox({ transition:"fade", width:"95%", height:"95%"});
    $('form').submit(function (evento) {
        $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    });
});