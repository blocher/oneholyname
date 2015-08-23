/**
 * FancyBox - jQuery Plugin
 * Simple and fancy lightbox alternative
 *
 * Examples and documentation at: http://fancybox.net
 *
 * Copyright (c) 2008 - 2010 Janis Skarnelis
 * That said, it is hardly a one-person project. Many people have submitted bugs, code, and offered their advice freely. Their support is greatly appreciated.
 *
 * Version: 1.3.4 (11/11/2010)
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

/**
 * NextGEN Gallery Optimizer Basic Fancybox Lightbox
 *
 * Used with permission and packaged with enhancements by Mark Jeldi,
 * as part of the NextGEN Gallery Optimizer WordPress plugin.
 *
 * @package		NextGEN_Gallery_Optimizer_Basic
 * @link				http://www.nextgengalleryoptimizer.com
 */
;(function(a){var n,t,u,f,B,m,C,h,y,z,r=0,d={},p=[],q=0,c={},k=[],E=null,v=new Image,H=/\.(jpg|gif|png|bmp|jpeg)(.*)?$/i,R=/[^\.]\.(swf)\s*$/i,I,J=1,x=0,w="",s,g,l=!1,A=a.extend(a("<div/>")[0],{prop:0}),F=navigator.userAgent.match(/msie [6]/i)&&!window.XMLHttpRequest,K=function(){t.hide();v.onerror=v.onload=null;E&&E.abort();n.empty()},L=function(){!1===d.onError(p,r,d)?(t.hide(),l=!1):(d.titleShow=!1,d.width="auto",d.height="auto",n.html('<p id="nggobasic-fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>'),
D())},G=function(){var b=p[r],e,c,f,g,k,h;K();d=a.extend({},a.fn.nggobasicFancybox.defaults,"undefined"==typeof a(b).data("nggobasicFancybox")?d:a(b).data("nggobasicFancybox"));h=d.onStart(p,r,d);if(!1===h)l=!1;else{"object"==typeof h&&(d=a.extend(d,h));f=d.title||(b.nodeName?a(b).attr("title"):b.title)||"";b.nodeName&&!d.orig&&(d.orig=a(b).children("img:first").length?a(b).children("img:first"):a(b));" "===f&&(f="");""===f&&d.orig&&d.titleFromAlt&&(f=d.orig.attr("alt"));e=d.href||(b.nodeName?a(b).attr("href"):
b.href)||null;if(/^(?:javascript)/i.test(e)||"#"==e)e=null;d.type?(c=d.type,e||(e=d.content)):d.content?c="html":e&&(c=e.match(H)?"image":e.match(R)?"swf":a(b).hasClass("iframe")?"iframe":0===e.indexOf("#")?"inline":"ajax");if(c)switch("inline"==c&&(b=e.substr(e.indexOf("#")),c=0<a(b).length?"inline":"ajax"),d.type=c,d.href=e,d.title=f,d.autoDimensions&&("html"==d.type||"inline"==d.type||"ajax"==d.type?(d.width="auto",d.height="auto"):d.autoDimensions=!1),d.modal&&(d.overlayShow=!0,d.hideOnOverlayClick=
!1,d.hideOnContentClick=!1,d.enableEscapeButton=!1,d.showCloseButton=!1),d.padding=parseInt(d.padding,10),d.margin=parseInt(d.margin,10),n.css("padding",d.padding+d.margin),a(".nggobasic-fancybox-inline-tmp").unbind("nggobasic-fancybox-cancel").bind("nggobasic-fancybox-change",function(){a(this).replaceWith(m.children())}),c){case "html":n.html(d.content);D();break;case "inline":if(!0===a(b).parent().is("#nggobasic-fancybox-content")){l=!1;break}a('<div class="nggobasic-fancybox-inline-tmp" />').hide().insertBefore(a(b)).bind("nggobasic-fancybox-cleanup",
function(){a(this).replaceWith(m.children())}).bind("nggobasic-fancybox-cancel",function(){a(this).replaceWith(n.children())});a(b).appendTo(n);D();break;case "image":l=!1;a.nggobasicFancybox.showActivity();v=new Image;v.onerror=function(){L()};v.onload=function(){l=!0;v.onerror=v.onload=null;d.width=v.width;d.height=v.height;a("<img />").attr({id:"nggobasic-fancybox-img",src:v.src,alt:d.title}).appendTo(n);M()};v.src=e;break;case "swf":d.scrolling="no";g='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+
d.width+'" height="'+d.height+'"><param name="movie" value="'+e+'"></param>';k="";a.each(d.swf,function(b,a){g+='<param name="'+b+'" value="'+a+'"></param>';k+=" "+b+'="'+a+'"'});g+='<embed src="'+e+'" type="application/x-shockwave-flash" width="'+d.width+'" height="'+d.height+'"'+k+"></embed></object>";n.html(g);D();break;case "ajax":l=!1;a.nggobasicFancybox.showActivity();d.ajax.win=d.ajax.success;E=a.ajax(a.extend({},d.ajax,{url:e,data:d.ajax.data||{},error:function(b,a,e){0<b.status&&L()},success:function(b,
a,c){if(200==("object"==typeof c?c:E).status){if("function"==typeof d.ajax.win){h=d.ajax.win(e,b,a,c);if(!1===h){t.hide();return}if("string"==typeof h||"object"==typeof h)b=h}n.html(b);D()}}}));break;case "iframe":M()}else L()}},D=function(){var b=d.width,e=d.height,b=-1<b.toString().indexOf("%")?parseInt((a(window).width()-2*d.margin)*parseFloat(b)/100,10)+"px":"auto"==b?"auto":b+"px",e=-1<e.toString().indexOf("%")?parseInt((a(window).height()-2*d.margin)*parseFloat(e)/100,10)+"px":"auto"==e?"auto":
e+"px";n.wrapInner('<div style="width:'+b+";height:"+e+";overflow: "+("auto"==d.scrolling?"auto":"yes"==d.scrolling?"scroll":"hidden")+';position:relative;"></div>');d.width=n.width();d.height=n.height();M()},M=function(){var b,e;t.hide();if(f.is(":visible")&&!1===c.onCleanup(k,q,c))a(".nggobasic-fancybox-inline-tmp").trigger("nggobasic-fancybox-cancel"),l=!1;else{l=!0;a(m.add(u)).unbind();a(window).unbind("resize.fb scroll.fb");a(document).unbind("keydown.fb");f.is(":visible")&&"outside"!==c.titlePosition&&
f.css("height",f.height());k=p;q=r;c=d;if(c.overlayShow){if(u.css({"background-color":c.overlayColor,opacity:c.overlayOpacity,cursor:c.hideOnOverlayClick?"pointer":"auto",height:a(document).height(),width:a(document).width()}),!u.is(":visible")){if(F)a("select:not(#nggobasic-fancybox-tmp select)").filter(function(){return"hidden"!==this.style.visibility}).css({visibility:"hidden"}).one("nggobasic-fancybox-cleanup",function(){this.style.visibility="inherit"});u.show()}}else u.hide();g=S();w=c.title||
"";x=0;h.empty().removeAttr("style").removeClass();if(!1!==c.titleShow&&(w=a.isFunction(c.titleFormat)?c.titleFormat(w,k,q,c):w&&w.length?"float"==c.titlePosition?'<table id="nggobasic-fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="nggobasic-fancybox-title-float-left"></td><td id="nggobasic-fancybox-title-float-main">'+w+'</td><td id="nggobasic-fancybox-title-float-right"></td></tr></table>':'<div id="nggobasic-fancybox-title-'+c.titlePosition+'">'+w+"</div>":!1)&&""!==w)switch(h.addClass("nggobasic-fancybox-title-"+
c.titlePosition).html(w).appendTo("body").show(),c.titlePosition){case "inside":h.css({width:g.width-2*c.padding,marginLeft:c.padding,marginRight:c.padding});x=h.outerHeight(!0);h.appendTo(B);g.height+=x;break;case "over":h.css({marginLeft:c.padding,width:g.width-2*c.padding,bottom:c.padding}).appendTo(B);break;case "float":h.css("left",-1*parseInt((h.width()-g.width-40)/2,10)).appendTo(f);break;default:h.css({width:g.width-2*c.padding,paddingLeft:c.padding,paddingRight:c.padding}).appendTo(f)}h.hide();
f.is(":visible")?(a(C.add(y).add(z)).hide(),b=f.position(),s={top:b.top,left:b.left,width:f.width(),height:f.height()},e=s.width==g.width&&s.height==g.height,m.fadeTo(c.changeFade,.3,function(){var b=function(){m.html(n.contents()).fadeTo(c.changeFade,1,N)};a(".nggobasic-fancybox-inline-tmp").trigger("nggobasic-fancybox-change");m.empty().removeAttr("filter").css({"border-width":c.padding,width:g.width-2*c.padding,height:d.autoDimensions?"auto":g.height-x-2*c.padding});e?b():(A.prop=0,a(A).animate({prop:1},
{duration:c.changeSpeed,easing:c.easingChange,step:O,complete:b}))})):(f.removeAttr("style"),m.css("border-width",c.padding),"elastic"==c.transitionIn?(s=Q(),m.html(n.contents()),f.show(),c.opacity&&(g.opacity=0),A.prop=0,a(A).animate({prop:1},{duration:c.speedIn,easing:c.easingIn,step:O,complete:N})):("inside"==c.titlePosition&&0<x&&h.show(),m.css({width:g.width-2*c.padding,height:d.autoDimensions?"auto":g.height-x-2*c.padding}).html(n.contents()),f.css(g).fadeIn("none"==c.transitionIn?0:c.speedIn,
N)))}},T=function(){(c.enableEscapeButton||c.enableKeyboardNav)&&a(document).bind("keydown.fb",function(b){27==b.keyCode&&c.enableEscapeButton?(b.preventDefault(),a.nggobasicFancybox.close()):37!=b.keyCode&&39!=b.keyCode||!c.enableKeyboardNav||"INPUT"===b.target.tagName||"TEXTAREA"===b.target.tagName||"SELECT"===b.target.tagName||(b.preventDefault(),a.nggobasicFancybox[37==b.keyCode?"prev":"next"]())});c.showNavArrows?((c.cyclic&&1<k.length||0!==q)&&y.show(),(c.cyclic&&1<k.length||q!=k.length-1)&&
z.show()):(y.hide(),z.hide())},N=function(){a.support.opacity||(m.get(0).style.removeAttribute("filter"),f.get(0).style.removeAttribute("filter"));d.autoDimensions&&m.css("height","auto");f.css("height","auto");w&&w.length&&h.show();c.showCloseButton&&C.show();T();c.hideOnContentClick&&m.bind("click",a.nggobasicFancybox.close);c.hideOnOverlayClick&&u.bind("click",a.nggobasicFancybox.close);a(window).bind("resize.fb",a.nggobasicFancybox.resize);c.centerOnScroll&&a(window).bind("scroll.fb",a.nggobasicFancybox.center);
var b;if(navigator.userAgent.match(/msie [6]/i)||navigator.userAgent.match(/msie [7]/i)||navigator.userAgent.match(/msie [8]/i))b=!0;"iframe"==c.type&&a('<iframe id="nggobasic-fancybox-frame" name="nggobasic-fancybox-frame'+(new Date).getTime()+'" frameborder="0" hspace="0" '+(b?'allowtransparency="true""':"")+' scrolling="'+d.scrolling+'" src="'+c.href+'"></iframe>').appendTo(m);f.show();l=!1;a.nggobasicFancybox.center();c.onComplete(k,q,c);var e;k.length-1>q&&(b=k[q+1].href,"undefined"!==typeof b&&
b.match(H)&&(e=new Image,e.src=b));0<q&&(b=k[q-1].href,"undefined"!==typeof b&&b.match(H)&&(e=new Image,e.src=b))},O=function(b){var a={width:parseInt(s.width+(g.width-s.width)*b,10),height:parseInt(s.height+(g.height-s.height)*b,10),top:parseInt(s.top+(g.top-s.top)*b,10),left:parseInt(s.left+(g.left-s.left)*b,10)};"undefined"!==typeof g.opacity&&(a.opacity=.5>b?.5:b);f.css(a);m.css({width:a.width-2*c.padding,height:a.height-x*b-2*c.padding})},P=function(){var b=0,e;if(!1!==c.titleShow&&(null!==c.titleFormat||
""!==c.title&&" "!==c.title)){"outside"===c.titlePosition&&(b=14);if("inside"===c.titlePosition||"float"===c.titlePosition)b=28;e=F?b/2:b/2+20;t.css("margin-top","-"+e+"px")}/iP(hone|od|ad)/.test(navigator.platform)&&/\sSafari\//.test(navigator.userAgent)?(e=navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/),e=[parseInt(e[1],10),parseInt(e[2],10),parseInt(e[3]||0,10)]):e=void 0;return[a(window).width()-2*c.margin,(e&&8<=e[0]?window.innerHeight:a(window).height())-(2*c.margin+b),a(document).scrollLeft()+
c.margin,a(document).scrollTop()+c.margin]},S=function(){var b=P(),a={},f=c.autoScale,g=2*c.padding;-1<c.width.toString().indexOf("%")?a.width=parseInt(b[0]*parseFloat(c.width)/100,10):a.width=c.width+g;-1<c.height.toString().indexOf("%")?a.height=parseInt(b[1]*parseFloat(c.height)/100,10):a.height=c.height+g;f&&(a.width>b[0]||a.height>b[1])&&("image"==d.type||"swf"==d.type?(f=c.width/c.height,a.width>b[0]&&(a.width=b[0],a.height=parseInt((a.width-g)/f+g,10)),a.height>b[1]&&(a.height=b[1],a.width=
parseInt((a.height-g)*f+g,10))):(a.width=Math.min(a.width,b[0]),a.height=Math.min(a.height,b[1])));a.top=parseInt(Math.max(b[3]-20,b[3]+.5*(b[1]-a.height-40)),10);a.left=parseInt(Math.max(b[2]-20,b[2]+.5*(b[0]-a.width-40)),10);return a},Q=function(){var b=d.orig?a(d.orig):!1,e={};b&&b.length?(e=b.offset(),e.top+=parseInt(b.css("paddingTop"),10)||0,e.left+=parseInt(b.css("paddingLeft"),10)||0,e.top+=parseInt(b.css("border-top-width"),10)||0,e.left+=parseInt(b.css("border-left-width"),10)||0,e.width=
b.width(),e.height=b.height(),e={width:e.width+2*c.padding,height:e.height+2*c.padding,top:e.top-c.padding-20,left:e.left-c.padding-20}):(b=P(),e={width:2*c.padding,height:2*c.padding,top:parseInt(b[3]+.5*b[1],10),left:parseInt(b[2]+.5*b[0],10)});return e},U=function(){t.is(":visible")?(a("div",t).css("top",-40*J+"px"),J=(J+1)%12):clearInterval(I)};a.fn.nggobasicFancybox=function(b){if(!a(this).length)return this;a(this).data("nggobasicFancybox",a.extend({},b,a.metadata?a(this).metadata():{})).unbind("click.fb").bind("click.fb",
function(b){b.preventDefault();l||(l=!0,a(this).blur(),p=[],r=0,(b=a(this).attr("rel")||"")&&""!==b&&"nofollow"!==b?(p=a("a[rel="+b+"], area[rel="+b+"]"),r=p.index(this)):p.push(this),G())});return this};a.nggobasicFancybox=function(b,c){var d;if(!l){l=!0;d="undefined"!==typeof c?c:{};p=[];r=parseInt(d.index,10)||0;if(a.isArray(b)){for(var f=0,g=b.length;f<g;f++)"object"==typeof b[f]?a(b[f]).data("nggobasicFancybox",a.extend({},d,b[f])):b[f]=a({}).data("nggobasicFancybox",a.extend({content:b[f]},
d));p=jQuery.merge(p,b)}else"object"==typeof b?a(b).data("nggobasicFancybox",a.extend({},d,b)):b=a({}).data("nggobasicFancybox",a.extend({content:b},d)),p.push(b);if(r>p.length||0>r)r=0;G()}};a.nggobasicFancybox.showActivity=function(){clearInterval(I);t.show();I=setInterval(U,66)};a.nggobasicFancybox.hideActivity=function(){t.hide()};a.nggobasicFancybox.next=function(){return a.nggobasicFancybox.pos(q+1)};a.nggobasicFancybox.prev=function(){return a.nggobasicFancybox.pos(q-1)};a.nggobasicFancybox.pos=
function(a){l||(a=parseInt(a),p=k,-1<a&&a<k.length?(r=a,G()):c.cyclic&&1<k.length&&(r=a>=k.length?0:k.length-1,G()))};a.nggobasicFancybox.cancel=function(){l||(l=!0,a(".nggobasic-fancybox-inline-tmp").trigger("nggobasic-fancybox-cancel"),K(),d.onCancel(p,r,d),l=!1)};a.nggobasicFancybox.close=function(){function b(){u.fadeOut("fast");h.empty().hide();f.hide();a(".nggobasic-fancybox-inline-tmp, select:not(#nggobasic-fancybox-tmp select)").trigger("nggobasic-fancybox-cleanup");m.empty();c.onClosed(k,
q,c);k=d=[];q=r=0;c=d={};l=!1}if(!l&&!f.is(":hidden"))if(l=!0,c&&!1===c.onCleanup(k,q,c))l=!1;else if(K(),a(C.add(y).add(z)).hide(),a(m.add(u)).unbind(),a(window).unbind("resize.fb scroll.fb"),a(document).unbind("keydown.fb"),m.find("iframe").attr("src",F&&/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank"),"inside"!==c.titlePosition&&h.empty(),f.stop(),"elastic"==c.transitionOut){s=Q();var e=f.position();g={top:e.top,left:e.left,width:f.width(),height:f.height()};c.opacity&&
(g.opacity=1);h.empty().hide();A.prop=1;a(A).animate({prop:0},{duration:c.speedOut,easing:c.easingOut,step:O,complete:b})}else f.fadeOut("none"==c.transitionOut?0:c.speedOut,b)};a.nggobasicFancybox.resize=function(){u.is(":visible")&&(u.css("height",a(document).height()),u.css("width",a(document).width()));a.nggobasicFancybox.center(!0)};a.nggobasicFancybox.center=function(a){var d,g;l||(g=!0===a?1:0,d=P(),!g&&(f.width()>d[0]||f.height()>d[1])||f.stop().animate({top:parseInt(Math.max(d[3]-20,d[3]+
.5*(d[1]-m.height()-40)-c.padding)),left:parseInt(Math.max(d[2]-20,d[2]+.5*(d[0]-m.width()-40)-c.padding))},"number"==typeof a?a:200))};a.nggobasicFancybox.init=function(){a("#nggobasic-fancybox-wrap").length||(a("body").append(n=a('<div id="nggobasic-fancybox-tmp"></div>'),t=a('<div id="nggobasic-fancybox-loading"><div></div></div>'),u=a('<div id="nggobasic-fancybox-overlay"></div>'),f=a('<div id="nggobasic-fancybox-wrap"></div>')),B=a('<div id="nggobasic-fancybox-outer"></div>').append('<div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-n"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-ne"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-e"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-se"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-s"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-sw"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-w"></div><div class="nggobasic-fancybox-bg" id="nggobasic-fancybox-bg-nw"></div>').appendTo(f),
B.append(m=a('<div id="nggobasic-fancybox-content"></div>'),C=a('<a id="nggobasic-fancybox-close"></a>'),h=a('<div id="nggobasic-fancybox-title"></div>'),y=a('<a href="javascript:;" id="nggobasic-fancybox-left"><span class="fancy-ico" id="nggobasic-fancybox-left-ico"></span></a>'),z=a('<a href="javascript:;" id="nggobasic-fancybox-right"><span class="fancy-ico" id="nggobasic-fancybox-right-ico"></span></a>')),C.click(a.nggobasicFancybox.close),t.click(a.nggobasicFancybox.cancel),y.click(function(b){b.preventDefault();
a.nggobasicFancybox.prev()}),z.click(function(b){b.preventDefault();a.nggobasicFancybox.next()}),a.fn.mousewheel&&f.bind("mousewheel.fb",function(b,c){if(l)b.preventDefault();else if(0===a(b.target).get(0).clientHeight||a(b.target).get(0).scrollHeight===a(b.target).get(0).clientHeight)b.preventDefault(),a.nggobasicFancybox[0<c?"prev":"next"]()}),a.support.opacity||f.addClass("nggobasic-fancybox-ie"),F&&(t.addClass("nggobasic-fancybox-ie6"),f.addClass("nggobasic-fancybox-ie6"),a('<iframe id="nggobasic-fancybox-hide-sel-frame" src="'+
(/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank")+'" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(B)))};a.fn.nggobasicFancybox.defaults={padding:10,margin:40,opacity:!1,modal:!1,cyclic:!1,scrolling:"auto",width:560,height:340,autoScale:!0,autoDimensions:!0,centerOnScroll:!1,ajax:{},swf:{wmode:"transparent"},hideOnOverlayClick:!0,hideOnContentClick:!1,overlayShow:!0,overlayOpacity:.3,overlayColor:"#666",titleShow:!0,titlePosition:"float",
titleFormat:null,titleFromAlt:!1,transitionIn:"fade",transitionOut:"fade",speedIn:300,speedOut:300,changeSpeed:300,changeFade:"fast",easingIn:"swing",easingOut:"swing",showCloseButton:!0,showNavArrows:!0,enableEscapeButton:!0,enableKeyboardNav:!0,onStart:function(){},onCancel:function(){},onComplete:function(){},onCleanup:function(){},onClosed:function(){},onError:function(){}};a.nggobasicFancybox.attach=function(){a("a.nggobasic-fancybox").nggobasicFancybox()};a(document).ready(function(){a.nggobasicFancybox.init();
a.nggobasicFancybox.attach();a(this).bind("refreshed",a.nggobasicFancybox.attach)})})(jQuery);jQuery.noConflict();