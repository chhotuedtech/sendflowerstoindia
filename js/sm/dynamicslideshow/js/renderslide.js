/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */
 
"use strict";
var DynamicSlideshow = DynamicSlideshow || {};
    DynamicSlideshow = Class.create();
DynamicSlideshow.prototype = {
    form: null,
    delay: null,
    container: null,
    list: null,
    slider: null,
    count: 0,
    index: 0,
    layers: {},
    layerParams: "align|style|text|left|top|max_width|max_height|whitespace|scaleX|scaleY|proportional_scale|animation|easing|speed|split|splitdelay|endanimation|endeasing|endspeed|endsplit|endsplitdelay|endtime|link_enable|link_type|link|link_open_in|link_slide|corner_left|corner_right|resizeme|hiddenunder|id|classes|title|alt|parallaxLevels".split("|"),
    videoParams: "width|height|fullwidth|loop|control|args|autoplay|autoplayonlyfirsttime|nextslide|mute|force_rewind".split("|"),
    animParams: "animation|speed|easing|split|splitdelay|endanimation|endspeed|endeasing|endsplit|endsplitdelay".split("|"),
    cusAnimParams: "name|movex|movey|movez|rotationx|rotationy|rotationz|scalex|scaley|skewx|skewy|captionopacity|captionperspective|originx|originy".split("|"),
    cssParams: "font-family|color|padding|font-style|font-size|line-height|font-weight|text-decoration|background|border-color|border-style|border-width|border-radius|css".split("|"),
    cssState: "normal",
    cssUsingHover: 2,
    animPos: {},
    cusAnimPos: {},
    anims: new Hash(),
    styles: new Hash(),
    autorun: true,
    layerParamsElm: {},
    selectedLayer: null,
    deleteBtn: null,
    dupLayerBtn: null,
    editLayerBtn: null,
    videoData: null,
    lastVideoId: null,
    videoSearch: false,
    initialize: function(a, b, c) {
        this.mediaUrl = c.media_url;
        this.css_save_url = c.css_save_url;
        this.css_delete_url = c.css_delete_url;
        this.css_url = c.css_url;
        this.anim_save_url = c.anim_save_url;
        this.anim_delete_url = c.anim_delete_url;
        this.form = a;
        this.delay = b || 9e3;
        c.anims.each(function(a) {
            this.anims.set(a.id, a);
        }, this);
        this.collectContainer();
        this.collectBtns();
        this.collectParamsElement();
        this.updateContainer();
        this.updateList();
        this.selectLayer();
        this.prepareAnimation();
        this.updateAnimationSelect();
    },
    deleteLayerCss: function(a, b) {
        if (b && confirm(Translator.translate("Do you want delete this style?"))) new Ajax.Request(this.css_delete_url, {
            method: "post",
            parameters: {
                id: b
            },
            onSuccess: function(c) {
                var d = c.responseText.evalJSON();
                if (1 == d.success) {
                    Windows.close(a);
                    var e = $("layer_style");
                    for (var f = 0; f < e.options.length; f++) if (e.options.item(f).value == b) e.options.item(f).remove();
                    this.selectedLayer.removeClassName(this.selectedLayer.params["style"]);
                    this.selectedLayer.params["style"] = "";
                    e.value = "";
                    this.updateCssNew();
                }
            }.bind(this)
        });
    },
    assignCssForm: function(a) {
        this.cssObject = a.normal;
        if (0 === a.hover.length) {
            this.cssHover = Object.clone(a.normal);
            if (a.normal["padding"]) this.cssHover["padding"] = Object.clone(a.normal["padding"]);
            if (a.normal["border-radius"]) this.cssHover["border-radius"] = Object.clone(a.normal["border-radius"]);
        } else this.cssHover = a.hover;
        this.cssUsingHover = a.settings ? a.settings.hover : 0;
        this.updateCssElementsEditor(this.cssObject);
        this.updateCssPreview();
        this.bindCssEditorEvent();
        this.toggleCssEditMode(1, true);
        this.toggleCssState(1);
    },
    updateCssElementsEditor: function(a) {
        var b = this;
        function c(a, c) {
            a.on("slide", function(a, d) {
                var e = jQuery(this).data("sid");
                if ("normal" == b.cssState) {
                    if (!b.cssObject[c]) b.cssObject[c] = [ 0, 0, 0, 0 ];
                    b.cssObject[c][e] = d.value;
                } else {
                    if (!b.cssHover[c]) b.cssHover[c] = [ 0, 0, 0, 0 ];
                    b.cssHover[c][e] = d.value;
                }
                b.updateCssPreview();
            });
        }
        function d(a, c) {
            var d = {}, f = a.val() ? e(a.val(), parseInt(c) / 100) : "";
            d["background-color"] = f ? "rgba(" + f + ")" : "transparent";
            if ("normal" == b.cssState) b.cssObject["background-color"] = d["background-color"]; else b.cssHover["background-color"] = d["background-color"];
            b.updateCssPreview();
        }
        function e(a, b) {
            var c = parseInt(a, 16), d = c >> 16 & 255, e = c >> 8 & 255, f = 255 & c, g = b < 0 ? 0 : b > 1 ? 1 : b;
            return [ d, e, f, g ].join();
        }
        function f(a, b, c) {
            return ((1 << 24) + (a << 16) + (b << 8) + c).toString(16).slice(1);
        }
        this.cssParams.each(function(e) {
            switch (e) {
              case "padding":
              case "border-radius":
                if (a[e]) for (var g in a[e]) {
                    var h = jQuery("#css_" + e + "_" + g);
                    if (h.length) {
                        h.slider("option", "value", a[e][g]);
                        if (!h.data("binded")) {
                            h.data("binded", true);
                            c(h, e);
                        }
                    }
                } else for (var g = 0; g <= 4; g++) {
                    var h = jQuery("#css_" + e + "_" + g);
                    if (h.length) {
                        h.slider("option", "value", 0);
                        if (!h.data("binded")) {
                            h.data("binded", true);
                            c(h, e);
                        }
                    }
                }
                break;

              case "font-size":
              case "line-height":
              case "font-weight":
              case "border-width":
                var h = jQuery("#css_" + e);
                if (h.length) {
                    a[e] = a[e] || 0;
                    h.slider("option", "value", a[e]);
                    if (!h.data("binded")) {
                        h.data("binded", true);
                        h.on("slide", function(a, c) {
                            var d = {};
                            d[e] = c.value;
                            b.updateCssObject(e, c.value);
                            b.updateCssPreview();
                        });
                    }
                }
                break;

              case "font-style":
              case "text-decoration":
                var h = $("css_" + e);
                if (h) {
                    if (a[e]) h.value = a[e];
                    if (!h.binded) {
                        h.binded = true;
                        h.observe("change", function() {
                            var a = {};
                            a[e] = h.value;
                            this.updateCssObject(e, h.value);
                            this.updateCssPreview();
                        }.bind(this));
                    }
                }
                break;

              case "background":
                var i = jQuery("#css_background-transparency"), j = jQuery("#css_background-color");
                if (i.length && j.length) {
                    if (!a["background-color"]) {
                        j.val("");
                        i.slider("option", "value", 100);
                    } else if (0 === a["background-color"].indexOf("rgb")) {
                        var k = a["background-color"].replace(/[rgba\(\)]/g, ""), l = k.split(","), m = f(parseInt(l[0]), parseInt(l[1]), parseInt(l[2]));
                        j.val(m);
                        if (l[3]) i.slider("option", "value", 100 * parseFloat(l[3])); else i.slider("option", "value", 100);
                    } else {
                        j.val(a["background-color"]);
                        i.slider("option", "value", 100);
                    }
                    if (!j.binded) {
                        j.binded = true;
                        j.on("change", function() {
                            d(j, i.slider("value"));
                        });
                    }
                    $("css_background-color").color && $("css_background-color").color.importColor();
                    if (!i.data("binded")) {
                        i.data("binded", true);
                        i.on("slide", function(a, b) {
                            d(j, b.value);
                        });
                    }
                }
                break;

              case "color":
              case "border-color":
                var h = $("css_" + e);
                if (h) {
                    if (a[e]) if (0 === a[e].indexOf("rgb")) {
                        var k = a[e].replace(/[rgba\(\)]/g, ""), l = k.split(","), m = f(parseInt(l[0]), parseInt(l[1]), parseInt(l[2]));
                        h.value = m.toUpperCase();
                    } else h.value = a[e];
                    if (!h.binded) {
                        h.binded = true;
                        h.observe("change", function() {
                            var a = {};
                            a[e] = "#" + this.cleanColor(h.value);
                            this.updateCssObject(e, a[e]);
                            this.updateCssPreview();
                        }.bind(this));
                    }
                    h.color && h.color.importColor();
                }
                break;

              case "css":
                var h = $("css_css");
                if (h) {
                    var n = this.getCssFromObject(a, false);
                    if (CodeMirror) if (!h.cm) {
                        h.value = n;
                        this.cssCM = CodeMirror.fromTextArea(h, {
                            mode: "css"
                        });
                        this.cssCM.on("blur", function(a) {
                            var b = a.getValue(), c = this.getStyleFromCss(b);
                            if ("normal" == this.cssState) this.cssObject = c; else this.cssHover = c;
                        }.bind(this));
                        h.cm = this.cssCM;
                    }
                }
                break;

              default:
                var h = $("css_" + e);
                if (h) {
                    h.value = a[e];
                    if (!h.binded) {
                        h.binded = true;
                        h.observe("change", function() {
                            var a = {};
                            a[e] = h.value;
                            this.updateCssObject(e, h.value);
                            this.updateCssPreview();
                        }.bind(this));
                    }
                }
            }
        }, this);
    },
    updateCssObject: function(a, b) {
        if (a && b) if ("normal" == this.cssState) this.cssObject[a] = b; else this.cssHover[a] = b;
    },
    getCssFromObject: function(a, b) {
        var c = [];
        if ("object" === typeof a) for (var d in a) if ("object" === typeof a[d] && a[d].length) c.push(d + ": " + a[d].invoke("toString").invoke("concat", "px").join(" ")); else switch (d) {
          case "font-size":
          case "line-height":
          case "border-width":
            c.push(d + ": " + a[d] + "px");
            break;

          default:
            c.push(d + ": " + a[d]);
        }
        if (b) return c; else {
            c = c.collect(function(a) {
                return "	" + a + ";";
            });
            return "{\n" + c.join("\n") + "\n}";
        }
    },
    bindCssEditorEvent: function() {
        $$('input[name="css_hover"]').each(function(a) {
            a.observe("click", function() {
                this.cssUsingHover = a.value;
            }.bind(this));
        }, this);
        $$('input[name="css_mode"]').each(function(a) {
            a.observe("click", function() {
                this.toggleCssEditMode(parseInt(a.value), false);
            }.bind(this));
        }, this);
        $$('input[name="css_state"]').each(function(a) {
            a.observe("click", function() {
                this.toggleCssState(parseInt(a.value));
            }.bind(this));
        }, this);
    },
    toggleCssState: function(a) {
        var b = $$('input[name="css_hover"]')[0], c = b.up("tr");
        switch (a) {
          case 1:
            this.cssState = "normal";
            if (1 == this.cssMode) this.updateCssElementsEditor(this.cssObject); else {
                var d = this.getCssFromObject(this.cssObject, false);
                this.cssCM.setValue(d);
            }
            c.hide();
            break;

          case 2:
            this.cssState = "hover";
            if (1 == this.cssMode) this.updateCssElementsEditor(this.cssHover); else {
                var d = this.getCssFromObject(this.cssHover, false);
                this.cssCM.setValue(d);
            }
            c.show();
        }
        this.updateCssPreview();
    },
    toggleCssEditMode: function(a, b) {
        var c = $("css_container_fieldset"), d = $("css_advance_fieldset");
        this.cssMode = a;
        switch (a) {
          case 1:
            c && c.show();
            c && this.updateCssEditor(b);
            d && d.hide();
            break;

          case 2:
            c && c.hide();
            d && d.show();
            if (this.cssCM) {
                var e = "normal" == this.cssState ? this.getCssFromObject(this.cssObject, false) : this.getCssFromObject(this.cssHover, false);
                this.cssCM.setValue(e);
            }
        }
    },
    updateCssEditor: function(a) {
        if (a) return;
        if (this.cssCM) {
            var b = this.cssCM.getValue(), c = this.getStyleFromCss(b);
            this.updateCssElementsEditor(c);
            this.updateCssPreview();
            if ("normal" == this.cssState) this.cssObject = c; else this.cssHover = c;
        }
    },
    getStyleFromCss: function(a) {
        a = "#dummy" + a;
        var b = document.implementation.createHTMLDocument(""), c = document.createElement("style");
        c.textContent = a;
        b.body.appendChild(c);
        var d = c.sheet.cssRules[0].style, e = {};
        d.cssText.split(";").each(function(a) {
            var b = a.split(":"), c = b[0] ? b[0].trim() : "";
            if (c) if ("padding" == c) e["padding"] = [ d.paddingTop ? parseInt(d.paddingTop) : 0, d.paddingRight ? parseInt(d.paddingRight) : 0, d.paddingBottom ? parseInt(d.paddingBottom) : 0, d.paddingLeft ? parseInt(d.paddingLeft) : 0 ]; else if ("font-size" == c) e["font-size"] = parseInt(d.fontSize); else if ("font-weight" == c) e["font-weight"] = parseInt(d.fontWeight); else if ("line-height" == c) e["line-height"] = parseInt(d.lineHeight); else e[b[0].trim()] = b[1].trim();
        });
        e["border-color"] = d.borderColor;
        e["border-style"] = d.borderStyle;
        e["border-width"] = parseInt(d.borderWidth);
        e["border-radius"] = [ d.borderTopLeftRadius ? parseInt(d.borderTopLeftRadius) : 0, d.borderTopRightRadius ? parseInt(d.borderTopRightRadius) : 0, d.borderBottomRightRadius ? parseInt(d.borderBottomRightRadius) : 0, d.borderBottomLeftRadius ? parseInt(d.borderBottomLeftRadius) : 0 ];
        return e;
    },
    updateCssPreview: function() {
        var a = $("css_preview"), b = "normal" == this.cssState ? this.cssObject : this.cssHover;
        if (a) {
            var c = {};
            if ("normal" == this.cssState) {
                c.fontFamily = b["font-family"] || "inherit";
                c.fontSize = null != b["font-size"] ? b["font-size"] + "px" : "inherit";
                c.fontStyle = b["font-style"] || "normal";
                c.fontWeight = b["font-weight"] || "normal";
                c.color = b["color"] || "000";
                c.lineHeight = null != b["line-height"] ? b["line-height"] + "px" : "100%";
                c.textDecoration = b["text-decoration"] || "none";
                c.backgroundColor = b["background-color"] || "transparent";
                c.borderColor = b["border-color"] || "000";
                c.borderStyle = b["border-style"] || "solid";
                c.borderWidth = null != b["border-width"] ? b["border-width"] + "px" : "0";
                if (b["padding"]) {
                    if (null != b["padding"][0]) c.paddingTop = b["padding"][0] + "px";
                    if (null != b["padding"][1]) c.paddingRight = b["padding"][1] + "px";
                    if (null != b["padding"][2]) c.paddingBottom = b["padding"][2] + "px";
                    if (null != b["padding"][3]) c.paddingLeft = b["padding"][3] + "px";
                } else {
                    c.paddingTop = 0;
                    c.paddingRight = 0;
                    c.paddingBottom = 0;
                    c.paddingLeft = 0;
                }
                if (b["border-radius"]) {
                    if (null != b["border-radius"][0]) c.borderTopLeftRadius = b["border-radius"][0] + "px";
                    if (null != b["border-radius"][1]) c.borderTopRightRadius = b["border-radius"][1] + "px";
                    if (null != b["border-radius"][2]) c.borderBottomRightRadius = b["border-radius"][2] + "px";
                    if (null != b["border-radius"][3]) c.borderBottomLeftRadius = b["border-radius"][3] + "px";
                } else {
                    c.borderTopLeftRadius = 0;
                    c.borderTopRightRadius = 0;
                    c.borderBottomRightRadius = 0;
                    c.borderBottomLeftRadius = 0;
                }
            } else {
                if (b["font-family"]) c.fontFamily = b["font-family"];
                if (null != b["font-size"]) c.fontSize = b["font-size"] + "px";
                if (b["font-style"]) c.fontStyle = b["font-style"];
                if (b["font-weight"]) c.fontWeight = b["font-weight"];
                if (b["color"]) c.color = b["color"];
                if (null != b["line-height"]) c.lineHeight = b["line-height"] + "px";
                if (b["text-decoration"]) c.textDecoration = b["text-decoration"];
                if (b["background-color"]) c.backgroundColor = b["background-color"];
                if (b["border-color"]) c.borderColor = b["border-color"];
                if (b["border-style"]) c.borderStyle = b["border-style"];
                if (null != b["border-width"]) c.borderWidth = b["border-width"] + "px";
                if (b["padding"]) {
                    if (null != b["padding"][0]) c.paddingTop = b["padding"][0] + "px";
                    if (null != b["padding"][1]) c.paddingRight = b["padding"][1] + "px";
                    if (null != b["padding"][2]) c.paddingBottom = b["padding"][2] + "px";
                    if (null != b["padding"][3]) c.paddingLeft = b["padding"][3] + "px";
                }
                if (b["border-radius"]) {
                    if (null != b["border-radius"][0]) c.borderTopLeftRadius = b["border-radius"][0] + "px";
                    if (null != b["border-radius"][1]) c.borderTopRightRadius = b["border-radius"][1] + "px";
                    if (null != b["border-radius"][2]) c.borderBottomRightRadius = b["border-radius"][2] + "px";
                    if (null != b["border-radius"][3]) c.borderBottomLeftRadius = b["border-radius"][3] + "px";
                }
            }
            a.setStyle(c);
        }
    },
    openCssDialog: function(a, b, c) {
        var d = $("layer_style");
        if (d.value) {
            a += "style/" + d.value;
            _MediabrowserUtility.openDialog(a, b, 1e3, null, c);
        } else alert(Translator.translate("Please choose a style."));
    },
    openAnimationDialog: function(a, b, c, d) {
        a += "type/" + d;
        if ("in" == d) a += "/aid/" + $("layer_animation").value; else if ("out" == d) {
            var e = $("layer_endanimation").value;
            if ("auto" == e) e = $("layer_animation").value;
            a += "/aid/" + e;
        }
        _MediabrowserUtility.openDialog(a, b, null, null, c);
    },
    updateAnimationSelect: function() {
        var a = $("layer_animation"), b = $("layer_endanimation"), c = 1 === arguments.length ? arguments[0] : this.anims;
        function d(a, b) {
            for (var c = 0; c < b.length; c++) if (b.item(c).value === a) return b.item(c);
            return false;
        }
        c.each(function(c) {
            var e = "custom-" + c.key, f = d(e, a.options), g = d(e, b.options);
            if (!f) a.options.add(new Option(c.value.name, e)); else f.update(c.value.name);
            if (!g) b.options.add(new Option(c.value.name, e)); else g.update(c.value.name);
        });
    },
    removeCustomAnimation: function(a, b) {
        if (0 === b.indexOf("custom")) {
            if (!confirm(Translator.translate("Really delete this animation?"))) return;
            var c = b.split("-")[1];
            new Ajax.Request(this.anim_delete_url, {
                parameters: {
                    id: c
                },
                method: "post",
                onComplete: function() {
                    Windows.close(a);
                },
                onSuccess: function(a) {
                    try {
                        var b = a.responseText.evalJSON();
                        if (1 == b.success) {
                            var c = $("layer_animation").selectedIndex;
                            $("layer_animation").options.item(c).remove();
                            if (0 != $("layer_endanimation").selectedIndex) $("layer_endanimation").options.item($("layer_endanimation").selectedIndex).remove(); else $("layer_endanimation").options.item(c + 1).remove();
                        }
                    } catch (d) {}
                }.bind(this)
            });
        } else alert(Translator.translate("Default animations can't be deleted"));
    },
    addCustomAnimation: function(a, b) {
        if (editForm.validator.validate()) {
            var c = {};
            this.cusAnimParams.each(function(a) {
                var b = $$('input[name="anim-' + a + '"]')[0];
                if (b) c[a] = b.value;
            });
            if ($("anim_id")) c.id = $("anim_id").value;
            new Ajax.Request(this.anim_save_url, {
                method: "post",
                parameters: c,
                onComplete: function() {
                    Windows.close(a);
                },
                onSuccess: function(a) {
                    try {
                        var c = a.responseText.evalJSON();
                        if (1 == c.success) if (c.data) {
                            this.anims.set(c.data.id, c.data);
                            var d = new Hash();
                            d.set(c.data.id, c.data);
                            this.updateAnimationSelect(d);
                            setTimeout(function() {
                                var a = "custom-" + c.data.id;
                                if ("in" == b) {
                                    $("layer_animation").value = a;
                                    this.selectedLayer.params.animation = a;
                                } else {
                                    $("layer_endanimation").value = a;
                                    this.selectedLayer.params.endanimation = a;
                                }
                            }.bind(this));
                        }
                    } catch (e) {}
                }.bind(this)
            });
        }
    },
    initCustomAnimSlider: function(a) {
        a.each(function(b) {
            var c = $(b.id);
            if (c) {
                if (!f) var d = c.up("div.anim-slider-wrapper").getDimensions(), e = Math.floor(d.width / a.length), f = Math.floor(e / 2) - 1;
                c.setStyle({
                    marginLeft: f + "px",
                    marginRight: f + "px",
                    backgroundColor: b.color
                });
                c.insert({
                    after: '<span class="anim-label">' + b.label + "</span>"
                });
                c.slider = jQuery("#" + b.id).slider({
                    orientation: "vertical",
                    min: b.range[0],
                    max: b.range[1],
                    value: b.value,
                    create: function() {
                        jQuery(this).find("a.ui-slider-handle").css({
                            backgroundColor: b.color
                        }).attr("title", b.label);
                        jQuery(this).find("input.anim-value").val(b.value);
                    },
                    slide: function(a, b) {
                        jQuery(this).find("input.anim-value").val(b.value);
                    }
                });
                c.down("input").observe("change", function(a) {
                    var d = parseInt(Event.findElement(a, "input").value);
                    if (isNaN(d)) return;
                    d = d < b.range[0] ? b.range[0] : d > b.range[1] ? b.range[1] : d;
                    c.slider.slider("value", d);
                });
            }
        });
    },
    runCustomAnimation: function(a) {
        setTimeout(function() {
            this.toggleAutotun(false);
            this.prepareAnimationTarget("custom_animation_preview_target");
            if ("in" == a) this.setInCustomAnimation("custom_animation_preview_target"); else this.setOutCustomAnimation("custom_animation_preview_target");
        }.bind(this));
    },
    setScale: function(a, b) {
        if (this.selectedLayer && "image" === this.selectedLayer.params.type) {
            var c = this.selectedLayer.params, d = 0 === c.image_url.indexOf("http") ? c.image_url : this.mediaUrl + c.image_url, e = new Element("img", {
                src: d
            }), f = e.width, g = e.height;
            if (!b && a) {
                f = parseInt(c.scaleX);
                if (isNaN(f)) f = e.width;
                g = c["proportional_scale"] ? Math.round(100 / e.width * f / 100 * e.height) : !isNaN(c.scaleY) ? c.scaleY : e.height;
            } else if (!b && !a) {
                g = parseInt(c.scaleY);
                if (isNaN(g)) g = e.height;
                f = c["proportional_scale"] ? Math.round(100 / e.height * g / 100 * e.width) : !isNaN(c.scaleX) ? c.scaleX : e.width;
            }
            c.scaleX = f;
            c.scaleY = g;
            this.layerParamsElm["scaleX"].value = f;
            this.layerParamsElm["scaleY"].value = g;
            this.updateLayerHtmlScale(c);
            this.updateAlign(this.selectedLayer);
        }
    },
    toggleAutotun: function() {
        var a = $("animation_control"), b = a.down("span");
        if (b) if (1 === arguments.length) {
            if (this.autorun) {
                var c = arguments[0];
                if (c) {
                    b.addClassName("on");
                    this.toggleAnimPreview(true);
                } else {
                    b.removeClassName("on");
                    this.toggleAnimPreview(false);
                }
            }
        } else if (this.selectedLayer) if (b.hasClassName("on")) {
            b.removeClassName("on");
            this.toggleAnimPreview(false);
            this.autorun = false;
        } else {
            b.addClassName("on");
            this.toggleAnimPreview(true);
            this.autorun = true;
        }
    },
    prepareAnimation: function() {
        this.animParams.each(function(a) {
            var b = $("layer_" + a);
            if (b) b.observe("change", function() {
                if (this.autorun) this.setInAnimation();
            }.bind(this));
        }, this);
        this.prepareAnimationTarget("animation_preview");
    },
    prepareAnimationTarget: function(a) {
        var b = $(a);
        if (!b) return;
        var c = b.up();
        if (!c) return;
        var d = b.getDimensions(), e = c.getDimensions();
        if (!e.width && !e.height) {
            setTimeout(function() {
                this.prepareAnimationTarget(a);
            }.bind(this), 500);
            return;
        }
        var f = parseInt(e.width / 2 - d.width / 2), g = parseInt(e.height / 2 - d.height / 2);
        b.setStyle({
            top: g + "px",
            left: f + "px",
            visibility: "visible"
        });
        return {
            top: g,
            left: f
        };
    },
    toggleAnimPreview: function(a) {
        var b = jQuery("#animation_preview");
        if (!b.length) return;
        punchgs.TweenLite.killTweensOf(b, false);
        if (a) {
            b.data("timer") && clearTimeout(b.data("timer"));
            b.removeClass("reset");
            this.setInAnimation();
        } else {
            b.data("timer") && clearTimeout(b.data("timer"));
            b.addClass("reset");
        }
    },
    getCustomAnimationParams: function() {
        var a = {};
        this.cusAnimParams.each(function(b) {
            var c = $$('input[name="anim-' + b + '"]')[0];
            if (c) if ("name" != b) a[b] = parseInt(c.value); else a[b] = c.value;
        });
        a["easing"] = $("anim_easing") ? $("anim_easing").value : "Linear.easeNone";
        a["speed"] = $("anim_speed") ? parseInt($("anim_speed").value) : null;
        if (isNaN(a["speed"])) a["speed"] = 500;
        a["speed"] = a["speed"] < 100 ? 100 : a["speed"];
        a["split"] = $("anim_split") ? $("anim_split").value : null;
        a["splitdelay"] = $("anim_splitdelay") ? parseInt($("anim_splitdelay").value) / 100 : null;
        if (isNaN(a["splitdelay"])) a["splitdelay"] = 10 / 100;
        return a;
    },
    setInCustomAnimation: function(a) {
        var b = jQuery("#" + a);
        if (!b.length) return;
        var c = this.getCustomAnimationParams(), d = b;
        if (b.data("splittext")) b.data("splittext").revert();
        if ("chars" == c["split"] || "words" == c["split"] || "lines" == c["split"]) if (b.find("a").length > 0) b.data("splittext", new SplitText(b.find("a"), {
            type: "lines,words,chars"
        })); else b.data("splittext", new SplitText(b, {
            type: "lines,words,chars"
        }));
        if ("chars" == c["split"]) d = b.data("splittext").chars;
        if ("words" == c["split"]) d = b.data("splittext").words;
        if ("lines" == c["split"]) d = b.data("splittext").lines;
        var e = d.length * c["splitdelay"] * 1e3 + c["speed"];
        punchgs.TweenLite.killTweensOf(d, false);
        if (d != b) punchgs.TweenLite.set(b, {
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            z: 0,
            x: 0,
            y: 0,
            opacity: 1,
            visibility: "visible",
            overwrite: "all"
        });
        var f = new punchgs.TimelineLite();
        f.staggerFromTo(d, c["speed"] / 1e3, {
            scaleX: c["scalex"] / 100,
            scaleY: c["scaley"] / 100,
            rotationX: c["rotationx"],
            rotationY: c["rotationy"],
            rotationZ: c["rotationz"],
            x: c["movex"],
            y: c["movey"],
            z: c["movez"] + 1,
            skewX: c["skewx"],
            skewY: c["skewy"],
            opacity: c["captionopacity"] / 100,
            transformPerspective: c["captionperspective"],
            transformOrigin: c["originx"] + "% " + c["originy"] + "%",
            visibility: "hidden"
        }, {
            x: 0,
            y: 0,
            z: 1,
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            visibility: "visible",
            opacity: 1,
            ease: c["easing"],
            overwrite: "all"
        }, c["splitdelay"]);
        setTimeout(function() {
            this.setInCustomAnimation("custom_animation_preview_target");
        }.bind(this), e + 500);
    },
    setOutCustomAnimation: function(a) {
        var b = jQuery("#" + a);
        if (!b.length) return;
        var c = this.getCustomAnimationParams(), d = b;
        if (b.data("splittext")) b.data("splittext").revert();
        if ("chars" == c["split"] || "words" == c["split"] || "lines" == c["split"]) if (b.find("a").length) b.data("splittext", new SplitText(b.find("a"), {
            type: "lines,words,chars"
        })); else b.data("splittext", new SplitText(b, {
            type: "lines,words,chars"
        }));
        if ("chars" == c["split"]) d = b.data("splittext").chars;
        if ("words" == c["split"]) d = b.data("splittext").words;
        if ("lines" == c["split"]) d = b.data("splittext").lines;
        var e = d.length * c["splitdelay"] * 1e3 + c["speed"];
        punchgs.TweenLite.killTweensOf(d, false);
        if (d != b) punchgs.TweenLite.set(b, {
            opacity: 1,
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            z: 0,
            x: 0,
            y: 0,
            visibility: "visible",
            overwrite: "all"
        });
        var f = new punchgs.TimelineLite();
        punchgs.TweenLite.killTweensOf(d, false);
        f.staggerFromTo(d, c["speed"] / 1e3, {
            x: 0,
            y: 0,
            z: 1,
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            visibility: "visible",
            opacity: 1
        }, {
            scaleX: c["scalex"] / 100,
            scaleY: c["scaley"] / 100,
            rotationX: c["rotationx"],
            rotationY: c["rotationy"],
            rotationZ: c["rotationz"],
            x: c["movex"],
            y: c["movey"],
            z: c["movez"] + 1,
            skewX: c["skewx"],
            skewY: c["skewy"],
            opacity: c["captionopacity"] / 100,
            transformPerspective: c["captionperspective"],
            transformOrigin: c["originx"] + "% " + c["originy"] + "%",
            overwrite: "all"
        }, c["splitdelay"]);
        setTimeout(function() {
            this.setOutCustomAnimation("custom_animation_preview_target");
        }.bind(this), e + 500);
    },
    getCustomAnimation: function(a) {
        return this.anims.get(a).params;
    },
    setInAnimation: function() {
        var a = jQuery("#animation_preview"), b = $("layer_animation").value || "fade", c = ($("layer_speed").value || 500) / 1e3, d = $("layer_easing").value, e = ($("layer_splitdelay").value || 10) / 100, f = $("layer_split").value, g = a, h = 1, i = 0, j = 0, k, l;
        if (a.data("splittext")) a.data("splittext").revert();
        if ("chars" == f || "words" == f || "lines" == f) if (a.find("a").length) a.data("splittext", new SplitText(a.find("a"), {
            type: "lines,words,chars"
        })); else a.data("splittext", new SplitText(a, {
            type: "lines,words,chars"
        }));
        if ("chars" == f) g = a.data("splittext").chars;
        if ("words" == f) g = a.data("splittext").words;
        if ("lines" == f) g = a.data("splittext").lines;
        var m = 1e3 * (g.length * e + c);
        punchgs.TweenLite.killTweensOf(a, false);
        punchgs.TweenLite.killTweensOf(g, false);
        punchgs.TweenLite.set(a, {
            clearProps: "transform"
        });
        punchgs.TweenLite.set(g, {
            clearProps: "transform"
        });
        if (g != a) punchgs.TweenLite.set(a, {
            opacity: 1,
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            z: 0,
            x: 0,
            y: 0,
            visibility: "visible",
            overwrite: "all"
        });
        var n = new punchgs.TimelineLite();
        a.data("timer") && clearTimeout(a.data("timer"));
        if ("randomrotate" == b) {
            h = 3 * Math.random() + 1;
            i = Math.round(200 * Math.random() - 100);
            l = Math.round(200 * Math.random() - 100);
            k = Math.round(200 * Math.random() - 100);
        }
        if ("lfr" == b || "skewfromright" == b) l = 560;
        if ("lfl" == b || "skewfromleft" == b) l = -100;
        if ("sfl" == b || "skewfromleftshort" == b) l = -50;
        if ("sfr" == b || "skewfromrightshort" == b) l = 50;
        if ("lft" == b) k = -50;
        if ("lfb" == b) k = 250;
        if ("sft" == b) k = -50;
        if ("sfb" == b) k = 50;
        if ("skewfromright" == b || "skewfromrightshort" == b) j = -85;
        if ("skewfromleft" == b || "skewfromleftshort" == b) j = 85;
        if (b.split("custom").length > 1) {
            var o = b.split("-")[1], p = this.getCustomAnimation(o);
            a.data("tween", n.staggerFromTo(g, c, {
                scaleX: parseInt(p.scalex) / 100,
                scaleY: parseInt(p.scaley) / 100,
                rotationX: parseInt(p.rotationx),
                rotationY: parseInt(p.rotationy),
                rotationZ: parseInt(p.rotationz),
                x: parseInt(p.movex),
                y: parseInt(p.movey),
                z: parseInt(p.movez) + 1,
                skewX: parseInt(p.skewx),
                skewY: parseInt(p.skewy),
                opacity: parseInt(p.captionopacity) / 100,
                transformPerspective: parseInt(p.captionperspective),
                transformOrigin: parseInt(p.originx) + "% " + parseInt(p.originy) + "%",
                visibility: "hidden"
            }, {
                x: 0,
                y: 0,
                scaleX: 1,
                scaleY: 1,
                rotationX: 0,
                rotationY: 0,
                rotationZ: 0,
                skewX: 0,
                skewY: 0,
                z: 1,
                visibility: "visible",
                opacity: 1,
                ease: d,
                overwrite: "all"
            }, e));
            a.data("timer", setTimeout(function() {
                this.setOutAnimation();
            }.bind(this), m + 500));
        } else {
            a.data("tween", n.staggerFromTo(g, c, {
                scale: h,
                rotation: i,
                rotationX: 0,
                rotationY: 0,
                rotationZ: 0,
                x: l,
                y: k,
                opacity: 0,
                z: 1,
                skewX: j,
                skewY: 0,
                transformPerspective: 600,
                transformOrigin: "50% 50%",
                visibility: "visible"
            }, {
                scale: 1,
                skewX: 0,
                rotation: 0,
                z: 1,
                x: 0,
                y: 0,
                visibility: "visible",
                opacity: 1,
                ease: d,
                overwrite: "all"
            }, e));
            a.data("timer", setTimeout(function() {
                this.setOutAnimation();
            }.bind(this), m + 500));
        }
    },
    setOutAnimation: function() {
        var a = jQuery("#animation_preview"), b = $("layer_endanimation").value || "auto", c = ($("layer_endspeed").value || 500) / 1e3, d = $("layer_endeasing").value, e = ($("layer_endsplitdelay").value || 10) / 100, f = $("layer_endsplit").value, g = a, h = 0, i = 0, j = 0;
        if (a.data("splittext")) if ("none" != f) a.data("splittext").revert();
        if ("chars" == f || "words" == f || "lines" == f) if (a.find("a").length) a.data("splittext", new SplitText(a.find("a"), {
            type: "lines,words,chars"
        })); else a.data("splittext", new SplitText(a, {
            type: "lines,words,chars"
        }));
        if ("chars" == f) g = a.data("splittext").chars;
        if ("words" == f) g = a.data("splittext").words;
        if ("lines" == f) g = a.data("splittext").lines;
        var k = 1e3 * (g.length * e + c);
        punchgs.TweenLite.killTweensOf(g, false);
        if (g != a) punchgs.TweenLite.set(a, {
            opacity: 1,
            scaleX: 1,
            scaleY: 1,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            skewX: 0,
            skewY: 0,
            z: 0,
            x: 0,
            y: 0,
            visibility: "visible",
            overwrite: "all"
        });
        var l = new punchgs.TimelineLite();
        if ("fadeout" == b || "ltr" == b || "ltl" == b || "str" == b || "stl" == b || "ltt" == b || "ltb" == b || "stt" == b || "stb" == b || "skewtoright" == b || "skewtorightshort" == b || "skewtoleft" == b || "skewtoleftshort" == b) {
            if ("skewtoright" == b || "skewtorightshort" == b) j = 35;
            if ("skewtoleft" == b || "skewtoleftshort" == b) j = -35;
            if ("ltr" == b || "skewtoright" == b) h = 560; else if ("ltl" == b || "skewtoleft" == b) h = -100; else if ("ltt" == b) i = -50; else if ("ltb" == b) i = 250; else if ("str" == b || "skewtorightshort" == b) h = 50; else if ("stl" == b || "skewtoleftshort" == b) h = -50; else if ("stt" == b) i = -50; else if ("stb" == b) i = 50;
            if ("skewtorightshort" == b) h = 400;
            if ("skewtoleftshort" == b) h = 0;
            a.data("tween", l.staggerTo(g, c, {
                scale: 1,
                rotation: 0,
                skewX: j,
                opacity: 0,
                x: h,
                y: i,
                z: 2,
                overwrite: "auto",
                ease: d
            }, e));
            a.data("timer", setTimeout(function() {
                this.setInAnimation();
            }.bind(this), k + 500));
        } else if (b.split("custom").length > 1) {
            var m = b.split("-")[1], n = this.getCustomAnimation(m);
            a.data("tween", l.staggerFromTo(g, c, {
                x: 0,
                y: 0,
                scaleX: 1,
                scaleY: 1,
                rotationX: 0,
                rotationY: 0,
                rotationZ: 0,
                skewX: 0,
                skewY: 0,
                transformPerspective: parseInt(n.captionperspective),
                transformOrigin: parseInt(n.originx) + "% " + parseInt(n.originy) + "%",
                z: 1,
                visibility: "visible",
                opacity: 1
            }, {
                scaleX: parseInt(n.scalex) / 100,
                scaleY: parseInt(n.scaley) / 100,
                rotationX: parseInt(n.rotationx),
                rotationY: parseInt(n.rotationy),
                rotationZ: parseInt(n.rotationz),
                x: parseInt(n.movex),
                y: parseInt(n.movey),
                z: parseInt(n.movez) + 1,
                skewX: parseInt(n.skewx),
                skewY: parseInt(n.skewy),
                opacity: parseInt(n.captionopacity) / 100,
                transformPerspective: parseInt(n.captionperspective),
                transformOrigin: parseInt(n.originx) + "% " + parseInt(n.originy) + "%",
                ease: d
            }, e));
            a.data("timer", setTimeout(function() {
                this.setInAnimation();
            }.bind(this), k + 500));
        } else {
            a.data("tween").reverse();
            a.data("timer", setTimeout(function() {
                this.setInAnimation();
            }.bind(this), 1e3 * c + 1e3));
        }
    },
    collectContainer: function() {
        this.container = $("divLayers");
        Event.observe(this.container, "click", function(a) {
            var b = Event.element(a);
            if (b == this.container) this.selectLayer();
        }.bind(this));
        this.list = $("listLayers");
        this.list.sort = "depth";
    },
    collectBtns: function() {
        this.deleteBtn = $("deleteLayerBtn") || null;
        this.dupLayerBtn = $("dupLayerBtn") || null;
        this.editLayerBtn = $("editLayerBtn") || null;
        this.cInAnimation = $("cInAnimation") || null;
        this.cNewInAnimation = $("cNewInAnimation") || null;
        this.cOutAnimation = $("cOutAnimation") || null;
        this.cNewOutAnimation = $("cNewOutAnimation") || null;
        this.editStyleBtn = $("editStyleBtn") || null;
    },
    collectParamsElement: function() {
        this.layerParams.each(function(a) {
            var b = $("layer_" + a);
            if (b) {
                if ("style" == a) for (var c = 0; c < b.options.length; c++) this.styles.set(b.options[c].value, b.options[c].innerHTML);
                this.layerParamsElm[a] = b;
                if ("TABLE" === b.tagName) b.select("a").each(function(a) {
                    Event.observe(a, "click", function(a) {
                        a.stop();
                        if (b.hasClassName("disabled")) return false;
                        var c = Event.findElement(a, "a");
                        b.select("a").each(function(a) {
                            a.removeClassName("selected");
                        });
                        c.addClassName("selected");
                        this.selectedLayer.params.align_hor = c.readAttribute("data-hor");
                        this.selectedLayer.params.align_vert = c.readAttribute("data-ver");
                        this.selectedLayer.params.align = c.readAttribute("data-id");
                        switch (this.selectedLayer.params.align_hor) {
                          case "left":
                          case "right":
                            this.selectedLayer.params.left = 10;
                            this.layerParamsElm.left.value = 10;
                            break;

                          case "center":
                            this.selectedLayer.params.left = 0;
                            this.layerParamsElm.left.value = 0;
                        }
                        switch (this.selectedLayer.params.align_vert) {
                          case "top":
                          case "bottom":
                            this.selectedLayer.params.top = 10;
                            this.layerParamsElm.top.value = 10;
                            break;

                          case "middle":
                            this.selectedLayer.params.top = 0;
                            this.layerParamsElm.top.value = 0;
                        }
                        this.checkFullWidthVideo(this.selectedLayer.params);
                        this.updateAlign(this.selectedLayer);
                    }.bind(this));
                }.bind(this)); else b.observe("change", function(b) {
                    var c = Event.element(b);
                    if (this.selectedLayer) {
                        var d = this.selectedLayer.params[a];
                        this.selectedLayer.params[a] = c.value;
                        this.updateListItem(this.selectedLayer.params);
                        if ("text" === a) {
                            if ("text" === this.selectedLayer.params.type) this.selectedLayer.innerHTML = c.value; else if ("video" === this.selectedLayer.params.type) {
                                this.selectedLayer.params.video_data.title = c.value.escapeHTML();
                                var e = this.selectedLayer.down("span");
                                if (e) e.update(c.value.escapeHTML());
                            }
                        } else if ("left" === a || "top" === a) this.updateAlign(this.selectedLayer); else if ("style" === a) {
                            var f = c.options[c.selectedIndex].innerHTML;
                            this.selectedLayer.params[a] = f;
                            this.selectedLayer.params["style_id"] = c.value;
                            this.selectedLayer.removeClassName(d);
                            this.selectedLayer.addClassName(f);
                        } else if ("endtime" === a) this.slider.slider({
                            values: [ parseInt(this.selectedLayer.params.time), parseInt(c.value) ]
                        }); else if ("style_custom" === a) {
                            this.selectedLayer.removeClassName(d);
                            this.selectedLayer.addClassName(c.value);
                        } else if ("hiddenunder" === a || "resizeme" == a || "proportional_scale" == a) this.selectedLayer.params[a] = c.checked; else if ("corner_left" === a || "corner_right" === a) this.updateLayerHtmlCorners(this.selectedLayer.params); else if ("scaleX" === a) this.setScale(true, false); else if ("scaleY" === a) this.setScale(false, false); else if ("animation" === a) {
                            if (0 === c.value.indexOf("custom")) enableElement(this.cInAnimation); else disableElement(this.cInAnimation);
                            if (fireEvent) fireEvent($("layer_endanimation"), "change");
                        } else if ("endanimation" === a) if ("auto" === c.value) if (0 === $("layer_animation").value.indexOf("custom")) enableElement(this.cOutAnimation); else disableElement(this.cOutAnimation); else if (0 === c.value.indexOf("custom")) enableElement(this.cOutAnimation); else disableElement(this.cOutAnimation);
                    }
                }.bind(this));
            }
        }.bind(this));
    },
    selectLayer: function() {
        var a;
        if (1 === arguments.length) a = $(arguments[0]);
        if (a) {
            this.selectedLayer = a;
            this.container.select(".slide_layer").each(function(a) {
                a.removeClassName("selected");
            });
            a.addClassName("selected");
            this.list.select(".item").each(function(a) {
                a.removeClassName("selected");
            });
            var b = this.list.down("#item_" + a.params.serial);
            if (b) b.addClassName("selected");
            this.layerParams.each(function(b) {
                this.layerParamsElm[b].checked = a.params[b];
                this.layerParamsElm[b].value = a.params[b];
                switch (a.params.type) {
                  case "video":
                    switch (b) {
                      case "link_enable":
                      case "link_type":
                      case "link":
                      case "link_open_in":
                      case "link_slide":
                      case "hiddenunder":
                      case "corner_left":
                      case "corner_right":
                      case "alt":
                      case "proportional_scale":
                      case "scaleX":
                      case "scaleY":
                      case "whitespace":
                      case "max_width":
                      case "max_height":
                        disableElement(this.layerParamsElm[b]);
                        break;

                      default:
                        enableElement(this.layerParamsElm[b]);
                    }
                    break;

                  case "image":
                    switch (b) {
                      case "hiddenunder":
                      case "corner_left":
                      case "corner_right":
                      case "whitespace":
                      case "max_width":
                      case "max_height":
                        disableElement(this.layerParamsElm[b]);
                        break;

                      default:
                        enableElement(this.layerParamsElm[b]);
                    }
                    break;

                  default:
                    switch (b) {
                      case "alt":
                      case "scaleX":
                      case "scaleY":
                      case "proportional_scale":
                        disableElement(this.layerParamsElm[b]);
                        break;

                      default:
                        enableElement(this.layerParamsElm[b]);
                    }
                }
                if ("link_enable" == b || "link_type" == b || "animation" == b || "endanimation" == b) {
                    if (fireEvent) setTimeout(function() {
                        fireEvent(this.layerParamsElm[b], "change");
                    }.bind(this));
                } else if ("align" == b) {
                    var c = this.layerParamsElm[b];
                    c.removeClassName("disabled");
                    if (!a.params.align) a.params.align = a.params.align_hor + " " + a.params.align_vert;
                    c.select("a").each(function(b) {
                        if (b.readAttribute("data-id") == a.params.align) b.addClassName("selected"); else b.removeClassName("selected");
                    });
                } else if ("style" == b) if (a.params["style_id"]) this.layerParamsElm[b].value = a.params["style_id"];
            }.bind(this));
            if (this.slider) {
                var c = this.list.down("#item_" + a.params.serial).down("input.time"), d = $("layer_endtime");
                if (c) c.value = parseInt(this.selectedLayer.params.time);
                if (d) d.value = parseInt(this.selectedLayer.params.endtime) || this.delay;
                this.slider.slider("enable");
                this.slider.slider("values", 1, parseInt(this.selectedLayer.params.endtime) || this.delay);
                this.slider.slider("values", 0, parseInt(this.selectedLayer.params.time));
                this.slider.off("slide");
                this.slider.on("slide", function(a, b) {
                    this.selectedLayer.params.time = Math.round(b.values[0]);
                    this.selectedLayer.params.endtime = Math.round(b.values[1]);
                    if (c) c.value = Math.round(b.values[0]);
                    if (d) d.value = Math.round(b.values[1]);
                }.bind(this));
            }
            this.toggleAutotun(true);
            this.toogleDelete(true);
            this.toogleCustomAnim(true);
            this.toggleEditStyle(true);
        } else {
            this.selectedLayer = null;
            this.toggleAutotun(false);
            this.toogleDelete(false);
            this.toogleCustomAnim(false);
            this.toggleEditStyle(false);
            this.layerParams.each(function(a) {
                this.layerParamsElm[a].disabled = true;
                if ("align" === a) {
                    var b = $("layer_align");
                    b.addClassName("disabled");
                    b.select("a").each(function(a) {
                        a.removeClassName("selected");
                    });
                }
            }.bind(this));
            this.container.select(".slide_layer").each(function(a) {
                a.removeClassName("selected");
            });
            this.list.select(".item").each(function(a) {
                a.removeClassName("selected");
            });
            if (this.slider) this.slider.slider("disable");
        }
    },
    toggleEditStyle: function(a) {
        if (this.editStyleBtn) if (a) if (this.selectedLayer && "video" != this.selectedLayer.params.type) enableElement(this.editStyleBtn); else disableElement(this.editStyleBtn); else disableElement(this.editStyleBtn);
    },
    toogleDelete: function(a) {
        if (this.deleteBtn && this.dupLayerBtn && this.editLayerBtn) if (a) {
            enableElement(this.deleteBtn);
            enableElement(this.dupLayerBtn);
            if (this.selectedLayer && "text" != this.selectedLayer.params.type) enableElement(this.editLayerBtn); else disableElement(this.editLayerBtn);
        } else {
            disableElement(this.deleteBtn);
            disableElement(this.dupLayerBtn);
            disableElement(this.editLayerBtn);
        }
    },
    toogleCustomAnim: function(a) {
        if (this.cInAnimation && this.cOutAnimation) if (a) {
            enableElement(this.cInAnimation);
            enableElement(this.cNewInAnimation);
            enableElement(this.cOutAnimation);
            enableElement(this.cNewOutAnimation);
        } else {
            disableElement(this.cInAnimation);
            disableElement(this.cNewInAnimation);
            disableElement(this.cOutAnimation);
            disableElement(this.cNewOutAnimation);
        }
    },
    updateContainer: function() {
        var a = $("background_type");
        if (a) switch (a.value) {
          case "external":
          case "image":
            this.container.removeClassName("trans_bg");
            var b = "image" === a.value ? $("image_url") : $("bg_external");
            if (b && b.value) {
                var c = 0 === b.value.indexOf("http") ? b.value : this.mediaUrl + b.value;
                this.container.setStyle({
                    backgroundColor: "transparent",
                    backgroundImage: "url(" + c + ")"
                });
                var d = $("kenburn_effect");
                if ("on" == d.value) {
                    this.container.setStyle({
                        backgroundRepeat: "",
                        backgroundPosition: "",
                        backgroundSize: ""
                    });
                    var e = $("kb_start_fit").value, f = new Image();
                    f.onload = function() {
                        var a = this.container.getDimensions();
                        if (!a.width && !a.height) {
                            setTimeout(function() {
                                this.updateContainer();
                            }.bind(this), 500);
                            return;
                        }
                        var b = f.width, c = f.height, d = a.width / b, g = c * d, h = g / a.height * e;
                        this.container.setStyle({
                            backgroundSize: e + "% " + h + "%"
                        });
                    }.bind(this);
                    f.src = c;
                }
            } else this.container.setStyle({
                background: "transparent"
            });
            break;

          case "trans":
            this.container.addClassName("trans_bg");
            break;

          case "solid":
            this.container.removeClassName("trans_bg");
            var g = $("slide_bg_color");
            if (g) this.container.setStyle({
                backgroundImage: "",
                backgroundColor: "#" + this.cleanColor(g.value)
            });
        }
        this.updateContainerOpts();
    },
    cleanColor: function(a) {
        return 0 === a.indexOf("#") ? a.replace("#", "") : a;
    },
    updateContainerOpts: function() {
        var a = $("bg_fit"), b = $("bg_repeat"), c = $("bg_position"), d = $("kenburn_effect");
        if ("on" == d.value) return;
        if (a) switch (a.value) {
          case "percentage":
            var e = $("bg_fit_x"), f = $("bg_fit_y");
            if (e && f) this.container.setStyle({
                backgroundSize: parseInt(e.value) + "% " + parseInt(f.value) + "%"
            });
            break;

          default:
            this.container.setStyle({
                backgroundSize: a.value
            });
        }
        if (b) this.container.setStyle({
            backgroundRepeat: b.value
        });
        if (c) switch (c.value) {
          case "percentage":
            var g = $("bg_position_x"), h = $("bg_position_y");
            if (g && h) this.container.setStyle({
                backgroundPosition: parseInt(g.value) + "% " + parseInt(h.value) + "%"
            });
            break;

          default:
            this.container.setStyle({
                backgroundPosition: c.value
            });
        }
    },
    updateList: function() {
        var a = jQuery("#timeline");
        if (!a.length) return;
        a.find("span.min").html("0ms");
        a.find("span.max").html(this.delay + "ms");
        var b = this.delay, c = 0;
        this.slider = a.slider({
            range: true,
            min: c,
            max: b,
            values: [ 0, 0 ]
        });
    },
    editLayer: function() {
        if (this.selectedLayer) switch (this.selectedLayer.params.type) {
          case "image":
            var a = $("addLayerImageUrl");
            if (a) {
                var b = a.value;
                b = b + "onInsertCallbackParams/" + this.selectedLayer.params.serial;
                _MediabrowserUtility.openDialog(b, "editLayerImageWindow", null, null, Translator.translate("Add Image"));
            }
            break;

          case "video":
            var c = $("addLayerVideoUrl");
            if (c) {
                var b = c.value;
                b += "serial/" + this.selectedLayer.params.serial;
                _MediabrowserUtility.openDialog(b, "editLayerVideoWindow", null, null, Translator.translate("Add Video"));
            }
        }
    },
    deleteLayer: function() {
        if (this.selectedLayer) {
            delete this.layers[this.selectedLayer.params.serial];
            this.selectedLayer.remove();
            var a = this.getItem(this.selectedLayer.params.serial);
            if (a) a.remove();
            delete this.selectedLayer;
            this.count--;
            this.selectLayer();
            this.updateHideItemLayer();
        }
    },
    deleteAllLayers: function() {
        if (confirm(Translator.translate("Do you really want to delete all the layers?"))) {
            this.deleteLayer();
            this.container.update("");
            this.list.update("");
            this.layers = {};
            this.count = 0;
            this.selectLayer();
        }
    },
    getCssForSave: function(a) {
        var b = this.getCssFromObject(this.cssObject, true), c = {};
        b.each(function(a) {
            var b = a.split(":");
            c[b[0]] = b[1].toString().trim();
        });
        if ("object" === typeof a) Object.extend(c, a);
        if (1 == this.cssUsingHover) {
            c["hover"] = 1;
            var d = this.getCssFromObject(this.cssHover, true);
            d.each(function(a) {
                var b = a.split(":");
                c["__" + b[0]] = b[1].toString().trim();
            });
        }
        return c;
    },
    saveAsLayerCss: function(a) {
        if (this.cssCM) {
            var b = prompt(Translator.translate("Please enter new style name"));
            if (b) {
                var c = new RegExp("-?[_a-zA-Z]+[_a-zA-Z0-9-]*", "g");
                if (c.exec(b) == b) {
                    var d = false, e = $("layer_style");
                    for (var f = 0; f < e.options.length; f++) if (e.options.item(f).text == b) {
                        d = true;
                        break;
                    }
                    if (d) {
                        alert(Translator.translate("Name already existed. Try other name"));
                        this.saveAsLayerCss();
                    } else {
                        disableElement($("btnCssSaveAs"));
                        disableElement($("btnCssSave"));
                        var g = this.getCssForSave({
                            name: b
                        });
                        new Ajax.Request(this.css_save_url, {
                            method: "post",
                            parameters: g,
                            onSuccess: function(b) {
                                try {
                                    Windows.close(a);
                                    var c = b.responseText.evalJSON();
                                    if (c.success) {
                                        this.styles.set(c.name, c.id);
                                        var d = $("layer_style");
                                        d.options.add(new Option(c.name, c.id));
                                        this.selectedLayer.params["style_id"] = c.id;
                                        this.selectedLayer.params["style"] = c.name;
                                        this.selectedLayer.addClassName(c.name);
                                        this.layerParamsElm["style"].value = c.id;
                                        this.updateCssNew();
                                    }
                                } catch (e) {}
                            }.bind(this)
                        });
                    }
                } else {
                    alert(Translator.translate("Name invalid. Try again"));
                    this.saveAsLayerCss();
                }
            } else if ("" == b) {
                alert(Translator.translate("You must enter a style name"));
                this.saveAsLayerCss();
            }
        }
    },
    saveLayerCss: function(a, b) {
        if (this.cssCM) {
            disableElement($("btnCssSave"));
            var c = this.getCssForSave({
                id: b
            });
            new Ajax.Request(this.css_save_url, {
                method: "post",
                parameters: c,
                onSuccess: function(b) {
                    try {
                        Windows.close(a);
                        var c = b.responseText.evalJSON();
                        if (1 == c.success) this.updateCssNew();
                    } catch (d) {}
                }.bind(this)
            });
        }
    },
    updateCssNew: function() {
        var a = new Element("link", {
            rel: "stylesheet",
            type: "text/css",
            href: this.css_url
        });
        var b = $$("head")[0];
        b.appendChild(a);
    },
    addLayerText: function() {
        var a = {
            text: "Text " + (this.index + 1),
            type: "text",
            style: this.layerParamsElm.style.options[0].innerHTML,
            style_id: this.layerParamsElm.style.options[0].value
        };
        this.addLayer(a);
    },
    addLayerImage: function(a, b) {
        if (b) {
            var c = this.selectedLayer.params;
            c.image_url = a;
            this.updateLayerHtml(c);
        } else {
            var c = {
                style: "",
                text: "Image " + (this.index + 1),
                type: "image",
                image_url: a
            };
            this.addLayer(c);
        }
    },
    addLayerVideo: function(a) {
        if (editForm && editForm.validate()) {
            var b = this.videoData || {};
            this.videoParams.each(function(a) {
                var c = $("video_" + a);
                if (c && "checkbox" == c.readAttribute("type")) b[a] = c.checked; else if (c) b[a] = c.value.trim();
            });
            b.title = $("video_title").value;
            b.video_type = $("video_type").value;
            var c = $("video_serial"), d = {};
            if (c && c.value) {
                d = this.layers[c.value];
                Object.extend(d.video_data, b);
                d.video_type = d.video_data.video_type;
                d.video_width = d.video_data.width;
                d.video_height = d.video_data.height;
                switch (d.video_type) {
                  case "youtube":
                  case "vimeo":
                    d.video_id = d.video_data.id;
                    d.video_title = d.video_data.title;
                    d.video_image_url = d.video_data.thumb_medium.url;
                    break;

                  case "html5":
                    b.urlPoster = $("video_poster").value;
                    b.urlMp4 = $("video_mp4").value;
                    b.urlWebm = $("video_webm").value;
                    b.urlOgv = $("video_ogv").value;
                    if (!b.urlMp4 && !b.urlOgv && !b.urlWebm) {
                        alert(Translator.translate("No video source found!"));
                        return;
                    }
                    b.title = b.title || Translator.translate("HTML5 Video");
                    d.video_image_url = b.urlPoster;
                }
                Object.extend(d.video_data, b);
                d.video_args = d.video_data.args;
                d.text = d.video_data.title;
                this.checkFullWidthVideo(d);
                this.updateLayerHtml(d);
                this.updateListItem(d);
            } else {
                d.type = "video";
                d.style = "";
                d.video_type = b.video_type;
                switch (d.video_type) {
                  case "youtube":
                  case "vimeo":
                    d.video_id = b.id;
                    d.video_title = b.title;
                    d.video_image_url = b.thumb_medium.url;
                    break;

                  case "html5":
                    b.urlPoster = $("video_poster").value;
                    b.urlMp4 = $("video_mp4").value;
                    b.urlWebm = $("video_webm").value;
                    b.urlOgv = $("video_ogv").value;
                    if (!b.urlMp4 && !b.urlOgv && !b.urlWebm) {
                        alert(Translator.translate("No video source found!"));
                        return;
                    }
                    b.title = b.title || Translator.translate("HTML5 Video");
                    d.video_image_url = b.urlPoster;
                }
                d.video_width = b.width;
                d.video_height = b.height;
                d.video_data = b;
                d.video_args = b.args;
                d.text = b.title;
                this.addLayer(d);
            }
            this.videoData = null;
            Windows.close(a);
        }
    },
    onYoutubeCallback: function(a) {
        var b = a.entry;
        var c = {};
        c.id = this.lastVideoId;
        c.video_type = "youtube";
        c.title = b.title.$t;
        c.author = b.author[0].name.$t;
        c.link = b.link[0].href;
        var d = b.media$group.media$thumbnail;
        c.thumb_small = {
            url: d[0].url,
            width: d[0].width,
            height: d[0].height
        };
        c.thumb_medium = {
            url: d[1].url,
            width: d[1].width,
            height: d[1].height
        };
        c.thumb_big = {
            url: d[2].url,
            width: d[2].width,
            height: d[2].height
        };
        this.videoData = c;
        this.updateVideoView(c);
        this.updateVieoControl(c);
    },
    onVimeoCallback: function(a) {
        a = a[0];
        var b = {};
        b.video_type = "vimeo";
        b.id = a.id;
        b.title = a.title;
        b.link = a.url;
        b.author = a.user_name;
        b.thumb_large = {
            url: a.thumbnail_large,
            width: 640,
            height: 360
        };
        b.thumb_medium = {
            url: a.thumbnail_medium,
            width: 200,
            height: 150
        };
        b.thumb_small = {
            url: a.thumbnail_small,
            width: 100,
            height: 75
        };
        this.videoData = b;
        this.updateVideoView(b);
        this.updateVieoControl(b);
    },
    changeVideoType: function() {
        var a = $("videoControl");
        var b = $("videoView");
        a.hide();
        b.update("");
        $("videoLoading").hide();
    },
    onChangeVideoType: function(a) {
        if ("html5" === a.value) this.toggleVideoForm(true); else this.toggleVideoForm(false);
        $("video_src").value = "";
        this.updateVideoView(null);
    },
    onChangeVideoPoster: function(a) {
        this.updateVideoView({
            video_type: "html5",
            urlPoster: $(a).value
        });
    },
    onChangeVideoFullWidth: function(a) {
        var b = a.checked;
        "width|height".split("|").each(function(a) {
            var c = $("video_" + a);
            if (c) if (b) disableElement(c); else enableElement(c);
        });
    },
    toggleVideoForm: function(a) {
        this.videoParams.each(function(b) {
            var c = $("video_" + b);
            if (c) {
                if (a) enableElement(c); else disableElement(c);
                if (fireEvent) fireEvent(c, "change");
            }
        });
    },
    assignVideoForm: function(a) {
        if (a) {
            var b = this.layers[a].video_data;
            $("video_type").value = b.video_type;
            if (fireEvent) fireEvent($("video_type"), "change");
            switch (b.video_type) {
              case "youtube":
              case "vimeo":
                this.toggleVideoForm(true);
                break;

              case "html5":
                $("video_poster").value = b.urlPoster;
                $("video_mp4").value = b.urlMp4;
                $("video_webm").value = b.urlWebm;
                $("video_ogv").value = b.urlOgv;
            }
            this.videoParams.each(function(a) {
                var c = $("video_" + a);
                if (c) {
                    c.value = b[a];
                    c.checked = b[a];
                    if (fireEvent) fireEvent(c, "change");
                }
            }.bind(this));
            $("video_src").value = b.id;
            this.updateVideoView(b);
        }
    },
    updateVieoControl: function(a) {
        switch (a.video_type) {
          case "youtube":
            $("video_args").value = "hd=1&wmode=opaque&controls=1&showinfo=0;rel=0;";
            break;

          case "vimeo":
            $("video_args").value = "title=0&byline=0&portrait=0&api=1";
        }
        $("videoLoading").hide();
        this.toggleVideoForm(true);
    },
    updateVideoView: function(a) {
        var b = $("video_thumb_wrapper");
        var c = $("video_title");
        if (a) {
            if (a.title) c.value = a.title;
            b.update("");
            switch (a.video_type) {
              case "youtube":
              case "vimeo":
                var d = a.thumb_medium;
                var e = new Element("img", {
                    src: d.url,
                    width: d.width + "px",
                    height: d.height + "px"
                });
                e.setStyle({
                    border: "1px solid #000"
                });
                b.insert(e);
                break;

              case "html5":
                if (!a.urlPoster) return;
                var d = 0 === a.urlPoster.indexOf("http") ? a.urlPoster : this.mediaUrl + a.urlPoster;
                var e = new Element("img", {
                    src: d,
                    width: 280 + "px"
                });
                e.setStyle({
                    border: "1px solid #000"
                });
                b.insert(e);
            }
        } else {
            b.update("");
            c.value = "";
        }
    },
    onSelectHtml5Video: function(a, b) {
        Windows.close(b);
    },
    searchVideo: function() {
        var a = $("video_type").value;
        var b = $("video_src").value.trim();
        var c = this.getVideoId(a, b);
        if (c) {
            this.lastVideoId = c;
            var d = $$("head")[0];
            var e = new Element("script", {
                type: "text/javascript"
            });
            switch (a) {
              case "youtube":
                var f = "https://gdata.youtube.com/feeds/api/videos/" + c + "?v=2&alt=json-in-script&callback=DnmSl.onYoutubeCallback";
                e.src = f;
                d.appendChild(e);
                break;

              case "vimeo":
                var f = "http://vimeo.com/api/v2/video/" + c + ".json?callback=DnmSl.onVimeoCallback";
                e.src = f;
                d.appendChild(e);
            }
            setTimeout(function() {
                $("videoLoading") && $("videoLoading").hide();
            }, 5e3);
            $("videoLoading").show();
        }
    },
    getVideoId: function(a, b) {
        switch (a) {
          case "youtube":
            var c = b.split("v=")[1];
            if (c) {
                var d = c.indexOf("&");
                if (d != -1) c = c.substring(0, d);
            } else c = b;
            return c;
            break;

          case "vimeo":
            var c = b.replace(/[^0-9]+/g, "");
            return c;
        }
        return null;
    },
    duplicateLayer: function() {
        if (this.selectedLayer) {
            var a = Object.clone(this.selectedLayer.params);
            a.left += 20;
            a.top += 20;
            a.serial = void 0;
            a.time = void 0;
            a.order = this.count + 1;
            this.addLayer(a);
        }
    },
    previewSlide: function() {},
    save: function(a) {
        if (this.form && this.form.validate()) {
            var b = this.form.validator.form;
            var c = b.action;
            var d = b.serialize(true);
            if (d["transitions[]"]) {
                d["slide_transition"] = d["transitions[]"].join(",");
                delete d["transitions[]"];
            }
            d.layers = JSON.stringify(this.layers);
            new Ajax.Request(c, {
                method: "post",
                parameters: d,
                onSuccess: function(b) {
                    if (a) window.location.reload(); else if (0 === b.responseText.indexOf("http://")) window.location.href = b.responseText;
                }
            });
        }
    },
    addLayer: function(a) {
        var b = this.container.getDimensions();
        if (!b.width && !b.height) {
            setTimeout(function() {
                this.addLayer(a);
            }.bind(this), 500);
            return;
        }
        if (void 0 == a.order) a.order = this.index + 1;
        if (void 0 == a.left) a.left = 10;
        if (void 0 == a.top) a.top = 10;
        if (void 0 == a.scaleX) a.scaleX = "";
        if (void 0 == a.scaleY) a.scaleY = "";
        if (void 0 == a.whitespace) a.whitespace = "normal";
        if (void 0 == a.max_width) a.max_width = "auto";
        if (void 0 == a.max_height) a.max_height = "auto";
        if (void 0 == a.animation) a.animation = $("layer_animation").value;
        if (void 0 == a.easing) a.easing = $("layer_easing").value;
        if (void 0 == a.speed) a.speed = 500;
        if (void 0 == a.splitdelay) a.splitdelay = 10;
        if (void 0 == a.split) a.split = "none";
        if (void 0 == a.align_hor) a.align_hor = "left";
        if (void 0 == a.align_vert) a.align_vert = "top";
        if (void 0 == a.align) a.align = a.align_hor + " " + a.align_vert;
        if (void 0 == a.hiddenunder) a.hiddenunder = false;
        if (void 0 == a.resizeme) a.resizeme = true;
        if (void 0 == a.link_enable) a.link_enable = "false";
        if (void 0 == a.link_type) a.link_type = "regular";
        if (void 0 == a.link) a.link = "";
        if (void 0 == a.link_open_in) a.link_open_in = "same";
        if (void 0 == a.link_slide) a.link_slide = "nothing";
        if (void 0 == a.scrollunder_offset) a.scrollunder_offset = "";
        if (void 0 == a.style) a.style = $("layer_style").options.item(0).innerHTML;
        if (void 0 == a.style_custom) a.style_custom = "";
        if (void 0 == a.parallaxLevels) a.parallaxLevels = "rs-parallaxlevel-0";
        if (void 0 == a.time) {
            var c = 500 + 300 * this.count;
            a.time = c > this.delay ? this.delay : c;
        }
        if (void 0 == a.endtime) a.endtime = "";
        if (void 0 == a.endspeed) a.endspeed = 500;
        if (void 0 == a.endsplitdelay) a.endsplitdelay = 10;
        if (void 0 == a.endsplit) a.endsplit = "none";
        if (void 0 == a.endanimation) a.endanimation = $("layer_endanimation").value;
        if (void 0 == a.endeasing) a.endeasing = $("layer_endeasing").value;
        if (void 0 == a.corner_left) a.corner_left = $("layer_corner_left").value;
        if (void 0 == a.corner_right) a.corner_right = $("layer_corner_right").value;
        if (void 0 == a.id) a.id = "";
        if (void 0 == a.classes) a.classes = "";
        if (void 0 == a.title) a.title = "";
      //  if (void 0 == a.rel) a.rel = "";
        if (void 0 == a.alt) a.alt = "";
        a.serial = this.index + 1;
        a.top = Math.round(a.top);
        a.left = Math.round(a.left);
        a.sort = null;
        this.layers[a.serial] = a;
        this.checkFullWidthVideo(a);
        var d = this.renderLayerHtml(a);
        d.params = a;
        this.container.insert(d);
        this.bindLayerEvent(d);
        this.updateAlign(d);
        this.updateLayerHtmlCorners(a);
        this.addListItem(a);
        this.updateHideItemLayer();
        this.index++;
        this.count++;
    },
    checkFullWidthVideo: function(a) {
        if ("video" == a.type && a.video_data && true === a.video_data.fullwidth) {
            a.top = 0;
            a.left = 0;
            a.align_hor = "left";
            a.align_vert = "top";
            a.video_height = this.container.getHeight();
            a.video_width = this.container.getWidth();
            return a;
        }
    },
    getLayer: function(a) {
        return this.container.down("#slide_layer_" + a);
    },
    getItem: function(a) {
        return this.list.down("#item_" + a);
    },
    sortLayerItem: function(a, b) {
        this.list.sort = b;
        a = $(a);
        a.up().select("button").invoke("addClassName", "normal");
        a.removeClassName("normal");
        var c = [];
        for (var d in this.layers) {
            var e = this.layers[d];
            e.sort = b;
            c.push(e);
        }
        switch (b) {
          case "time":
            c.sort(function(a, b) {
                return a.time - b.time;
            });
            break;

          case "depth":
            c.sort(function(a, b) {
                return a.order - b.order;
            });
        }
        this.list.update("");
        c.each(function(a) {
            this.addListItem(a);
        }, this);
        this.selectLayer(this.selectedLayer);
        this.updateHideItemLayer();
    },
    updateHideItemLayer: function() {
        for (var a in this.layers) if (false == this.isLayerVisible(a)) this.hideLayer(a); else this.showLayer(a);
    },
    updateListItem: function(a) {
        var b = this.list.down("#item_" + a.serial);
        if (b) b.down(".name").innerHTML = this.getListItemName(a);
    },
    getListItemName: function(a) {
        var b = a.text.stripTags().escapeHTML();
        switch (a.video_type) {
          case "youtube":
            return "Youtube: " + b;
            break;

          case "vimeo":
            return "Vimeo: " + b;
            break;

          case "html5":
            return "Video: " + b;
            break;

          default:
            return b;
        }
    },
    addListItem: function(a) {
        a.sort = a.sort || this.list.sort || "depth";
        var b = new Element("div", {
            "class": "item",
            id: "item_" + a.serial,
            title: Translator.translate("Drag to change layer depth")
        });
        var c = new Element("div", {
            "class": "name"
        });
        c.innerHTML = this.getListItemName(a);
        var d = new Element("input", {
            type: "text",
            readonly: "readonly",
            "class": "input-text order validate-number",
            title: Translator.translate("Layer Depth")
        });
        d.value = a.order;
        if ("time" === a.sort) d.addClassName("fade");
        var e = new Element("input", {
            type: "text",
            "class": "input-text time validate-number",
            title: Translator.translate("Edit Start Time")
        });
        e.value = a.time;
        if ("depth" === a.sort) e.addClassName("fade");
        var f = new Element("span", {
            "class": "arrow"
        });
        f.insert("<i class='fa fa-arrows-v'></i>");
        Event.observe(e, "change", function(b) {
            var c = Event.findElement(b, "input");
            a.time = Number(c.value);
            var d = $("layer_endtime");
            this.slider.slider({
                values: [ parseInt(c.value), parseInt(d.value) ]
            });
        }.bind(this));
        var g = new Element("span", {
            "class": "eye",
            title: Translator.translate("Click to Show / Hide layer")
        });
        g.insert('<i class="fa fa-eye"></i>');
        Event.observe(g, "click", function(a) {
            var b = Event.findElement(a, "div.item");
            var c = b.params.serial;
            if (this.isLayerVisible(c)) this.hideLayer(c); else this.showLayer(c);
        }.bind(this));
        var h = new Element("div", {
            "class": "right"
        });
        h.insert(d);
        h.insert(e);
        h.insert(g);
        b.insert(f);
        b.insert(c);
        b.insert(h);
        b.params = a;
        this.list.insert(b);
        this.bindListItemEvent(b);
        this.bindListEvent(this.list);
    },
    setHideAll: function() {
        var a = $("button_sort_visibility");
        if (a.hasClassName("e-disabled")) {
            a.removeClassName("e-disabled");
            this.showAllLayers();
        } else {
            a.addClassName("e-disabled");
            this.hideAllLayers();
        }
    },
    isLayerVisible: function(a) {
        var b = this.getLayer(a);
        var c = !b.hasClassName("hide");
        return c;
    },
    isAllLayersHidden: function() {
        for (var a in this.layers) if (true == this.isLayerVisible(a)) return false;
        return true;
    },
    getLayerHidden: function() {
        var a = [];
        for (var b in this.layers) if (false == this.isLayerVisible(b)) a.push(b);
        return a;
    },
    hideLayer: function(a, b) {
        var c = this.getLayer(a);
        c.addClassName("hide");
        this.setSortboxItemHidden(a);
        if (true != b) if (this.isAllLayersHidden()) $("button_sort_visibility").addClassName("e-disabled");
    },
    hideAllLayers: function() {
        for (var a in this.layers) this.hideLayer(a, true);
    },
    showLayer: function(a, b) {
        var c = this.getLayer(a);
        c.removeClassName("hide");
        this.setSortboxItemVisible(a);
        if (true != b) $("button_sort_visibility").removeClassName("e-disabled");
    },
    showAllLayers: function() {
        for (var a in this.layers) this.showLayer(a, true);
    },
    setSortboxItemHidden: function(a) {
        var b = this.getItem(a);
        b.addClassName("hide");
    },
    setSortboxItemVisible: function(a) {
        var b = this.getItem(a);
        b.removeClassName("hide");
    },
    bindListItemEvent: function(a) {
        Event.observe(a, "click", function(a) {
            var b = Event.findElement(a, "div.item");
            var c = this.getLayer(b.params.serial);
            if (c) this.selectLayer(c);
        }.bind(this));
    },
    bindListEvent: function(a) {
        Sortable.create(a, {
            tag: "div",
            onUpdate: function() {
                this.reorderLayers();
            }.bind(this)
        });
    },
    reorderLayers: function() {
        switch (this.list.sort) {
          case "depth":
            var a = 1;
            this.list.select(".item").each(function(b) {
                var c = this.getLayer(b.params.serial);
                if (c) {
                    c.params.order = a++;
                    this.updateLayerHtml(c.params);
                    this.updateListHtml(c.params);
                }
            }, this);
            break;

          case "time":
            var b = [];
            for (var c in this.layers) b.push(this.layers[c].time);
            b.sort(function(a, b) {
                return a - b;
            });
            this.list.select(".item").each(function(a, c) {
                var d = this.layers[a.params.serial];
                d.time = b[c];
                this.updateListHtml(d);
            }, this);
        }
    },
    bindLayerEvent: function(a) {
        if (a) {
            var b = this.container.getDimensions();
            var c = a.getDimensions();
            new Draggable(a, {
                snap: [ 1, 1 ],
                change: function(d) {
                    var e = d.element;
                    var f = e.positionedOffset();
                    var g = f[1];
                    var h = f[0];
                    var i, j;
                    switch (e.params.align_hor) {
                      case "left":
                        i = h;
                        break;

                      case "right":
                        i = b.width - h - c.width;
                        break;

                      case "center":
                        i = h - (b.width - c.width) / 2;
                        i = Math.round(i);
                    }
                    switch (e.params.align_vert) {
                      case "top":
                        j = g;
                        break;

                      case "bottom":
                        j = b.height - g - c.height;
                        break;

                      case "middle":
                        j = g - (b.height - c.height) / 2;
                        j = Math.round(j);
                    }
                    this.layerParamsElm.left.value = i;
                    this.layerParamsElm.top.value = j;
                    e.params.left = i;
                    e.params.top = j;
                    a.setStyle({
                        right: "auto",
                        bottom: "auto"
                    });
                }.bind(this)
            });
            Event.observe(a, "mousedown", function(a) {
                var b = Event.findElement(a, "div.slide_layer");
                if (this.selectedLayer != b) this.selectLayer(b);
            }.bind(this));
        }
    },
    updateAlign: function(a) {
        if (a) {
            var b = a.getDimensions();
            var c = this.container.getDimensions();
            var d = {};
            if (!c.height && !c.width) {
                setTimeout(function() {
                    this.updateAlign(a);
                }.bind(this), 500);
                return;
            }
            switch (a.params.align_hor) {
              default:
              case "left":
                d.right = "auto";
                d.left = a.params.left + "px";
                break;

              case "right":
                d.left = "auto";
                d.right = a.params.left + "px";
                break;

              case "center":
                var e = Math.round((c.width - b.width) / 2) + parseInt(a.params.left);
                d.left = e + "px";
                d.right = "auto";
            }
            switch (a.params.align_vert) {
              default:
              case "top":
                d.bottom = "auto";
                d.top = a.params.top + "px";
                break;

              case "bottom":
                d.top = "auto";
                d.bottom = a.params.top + "px";
                break;

              case "middle":
                var f = Math.round((c.height - b.height) / 2) + parseInt(a.params.top);
                d.top = f + "px";
                d.bottom = "auto";
            }
            this.layerParamsElm.left.value = a.params.left;
            this.layerParamsElm.top.value = a.params.top;
            a.setStyle(d);
        }
    },
    renderLayerHtml: function(a) {
        var b = new Element("div", {
            id: "slide_layer_" + a.serial,
            "class": "slide_layer tp-caption " + a.style + " " + a.style_custom + " " + a.parallaxLevels
        });
        b.setStyle({
            zIndex: Number(a.order),
            position: "absolute"
        });
        switch (a.type) {
          case "image":
            var c = 0 === a.image_url.indexOf("http") ? a.image_url : this.mediaUrl + a.image_url;
            var d = new Element("img", {
                src: c
            });
            a.scaleX && d.setStyle({
                width: a.scaleX + "px"
            });
            a.scaleY && d.setStyle({
                height: a.scaleY + "px"
            });
            b.insert(d);
            b.addClassName("layer-img");
            break;

          case "text":
          default:
            b.innerHTML = a.text;
            break;

          case "video":
            var e = this.renderVideoLayerHtml(a);
            b.insert(e);
            b.addClassName("layer-video");
        }
        return b;
    },
    updateListHtml: function(a) {
        var b = this.getItem(a.serial);
        if (b) {
            b.down("input.order").value = a.order;
            b.down("input.time").value = a.time;
        }
    },
    updateLayerHtml: function(a) {
        var b = this.getLayer(a.serial);
        if (b) {
            b.setStyle({
                zIndex: a.order
            });
            switch (a.type) {
              case "image":
                var c = 0 === a.image_url.indexOf("http") ? a.image_url : this.mediaUrl + a.image_url;
                b.down("img").src = c;
                setTimeout(function() {
                    this.updateAlign(b);
                }.bind(this), 100);
                break;

              case "video":
                var d = this.renderVideoLayerHtml(a);
                b.update(d);
                setTimeout(function() {
                    this.updateAlign(b);
                }.bind(this), 100);
            }
        }
    },
    updateLayerHtmlScale: function(a) {
        var b = this.getLayer(a.serial), c = b.down("img");
        if (c) c.setStyle({
            width: a.scaleX + "px",
            height: a.scaleY + "px"
        });
    },
    updateLayerHtmlCorners: function(a) {
        var b = this.getLayer(a.serial), c = b.offsetHeight, d = b.getStyle("backgroundColor");
        if (b.down(".frontcorner")) b.down(".frontcorner").remove();
        if (b.down(".frontcornertop")) b.down(".frontcornertop").remove();
        switch (a.corner_left) {
          case "curved":
            if (!b.down(".frontcorner")) b.insert({
                bottom: '<div class="frontcorner"></div>'
            });
            break;

          case "reverced":
            if (!b.down(".frontcornertop")) b.insert({
                bottom: '<div class="frontcornertop"></div>'
            });
        }
        if (b.down(".backcorner")) b.down(".backcorner").remove();
        if (b.down(".backcornertop")) b.down(".backcornertop").remove();
        switch (a.corner_right) {
          case "curved":
            if (!b.down(".backcorner")) b.insert({
                bottom: '<div class="backcorner"></div>'
            });
            break;

          case "reverced":
            if (!b.down(".backcornertop")) b.insert({
                bottom: '<div class="backcornertop"></div>'
            });
        }
        b.down(".frontcorner") && b.down(".frontcorner").setStyle({
            borderWidth: c + "px",
            left: 0 - c + "px",
            borderRight: "0px solid transparent",
            borderTopColor: d
        });
        b.down(".frontcornertop") && b.down(".frontcornertop").setStyle({
            borderWidth: c + "px",
            left: 0 - c + "px",
            borderRight: "0px solid transparent",
            borderBottomColor: d
        });
        b.down(".backcorner") && b.down(".backcorner").setStyle({
            borderWidth: c + "px",
            right: 0 - c + "px",
            borderLeft: "0px solid transparent",
            borderBottomColor: d
        });
        b.down(".backcornertop") && b.down(".backcornertop").setStyle({
            borderWidth: c + "px",
            right: 0 - c + "px",
            borderLeft: "0px solid transparent",
            borderTopColor: d
        });
    },
    renderVideoLayerHtml: function(a) {
        if (a) {
            var b = {
                width: a.video_width + "px",
                height: a.video_height + "px"
            };
            if (a.video_image_url) b.backgroundImage = "url(" + a.video_image_url + ")"; else b.backgroundColor = "#000";
            var c = new Element("div", {
                "class": "slide_layer_video"
            });
            c.setStyle(b);
            switch (a.video_type) {
              case "html5":
                if (!a.video_image_url) {
                    var d = new Element("span");
                    d.update(a.text);
                    c.update(d);
                }
            }
            return c;
        }
        return null;
    }
};