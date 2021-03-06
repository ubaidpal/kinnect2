
    /*!
     * http://jqueryte.com
     * jQuery TE 1.0.5
     * Copyright (C) 2012, Fatih Koca (fatihkoca@me.com), AUTHOR.txt (http://jqueryte.com/about)
     * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
     This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     You should have received a copy of the GNU General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
     *
     */
    (function(a){a.fn.jqte=function(b){var c=a.extend({css:"jqte",b:true,i:true,u:true,ol:true,ul:true,sub:true,sup:true,outdent:true,indent:true,strike:true,remove:true,rule:true},b);var d=a.extend({b:{cls:"bold",command:"Bold",key:"B",tags:["b","strong"]},i:{cls:"italic",command:"Italic",key:"I",tags:["i","em"]},u:{cls:"underline",command:"Underline",key:"U",tags:["u"]},ol:{cls:"orderedlist",command:"insertorderedlist",key:"?",tags:["ol"]},ul:{cls:"unorderedlist",command:"insertunorderedlist",key:"?",tags:["ul"]},sub:{cls:"subscript",command:"subscript",key:"(",tags:["sub"]},sup:{cls:"superscript",command:"superscript",key:"&",tags:["sup"]},outdent:{cls:"outdent",command:"outdent",key:"%",tags:["sup"]},indent:{cls:"indent",command:"indent",key:"'",tags:["sup"]},strike:{cls:"strike",command:"strikeThrough",key:"K",tags:["strike"]},remove:{cls:"remove",command:"removeformat",key:".",css:false},hr:{cls:"rule",command:"inserthorizontalrule",key:"H",tags:["hr"]}},b);return this.each(function(){function i(a){var b,c,d=navigator.userAgent.toLowerCase();if(window.getSelection){c=window.getSelection();if(c.getRangeAt){b=c.getRangeAt(0)}if(b){c.removeAllRanges();c.addRange(b)}if(!d.match(/msie/))document.execCommand("StyleWithCSS",false,false);document.execCommand(a,false,null)}else if(document.selection&&document.selection.createRange&&document.selection.type!="None"){b=document.selection.createRange();b.execCommand(a,false,null)}}function k(b,c){var d=false,e=j(),f,g;a.each(b,function(b,c){f=e.prop("tagName").toLowerCase();g=e.attr("style");if(f==c)d=true;else{e.parents().each(function(){f=a(this).prop("tagName").toLowerCase();if(f==c)d=true})}});return d}function l(b){if(c.b)k(d.b.tags,d.b.css)?f.find("."+d.b.cls).addClass(h):a("."+d.b.cls).removeClass(h);if(c.i)k(d.i.tags,d.i.css)?f.find("."+d.i.cls).addClass(h):a("."+d.i.cls).removeClass(h);if(c.u)k(d.u.tags,d.u.css)?f.find("."+d.u.cls).addClass(h):a("."+d.u.cls).removeClass(h);if(c.ol)k(d.ol.tags,d.ol.css)?f.find("."+d.ol.cls).addClass(h):a("."+d.ol.cls).removeClass(h);if(c.ul)k(d.ul.tags,d.ul.css)?f.find("."+d.ul.cls).addClass(h):a("."+d.ul.cls).removeClass(h);if(c.sub)k(d.sub.tags,d.sub.css)?f.find("."+d.sub.cls).addClass(h):a("."+d.sub.cls).removeClass(h);if(c.sup)k(d.sup.tags,d.sup.css)?f.find("."+d.sup.cls).addClass(h):a("."+d.sup.cls).removeClass(h);if(c.strike)k(d.strike.tags,d.strike.css)?f.find("."+d.strike.cls).addClass(h):a("."+d.strike.cls).removeClass(h)}function m(){var a=g.html().replace(/<p><\/p>/g,"").replace(/?/g," ").replace(/<p> <\/p>/g,""),c,d;c=[/\<div>(.*?)\<\/div>/ig,/\<br>(.*?)\<br>/ig,/\<br\/>(.*?)\<br\/>/ig,/\<strong>(.*?)\<\/strong>/ig,/\<em>(.*?)\<\/em>/ig];d=["<p>$1</p>","<p>$1</p>","<p>$1</p>","<b>$1</b>","<i>$1</i>"];for(var e=0;e<c.length;e++){a=a.replace(c[e],d[e])}b.val(g.html())}var b=a(this);b.hide().before('<div class="'+c.css+'" ></div>');var e=b.prev("."+c.css);e.html('<div class="'+c.css+"_Panel"+'" unselectable="on"></div> <div class="'+c.css+"_Content"+'" contenteditable="true"></div>');var f=e.find("."+c.css+"_Panel");var g=e.find("."+c.css+"_Content");var h=c.css+"_Active";g.html(b.val());f.bind("selectstart mousedown",function(a){a.preventDefault()});if(c.b)f.append('<a class="'+d.b.cls+'" toggletag="'+d.b.command+'" unselectable="on"></a>');if(c.i)f.append('<a class="'+d.i.cls+'" toggletag="'+d.i.command+'" unselectable="on"></a>');if(c.u)f.append('<a class="'+d.u.cls+'" toggletag="'+d.u.command+'" unselectable="on"></a>');if(c.ol)f.append('<a class="'+d.ol.cls+'" toggletag="'+d.ol.command+'" unselectable="on"></a>');if(c.ul)f.append('<a class="'+d.ul.cls+'" toggletag="'+d.ul.command+'" unselectable="on"></a>');if(c.sub)f.append('<a class="'+d.sub.cls+'" toggletag="'+d.sub.command+'" unselectable="on"></a>');if(c.sup)f.append('<a class="'+d.sup.cls+'" toggletag="'+d.sup.command+'" unselectable="on"></a>');if(c.outdent)f.append('<a class="'+d.outdent.cls+'" toggletag="'+d.outdent.command+'" unselectable="on"></a>');if(c.indent)f.append('<a class="'+d.indent.cls+'" toggletag="'+d.indent.command+'" unselectable="on"></a>');if(c.strike)f.append('<a class="'+d.strike.cls+'" toggletag="'+d.strike.command+'" unselectable="on"></a>');if(c.remove)f.append('<a class="'+d.remove.cls+'" toggletag="'+d.remove.command+'" unselectable="on"></a>');if(c.rule)f.append('<a class="'+d.hr.cls+'" toggletag="'+d.hr.command+'" unselectable="on"></a>');g.focusout(function(){f.find("a").removeClass(h)});var j=function(){var b,c;if(window.getSelection){c=getSelection();b=c.anchorNode}if(!b&&document.selection){c=document.selection;var d=c.getRangeAt?c.getRangeAt(0):c.createRange();b=d.commonAncestorContainer?d.commonAncestorContainer:d.parentElement?d.parentElement():d.item(0)}if(b){return b.nodeName=="#text"?a(b.parentNode):a(b)}};f.find("a").click(function(){if(g.not(":focus"))g.focus();i(a(this).attr("toggletag"));m();a(this).hasClass(h)||a(this).is("[toggletag="+d.remove.command+"]")||a(this).is("[toggletag="+d.hr.command+"]")||a(this).is("[toggletag="+d.indent.command+"]")||a(this).is("[toggletag="+d.outdent.command+"]")?a(this).removeClass(h):a(this).addClass(h)});a.ctrl=function(a,b,c){g.keydown(function(d){if(!c)c=[];if(d.keyCode==a.charCodeAt(0)&&d.ctrlKey){b.apply(this,c);return false}})};a.ctrl(d.b.key,function(){i(d.b.command)});a.ctrl(d.i.key,function(){i(d.i.command)});a.ctrl(d.u.key,function(){i(d.u.command)});a.ctrl(d.ol.key,function(){i(d.ol.command)});a.ctrl(d.ul.key,function(){i(d.ul.command)});a.ctrl(d.sub.key,function(){i(d.sub.command)});a.ctrl(d.sup.key,function(){i(d.sup.command)});a.ctrl(d.indent.key,function(){i(d.indent.command)});a.ctrl(d.outdent.key,function(){i(d.outdent.command)});a.ctrl(d.strike.key,function(){i(d.strike.command)});a.ctrl(d.remove.key,function(){i(d.remove.command)});a.ctrl(d.hr.key,function(){i(d.hr.command)});g.bind("mouseup keyup",l).bind("keypress keyup keydown drop cut copy paste DOMCharacterDataModified DOMSubtreeModified",function(){a(this).trigger("change")}).bind("change",function(){setTimeout(m,0)})})}})(jQuery)

        /*
         File: jQuery-TE-Image 1.0.0
         Author: Jeff Hansen
         Description: An extension to the jQuery-TE plugin - TE source was obsfucated, despite being Open Source.
         */
    (function ($) {
        $.fn.extend({
            insertImageAtCaret: function (myValue) {
                var obj = $(this);
                if ($.browser.msie) {
                    var sel = document.selection.createRange();
                    sel.execCommand("InsertImage", false, myValue);
                } else if ($.browser.mozilla || $.browser.webkit) {
                    document.execCommand("StyleWithCSS", false, false);
                    document.execCommand("InsertImage", false, myValue);
                }
            }
        });

        jQuery.fn.jqte_image = function (config) {
            var elements = $(this).each(function () {
                var $this = $(this);
                var panel = $this.siblings(".jqte").children(".jqte_Panel");
                panel.append('<a class="image"></a>');
                panel.find(".image").click(config.onClick);
            });

            config.insertImage = function (imageSrc) {
                elements.each(function () {
                    $(this).parent().find(".jqte_Content").insertImageAtCaret(imageSrc);
                });
            };
            return elements;

        };
    })(jQuery);

