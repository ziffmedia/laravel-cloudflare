!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=1)}([function(t,e,n){var r=n(4);"string"==typeof r&&(r=[[t.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};n(6)(r,o);r.locals&&(t.exports=r.locals)},function(t,e,n){t.exports=n(2)},function(t,e,n){Nova.booting((function(t,e,r){e.addRoutes([{name:"laravel-cloudflare",path:"/laravel-cloudflare",component:n(8).default}])}))},function(t,e,n){"use strict";var r=n(0);n.n(r).a},function(t,e,n){(t.exports=n(5)(!1)).push([t.i,"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n/* Scoped Styles */\n",""])},function(t,e){t.exports=function(t){var e=[];return e.toString=function(){return this.map((function(e){var n=function(t,e){var n=t[1]||"",r=t[3];if(!r)return n;if(e&&"function"==typeof btoa){var o=(i=r,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(i))))+" */"),s=r.sources.map((function(t){return"/*# sourceURL="+r.sourceRoot+t+" */"}));return[n].concat(s).concat([o]).join("\n")}var i;return[n].join("\n")}(e,t);return e[2]?"@media "+e[2]+"{"+n+"}":n})).join("")},e.i=function(t,n){"string"==typeof t&&(t=[[null,t,""]]);for(var r={},o=0;o<this.length;o++){var s=this[o][0];"number"==typeof s&&(r[s]=!0)}for(o=0;o<t.length;o++){var i=t[o];"number"==typeof i[0]&&r[i[0]]||(n&&!i[2]?i[2]=n:n&&(i[2]="("+i[2]+") and ("+n+")"),e.push(i))}},e}},function(t,e,n){var r,o,s={},i=(r=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===o&&(o=r.apply(this,arguments)),o}),a=function(t,e){return e?e.querySelector(t):document.querySelector(t)},l=function(t){var e={};return function(t,n){if("function"==typeof t)return t();if(void 0===e[t]){var r=a.call(this,t,n);if(window.HTMLIFrameElement&&r instanceof window.HTMLIFrameElement)try{r=r.contentDocument.head}catch(t){r=null}e[t]=r}return e[t]}}(),u=null,c=0,f=[],d=n(7);function p(t,e){for(var n=0;n<t.length;n++){var r=t[n],o=s[r.id];if(o){o.refs++;for(var i=0;i<o.parts.length;i++)o.parts[i](r.parts[i]);for(;i<r.parts.length;i++)o.parts.push(g(r.parts[i],e))}else{var a=[];for(i=0;i<r.parts.length;i++)a.push(g(r.parts[i],e));s[r.id]={id:r.id,refs:1,parts:a}}}}function v(t,e){for(var n=[],r={},o=0;o<t.length;o++){var s=t[o],i=e.base?s[0]+e.base:s[0],a={css:s[1],media:s[2],sourceMap:s[3]};r[i]?r[i].parts.push(a):n.push(r[i]={id:i,parts:[a]})}return n}function h(t,e){var n=l(t.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var r=f[f.length-1];if("top"===t.insertAt)r?r.nextSibling?n.insertBefore(e,r.nextSibling):n.appendChild(e):n.insertBefore(e,n.firstChild),f.push(e);else if("bottom"===t.insertAt)n.appendChild(e);else{if("object"!=typeof t.insertAt||!t.insertAt.before)throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");var o=l(t.insertAt.before,n);n.insertBefore(e,o)}}function m(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t);var e=f.indexOf(t);e>=0&&f.splice(e,1)}function b(t){var e=document.createElement("style");if(void 0===t.attrs.type&&(t.attrs.type="text/css"),void 0===t.attrs.nonce){var r=function(){0;return n.nc}();r&&(t.attrs.nonce=r)}return y(e,t.attrs),h(t,e),e}function y(t,e){Object.keys(e).forEach((function(n){t.setAttribute(n,e[n])}))}function g(t,e){var n,r,o,s;if(e.transform&&t.css){if(!(s="function"==typeof e.transform?e.transform(t.css):e.transform.default(t.css)))return function(){};t.css=s}if(e.singleton){var i=c++;n=u||(u=b(e)),r=_.bind(null,n,i,!1),o=_.bind(null,n,i,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=function(t){var e=document.createElement("link");return void 0===t.attrs.type&&(t.attrs.type="text/css"),t.attrs.rel="stylesheet",y(e,t.attrs),h(t,e),e}(e),r=C.bind(null,n,e),o=function(){m(n),n.href&&URL.revokeObjectURL(n.href)}):(n=b(e),r=w.bind(null,n),o=function(){m(n)});return r(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;r(t=e)}else o()}}t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(e=e||{}).attrs="object"==typeof e.attrs?e.attrs:{},e.singleton||"boolean"==typeof e.singleton||(e.singleton=i()),e.insertInto||(e.insertInto="head"),e.insertAt||(e.insertAt="bottom");var n=v(t,e);return p(n,e),function(t){for(var r=[],o=0;o<n.length;o++){var i=n[o];(a=s[i.id]).refs--,r.push(a)}t&&p(v(t,e),e);for(o=0;o<r.length;o++){var a;if(0===(a=r[o]).refs){for(var l=0;l<a.parts.length;l++)a.parts[l]();delete s[a.id]}}}};var U,x=(U=[],function(t,e){return U[t]=e,U.filter(Boolean).join("\n")});function _(t,e,n,r){var o=n?"":r.css;if(t.styleSheet)t.styleSheet.cssText=x(e,o);else{var s=document.createTextNode(o),i=t.childNodes;i[e]&&t.removeChild(i[e]),i.length?t.insertBefore(s,i[e]):t.appendChild(s)}}function w(t,e){var n=e.css,r=e.media;if(r&&t.setAttribute("media",r),t.styleSheet)t.styleSheet.cssText=n;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(n))}}function C(t,e,n){var r=n.css,o=n.sourceMap,s=void 0===e.convertToAbsoluteUrls&&o;(e.convertToAbsoluteUrls||s)&&(r=d(r)),o&&(r+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */");var i=new Blob([r],{type:"text/css"}),a=t.href;t.href=URL.createObjectURL(i),a&&URL.revokeObjectURL(a)}},function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var n=e.protocol+"//"+e.host,r=n+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,(function(t,e){var o,s=e.trim().replace(/^"(.*)"$/,(function(t,e){return e})).replace(/^'(.*)'$/,(function(t,e){return e}));return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(s)?t:(o=0===s.indexOf("//")?s:0===s.indexOf("/")?n+s:r+s.replace(/^\.\//,""),"url("+JSON.stringify(o)+")")}))}},function(t,e,n){"use strict";n.r(e);var r={data:function(){return{urls:[],validUrls:[],invalidUrls:[]}},computed:{invalidUrlsMessage:function(){var t=this.invalidUrls.length;return t?(1===t?"1 URL above is":t+" URLs above are")+" invalid.":""}},methods:{submitUrls:function(t){if(t.preventDefault(),this.urls.length){var e=this.urls.split("\n"),n=this,r={urls:e};Nova.request().post("/nova-vendor/laravel-cloudflare/purge",r).then((function(t){n.processResponse(t)}))}},processResponse:function(t){if(t.data&&t.data.validUrls){if(this.validUrls=t.data.validUrls,this.invalidUrls=t.data.invalidUrls,t.data.errorMessage)this.$toasted.show("Server error: "+t.data.errorMessage,{type:"error"});else if(this.validUrls.length){var e=(0===this.invalidUrls.length?"All URLs":1===this.validUrls.length?"1 URL":this.validUrls.length+" URLs")+" successfully purged.";this.$toasted.show(e,{type:"success"}),this.urls=this.invalidUrls.join("\n")}}else this.$toasted.show("Server error: response does not have data.",{type:"error"})}}};n(3);var o=function(t,e,n,r,o,s,i,a){var l,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),r&&(u.functional=!0),s&&(u._scopeId="data-v-"+s),i?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},u._ssrRegister=l):o&&(l=a?function(){o.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:o),l)if(u.functional){u._injectStyles=l;var c=u.render;u.render=function(t,e){return l.call(e),c(t,e)}}else{var f=u.beforeCreate;u.beforeCreate=f?[].concat(f,l):[l]}return{exports:t,options:u}}(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("form",{attrs:{autocomplete:"off",method:"post",action:"/cache-tool/{urls}",novalidate:"true"},on:{submit:t.submitUrls}},[n("div",{staticClass:"mb-8"},[n("h1",{staticClass:"mb-3 text-90 font-normal text-2xl"},[t._v("Cache Purge")]),t._v(" "),n("div",{staticClass:"card"},[n("div",{staticClass:"flex border-b border-40"},[t._m(0),t._v(" "),n("div",{staticClass:"py-6 px-8 w-1/2"},[n("textarea",{directives:[{name:"model",rawName:"v-model",value:t.urls,expression:"urls"}],ref:"textInput",staticClass:"w-full form-control form-input form-input-bordered",class:t.invalidUrls.length?"border-danger":"",staticStyle:{"min-height":"10rem"},attrs:{id:"purgeUrl",type:"textarea",placeholder:"https://www.example.com/page"},domProps:{value:t.urls},on:{input:function(e){e.target.composing||(t.urls=e.target.value)}}}),t._v(" "),t.invalidUrlsMessage?n("div",{staticClass:"help-text error-text mt-2 text-danger"},[t._v(t._s(t.invalidUrlsMessage))]):t._e(),t._v(" "),n("div",{staticClass:"help-text help-text mt-2"},[t._v("Enter URLs above, use <enter> to delimit URLs.")])])])])]),t._v(" "),t._m(1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"w-1/5 px-8 py-6"},[e("label",{staticClass:"inline-block text-80 pt-2 leading-tight",attrs:{for:"purgeUrl"}},[this._v("URLs")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"flex items-center justify-end"},[e("button",{staticClass:"btn btn-default btn-primary inline-flex items-center relative",attrs:{tabindex:"0",type:"submit"}},[this._v("\n            Clear Cache\n        ")])])}],!1,null,null,null);e.default=o.exports}]);