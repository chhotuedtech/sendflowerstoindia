CodeMirror.defineMode("css", function(a, b) {
    "use strict";
    if (!b.propertyKeywords) b = CodeMirror.resolveMode("text/css");
    var c = a.indentUnit || a.tabSize || 2, d = b.hooks || {}, e = b.atMediaTypes || {}, f = b.atMediaFeatures || {}, g = b.propertyKeywords || {}, h = b.colorKeywords || {}, i = b.valueKeywords || {}, j = !!b.allowNested, k = null;
    function l(a, b) {
        k = b;
        return a;
    }
    function m(a, b) {
        var c = a.next();
        if (d[c]) {
            var e = d[c](a, b);
            if (false !== e) return e;
        }
        if ("@" == c) {
            a.eatWhile(/[\w\\\-]/);
            return l("def", a.current());
        } else if ("=" == c) l(null, "compare"); else if (("~" == c || "|" == c) && a.eat("=")) return l(null, "compare"); else if ('"' == c || "'" == c) {
            b.tokenize = n(c);
            return b.tokenize(a, b);
        } else if ("#" == c) {
            a.eatWhile(/[\w\\\-]/);
            return l("atom", "hash");
        } else if ("!" == c) {
            a.match(/^\s*\w*/);
            return l("keyword", "important");
        } else if (/\d/.test(c) || "." == c && a.eat(/\d/)) {
            a.eatWhile(/[\w.%]/);
            return l("number", "unit");
        } else if ("-" === c) {
            if (/\d/.test(a.peek())) {
                a.eatWhile(/[\w.%]/);
                return l("number", "unit");
            } else if (a.match(/^[^-]+-/)) return l("meta", "meta");
        } else if (/[,+>*\/]/.test(c)) return l(null, "select-op"); else if ("." == c && a.match(/^-?[_a-z][_a-z0-9-]*/i)) return l("qualifier", "qualifier"); else if (":" == c) return l("operator", c); else if (/[;{}\[\]\(\)]/.test(c)) return l(null, c); else if ("u" == c && a.match("rl(")) {
            a.backUp(1);
            b.tokenize = o;
            return l("property", "variable");
        } else {
            a.eatWhile(/[\w\\\-]/);
            return l("property", "variable");
        }
    }
    function n(a, b) {
        return function(c, d) {
            var e = false, f;
            while (null != (f = c.next())) {
                if (f == a && !e) break;
                e = !e && "\\" == f;
            }
            if (!e) {
                if (b) c.backUp(1);
                d.tokenize = m;
            }
            return l("string", "string");
        };
    }
    function o(a, b) {
        a.next();
        if (!a.match(/\s*[\"\']/, false)) b.tokenize = n(")", true); else b.tokenize = m;
        return l(null, "(");
    }
    return {
        startState: function(a) {
            return {
                tokenize: m,
                baseIndent: a || 0,
                stack: [],
                lastToken: null
            };
        },
        token: function(a, b) {
            b.tokenize = b.tokenize || m;
            if (b.tokenize == m && a.eatSpace()) return null;
            var c = b.tokenize(a, b);
            if (c && "string" != typeof c) c = l(c[0], c[1]);
            var d = b.stack[b.stack.length - 1];
            if ("variable" == c) {
                if ("variable-definition" == k) b.stack.push("propertyValue");
                return b.lastToken = "variable-2";
            } else if ("property" == c) {
                var n = a.current().toLowerCase();
                if ("propertyValue" == d) if (i.hasOwnProperty(n)) c = "string-2"; else if (h.hasOwnProperty(n)) c = "keyword"; else c = "variable-2"; else if ("rule" == d) {
                    if (!g.hasOwnProperty(n)) c += " error";
                } else if ("block" == d) if (g.hasOwnProperty(n)) c = "property"; else if (h.hasOwnProperty(n)) c = "keyword"; else if (i.hasOwnProperty(n)) c = "string-2"; else c = "tag"; else if (!d || "@media{" == d) c = "tag"; else if ("@media" == d) if (e[a.current()]) c = "attribute"; else if (/^(only|not)$/.test(n)) c = "keyword"; else if ("and" == n) c = "error"; else if (f.hasOwnProperty(n)) c = "error"; else c = "attribute error"; else if ("@mediaType" == d) if (e.hasOwnProperty(n)) c = "attribute"; else if ("and" == n) c = "operator"; else if (/^(only|not)$/.test(n)) c = "error"; else c = "error"; else if ("@mediaType(" == d) if (g.hasOwnProperty(n)) ; else if (e.hasOwnProperty(n)) c = "error"; else if ("and" == n) c = "operator"; else if (/^(only|not)$/.test(n)) c = "error"; else c += " error"; else if ("@import" == d) c = "tag"; else c = "error";
            } else if ("atom" == c) if (!d || "@media{" == d || "block" == d) c = "builtin"; else if ("propertyValue" == d) {
                if (!/^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/.test(a.current())) c += " error";
            } else c = "error"; else if ("@media" == d && "{" == k) c = "error";
            if ("{" == k) if ("@media" == d || "@mediaType" == d) b.stack[b.stack.length - 1] = "@media{"; else {
                var o = j ? "block" : "rule";
                b.stack.push(o);
            } else if ("}" == k) {
                if ("interpolation" == d) c = "operator";
                while (b.stack.length) {
                    var p = b.stack.pop();
                    if (p.indexOf("{") > -1 || "block" == p || "rule" == p) break;
                }
            } else if ("interpolation" == k) b.stack.push("interpolation"); else if ("@media" == k) b.stack.push("@media"); else if ("@import" == k) b.stack.push("@import"); else if ("@media" == d && /\b(keyword|attribute)\b/.test(c)) b.stack[b.stack.length - 1] = "@mediaType"; else if ("@mediaType" == d && "," == a.current()) b.stack[b.stack.length - 1] = "@media"; else if ("(" == k) if ("@media" == d || "@mediaType" == d) {
                b.stack[b.stack.length - 1] = "@mediaType";
                b.stack.push("@mediaType(");
            } else b.stack.push("("); else if (")" == k) while (b.stack.length) {
                var p = b.stack.pop();
                if (p.indexOf("(") > -1) break;
            } else if (":" == k && "property" == b.lastToken) b.stack.push("propertyValue"); else if ("propertyValue" == d && ";" == k) b.stack.pop(); else if ("@import" == d && ";" == k) b.stack.pop();
            return b.lastToken = c;
        },
        indent: function(a, b) {
            var d = a.stack.length;
            if (/^\}/.test(b)) d -= "propertyValue" == a.stack[d - 1] ? 2 : 1;
            return a.baseIndent + d * c;
        },
        electricChars: "}",
        blockCommentStart: "/*",
        blockCommentEnd: "*/",
        fold: "brace"
    };
});

!function() {
    function a(a) {
        var b = {};
        for (var c = 0; c < a.length; ++c) b[a[c]] = true;
        return b;
    }
    var b = a([ "all", "aural", "braille", "handheld", "print", "projection", "screen", "tty", "tv", "embossed" ]);
    var c = a([ "width", "min-width", "max-width", "height", "min-height", "max-height", "device-width", "min-device-width", "max-device-width", "device-height", "min-device-height", "max-device-height", "aspect-ratio", "min-aspect-ratio", "max-aspect-ratio", "device-aspect-ratio", "min-device-aspect-ratio", "max-device-aspect-ratio", "color", "min-color", "max-color", "color-index", "min-color-index", "max-color-index", "monochrome", "min-monochrome", "max-monochrome", "resolution", "min-resolution", "max-resolution", "scan", "grid" ]);
    var d = a([ "align-content", "align-items", "align-self", "alignment-adjust", "alignment-baseline", "anchor-point", "animation", "animation-delay", "animation-direction", "animation-duration", "animation-iteration-count", "animation-name", "animation-play-state", "animation-timing-function", "appearance", "azimuth", "backface-visibility", "background", "background-attachment", "background-clip", "background-color", "background-image", "background-origin", "background-position", "background-repeat", "background-size", "baseline-shift", "binding", "bleed", "bookmark-label", "bookmark-level", "bookmark-state", "bookmark-target", "border", "border-bottom", "border-bottom-color", "border-bottom-left-radius", "border-bottom-right-radius", "border-bottom-style", "border-bottom-width", "border-collapse", "border-color", "border-image", "border-image-outset", "border-image-repeat", "border-image-slice", "border-image-source", "border-image-width", "border-left", "border-left-color", "border-left-style", "border-left-width", "border-radius", "border-right", "border-right-color", "border-right-style", "border-right-width", "border-spacing", "border-style", "border-top", "border-top-color", "border-top-left-radius", "border-top-right-radius", "border-top-style", "border-top-width", "border-width", "bottom", "box-decoration-break", "box-shadow", "box-sizing", "break-after", "break-before", "break-inside", "caption-side", "clear", "clip", "color", "color-profile", "column-count", "column-fill", "column-gap", "column-rule", "column-rule-color", "column-rule-style", "column-rule-width", "column-span", "column-width", "columns", "content", "counter-increment", "counter-reset", "crop", "cue", "cue-after", "cue-before", "cursor", "direction", "display", "dominant-baseline", "drop-initial-after-adjust", "drop-initial-after-align", "drop-initial-before-adjust", "drop-initial-before-align", "drop-initial-size", "drop-initial-value", "elevation", "empty-cells", "fit", "fit-position", "flex", "flex-basis", "flex-direction", "flex-flow", "flex-grow", "flex-shrink", "flex-wrap", "float", "float-offset", "flow-from", "flow-into", "font", "font-feature-settings", "font-family", "font-kerning", "font-language-override", "font-size", "font-size-adjust", "font-stretch", "font-style", "font-synthesis", "font-variant", "font-variant-alternates", "font-variant-caps", "font-variant-east-asian", "font-variant-ligatures", "font-variant-numeric", "font-variant-position", "font-weight", "grid-cell", "grid-column", "grid-column-align", "grid-column-sizing", "grid-column-span", "grid-columns", "grid-flow", "grid-row", "grid-row-align", "grid-row-sizing", "grid-row-span", "grid-rows", "grid-template", "hanging-punctuation", "height", "hyphens", "icon", "image-orientation", "image-rendering", "image-resolution", "inline-box-align", "justify-content", "left", "letter-spacing", "line-break", "line-height", "line-stacking", "line-stacking-ruby", "line-stacking-shift", "line-stacking-strategy", "list-style", "list-style-image", "list-style-position", "list-style-type", "margin", "margin-bottom", "margin-left", "margin-right", "margin-top", "marker-offset", "marks", "marquee-direction", "marquee-loop", "marquee-play-count", "marquee-speed", "marquee-style", "max-height", "max-width", "min-height", "min-width", "move-to", "nav-down", "nav-index", "nav-left", "nav-right", "nav-up", "opacity", "order", "orphans", "outline", "outline-color", "outline-offset", "outline-style", "outline-width", "overflow", "overflow-style", "overflow-wrap", "overflow-x", "overflow-y", "padding", "padding-bottom", "padding-left", "padding-right", "padding-top", "page", "page-break-after", "page-break-before", "page-break-inside", "page-policy", "pause", "pause-after", "pause-before", "perspective", "perspective-origin", "pitch", "pitch-range", "play-during", "position", "presentation-level", "punctuation-trim", "quotes", "region-break-after", "region-break-before", "region-break-inside", "region-fragment", "rendering-intent", "resize", "rest", "rest-after", "rest-before", "richness", "right", "rotation", "rotation-point", "ruby-align", "ruby-overhang", "ruby-position", "ruby-span", "shape-inside", "shape-outside", "size", "speak", "speak-as", "speak-header", "speak-numeral", "speak-punctuation", "speech-rate", "stress", "string-set", "tab-size", "table-layout", "target", "target-name", "target-new", "target-position", "text-align", "text-align-last", "text-decoration", "text-decoration-color", "text-decoration-line", "text-decoration-skip", "text-decoration-style", "text-emphasis", "text-emphasis-color", "text-emphasis-position", "text-emphasis-style", "text-height", "text-indent", "text-justify", "text-outline", "text-overflow", "text-shadow", "text-size-adjust", "text-space-collapse", "text-transform", "text-underline-position", "text-wrap", "top", "transform", "transform-origin", "transform-style", "transition", "transition-delay", "transition-duration", "transition-property", "transition-timing-function", "unicode-bidi", "vertical-align", "visibility", "voice-balance", "voice-duration", "voice-family", "voice-pitch", "voice-range", "voice-rate", "voice-stress", "voice-volume", "volume", "white-space", "widows", "width", "word-break", "word-spacing", "word-wrap", "z-index", "zoom", "clip-path", "clip-rule", "mask", "enable-background", "filter", "flood-color", "flood-opacity", "lighting-color", "stop-color", "stop-opacity", "pointer-events", "color-interpolation", "color-interpolation-filters", "color-profile", "color-rendering", "fill", "fill-opacity", "fill-rule", "image-rendering", "marker", "marker-end", "marker-mid", "marker-start", "shape-rendering", "stroke", "stroke-dasharray", "stroke-dashoffset", "stroke-linecap", "stroke-linejoin", "stroke-miterlimit", "stroke-opacity", "stroke-width", "text-rendering", "baseline-shift", "dominant-baseline", "glyph-orientation-horizontal", "glyph-orientation-vertical", "kerning", "text-anchor", "writing-mode" ]);
    var e = a([ "aliceblue", "antiquewhite", "aqua", "aquamarine", "azure", "beige", "bisque", "black", "blanchedalmond", "blue", "blueviolet", "brown", "burlywood", "cadetblue", "chartreuse", "chocolate", "coral", "cornflowerblue", "cornsilk", "crimson", "cyan", "darkblue", "darkcyan", "darkgoldenrod", "darkgray", "darkgreen", "darkkhaki", "darkmagenta", "darkolivegreen", "darkorange", "darkorchid", "darkred", "darksalmon", "darkseagreen", "darkslateblue", "darkslategray", "darkturquoise", "darkviolet", "deeppink", "deepskyblue", "dimgray", "dodgerblue", "firebrick", "floralwhite", "forestgreen", "fuchsia", "gainsboro", "ghostwhite", "gold", "goldenrod", "gray", "grey", "green", "greenyellow", "honeydew", "hotpink", "indianred", "indigo", "ivory", "khaki", "lavender", "lavenderblush", "lawngreen", "lemonchiffon", "lightblue", "lightcoral", "lightcyan", "lightgoldenrodyellow", "lightgray", "lightgreen", "lightpink", "lightsalmon", "lightseagreen", "lightskyblue", "lightslategray", "lightsteelblue", "lightyellow", "lime", "limegreen", "linen", "magenta", "maroon", "mediumaquamarine", "mediumblue", "mediumorchid", "mediumpurple", "mediumseagreen", "mediumslateblue", "mediumspringgreen", "mediumturquoise", "mediumvioletred", "midnightblue", "mintcream", "mistyrose", "moccasin", "navajowhite", "navy", "oldlace", "olive", "olivedrab", "orange", "orangered", "orchid", "palegoldenrod", "palegreen", "paleturquoise", "palevioletred", "papayawhip", "peachpuff", "peru", "pink", "plum", "powderblue", "purple", "red", "rosybrown", "royalblue", "saddlebrown", "salmon", "sandybrown", "seagreen", "seashell", "sienna", "silver", "skyblue", "slateblue", "slategray", "snow", "springgreen", "steelblue", "tan", "teal", "thistle", "tomato", "turquoise", "violet", "wheat", "white", "whitesmoke", "yellow", "yellowgreen" ]);
    var f = a([ "above", "absolute", "activeborder", "activecaption", "afar", "after-white-space", "ahead", "alias", "all", "all-scroll", "alternate", "always", "amharic", "amharic-abegede", "antialiased", "appworkspace", "arabic-indic", "armenian", "asterisks", "auto", "avoid", "avoid-column", "avoid-page", "avoid-region", "background", "backwards", "baseline", "below", "bidi-override", "binary", "bengali", "blink", "block", "block-axis", "bold", "bolder", "border", "border-box", "both", "bottom", "break", "break-all", "break-word", "button", "button-bevel", "buttonface", "buttonhighlight", "buttonshadow", "buttontext", "cambodian", "capitalize", "caps-lock-indicator", "caption", "captiontext", "caret", "cell", "center", "checkbox", "circle", "cjk-earthly-branch", "cjk-heavenly-stem", "cjk-ideographic", "clear", "clip", "close-quote", "col-resize", "collapse", "column", "compact", "condensed", "contain", "content", "content-box", "context-menu", "continuous", "copy", "cover", "crop", "cross", "crosshair", "currentcolor", "cursive", "dashed", "decimal", "decimal-leading-zero", "default", "default-button", "destination-atop", "destination-in", "destination-out", "destination-over", "devanagari", "disc", "discard", "document", "dot-dash", "dot-dot-dash", "dotted", "double", "down", "e-resize", "ease", "ease-in", "ease-in-out", "ease-out", "element", "ellipse", "ellipsis", "embed", "end", "ethiopic", "ethiopic-abegede", "ethiopic-abegede-am-et", "ethiopic-abegede-gez", "ethiopic-abegede-ti-er", "ethiopic-abegede-ti-et", "ethiopic-halehame-aa-er", "ethiopic-halehame-aa-et", "ethiopic-halehame-am-et", "ethiopic-halehame-gez", "ethiopic-halehame-om-et", "ethiopic-halehame-sid-et", "ethiopic-halehame-so-et", "ethiopic-halehame-ti-er", "ethiopic-halehame-ti-et", "ethiopic-halehame-tig", "ew-resize", "expanded", "extra-condensed", "extra-expanded", "fantasy", "fast", "fill", "fixed", "flat", "footnotes", "forwards", "from", "geometricPrecision", "georgian", "graytext", "groove", "gujarati", "gurmukhi", "hand", "hangul", "hangul-consonant", "hebrew", "help", "hidden", "hide", "higher", "highlight", "highlighttext", "hiragana", "hiragana-iroha", "horizontal", "hsl", "hsla", "icon", "ignore", "inactiveborder", "inactivecaption", "inactivecaptiontext", "infinite", "infobackground", "infotext", "inherit", "initial", "inline", "inline-axis", "inline-block", "inline-table", "inset", "inside", "intrinsic", "invert", "italic", "justify", "kannada", "katakana", "katakana-iroha", "keep-all", "khmer", "landscape", "lao", "large", "larger", "left", "level", "lighter", "line-through", "linear", "lines", "list-item", "listbox", "listitem", "local", "logical", "loud", "lower", "lower-alpha", "lower-armenian", "lower-greek", "lower-hexadecimal", "lower-latin", "lower-norwegian", "lower-roman", "lowercase", "ltr", "malayalam", "match", "media-controls-background", "media-current-time-display", "media-fullscreen-button", "media-mute-button", "media-play-button", "media-return-to-realtime-button", "media-rewind-button", "media-seek-back-button", "media-seek-forward-button", "media-slider", "media-sliderthumb", "media-time-remaining-display", "media-volume-slider", "media-volume-slider-container", "media-volume-sliderthumb", "medium", "menu", "menulist", "menulist-button", "menulist-text", "menulist-textfield", "menutext", "message-box", "middle", "min-intrinsic", "mix", "mongolian", "monospace", "move", "multiple", "myanmar", "n-resize", "narrower", "ne-resize", "nesw-resize", "no-close-quote", "no-drop", "no-open-quote", "no-repeat", "none", "normal", "not-allowed", "nowrap", "ns-resize", "nw-resize", "nwse-resize", "oblique", "octal", "open-quote", "optimizeLegibility", "optimizeSpeed", "oriya", "oromo", "outset", "outside", "outside-shape", "overlay", "overline", "padding", "padding-box", "painted", "page", "paused", "persian", "plus-darker", "plus-lighter", "pointer", "polygon", "portrait", "pre", "pre-line", "pre-wrap", "preserve-3d", "progress", "push-button", "radio", "read-only", "read-write", "read-write-plaintext-only", "rectangle", "region", "relative", "repeat", "repeat-x", "repeat-y", "reset", "reverse", "rgb", "rgba", "ridge", "right", "round", "row-resize", "rtl", "run-in", "running", "s-resize", "sans-serif", "scroll", "scrollbar", "se-resize", "searchfield", "searchfield-cancel-button", "searchfield-decoration", "searchfield-results-button", "searchfield-results-decoration", "semi-condensed", "semi-expanded", "separate", "serif", "show", "sidama", "single", "skip-white-space", "slide", "slider-horizontal", "slider-vertical", "sliderthumb-horizontal", "sliderthumb-vertical", "slow", "small", "small-caps", "small-caption", "smaller", "solid", "somali", "source-atop", "source-in", "source-out", "source-over", "space", "square", "square-button", "start", "static", "status-bar", "stretch", "stroke", "sub", "subpixel-antialiased", "super", "sw-resize", "table", "table-caption", "table-cell", "table-column", "table-column-group", "table-footer-group", "table-header-group", "table-row", "table-row-group", "telugu", "text", "text-bottom", "text-top", "textarea", "textfield", "thai", "thick", "thin", "threeddarkshadow", "threedface", "threedhighlight", "threedlightshadow", "threedshadow", "tibetan", "tigre", "tigrinya-er", "tigrinya-er-abegede", "tigrinya-et", "tigrinya-et-abegede", "to", "top", "transparent", "ultra-condensed", "ultra-expanded", "underline", "up", "upper-alpha", "upper-armenian", "upper-greek", "upper-hexadecimal", "upper-latin", "upper-norwegian", "upper-roman", "uppercase", "urdu", "url", "vertical", "vertical-text", "visible", "visibleFill", "visiblePainted", "visibleStroke", "visual", "w-resize", "wait", "wave", "wider", "window", "windowframe", "windowtext", "x-large", "x-small", "xor", "xx-large", "xx-small" ]);
    function g(a, b) {
        var c = false, d;
        while (null != (d = a.next())) {
            if (c && "/" == d) {
                b.tokenize = null;
                break;
            }
            c = "*" == d;
        }
        return [ "comment", "comment" ];
    }
    CodeMirror.defineMIME("text/css", {
        atMediaTypes: b,
        atMediaFeatures: c,
        propertyKeywords: d,
        colorKeywords: e,
        valueKeywords: f,
        hooks: {
            "<": function(a, b) {
                function c(a, b) {
                    var c = 0, d;
                    while (null != (d = a.next())) {
                        if (c >= 2 && ">" == d) {
                            b.tokenize = null;
                            break;
                        }
                        c = "-" == d ? c + 1 : 0;
                    }
                    return [ "comment", "comment" ];
                }
                if (a.eat("!")) {
                    b.tokenize = c;
                    return c(a, b);
                }
            },
            "/": function(a, b) {
                if (a.eat("*")) {
                    b.tokenize = g;
                    return g(a, b);
                }
                return false;
            }
        },
        name: "css"
    });
    CodeMirror.defineMIME("text/x-scss", {
        atMediaTypes: b,
        atMediaFeatures: c,
        propertyKeywords: d,
        colorKeywords: e,
        valueKeywords: f,
        allowNested: true,
        hooks: {
            ":": function(a) {
                if (a.match(/\s*{/)) return [ null, "{" ];
                return false;
            },
            $: function(a) {
                a.match(/^[\w-]+/);
                if (":" == a.peek()) return [ "variable", "variable-definition" ];
                return [ "variable", "variable" ];
            },
            ",": function(a, b) {
                if ("propertyValue" == b.stack[b.stack.length - 1] && a.match(/^ *\$/, false)) return [ "operator", ";" ];
            },
            "/": function(a, b) {
                if (a.eat("/")) {
                    a.skipToEnd();
                    return [ "comment", "comment" ];
                } else if (a.eat("*")) {
                    b.tokenize = g;
                    return g(a, b);
                } else return [ "operator", "operator" ];
            },
            "#": function(a) {
                if (a.eat("{")) return [ "operator", "interpolation" ]; else {
                    a.eatWhile(/[\w\\\-]/);
                    return [ "atom", "hash" ];
                }
            }
        },
        name: "css"
    });
}();