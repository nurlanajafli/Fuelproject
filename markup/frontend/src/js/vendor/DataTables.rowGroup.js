/*!
   Copyright 2017-2021 SpryMedia Ltd.

 This source file is free software, available under the following license:
   MIT license - http://datatables.net/license/mit

 This source file is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.

 For details please refer to: http://www.datatables.net
 RowGroup 1.1.4
 ©2017-2021 SpryMedia Ltd - datatables.net/license
*/
var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.findInternal=function(b,c,d){b instanceof String&&(b=String(b));for(var g=b.length,e=0;e<g;e++){var k=b[e];if(c.call(d,k,e,b))return{i:e,v:k}}return{i:-1,v:void 0}};$jscomp.ASSUME_ES5=!1;$jscomp.ASSUME_NO_NATIVE_MAP=!1;$jscomp.ASSUME_NO_NATIVE_SET=!1;$jscomp.SIMPLE_FROUND_POLYFILL=!1;$jscomp.ISOLATE_POLYFILLS=!1;
$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(b,c,d){if(b==Array.prototype||b==Object.prototype)return b;b[c]=d.value;return b};$jscomp.getGlobal=function(b){b=["object"==typeof globalThis&&globalThis,b,"object"==typeof window&&window,"object"==typeof self&&self,"object"==typeof global&&global];for(var c=0;c<b.length;++c){var d=b[c];if(d&&d.Math==Math)return d}throw Error("Cannot find global object");};$jscomp.global=$jscomp.getGlobal(this);
$jscomp.IS_SYMBOL_NATIVE="function"===typeof Symbol&&"symbol"===typeof Symbol("x");$jscomp.TRUST_ES6_POLYFILLS=!$jscomp.ISOLATE_POLYFILLS||$jscomp.IS_SYMBOL_NATIVE;$jscomp.polyfills={};$jscomp.propertyToPolyfillSymbol={};$jscomp.POLYFILL_PREFIX="$jscp$";var $jscomp$lookupPolyfilledValue=function(b,c){var d=$jscomp.propertyToPolyfillSymbol[c];if(null==d)return b[c];d=b[d];return void 0!==d?d:b[c]};
$jscomp.polyfill=function(b,c,d,g){c&&($jscomp.ISOLATE_POLYFILLS?$jscomp.polyfillIsolated(b,c,d,g):$jscomp.polyfillUnisolated(b,c,d,g))};$jscomp.polyfillUnisolated=function(b,c,d,g){d=$jscomp.global;b=b.split(".");for(g=0;g<b.length-1;g++){var e=b[g];if(!(e in d))return;d=d[e]}b=b[b.length-1];g=d[b];c=c(g);c!=g&&null!=c&&$jscomp.defineProperty(d,b,{configurable:!0,writable:!0,value:c})};
$jscomp.polyfillIsolated=function(b,c,d,g){var e=b.split(".");b=1===e.length;g=e[0];g=!b&&g in $jscomp.polyfills?$jscomp.polyfills:$jscomp.global;for(var k=0;k<e.length-1;k++){var a=e[k];if(!(a in g))return;g=g[a]}e=e[e.length-1];d=$jscomp.IS_SYMBOL_NATIVE&&"es6"===d?g[e]:null;c=c(d);null!=c&&(b?$jscomp.defineProperty($jscomp.polyfills,e,{configurable:!0,writable:!0,value:c}):c!==d&&($jscomp.propertyToPolyfillSymbol[e]=$jscomp.IS_SYMBOL_NATIVE?$jscomp.global.Symbol(e):$jscomp.POLYFILL_PREFIX+e,e=
  $jscomp.propertyToPolyfillSymbol[e],$jscomp.defineProperty(g,e,{configurable:!0,writable:!0,value:c})))};$jscomp.polyfill("Array.prototype.find",function(b){return b?b:function(c,d){return $jscomp.findInternal(this,c,d).v}},"es6","es3");
(function(b){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(c){return b(c,window,document)}):"object"===typeof exports?module.exports=function(c,d){c||(c=window);d&&d.fn.dataTable||(d=require("datatables.net")(c,d).$);return b(d,c,c.document)}:b(jQuery,window,document)})(function(b,c,d,g){var e=b.fn.dataTable,k=function(a,f){if(!e.versionCheck||!e.versionCheck("1.10.8"))throw"RowGroup requires DataTables 1.10.8 or newer";this.c=b.extend(!0,{},e.defaults.rowGroup,
  k.defaults,f);this.s={dt:new e.Api(a)};this.dom={};a=this.s.dt.settings()[0];if(f=a.rowGroup)return f;a.rowGroup=this;this._constructor()};b.extend(k.prototype,{dataSrc:function(a){if(a===g)return this.c.dataSrc;var f=this.s.dt;this.c.dataSrc=a;b(f.table().node()).triggerHandler("rowgroup-datasrc.dt",[f,a]);return this},disable:function(){this.c.enable=!1;return this},enable:function(a){if(!1===a)return this.disable();this.c.enable=!0;return this},enabled:function(){return this.c.enable},_constructor:function(){var a=
    this,f=this.s.dt,h=f.settings()[0];f.on("draw.dtrg",function(m,r){a.c.enable&&h===r&&a._draw()});f.on("column-visibility.dt.dtrg responsive-resize.dt.dtrg",function(){a._adjustColspan()});f.on("destroy",function(){f.off(".dtrg")})},_adjustColspan:function(){b("tr."+this.c.className,this.s.dt.table().body()).find("td:visible").attr("colspan",this._colspan())},_colspan:function(){return this.s.dt.columns().visible().reduce(function(a,f){return a+f},0)},_draw:function(){var a=this._group(0,this.s.dt.rows({page:"current"}).indexes());
    this._groupDisplay(0,a)},_group:function(a,f){for(var h=Array.isArray(this.c.dataSrc)?this.c.dataSrc:[this.c.dataSrc],m=e.ext.oApi._fnGetObjectDataFn(h[a]),r=this.s.dt,n,q,p=[],l=0,t=f.length;l<t;l++){var u=f[l];n=r.row(u).data();n=m(n);if(null===n||n===g)n=this.c.emptyDataGroup;if(q===g||n!==q)p.push({dataPoint:n,rows:[]}),q=n;p[p.length-1].rows.push(u)}if(h[a+1]!==g)for(l=0,t=p.length;l<t;l++)p[l].children=this._group(a+1,p[l].rows);return p},_groupDisplay:function(a,f){for(var h=this.s.dt,m,r=
    0,n=f.length;r<n;r++){var q=f[r],p=q.dataPoint,l=q.rows;this.c.startRender&&(m=this.c.startRender.call(this,h.rows(l),p,a),(m=this._rowWrap(m,this.c.startClassName,a))&&m.insertBefore(h.row(l[0]).node()));this.c.endRender&&(m=this.c.endRender.call(this,h.rows(l),p,a),(m=this._rowWrap(m,this.c.endClassName,a))&&m.insertAfter(h.row(l[l.length-1]).node()));q.children&&this._groupDisplay(a+1,q.children)}},_rowWrap:function(a,f,h){if(null===a||""===a)a=this.c.emptyDataGroup;return a===g||null===a?null:
    ("object"===typeof a&&a.nodeName&&"tr"===a.nodeName.toLowerCase()?b(a):a instanceof b&&a.length&&"tr"===a[0].nodeName.toLowerCase()?a:b("<tr/>").append(b("<td/>").attr("colspan",this._colspan()).append(a))).addClass(this.c.className).addClass(f).addClass("dtrg-level-"+h)}});k.defaults={className:"dtrg-group",dataSrc:0,emptyDataGroup:"No group",enable:!0,endClassName:"dtrg-end",endRender:null,startClassName:"dtrg-start",startRender:function(a,f){return f}};k.version="1.1.4";b.fn.dataTable.RowGroup=
  k;b.fn.DataTable.RowGroup=k;e.Api.register("rowGroup()",function(){return this});e.Api.register("rowGroup().disable()",function(){return this.iterator("table",function(a){a.rowGroup&&a.rowGroup.enable(!1)})});e.Api.register("rowGroup().enable()",function(a){return this.iterator("table",function(f){f.rowGroup&&f.rowGroup.enable(a===g?!0:a)})});e.Api.register("rowGroup().enabled()",function(){var a=this.context;return a.length&&a[0].rowGroup?a[0].rowGroup.enabled():!1});e.Api.register("rowGroup().dataSrc()",
  function(a){return a===g?this.context[0].rowGroup.dataSrc():this.iterator("table",function(f){f.rowGroup&&f.rowGroup.dataSrc(a)})});b(d).on("preInit.dt.dtrg",function(a,f,h){"dt"===a.namespace&&(a=f.oInit.rowGroup,h=e.defaults.rowGroup,a||h)&&(h=b.extend({},h,a),!1!==a&&new k(f,h))});return k});
