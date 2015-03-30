// CodeMirror version 3.20
//
// CodeMirror is the only global var we claim
window.CodeMirror = function() {
    "use strict";
    var a = /gecko\/\d/i.test(navigator.userAgent);
    var b = /MSIE \d/.test(navigator.userAgent);
    var c = b && (null == document.documentMode || document.documentMode < 8);
    var d = b && (null == document.documentMode || document.documentMode < 9);
    var e = /Trident\/([7-9]|\d{2,})\./.test(navigator.userAgent);
    var f = /WebKit\//.test(navigator.userAgent);
    var g = f && /Qt\/\d+\.\d+/.test(navigator.userAgent);
    var h = /Chrome\//.test(navigator.userAgent);
    var i = /Opera\//.test(navigator.userAgent);
    var j = /Apple Computer/.test(navigator.vendor);
    var k = /KHTML\//.test(navigator.userAgent);
    var l = /Mac OS X 1\d\D([7-9]|\d\d)\D/.test(navigator.userAgent);
    var m = /Mac OS X 1\d\D([8-9]|\d\d)\D/.test(navigator.userAgent);
    var n = /PhantomJS/.test(navigator.userAgent);
    var o = /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent);
    var p = o || /Android|webOS|BlackBerry|Opera Mini|Opera Mobi|IEMobile/i.test(navigator.userAgent);
    var q = o || /Mac/.test(navigator.platform);
    var r = /win/i.test(navigator.platform);
    var s = i && navigator.userAgent.match(/Version\/(\d*\.\d*)/);
    if (s) s = Number(s[1]);
    if (s && s >= 15) {
        i = false;
        f = true;
    }
    var t = q && (g || i && (null == s || s < 12.11));
    var u = a || b && !d;
    var v = false, w = false;
    function x(a, c) {
        if (!(this instanceof x)) return new x(a, c);
        this.options = c = c || {};
        for (var d in _c) if (!c.hasOwnProperty(d) && _c.hasOwnProperty(d)) c[d] = _c[d];
        J(c);
        var e = "string" == typeof c.value ? 0 : c.value.first;
        var f = this.display = y(a, e);
        f.wrapper.CodeMirror = this;
        G(this);
        if (c.autofocus && !p) Nb(this);
        this.state = {
            keyMaps: [],
            overlays: [],
            modeGen: 0,
            overwrite: false,
            focused: false,
            suppressEdits: false,
            pasteIncoming: false,
            draggingText: false,
            highlight: new Xe()
        };
        E(this);
        if (c.lineWrapping) this.display.wrapper.className += " CodeMirror-wrap";
        var g = c.value;
        if ("string" == typeof g) g = new ge(c.value, c.mode);
        Fb(this, ke)(this, g);
        if (b) setTimeout(ff(Mb, this, true), 20);
        Pb(this);
        var h;
        try {
            h = document.activeElement == f.input;
        } catch (i) {}
        if (h || c.autofocus && !p) setTimeout(ff(mc, this), 20); else nc(this);
        Fb(this, function() {
            for (var a in $c) if ($c.propertyIsEnumerable(a)) $c[a](this, c[a], bd);
            for (var b = 0; b < fd.length; ++b) fd[b](this);
        })();
    }
    function y(a, b) {
        var d = {};
        var e = d.input = lf("textarea", null, null, "position: absolute; padding: 0; width: 1px; height: 1em; outline: none; font-size: 4px;");
        if (f) e.style.width = "1000px"; else e.setAttribute("wrap", "off");
        if (o) e.style.border = "1px solid black";
        e.setAttribute("autocorrect", "off");
        e.setAttribute("autocapitalize", "off");
        e.setAttribute("spellcheck", "false");
        d.inputDiv = lf("div", [ e ], null, "overflow: hidden; position: relative; width: 3px; height: 0px;");
        d.scrollbarH = lf("div", [ lf("div", null, null, "height: 1px") ], "CodeMirror-hscrollbar");
        d.scrollbarV = lf("div", [ lf("div", null, null, "width: 1px") ], "CodeMirror-vscrollbar");
        d.scrollbarFiller = lf("div", null, "CodeMirror-scrollbar-filler");
        d.gutterFiller = lf("div", null, "CodeMirror-gutter-filler");
        d.lineDiv = lf("div", null, "CodeMirror-code");
        d.selectionDiv = lf("div", null, null, "position: relative; z-index: 1");
        d.cursor = lf("div", " ", "CodeMirror-cursor");
        d.otherCursor = lf("div", " ", "CodeMirror-cursor CodeMirror-secondarycursor");
        d.measure = lf("div", null, "CodeMirror-measure");
        d.lineSpace = lf("div", [ d.measure, d.selectionDiv, d.lineDiv, d.cursor, d.otherCursor ], null, "position: relative; outline: none");
        d.mover = lf("div", [ lf("div", [ d.lineSpace ], "CodeMirror-lines") ], null, "position: relative");
        d.sizer = lf("div", [ d.mover ], "CodeMirror-sizer");
        d.heightForcer = lf("div", null, null, "position: absolute; height: " + Ve + "px; width: 1px;");
        d.gutters = lf("div", null, "CodeMirror-gutters");
        d.lineGutter = null;
        d.scroller = lf("div", [ d.sizer, d.heightForcer, d.gutters ], "CodeMirror-scroll");
        d.scroller.setAttribute("tabIndex", "-1");
        d.wrapper = lf("div", [ d.inputDiv, d.scrollbarH, d.scrollbarV, d.scrollbarFiller, d.gutterFiller, d.scroller ], "CodeMirror");
        if (c) {
            d.gutters.style.zIndex = -1;
            d.scroller.style.paddingRight = 0;
        }
        if (a.appendChild) a.appendChild(d.wrapper); else a(d.wrapper);
        if (o) e.style.width = "0px";
        if (!f) d.scroller.draggable = true;
        if (k) {
            d.inputDiv.style.height = "1px";
            d.inputDiv.style.position = "absolute";
        } else if (c) d.scrollbarH.style.minWidth = d.scrollbarV.style.minWidth = "18px";
        d.viewOffset = d.lastSizeC = 0;
        d.showingFrom = d.showingTo = b;
        d.lineNumWidth = d.lineNumInnerWidth = d.lineNumChars = null;
        d.prevInput = "";
        d.alignWidgets = false;
        d.pollingFast = false;
        d.poll = new Xe();
        d.cachedCharWidth = d.cachedTextHeight = null;
        d.measureLineCache = [];
        d.measureLineCachePos = 0;
        d.inaccurateSelection = false;
        d.maxLine = null;
        d.maxLineLength = 0;
        d.maxLineChanged = false;
        d.wheelDX = d.wheelDY = d.wheelStartX = d.wheelStartY = null;
        return d;
    }
    function z(a) {
        a.doc.mode = x.getMode(a.options, a.doc.modeOption);
        a.doc.iter(function(a) {
            if (a.stateAfter) a.stateAfter = null;
            if (a.styles) a.styles = null;
        });
        a.doc.frontier = a.doc.first;
        bb(a, 100);
        a.state.modeGen++;
        if (a.curOp) Ib(a);
    }
    function A(a) {
        if (a.options.lineWrapping) {
            a.display.wrapper.className += " CodeMirror-wrap";
            a.display.sizer.style.minWidth = "";
        } else {
            a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-wrap", "");
            I(a);
        }
        C(a);
        Ib(a);
        pb(a);
        setTimeout(function() {
            K(a);
        }, 100);
    }
    function B(a) {
        var b = Ab(a.display), c = a.options.lineWrapping;
        var d = c && Math.max(5, a.display.scroller.clientWidth / Bb(a.display) - 3);
        return function(e) {
            if (Gd(a.doc, e)) return 0; else if (c) return (Math.ceil(e.text.length / d) || 1) * b; else return b;
        };
    }
    function C(a) {
        var b = a.doc, c = B(a);
        b.iter(function(a) {
            var b = c(a);
            if (b != a.height) oe(a, b);
        });
    }
    function D(a) {
        var b = kd[a.options.keyMap], c = b.style;
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-keymap-\S+/g, "") + (c ? " cm-keymap-" + c : "");
        a.state.disableInput = b.disableInput;
    }
    function E(a) {
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-s-\S+/g, "") + a.options.theme.replace(/(^|\s)\s*/g, " cm-s-");
        pb(a);
    }
    function F(a) {
        G(a);
        Ib(a);
        setTimeout(function() {
            M(a);
        }, 20);
    }
    function G(a) {
        var b = a.display.gutters, c = a.options.gutters;
        mf(b);
        for (var d = 0; d < c.length; ++d) {
            var e = c[d];
            var f = b.appendChild(lf("div", null, "CodeMirror-gutter " + e));
            if ("CodeMirror-linenumbers" == e) {
                a.display.lineGutter = f;
                f.style.width = (a.display.lineNumWidth || 1) + "px";
            }
        }
        b.style.display = d ? "" : "none";
    }
    function H(a, b) {
        if (0 == b.height) return 0;
        var c = b.text.length, d, e = b;
        while (d = Dd(e)) {
            var f = d.find();
            e = le(a, f.from.line);
            c += f.from.ch - f.to.ch;
        }
        e = b;
        while (d = Ed(e)) {
            var f = d.find();
            c -= e.text.length - f.from.ch;
            e = le(a, f.to.line);
            c += e.text.length - f.to.ch;
        }
        return c;
    }
    function I(a) {
        var b = a.display, c = a.doc;
        b.maxLine = le(c, c.first);
        b.maxLineLength = H(c, b.maxLine);
        b.maxLineChanged = true;
        c.iter(function(a) {
            var d = H(c, a);
            if (d > b.maxLineLength) {
                b.maxLineLength = d;
                b.maxLine = a;
            }
        });
    }
    function J(a) {
        var b = bf(a.gutters, "CodeMirror-linenumbers");
        if (b == -1 && a.lineNumbers) a.gutters = a.gutters.concat([ "CodeMirror-linenumbers" ]); else if (b > -1 && !a.lineNumbers) {
            a.gutters = a.gutters.slice(0);
            a.gutters.splice(b, 1);
        }
    }
    function K(a) {
        var b = a.display, c = a.doc.height;
        var d = c + gb(b);
        b.sizer.style.minHeight = b.heightForcer.style.top = d + "px";
        b.gutters.style.height = Math.max(d, b.scroller.clientHeight - Ve) + "px";
        var e = Math.max(d, b.scroller.scrollHeight);
        var f = b.scroller.scrollWidth > b.scroller.clientWidth + 1;
        var g = e > b.scroller.clientHeight + 1;
        if (g) {
            b.scrollbarV.style.display = "block";
            b.scrollbarV.style.bottom = f ? tf(b.measure) + "px" : "0";
            b.scrollbarV.firstChild.style.height = e - b.scroller.clientHeight + b.scrollbarV.clientHeight + "px";
        } else {
            b.scrollbarV.style.display = "";
            b.scrollbarV.firstChild.style.height = "0";
        }
        if (f) {
            b.scrollbarH.style.display = "block";
            b.scrollbarH.style.right = g ? tf(b.measure) + "px" : "0";
            b.scrollbarH.firstChild.style.width = b.scroller.scrollWidth - b.scroller.clientWidth + b.scrollbarH.clientWidth + "px";
        } else {
            b.scrollbarH.style.display = "";
            b.scrollbarH.firstChild.style.width = "0";
        }
        if (f && g) {
            b.scrollbarFiller.style.display = "block";
            b.scrollbarFiller.style.height = b.scrollbarFiller.style.width = tf(b.measure) + "px";
        } else b.scrollbarFiller.style.display = "";
        if (f && a.options.coverGutterNextToScrollbar && a.options.fixedGutter) {
            b.gutterFiller.style.display = "block";
            b.gutterFiller.style.height = tf(b.measure) + "px";
            b.gutterFiller.style.width = b.gutters.offsetWidth + "px";
        } else b.gutterFiller.style.display = "";
        if (l && 0 === tf(b.measure)) {
            b.scrollbarV.style.minWidth = b.scrollbarH.style.minHeight = m ? "18px" : "12px";
            b.scrollbarV.style.pointerEvents = b.scrollbarH.style.pointerEvents = "none";
        }
    }
    function L(a, b, c) {
        var d = a.scroller.scrollTop, e = a.wrapper.clientHeight;
        if ("number" == typeof c) d = c; else if (c) {
            d = c.top;
            e = c.bottom - c.top;
        }
        d = Math.floor(d - fb(a));
        var f = Math.ceil(d + e);
        return {
            from: qe(b, d),
            to: qe(b, f)
        };
    }
    function M(a) {
        var b = a.display;
        if (!b.alignWidgets && (!b.gutters.firstChild || !a.options.fixedGutter)) return;
        var c = P(b) - b.scroller.scrollLeft + a.doc.scrollLeft;
        var d = b.gutters.offsetWidth, e = c + "px";
        for (var f = b.lineDiv.firstChild; f; f = f.nextSibling) if (f.alignable) for (var g = 0, h = f.alignable; g < h.length; ++g) h[g].style.left = e;
        if (a.options.fixedGutter) b.gutters.style.left = c + d + "px";
    }
    function N(a) {
        if (!a.options.lineNumbers) return false;
        var b = a.doc, c = O(a.options, b.first + b.size - 1), d = a.display;
        if (c.length != d.lineNumChars) {
            var e = d.measure.appendChild(lf("div", [ lf("div", c) ], "CodeMirror-linenumber CodeMirror-gutter-elt"));
            var f = e.firstChild.offsetWidth, g = e.offsetWidth - f;
            d.lineGutter.style.width = "";
            d.lineNumInnerWidth = Math.max(f, d.lineGutter.offsetWidth - g);
            d.lineNumWidth = d.lineNumInnerWidth + g;
            d.lineNumChars = d.lineNumInnerWidth ? c.length : -1;
            d.lineGutter.style.width = d.lineNumWidth + "px";
            return true;
        }
        return false;
    }
    function O(a, b) {
        return String(a.lineNumberFormatter(b + a.firstLineNumber));
    }
    function P(a) {
        return pf(a.scroller).left - pf(a.sizer).left;
    }
    function Q(a, b, c, d) {
        var e = a.display.showingFrom, f = a.display.showingTo, g;
        var h = L(a.display, a.doc, c);
        for (var i = true; ;i = false) {
            var j = a.display.scroller.clientWidth;
            if (!R(a, b, h, d)) break;
            g = true;
            b = [];
            Z(a);
            K(a);
            if (i && a.options.lineWrapping && j != a.display.scroller.clientWidth) {
                d = true;
                continue;
            }
            d = false;
            if (c) c = Math.min(a.display.scroller.scrollHeight - a.display.scroller.clientHeight, "number" == typeof c ? c : c.top);
            h = L(a.display, a.doc, c);
            if (h.from >= a.display.showingFrom && h.to <= a.display.showingTo) break;
        }
        if (g) {
            Qe(a, "update", a);
            if (a.display.showingFrom != e || a.display.showingTo != f) Qe(a, "viewportChange", a, a.display.showingFrom, a.display.showingTo);
        }
        return g;
    }
    function R(a, b, c, d) {
        var e = a.display, f = a.doc;
        if (!e.wrapper.clientWidth) {
            e.showingFrom = e.showingTo = f.first;
            e.viewOffset = 0;
            return;
        }
        if (!d && 0 == b.length && c.from > e.showingFrom && c.to < e.showingTo) return;
        if (N(a)) b = [ {
            from: f.first,
            to: f.first + f.size
        } ];
        var g = e.sizer.style.marginLeft = e.gutters.offsetWidth + "px";
        e.scrollbarH.style.left = a.options.fixedGutter ? g : "0";
        var h = 1/0;
        if (a.options.lineNumbers) for (var i = 0; i < b.length; ++i) if (b[i].diff && b[i].from < h) h = b[i].from;
        var j = f.first + f.size;
        var k = Math.max(c.from - a.options.viewportMargin, f.first);
        var l = Math.min(j, c.to + a.options.viewportMargin);
        if (e.showingFrom < k && k - e.showingFrom < 20) k = Math.max(f.first, e.showingFrom);
        if (e.showingTo > l && e.showingTo - l < 20) l = Math.min(j, e.showingTo);
        if (w) {
            k = pe(Fd(f, le(f, k)));
            while (l < j && Gd(f, le(f, l))) ++l;
        }
        var m = [ {
            from: Math.max(e.showingFrom, f.first),
            to: Math.min(e.showingTo, j)
        } ];
        if (m[0].from >= m[0].to) m = []; else m = U(m, b);
        if (w) for (var i = 0; i < m.length; ++i) {
            var n = m[i], o;
            while (o = Ed(le(f, n.to - 1))) {
                var p = o.find().from.line;
                if (p > n.from) n.to = p; else {
                    m.splice(i--, 1);
                    break;
                }
            }
        }
        var q = 0;
        for (var i = 0; i < m.length; ++i) {
            var n = m[i];
            if (n.from < k) n.from = k;
            if (n.to > l) n.to = l;
            if (n.from >= n.to) m.splice(i--, 1); else q += n.to - n.from;
        }
        if (!d && q == l - k && k == e.showingFrom && l == e.showingTo) {
            T(a);
            return;
        }
        m.sort(function(a, b) {
            return a.from - b.from;
        });
        try {
            var r = document.activeElement;
        } catch (s) {}
        if (q < .7 * (l - k)) e.lineDiv.style.display = "none";
        W(a, k, l, m, h);
        e.lineDiv.style.display = "";
        if (r && document.activeElement != r && r.offsetHeight) r.focus();
        var t = k != e.showingFrom || l != e.showingTo || e.lastSizeC != e.wrapper.clientHeight;
        if (t) {
            e.lastSizeC = e.wrapper.clientHeight;
            bb(a, 400);
        }
        e.showingFrom = k;
        e.showingTo = l;
        S(a);
        T(a);
        return true;
    }
    function S(a) {
        var b = a.display;
        var d = b.lineDiv.offsetTop;
        for (var e = b.lineDiv.firstChild, f; e; e = e.nextSibling) if (e.lineObj) {
            if (c) {
                var g = e.offsetTop + e.offsetHeight;
                f = g - d;
                d = g;
            } else {
                var h = pf(e);
                f = h.bottom - h.top;
            }
            var i = e.lineObj.height - f;
            if (f < 2) f = Ab(b);
            if (i > .001 || i < -.001) {
                oe(e.lineObj, f);
                var j = e.lineObj.widgets;
                if (j) for (var k = 0; k < j.length; ++k) j[k].height = j[k].node.offsetHeight;
            }
        }
    }
    function T(a) {
        var b = a.display.viewOffset = re(a, le(a.doc, a.display.showingFrom));
        a.display.mover.style.top = b + "px";
    }
    function U(a, b) {
        for (var c = 0, d = b.length || 0; c < d; ++c) {
            var e = b[c], f = [], g = e.diff || 0;
            for (var h = 0, i = a.length; h < i; ++h) {
                var j = a[h];
                if (e.to <= j.from && e.diff) f.push({
                    from: j.from + g,
                    to: j.to + g
                }); else if (e.to <= j.from || e.from >= j.to) f.push(j); else {
                    if (e.from > j.from) f.push({
                        from: j.from,
                        to: e.from
                    });
                    if (e.to < j.to) f.push({
                        from: e.to + g,
                        to: j.to + g
                    });
                }
            }
            a = f;
        }
        return a;
    }
    function V(a) {
        var b = a.display, c = {}, d = {};
        for (var e = b.gutters.firstChild, f = 0; e; e = e.nextSibling, ++f) {
            c[a.options.gutters[f]] = e.offsetLeft;
            d[a.options.gutters[f]] = e.offsetWidth;
        }
        return {
            fixedPos: P(b),
            gutterTotalWidth: b.gutters.offsetWidth,
            gutterLeft: c,
            gutterWidth: d,
            wrapperWidth: b.wrapper.clientWidth
        };
    }
    function W(a, b, c, d, e) {
        var g = V(a);
        var h = a.display, i = a.options.lineNumbers;
        if (!d.length && (!f || !a.display.currentWheelTarget)) mf(h.lineDiv);
        var j = h.lineDiv, k = j.firstChild;
        function l(b) {
            var c = b.nextSibling;
            if (f && q && a.display.currentWheelTarget == b) {
                b.style.display = "none";
                b.lineObj = null;
            } else b.parentNode.removeChild(b);
            return c;
        }
        var m = d.shift(), n = b;
        a.doc.iter(b, c, function(b) {
            if (m && m.to == n) m = d.shift();
            if (Gd(a.doc, b)) {
                if (0 != b.height) oe(b, 0);
                if (b.widgets && k && k.previousSibling) for (var c = 0; c < b.widgets.length; ++c) {
                    var f = b.widgets[c];
                    if (f.showIfHidden) {
                        var h = k.previousSibling;
                        if (/pre/i.test(h.nodeName)) {
                            var o = lf("div", null, null, "position: relative");
                            h.parentNode.replaceChild(o, h);
                            o.appendChild(h);
                            h = o;
                        }
                        var p = h.appendChild(lf("div", [ f.node ], "CodeMirror-linewidget"));
                        if (!f.handleMouseEvents) p.ignoreEvents = true;
                        Y(f, p, h, g);
                    }
                }
            } else if (m && m.from <= n && m.to > n) {
                while (k.lineObj != b) k = l(k);
                if (i && e <= n && k.lineNumber) of(k.lineNumber, O(a.options, n));
                k = k.nextSibling;
            } else {
                if (b.widgets) for (var q = 0, r = k, s; r && q < 20; ++q, r = r.nextSibling) if (r.lineObj == b && /div/i.test(r.nodeName)) {
                    s = r;
                    break;
                }
                var t = X(a, b, n, g, s);
                if (t != s) j.insertBefore(t, k); else {
                    while (k != s) k = l(k);
                    k = k.nextSibling;
                }
                t.lineObj = b;
            }
            ++n;
        });
        while (k) k = l(k);
    }
    function X(a, b, d, e, f) {
        var g = Xd(a, b), h = g.pre;
        var i = b.gutterMarkers, j = a.display, k;
        var l = g.bgClass ? g.bgClass + " " + (b.bgClass || "") : b.bgClass;
        if (!a.options.lineNumbers && !i && !l && !b.wrapClass && !b.widgets) return h;
        if (f) {
            f.alignable = null;
            var m = true, n = 0, o = null;
            for (var p = f.firstChild, q; p; p = q) {
                q = p.nextSibling;
                if (!/\bCodeMirror-linewidget\b/.test(p.className)) f.removeChild(p); else {
                    for (var r = 0; r < b.widgets.length; ++r) {
                        var s = b.widgets[r];
                        if (s.node == p.firstChild) {
                            if (!s.above && !o) o = p;
                            Y(s, p, f, e);
                            ++n;
                            break;
                        }
                    }
                    if (r == b.widgets.length) {
                        m = false;
                        break;
                    }
                }
            }
            f.insertBefore(h, o);
            if (m && n == b.widgets.length) {
                k = f;
                f.className = b.wrapClass || "";
            }
        }
        if (!k) {
            k = lf("div", null, b.wrapClass, "position: relative");
            k.appendChild(h);
        }
        if (l) k.insertBefore(lf("div", null, l + " CodeMirror-linebackground"), k.firstChild);
        if (a.options.lineNumbers || i) {
            var t = k.insertBefore(lf("div", null, null, "position: absolute; left: " + (a.options.fixedGutter ? e.fixedPos : -e.gutterTotalWidth) + "px"), k.firstChild);
            if (a.options.fixedGutter) (k.alignable || (k.alignable = [])).push(t);
            if (a.options.lineNumbers && (!i || !i["CodeMirror-linenumbers"])) k.lineNumber = t.appendChild(lf("div", O(a.options, d), "CodeMirror-linenumber CodeMirror-gutter-elt", "left: " + e.gutterLeft["CodeMirror-linenumbers"] + "px; width: " + j.lineNumInnerWidth + "px"));
            if (i) for (var u = 0; u < a.options.gutters.length; ++u) {
                var v = a.options.gutters[u], w = i.hasOwnProperty(v) && i[v];
                if (w) t.appendChild(lf("div", [ w ], "CodeMirror-gutter-elt", "left: " + e.gutterLeft[v] + "px; width: " + e.gutterWidth[v] + "px"));
            }
        }
        if (c) k.style.zIndex = 2;
        if (b.widgets && k != f) for (var r = 0, x = b.widgets; r < x.length; ++r) {
            var s = x[r], y = lf("div", [ s.node ], "CodeMirror-linewidget");
            if (!s.handleMouseEvents) y.ignoreEvents = true;
            Y(s, y, k, e);
            if (s.above) k.insertBefore(y, a.options.lineNumbers && 0 != b.height ? t : h); else k.appendChild(y);
            Qe(s, "redraw");
        }
        return k;
    }
    function Y(a, b, c, d) {
        if (a.noHScroll) {
            (c.alignable || (c.alignable = [])).push(b);
            var e = d.wrapperWidth;
            b.style.left = d.fixedPos + "px";
            if (!a.coverGutter) {
                e -= d.gutterTotalWidth;
                b.style.paddingLeft = d.gutterTotalWidth + "px";
            }
            b.style.width = e + "px";
        }
        if (a.coverGutter) {
            b.style.zIndex = 5;
            b.style.position = "relative";
            if (!a.noHScroll) b.style.marginLeft = -d.gutterTotalWidth + "px";
        }
    }
    function Z(a) {
        var b = a.display;
        var c = Cc(a.doc.sel.from, a.doc.sel.to);
        if (c || a.options.showCursorWhenSelecting) $(a); else b.cursor.style.display = b.otherCursor.style.display = "none";
        if (!c) _(a); else b.selectionDiv.style.display = "none";
        if (a.options.moveInputWithCursor) {
            var d = vb(a, a.doc.sel.head, "div");
            var e = pf(b.wrapper), f = pf(b.lineDiv);
            b.inputDiv.style.top = Math.max(0, Math.min(b.wrapper.clientHeight - 10, d.top + f.top - e.top)) + "px";
            b.inputDiv.style.left = Math.max(0, Math.min(b.wrapper.clientWidth - 10, d.left + f.left - e.left)) + "px";
        }
    }
    function $(a) {
        var b = a.display, c = vb(a, a.doc.sel.head, "div");
        b.cursor.style.left = c.left + "px";
        b.cursor.style.top = c.top + "px";
        b.cursor.style.height = Math.max(0, c.bottom - c.top) * a.options.cursorHeight + "px";
        b.cursor.style.display = "";
        if (c.other) {
            b.otherCursor.style.display = "";
            b.otherCursor.style.left = c.other.left + "px";
            b.otherCursor.style.top = c.other.top + "px";
            b.otherCursor.style.height = .85 * (c.other.bottom - c.other.top) + "px";
        } else b.otherCursor.style.display = "none";
    }
    function _(a) {
        var b = a.display, c = a.doc, d = a.doc.sel;
        var e = document.createDocumentFragment();
        var f = b.lineSpace.offsetWidth, g = hb(a.display);
        function h(a, b, c, d) {
            if (b < 0) b = 0;
            e.appendChild(lf("div", null, "CodeMirror-selected", "position: absolute; left: " + a + "px; top: " + b + "px; width: " + (null == c ? f - a : c) + "px; height: " + (d - b) + "px"));
        }
        function i(b, d, e) {
            var i = le(c, b);
            var j = i.text.length;
            var k, l;
            function m(c, d) {
                return ub(a, Bc(b, c), "div", i, d);
            }
            Af(se(i), d || 0, null == e ? j : e, function(a, b, c) {
                var i = m(a, "left"), n, o, p;
                if (a == b) {
                    n = i;
                    o = p = i.left;
                } else {
                    n = m(b - 1, "right");
                    if ("rtl" == c) {
                        var q = i;
                        i = n;
                        n = q;
                    }
                    o = i.left;
                    p = n.right;
                }
                if (null == d && 0 == a) o = g;
                if (n.top - i.top > 3) {
                    h(o, i.top, null, i.bottom);
                    o = g;
                    if (i.bottom < n.top) h(o, i.bottom, null, n.top);
                }
                if (null == e && b == j) p = f;
                if (!k || i.top < k.top || i.top == k.top && i.left < k.left) k = i;
                if (!l || n.bottom > l.bottom || n.bottom == l.bottom && n.right > l.right) l = n;
                if (o < g + 1) o = g;
                h(o, n.top, p - o, n.bottom);
            });
            return {
                start: k,
                end: l
            };
        }
        if (d.from.line == d.to.line) i(d.from.line, d.from.ch, d.to.ch); else {
            var j = le(c, d.from.line), k = le(c, d.to.line);
            var l = Fd(c, j) == Fd(c, k);
            var m = i(d.from.line, d.from.ch, l ? j.text.length : null).end;
            var n = i(d.to.line, l ? 0 : null, d.to.ch).start;
            if (l) if (m.top < n.top - 2) {
                h(m.right, m.top, null, m.bottom);
                h(g, n.top, n.left, n.bottom);
            } else h(m.right, m.top, n.left - m.right, m.bottom);
            if (m.bottom < n.top) h(g, m.bottom, null, n.top);
        }
        nf(b.selectionDiv, e);
        b.selectionDiv.style.display = "";
    }
    function ab(a) {
        if (!a.state.focused) return;
        var b = a.display;
        clearInterval(b.blinker);
        var c = true;
        b.cursor.style.visibility = b.otherCursor.style.visibility = "";
        if (a.options.cursorBlinkRate > 0) b.blinker = setInterval(function() {
            b.cursor.style.visibility = b.otherCursor.style.visibility = (c = !c) ? "" : "hidden";
        }, a.options.cursorBlinkRate);
    }
    function bb(a, b) {
        if (a.doc.mode.startState && a.doc.frontier < a.display.showingTo) a.state.highlight.set(b, ff(cb, a));
    }
    function cb(a) {
        var b = a.doc;
        if (b.frontier < b.first) b.frontier = b.first;
        if (b.frontier >= a.display.showingTo) return;
        var c = +new Date() + a.options.workTime;
        var d = hd(b.mode, eb(a, b.frontier));
        var e = [], f;
        b.iter(b.frontier, Math.min(b.first + b.size, a.display.showingTo + 500), function(g) {
            if (b.frontier >= a.display.showingFrom) {
                var h = g.styles;
                g.styles = Sd(a, g, d, true);
                var i = !h || h.length != g.styles.length;
                for (var j = 0; !i && j < h.length; ++j) i = h[j] != g.styles[j];
                if (i) if (f && f.end == b.frontier) f.end++; else e.push(f = {
                    start: b.frontier,
                    end: b.frontier + 1
                });
                g.stateAfter = hd(b.mode, d);
            } else {
                Ud(a, g.text, d);
                g.stateAfter = b.frontier % 5 == 0 ? hd(b.mode, d) : null;
            }
            ++b.frontier;
            if (+new Date() > c) {
                bb(a, a.options.workDelay);
                return true;
            }
        });
        if (e.length) Fb(a, function() {
            for (var a = 0; a < e.length; ++a) Ib(this, e[a].start, e[a].end);
        })();
    }
    function db(a, b, c) {
        var d, e, f = a.doc;
        var g = c ? -1 : b - (a.doc.mode.innerMode ? 1e3 : 100);
        for (var h = b; h > g; --h) {
            if (h <= f.first) return f.first;
            var i = le(f, h - 1);
            if (i.stateAfter && (!c || h <= f.frontier)) return h;
            var j = Ye(i.text, null, a.options.tabSize);
            if (null == e || d > j) {
                e = h - 1;
                d = j;
            }
        }
        return e;
    }
    function eb(a, b, c) {
        var d = a.doc, e = a.display;
        if (!d.mode.startState) return true;
        var f = db(a, b, c), g = f > d.first && le(d, f - 1).stateAfter;
        if (!g) g = id(d.mode); else g = hd(d.mode, g);
        d.iter(f, b, function(c) {
            Ud(a, c.text, g);
            var h = f == b - 1 || f % 5 == 0 || f >= e.showingFrom && f < e.showingTo;
            c.stateAfter = h ? hd(d.mode, g) : null;
            ++f;
        });
        if (c) d.frontier = f;
        return g;
    }
    function fb(a) {
        return a.lineSpace.offsetTop;
    }
    function gb(a) {
        return a.mover.offsetHeight - a.lineSpace.offsetHeight;
    }
    function hb(a) {
        var b = nf(a.measure, lf("pre", null, null, "text-align: left")).appendChild(lf("span", "x"));
        return b.offsetLeft;
    }
    function ib(a, b, c, d, e) {
        var f = -1;
        d = d || lb(a, b);
        if (d.crude) {
            var g = d.left + c * d.width;
            return {
                left: g,
                right: g + d.width,
                top: d.top,
                bottom: d.bottom
            };
        }
        for (var h = c; ;h += f) {
            var i = d[h];
            if (i) break;
            if (f < 0 && 0 == h) f = 1;
        }
        e = h > c ? "left" : h < c ? "right" : e;
        if ("left" == e && i.leftSide) i = i.leftSide; else if ("right" == e && i.rightSide) i = i.rightSide;
        return {
            left: h < c ? i.right : i.left,
            right: h > c ? i.left : i.right,
            top: i.top,
            bottom: i.bottom
        };
    }
    function jb(a, b) {
        var c = a.display.measureLineCache;
        for (var d = 0; d < c.length; ++d) {
            var e = c[d];
            if (e.text == b.text && e.markedSpans == b.markedSpans && a.display.scroller.clientWidth == e.width && e.classes == b.textClass + "|" + b.wrapClass) return e;
        }
    }
    function kb(a, b) {
        var c = jb(a, b);
        if (c) c.text = c.measure = c.markedSpans = null;
    }
    function lb(a, b) {
        var c = jb(a, b);
        if (c) return c.measure;
        var d = mb(a, b);
        var e = a.display.measureLineCache;
        var f = {
            text: b.text,
            width: a.display.scroller.clientWidth,
            markedSpans: b.markedSpans,
            measure: d,
            classes: b.textClass + "|" + b.wrapClass
        };
        if (16 == e.length) e[++a.display.measureLineCachePos % 16] = f; else e.push(f);
        return d;
    }
    function mb(a, e) {
        if (!a.options.lineWrapping && e.text.length >= a.options.crudeMeasuringFrom) return nb(a, e);
        var f = a.display, g = ef(e.text.length);
        var h = Xd(a, e, g, true).pre;
        if (b && !c && !a.options.lineWrapping && h.childNodes.length > 100) {
            var i = document.createDocumentFragment();
            var j = 10, k = h.childNodes.length;
            for (var l = 0, m = Math.ceil(k / j); l < m; ++l) {
                var n = lf("div", null, null, "display: inline-block");
                for (var o = 0; o < j && k; ++o) {
                    n.appendChild(h.firstChild);
                    --k;
                }
                i.appendChild(n);
            }
            h.appendChild(i);
        }
        nf(f.measure, h);
        var p = pf(f.lineDiv);
        var q = [], r = ef(e.text.length), s = h.offsetHeight;
        if (d && f.measure.first != h) nf(f.measure, h);
        function t(a) {
            var b = a.top - p.top, c = a.bottom - p.top;
            if (c > s) c = s;
            if (b < 0) b = 0;
            for (var d = q.length - 2; d >= 0; d -= 2) {
                var e = q[d], f = q[d + 1];
                if (e > c || f < b) continue;
                if (e <= b && f >= c || b <= e && c >= f || Math.min(c, f) - Math.max(b, e) >= c - b >> 1) {
                    q[d] = Math.min(b, e);
                    q[d + 1] = Math.max(c, f);
                    break;
                }
            }
            if (d < 0) {
                d = q.length;
                q.push(b, c);
            }
            return {
                left: a.left - p.left,
                right: a.right - p.left,
                top: d,
                bottom: null
            };
        }
        function u(a) {
            a.bottom = q[a.top + 1];
            a.top = q[a.top];
        }
        for (var l = 0, v; l < g.length; ++l) if (v = g[l]) {
            var w = v, x = null;
            if (/\bCodeMirror-widget\b/.test(v.className) && v.getClientRects) {
                if (1 == v.firstChild.nodeType) w = v.firstChild;
                var y = w.getClientRects();
                if (y.length > 1) {
                    x = r[l] = t(y[0]);
                    x.rightSide = t(y[y.length - 1]);
                }
            }
            if (!x) x = r[l] = t(pf(w));
            if (v.measureRight) x.right = pf(v.measureRight).left;
            if (v.leftSide) x.leftSide = t(pf(v.leftSide));
        }
        mf(a.display.measure);
        for (var l = 0, v; l < r.length; ++l) if (v = r[l]) {
            u(v);
            if (v.leftSide) u(v.leftSide);
            if (v.rightSide) u(v.rightSide);
        }
        return r;
    }
    function nb(a, b) {
        var c = new Od(b.text.slice(0, 100), null);
        if (b.textClass) c.textClass = b.textClass;
        var d = mb(a, c);
        var e = ib(a, c, 0, d, "left");
        var f = ib(a, c, 99, d, "right");
        return {
            crude: true,
            top: e.top,
            left: e.left,
            bottom: e.bottom,
            width: (f.right - e.left) / 100
        };
    }
    function ob(a, b) {
        var c = false;
        if (b.markedSpans) for (var d = 0; d < b.markedSpans; ++d) {
            var e = b.markedSpans[d];
            if (e.collapsed && (null == e.to || e.to == b.text.length)) c = true;
        }
        var f = !c && jb(a, b);
        if (f || b.text.length >= a.options.crudeMeasuringFrom) return ib(a, b, b.text.length, f && f.measure, "right").right;
        var g = Xd(a, b, null, true).pre;
        var h = g.appendChild(vf(a.display.measure));
        nf(a.display.measure, g);
        return pf(h).right - pf(a.display.lineDiv).left;
    }
    function pb(a) {
        a.display.measureLineCache.length = a.display.measureLineCachePos = 0;
        a.display.cachedCharWidth = a.display.cachedTextHeight = null;
        if (!a.options.lineWrapping) a.display.maxLineChanged = true;
        a.display.lineNumChars = null;
    }
    function qb() {
        return window.pageXOffset || (document.documentElement || document.body).scrollLeft;
    }
    function rb() {
        return window.pageYOffset || (document.documentElement || document.body).scrollTop;
    }
    function sb(a, b, c, d) {
        if (b.widgets) for (var e = 0; e < b.widgets.length; ++e) if (b.widgets[e].above) {
            var f = Md(b.widgets[e]);
            c.top += f;
            c.bottom += f;
        }
        if ("line" == d) return c;
        if (!d) d = "local";
        var g = re(a, b);
        if ("local" == d) g += fb(a.display); else g -= a.display.viewOffset;
        if ("page" == d || "window" == d) {
            var h = pf(a.display.lineSpace);
            g += h.top + ("window" == d ? 0 : rb());
            var i = h.left + ("window" == d ? 0 : qb());
            c.left += i;
            c.right += i;
        }
        c.top += g;
        c.bottom += g;
        return c;
    }
    function tb(a, b, c) {
        if ("div" == c) return b;
        var d = b.left, e = b.top;
        if ("page" == c) {
            d -= qb();
            e -= rb();
        } else if ("local" == c || !c) {
            var f = pf(a.display.sizer);
            d += f.left;
            e += f.top;
        }
        var g = pf(a.display.lineSpace);
        return {
            left: d - g.left,
            top: e - g.top
        };
    }
    function ub(a, b, c, d, e) {
        if (!d) d = le(a.doc, b.line);
        return sb(a, d, ib(a, d, b.ch, null, e), c);
    }
    function vb(a, b, c, d, e) {
        d = d || le(a.doc, b.line);
        if (!e) e = lb(a, d);
        function f(b, f) {
            var g = ib(a, d, b, e, f ? "right" : "left");
            if (f) g.left = g.right; else g.right = g.left;
            return sb(a, d, g, c);
        }
        function g(a, b) {
            var c = h[b], d = c.level % 2;
            if (a == Bf(c) && b && c.level < h[b - 1].level) {
                c = h[--b];
                a = Cf(c) - (c.level % 2 ? 0 : 1);
                d = true;
            } else if (a == Cf(c) && b < h.length - 1 && c.level < h[b + 1].level) {
                c = h[++b];
                a = Bf(c) - c.level % 2;
                d = false;
            }
            if (d && a == c.to && a > c.from) return f(a - 1);
            return f(a, d);
        }
        var h = se(d), i = b.ch;
        if (!h) return f(i);
        var j = Jf(h, i);
        var k = g(i, j);
        if (null != If) k.other = g(i, If);
        return k;
    }
    function wb(a, b, c, d) {
        var e = new Bc(a, b);
        e.xRel = d;
        if (c) e.outside = true;
        return e;
    }
    function xb(a, b, c) {
        var d = a.doc;
        c += a.display.viewOffset;
        if (c < 0) return wb(d.first, 0, true, -1);
        var e = qe(d, c), f = d.first + d.size - 1;
        if (e > f) return wb(d.first + d.size - 1, le(d, f).text.length, true, 1);
        if (b < 0) b = 0;
        for (;;) {
            var g = le(d, e);
            var h = yb(a, g, e, b, c);
            var i = Ed(g);
            var j = i && i.find();
            if (i && (h.ch > j.from.ch || h.ch == j.from.ch && h.xRel > 0)) e = j.to.line; else return h;
        }
    }
    function yb(a, b, c, d, e) {
        var f = e - re(a, b);
        var g = false, h = 2 * a.display.wrapper.clientWidth;
        var i = lb(a, b);
        function j(d) {
            var e = vb(a, Bc(c, d), "line", b, i);
            g = true;
            if (f > e.bottom) return e.left - h; else if (f < e.top) return e.left + h; else g = false;
            return e.left;
        }
        var k = se(b), l = b.text.length;
        var m = Df(b), n = Ef(b);
        var o = j(m), p = g, q = j(n), r = g;
        if (d > q) return wb(c, n, r, 1);
        for (;;) {
            if (k ? n == m || n == Lf(b, m, 1) : n - m <= 1) {
                var s = d < o || d - o <= q - d ? m : n;
                var t = d - (s == m ? o : q);
                while (kf.test(b.text.charAt(s))) ++s;
                var u = wb(c, s, s == m ? p : r, t < 0 ? -1 : t ? 1 : 0);
                return u;
            }
            var v = Math.ceil(l / 2), w = m + v;
            if (k) {
                w = m;
                for (var x = 0; x < v; ++x) w = Lf(b, w, 1);
            }
            var y = j(w);
            if (y > d) {
                n = w;
                q = y;
                if (r = g) q += 1e3;
                l = v;
            } else {
                m = w;
                o = y;
                p = g;
                l -= v;
            }
        }
    }
    var zb;
    function Ab(a) {
        if (null != a.cachedTextHeight) return a.cachedTextHeight;
        if (null == zb) {
            zb = lf("pre");
            for (var b = 0; b < 49; ++b) {
                zb.appendChild(document.createTextNode("x"));
                zb.appendChild(lf("br"));
            }
            zb.appendChild(document.createTextNode("x"));
        }
        nf(a.measure, zb);
        var c = zb.offsetHeight / 50;
        if (c > 3) a.cachedTextHeight = c;
        mf(a.measure);
        return c || 1;
    }
    function Bb(a) {
        if (null != a.cachedCharWidth) return a.cachedCharWidth;
        var b = lf("span", "x");
        var c = lf("pre", [ b ]);
        nf(a.measure, c);
        var d = b.offsetWidth;
        if (d > 2) a.cachedCharWidth = d;
        return d || 10;
    }
    var Cb = 0;
    function Db(a) {
        a.curOp = {
            changes: [],
            forceUpdate: false,
            updateInput: null,
            userSelChange: null,
            textChanged: null,
            selectionChanged: false,
            cursorActivity: false,
            updateMaxLine: false,
            updateScrollPos: false,
            id: ++Cb
        };
        if (!Pe++) Oe = [];
    }
    function Eb(a) {
        var b = a.curOp, c = a.doc, d = a.display;
        a.curOp = null;
        if (b.updateMaxLine) I(a);
        if (d.maxLineChanged && !a.options.lineWrapping && d.maxLine) {
            var e = ob(a, d.maxLine);
            d.sizer.style.minWidth = Math.max(0, e + 3 + Ve) + "px";
            d.maxLineChanged = false;
            var f = Math.max(0, d.sizer.offsetLeft + d.sizer.offsetWidth - d.scroller.clientWidth);
            if (f < c.scrollLeft && !b.updateScrollPos) ac(a, Math.min(d.scroller.scrollLeft, f), true);
        }
        var g, h;
        if (b.updateScrollPos) g = b.updateScrollPos; else if (b.selectionChanged && d.scroller.clientHeight) {
            var i = vb(a, c.sel.head);
            g = Rc(a, i.left, i.top, i.left, i.bottom);
        }
        if (b.changes.length || b.forceUpdate || g && null != g.scrollTop) {
            h = Q(a, b.changes, g && g.scrollTop, b.forceUpdate);
            if (a.display.scroller.offsetHeight) a.doc.scrollTop = a.display.scroller.scrollTop;
        }
        if (!h && b.selectionChanged) Z(a);
        if (b.updateScrollPos) {
            var j = Math.max(0, Math.min(d.scroller.scrollHeight - d.scroller.clientHeight, g.scrollTop));
            var k = Math.max(0, Math.min(d.scroller.scrollWidth - d.scroller.clientWidth, g.scrollLeft));
            d.scroller.scrollTop = d.scrollbarV.scrollTop = c.scrollTop = j;
            d.scroller.scrollLeft = d.scrollbarH.scrollLeft = c.scrollLeft = k;
            M(a);
            if (b.scrollToPos) Pc(a, Gc(a.doc, b.scrollToPos.from), Gc(a.doc, b.scrollToPos.to), b.scrollToPos.margin);
        } else if (g) Oc(a);
        if (b.selectionChanged) ab(a);
        if (a.state.focused && b.updateInput) Mb(a, b.userSelChange);
        var l = b.maybeHiddenMarkers, m = b.maybeUnhiddenMarkers;
        if (l) for (var n = 0; n < l.length; ++n) if (!l[n].lines.length) Ne(l[n], "hide");
        if (m) for (var n = 0; n < m.length; ++n) if (m[n].lines.length) Ne(m[n], "unhide");
        var o;
        if (!--Pe) {
            o = Oe;
            Oe = null;
        }
        if (b.textChanged) Ne(a, "change", a, b.textChanged);
        if (b.cursorActivity) Ne(a, "cursorActivity", a);
        if (o) for (var n = 0; n < o.length; ++n) o[n]();
    }
    function Fb(a, b) {
        return function() {
            var c = a || this, d = !c.curOp;
            if (d) Db(c);
            try {
                var e = b.apply(c, arguments);
            } finally {
                if (d) Eb(c);
            }
            return e;
        };
    }
    function Gb(a) {
        return function() {
            var b = this.cm && !this.cm.curOp, c;
            if (b) Db(this.cm);
            try {
                c = a.apply(this, arguments);
            } finally {
                if (b) Eb(this.cm);
            }
            return c;
        };
    }
    function Hb(a, b) {
        var c = !a.curOp, d;
        if (c) Db(a);
        try {
            d = b();
        } finally {
            if (c) Eb(a);
        }
        return d;
    }
    function Ib(a, b, c, d) {
        if (null == b) b = a.doc.first;
        if (null == c) c = a.doc.first + a.doc.size;
        a.curOp.changes.push({
            from: b,
            to: c,
            diff: d
        });
    }
    function Jb(a) {
        if (a.display.pollingFast) return;
        a.display.poll.set(a.options.pollInterval, function() {
            Lb(a);
            if (a.state.focused) Jb(a);
        });
    }
    function Kb(a) {
        var b = false;
        a.display.pollingFast = true;
        function c() {
            var d = Lb(a);
            if (!d && !b) {
                b = true;
                a.display.poll.set(60, c);
            } else {
                a.display.pollingFast = false;
                Jb(a);
            }
        }
        a.display.poll.set(20, c);
    }
    function Lb(a) {
        var c = a.display.input, e = a.display.prevInput, f = a.doc, g = f.sel;
        if (!a.state.focused || xf(c) || Ob(a) || a.state.disableInput) return false;
        if (a.state.pasteIncoming && a.state.fakedLastChar) {
            c.value = c.value.substring(0, c.value.length - 1);
            a.state.fakedLastChar = false;
        }
        var h = c.value;
        if (h == e && Cc(g.from, g.to)) return false;
        if (b && !d && a.display.inputHasSelection === h) {
            Mb(a, true);
            return false;
        }
        var i = !a.curOp;
        if (i) Db(a);
        g.shift = false;
        var j = 0, k = Math.min(e.length, h.length);
        while (j < k && e.charCodeAt(j) == h.charCodeAt(j)) ++j;
        var l = g.from, m = g.to;
        if (j < e.length) l = Bc(l.line, l.ch - (e.length - j)); else if (a.state.overwrite && Cc(l, m) && !a.state.pasteIncoming) m = Bc(m.line, Math.min(le(f, m.line).text.length, m.ch + (h.length - j)));
        var n = a.curOp.updateInput;
        var o = {
            from: l,
            to: m,
            text: wf(h.slice(j)),
            origin: a.state.pasteIncoming ? "paste" : "+input"
        };
        uc(a.doc, o, "end");
        a.curOp.updateInput = n;
        Qe(a, "inputRead", a, o);
        if (h.length > 1e3 || h.indexOf("\n") > -1) c.value = a.display.prevInput = ""; else a.display.prevInput = h;
        if (i) Eb(a);
        a.state.pasteIncoming = false;
        return true;
    }
    function Mb(a, c) {
        var e, f, g = a.doc;
        if (!Cc(g.sel.from, g.sel.to)) {
            a.display.prevInput = "";
            e = yf && (g.sel.to.line - g.sel.from.line > 100 || (f = a.getSelection()).length > 1e3);
            var h = e ? "-" : f || a.getSelection();
            a.display.input.value = h;
            if (a.state.focused) af(a.display.input);
            if (b && !d) a.display.inputHasSelection = h;
        } else if (c) {
            a.display.prevInput = a.display.input.value = "";
            if (b && !d) a.display.inputHasSelection = null;
        }
        a.display.inaccurateSelection = e;
    }
    function Nb(a) {
        if ("nocursor" != a.options.readOnly && (!p || document.activeElement != a.display.input)) a.display.input.focus();
    }
    function Ob(a) {
        return a.options.readOnly || a.doc.cantEdit;
    }
    function Pb(a) {
        var c = a.display;
        Le(c.scroller, "mousedown", Fb(a, Ub));
        if (b) Le(c.scroller, "dblclick", Fb(a, function(b) {
            if (Re(a, b)) return;
            var c = Rb(a, b);
            if (!c || Xb(a, b) || Qb(a.display, b)) return;
            Fe(b);
            var d = Yc(le(a.doc, c.line).text, c);
            Jc(a.doc, d.from, d.to);
        })); else Le(c.scroller, "dblclick", function(b) {
            Re(a, b) || Fe(b);
        });
        Le(c.lineSpace, "selectstart", function(a) {
            if (!Qb(c, a)) Fe(a);
        });
        if (!u) Le(c.scroller, "contextmenu", function(b) {
            pc(a, b);
        });
        Le(c.scroller, "scroll", function() {
            if (c.scroller.clientHeight) {
                _b(a, c.scroller.scrollTop);
                ac(a, c.scroller.scrollLeft, true);
                Ne(a, "scroll", a);
            }
        });
        Le(c.scrollbarV, "scroll", function() {
            if (c.scroller.clientHeight) _b(a, c.scrollbarV.scrollTop);
        });
        Le(c.scrollbarH, "scroll", function() {
            if (c.scroller.clientHeight) ac(a, c.scrollbarH.scrollLeft);
        });
        Le(c.scroller, "mousewheel", function(b) {
            dc(a, b);
        });
        Le(c.scroller, "DOMMouseScroll", function(b) {
            dc(a, b);
        });
        function e() {
            if (a.state.focused) setTimeout(ff(Nb, a), 0);
        }
        Le(c.scrollbarH, "mousedown", e);
        Le(c.scrollbarV, "mousedown", e);
        Le(c.wrapper, "scroll", function() {
            c.wrapper.scrollTop = c.wrapper.scrollLeft = 0;
        });
        var g;
        function h() {
            if (null == g) g = setTimeout(function() {
                g = null;
                c.cachedCharWidth = c.cachedTextHeight = sf = null;
                pb(a);
                Hb(a, ff(Ib, a));
            }, 100);
        }
        Le(window, "resize", h);
        function i() {
            for (var a = c.wrapper.parentNode; a && a != document.body; a = a.parentNode) ;
            if (a) setTimeout(i, 5e3); else Me(window, "resize", h);
        }
        setTimeout(i, 5e3);
        Le(c.input, "keyup", Fb(a, function(b) {
            if (Re(a, b) || a.options.onKeyEvent && a.options.onKeyEvent(a, Ee(b))) return;
            if (16 == b.keyCode) a.doc.sel.shift = false;
        }));
        Le(c.input, "input", function() {
            if (b && !d && a.display.inputHasSelection) a.display.inputHasSelection = null;
            Kb(a);
        });
        Le(c.input, "keydown", Fb(a, kc));
        Le(c.input, "keypress", Fb(a, lc));
        Le(c.input, "focus", ff(mc, a));
        Le(c.input, "blur", ff(nc, a));
        function j(b) {
            if (Re(a, b) || a.options.onDragEvent && a.options.onDragEvent(a, Ee(b))) return;
            Ie(b);
        }
        if (a.options.dragDrop) {
            Le(c.scroller, "dragstart", function(b) {
                $b(a, b);
            });
            Le(c.scroller, "dragenter", j);
            Le(c.scroller, "dragover", j);
            Le(c.scroller, "drop", Fb(a, Zb));
        }
        Le(c.scroller, "paste", function(b) {
            if (Qb(c, b)) return;
            Nb(a);
            Kb(a);
        });
        Le(c.input, "paste", function() {
            if (f && !a.state.fakedLastChar && !(new Date() - a.state.lastMiddleDown < 200)) {
                var b = c.input.selectionStart, d = c.input.selectionEnd;
                c.input.value += "$";
                c.input.selectionStart = b;
                c.input.selectionEnd = d;
                a.state.fakedLastChar = true;
            }
            a.state.pasteIncoming = true;
            Kb(a);
        });
        function l() {
            if (c.inaccurateSelection) {
                c.prevInput = "";
                c.inaccurateSelection = false;
                c.input.value = a.getSelection();
                af(c.input);
            }
        }
        Le(c.input, "cut", l);
        Le(c.input, "copy", l);
        if (k) Le(c.sizer, "mouseup", function() {
            if (document.activeElement == c.input) c.input.blur();
            Nb(a);
        });
    }
    function Qb(a, b) {
        for (var c = Je(b); c != a.wrapper; c = c.parentNode) if (!c || c.ignoreEvents || c.parentNode == a.sizer && c != a.mover) return true;
    }
    function Rb(a, b, c) {
        var d = a.display;
        if (!c) {
            var e = Je(b);
            if (e == d.scrollbarH || e == d.scrollbarH.firstChild || e == d.scrollbarV || e == d.scrollbarV.firstChild || e == d.scrollbarFiller || e == d.gutterFiller) return null;
        }
        var f, g, h = pf(d.lineSpace);
        try {
            f = b.clientX;
            g = b.clientY;
        } catch (b) {
            return null;
        }
        return xb(a, f - h.left, g - h.top);
    }
    var Sb, Tb;
    function Ub(a) {
        if (Re(this, a)) return;
        var c = this, d = c.display, e = c.doc, g = e.sel;
        g.shift = a.shiftKey;
        if (Qb(d, a)) {
            if (!f) {
                d.scroller.draggable = false;
                setTimeout(function() {
                    d.scroller.draggable = true;
                }, 100);
            }
            return;
        }
        if (Xb(c, a)) return;
        var h = Rb(c, a);
        switch (Ke(a)) {
          case 3:
            if (u) pc.call(c, c, a);
            return;

          case 2:
            if (f) c.state.lastMiddleDown = +new Date();
            if (h) Jc(c.doc, h);
            setTimeout(ff(Nb, c), 20);
            Fe(a);
            return;
        }
        if (!h) {
            if (Je(a) == d.scroller) Fe(a);
            return;
        }
        if (!c.state.focused) mc(c);
        var i = +new Date(), j = "single";
        if (Tb && Tb.time > i - 400 && Cc(Tb.pos, h)) {
            j = "triple";
            Fe(a);
            setTimeout(ff(Nb, c), 20);
            Zc(c, h.line);
        } else if (Sb && Sb.time > i - 400 && Cc(Sb.pos, h)) {
            j = "double";
            Tb = {
                time: i,
                pos: h
            };
            Fe(a);
            var k = Yc(le(e, h.line).text, h);
            Jc(c.doc, k.from, k.to);
        } else Sb = {
            time: i,
            pos: h
        };
        var l = h;
        if (c.options.dragDrop && qf && !Ob(c) && !Cc(g.from, g.to) && !Dc(h, g.from) && !Dc(g.to, h) && "single" == j) {
            var m = Fb(c, function(b) {
                if (f) d.scroller.draggable = false;
                c.state.draggingText = false;
                Me(document, "mouseup", m);
                Me(d.scroller, "drop", m);
                if (Math.abs(a.clientX - b.clientX) + Math.abs(a.clientY - b.clientY) < 10) {
                    Fe(b);
                    Jc(c.doc, h);
                    Nb(c);
                }
            });
            if (f) d.scroller.draggable = true;
            c.state.draggingText = m;
            if (d.scroller.dragDrop) d.scroller.dragDrop();
            Le(document, "mouseup", m);
            Le(d.scroller, "drop", m);
            return;
        }
        Fe(a);
        if ("single" == j) Jc(c.doc, Gc(e, h));
        var n = g.from, o = g.to, p = h;
        function q(a) {
            if (Cc(p, a)) return;
            p = a;
            if ("single" == j) {
                Jc(c.doc, Gc(e, h), a);
                return;
            }
            n = Gc(e, n);
            o = Gc(e, o);
            if ("double" == j) {
                var b = Yc(le(e, a.line).text, a);
                if (Dc(a, n)) Jc(c.doc, b.from, o); else Jc(c.doc, n, b.to);
            } else if ("triple" == j) if (Dc(a, n)) Jc(c.doc, o, Gc(e, Bc(a.line, 0))); else Jc(c.doc, n, Gc(e, Bc(a.line + 1, 0)));
        }
        var r = pf(d.wrapper);
        var s = 0;
        function t(a) {
            var b = ++s;
            var f = Rb(c, a, true);
            if (!f) return;
            if (!Cc(f, l)) {
                if (!c.state.focused) mc(c);
                l = f;
                q(f);
                var g = L(d, e);
                if (f.line >= g.to || f.line < g.from) setTimeout(Fb(c, function() {
                    if (s == b) t(a);
                }), 150);
            } else {
                var h = a.clientY < r.top ? -20 : a.clientY > r.bottom ? 20 : 0;
                if (h) setTimeout(Fb(c, function() {
                    if (s != b) return;
                    d.scroller.scrollTop += h;
                    t(a);
                }), 50);
            }
        }
        function v(a) {
            s = 1/0;
            Fe(a);
            Nb(c);
            Me(document, "mousemove", w);
            Me(document, "mouseup", x);
        }
        var w = Fb(c, function(a) {
            if (!b && !Ke(a)) v(a); else t(a);
        });
        var x = Fb(c, v);
        Le(document, "mousemove", w);
        Le(document, "mouseup", x);
    }
    function Vb(a, b, c, d, e) {
        try {
            var f = b.clientX, g = b.clientY;
        } catch (b) {
            return false;
        }
        if (f >= Math.floor(pf(a.display.gutters).right)) return false;
        if (d) Fe(b);
        var h = a.display;
        var i = pf(h.lineDiv);
        if (g > i.bottom || !Te(a, c)) return He(b);
        g -= i.top - h.viewOffset;
        for (var j = 0; j < a.options.gutters.length; ++j) {
            var k = h.gutters.childNodes[j];
            if (k && pf(k).right >= f) {
                var l = qe(a.doc, g);
                var m = a.options.gutters[j];
                e(a, c, a, l, m, b);
                return He(b);
            }
        }
    }
    function Wb(a, b) {
        if (!Te(a, "gutterContextMenu")) return false;
        return Vb(a, b, "gutterContextMenu", false, Ne);
    }
    function Xb(a, b) {
        return Vb(a, b, "gutterClick", true, Qe);
    }
    var Yb = 0;
    function Zb(a) {
        var c = this;
        if (Re(c, a) || Qb(c.display, a) || c.options.onDragEvent && c.options.onDragEvent(c, Ee(a))) return;
        Fe(a);
        if (b) Yb = +new Date();
        var d = Rb(c, a, true), e = a.dataTransfer.files;
        if (!d || Ob(c)) return;
        if (e && e.length && window.FileReader && window.File) {
            var f = e.length, g = Array(f), h = 0;
            var i = function(a, b) {
                var e = new FileReader();
                e.onload = function() {
                    g[b] = e.result;
                    if (++h == f) {
                        d = Gc(c.doc, d);
                        uc(c.doc, {
                            from: d,
                            to: d,
                            text: wf(g.join("\n")),
                            origin: "paste"
                        }, "around");
                    }
                };
                e.readAsText(a);
            };
            for (var j = 0; j < f; ++j) i(e[j], j);
        } else {
            if (c.state.draggingText && !(Dc(d, c.doc.sel.from) || Dc(c.doc.sel.to, d))) {
                c.state.draggingText(a);
                setTimeout(ff(Nb, c), 20);
                return;
            }
            try {
                var g = a.dataTransfer.getData("Text");
                if (g) {
                    var k = c.doc.sel.from, l = c.doc.sel.to;
                    Lc(c.doc, d, d);
                    if (c.state.draggingText) Ac(c.doc, "", k, l, "paste");
                    c.replaceSelection(g, null, "paste");
                    Nb(c);
                }
            } catch (a) {}
        }
    }
    function $b(a, c) {
        if (b && (!a.state.draggingText || +new Date() - Yb < 100)) {
            Ie(c);
            return;
        }
        if (Re(a, c) || Qb(a.display, c)) return;
        var d = a.getSelection();
        c.dataTransfer.setData("Text", d);
        if (c.dataTransfer.setDragImage && !j) {
            var e = lf("img", null, null, "position: fixed; left: 0; top: 0;");
            e.src = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
            if (i) {
                e.width = e.height = 1;
                a.display.wrapper.appendChild(e);
                e._top = e.offsetTop;
            }
            c.dataTransfer.setDragImage(e, 0, 0);
            if (i) e.parentNode.removeChild(e);
        }
    }
    function _b(b, c) {
        if (Math.abs(b.doc.scrollTop - c) < 2) return;
        b.doc.scrollTop = c;
        if (!a) Q(b, [], c);
        if (b.display.scroller.scrollTop != c) b.display.scroller.scrollTop = c;
        if (b.display.scrollbarV.scrollTop != c) b.display.scrollbarV.scrollTop = c;
        if (a) Q(b, []);
        bb(b, 100);
    }
    function ac(a, b, c) {
        if (c ? b == a.doc.scrollLeft : Math.abs(a.doc.scrollLeft - b) < 2) return;
        b = Math.min(b, a.display.scroller.scrollWidth - a.display.scroller.clientWidth);
        a.doc.scrollLeft = b;
        M(a);
        if (a.display.scroller.scrollLeft != b) a.display.scroller.scrollLeft = b;
        if (a.display.scrollbarH.scrollLeft != b) a.display.scrollbarH.scrollLeft = b;
    }
    var bc = 0, cc = null;
    if (b) cc = -.53; else if (a) cc = 15; else if (h) cc = -.7; else if (j) cc = -1 / 3;
    function dc(b, c) {
        var d = c.wheelDeltaX, e = c.wheelDeltaY;
        if (null == d && c.detail && c.axis == c.HORIZONTAL_AXIS) d = c.detail;
        if (null == e && c.detail && c.axis == c.VERTICAL_AXIS) e = c.detail; else if (null == e) e = c.wheelDelta;
        var g = b.display, h = g.scroller;
        if (!(d && h.scrollWidth > h.clientWidth || e && h.scrollHeight > h.clientHeight)) return;
        if (e && q && f) for (var j = c.target; j != h; j = j.parentNode) if (j.lineObj) {
            b.display.currentWheelTarget = j;
            break;
        }
        if (d && !a && !i && null != cc) {
            if (e) _b(b, Math.max(0, Math.min(h.scrollTop + e * cc, h.scrollHeight - h.clientHeight)));
            ac(b, Math.max(0, Math.min(h.scrollLeft + d * cc, h.scrollWidth - h.clientWidth)));
            Fe(c);
            g.wheelStartX = null;
            return;
        }
        if (e && null != cc) {
            var k = e * cc;
            var l = b.doc.scrollTop, m = l + g.wrapper.clientHeight;
            if (k < 0) l = Math.max(0, l + k - 50); else m = Math.min(b.doc.height, m + k + 50);
            Q(b, [], {
                top: l,
                bottom: m
            });
        }
        if (bc < 20) if (null == g.wheelStartX) {
            g.wheelStartX = h.scrollLeft;
            g.wheelStartY = h.scrollTop;
            g.wheelDX = d;
            g.wheelDY = e;
            setTimeout(function() {
                if (null == g.wheelStartX) return;
                var a = h.scrollLeft - g.wheelStartX;
                var b = h.scrollTop - g.wheelStartY;
                var c = b && g.wheelDY && b / g.wheelDY || a && g.wheelDX && a / g.wheelDX;
                g.wheelStartX = g.wheelStartY = null;
                if (!c) return;
                cc = (cc * bc + c) / (bc + 1);
                ++bc;
            }, 200);
        } else {
            g.wheelDX += d;
            g.wheelDY += e;
        }
    }
    function ec(a, b, c) {
        if ("string" == typeof b) {
            b = jd[b];
            if (!b) return false;
        }
        if (a.display.pollingFast && Lb(a)) a.display.pollingFast = false;
        var d = a.doc, e = d.sel.shift, f = false;
        try {
            if (Ob(a)) a.state.suppressEdits = true;
            if (c) d.sel.shift = false;
            f = b(a) != We;
        } finally {
            d.sel.shift = e;
            a.state.suppressEdits = false;
        }
        return f;
    }
    function fc(a) {
        var b = a.state.keyMaps.slice(0);
        if (a.options.extraKeys) b.push(a.options.extraKeys);
        b.push(a.options.keyMap);
        return b;
    }
    var gc;
    function hc(a, b) {
        var c = ld(a.options.keyMap), e = c.auto;
        clearTimeout(gc);
        if (e && !nd(b)) gc = setTimeout(function() {
            if (ld(a.options.keyMap) == c) {
                a.options.keyMap = e.call ? e.call(null, a) : e;
                D(a);
            }
        }, 50);
        var f = od(b, true), g = false;
        if (!f) return false;
        var h = fc(a);
        if (b.shiftKey) g = md("Shift-" + f, h, function(b) {
            return ec(a, b, true);
        }) || md(f, h, function(b) {
            if ("string" == typeof b ? /^go[A-Z]/.test(b) : b.motion) return ec(a, b);
        }); else g = md(f, h, function(b) {
            return ec(a, b);
        });
        if (g) {
            Fe(b);
            ab(a);
            if (d) {
                b.oldKeyCode = b.keyCode;
                b.keyCode = 0;
            }
            Qe(a, "keyHandled", a, f, b);
        }
        return g;
    }
    function ic(a, b, c) {
        var d = md("'" + c + "'", fc(a), function(b) {
            return ec(a, b, true);
        });
        if (d) {
            Fe(b);
            ab(a);
            Qe(a, "keyHandled", a, "'" + c + "'", b);
        }
        return d;
    }
    var jc = null;
    function kc(a) {
        var c = this;
        if (!c.state.focused) mc(c);
        if (Re(c, a) || c.options.onKeyEvent && c.options.onKeyEvent(c, Ee(a))) return;
        if (b && 27 == a.keyCode) a.returnValue = false;
        var d = a.keyCode;
        c.doc.sel.shift = 16 == d || a.shiftKey;
        var e = hc(c, a);
        if (i) {
            jc = e ? d : null;
            if (!e && 88 == d && !yf && (q ? a.metaKey : a.ctrlKey)) c.replaceSelection("");
        }
    }
    function lc(a) {
        var c = this;
        if (Re(c, a) || c.options.onKeyEvent && c.options.onKeyEvent(c, Ee(a))) return;
        var e = a.keyCode, f = a.charCode;
        if (i && e == jc) {
            jc = null;
            Fe(a);
            return;
        }
        if ((i && (!a.which || a.which < 10) || k) && hc(c, a)) return;
        var g = String.fromCharCode(null == f ? e : f);
        if (this.options.electricChars && this.doc.mode.electricChars && this.options.smartIndent && !Ob(this) && this.doc.mode.electricChars.indexOf(g) > -1) setTimeout(Fb(c, function() {
            Uc(c, c.doc.sel.to.line, "smart");
        }), 75);
        if (ic(c, a, g)) return;
        if (b && !d) c.display.inputHasSelection = null;
        Kb(c);
    }
    function mc(a) {
        if ("nocursor" == a.options.readOnly) return;
        if (!a.state.focused) {
            Ne(a, "focus", a);
            a.state.focused = true;
            if (a.display.wrapper.className.search(/\bCodeMirror-focused\b/) == -1) a.display.wrapper.className += " CodeMirror-focused";
            if (!a.curOp) {
                Mb(a, true);
                if (f) setTimeout(ff(Mb, a, true), 0);
            }
        }
        Jb(a);
        ab(a);
    }
    function nc(a) {
        if (a.state.focused) {
            Ne(a, "blur", a);
            a.state.focused = false;
            a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-focused", "");
        }
        clearInterval(a.display.blinker);
        setTimeout(function() {
            if (!a.state.focused) a.doc.sel.shift = false;
        }, 150);
    }
    var oc;
    function pc(a, c) {
        if (Re(a, c, "contextmenu")) return;
        var e = a.display, f = a.doc.sel;
        if (Qb(e, c) || Wb(a, c)) return;
        var g = Rb(a, c), h = e.scroller.scrollTop;
        if (!g || i) return;
        var j = a.options.resetSelectionOnContextMenu;
        if (j && (Cc(f.from, f.to) || Dc(g, f.from) || !Dc(g, f.to))) Fb(a, Lc)(a.doc, g, g);
        var k = e.input.style.cssText;
        e.inputDiv.style.position = "absolute";
        e.input.style.cssText = "position: fixed; width: 30px; height: 30px; top: " + (c.clientY - 5) + "px; left: " + (c.clientX - 5) + "px; z-index: 1000; background: white; outline: none;" + "border-width: 0; outline: none; overflow: hidden; opacity: .05; -ms-opacity: .05; filter: alpha(opacity=5);";
        Nb(a);
        Mb(a, true);
        if (Cc(f.from, f.to)) e.input.value = e.prevInput = " ";
        function l() {
            if (null != e.input.selectionStart) {
                var a = e.input.value = "?" + (Cc(f.from, f.to) ? "" : e.input.value);
                e.prevInput = "?";
                e.input.selectionStart = 1;
                e.input.selectionEnd = a.length;
            }
        }
        function m() {
            e.inputDiv.style.position = "relative";
            e.input.style.cssText = k;
            if (d) e.scrollbarV.scrollTop = e.scroller.scrollTop = h;
            Jb(a);
            if (null != e.input.selectionStart) {
                if (!b || d) l();
                clearTimeout(oc);
                var c = 0, f = function() {
                    if (" " == e.prevInput && 0 == e.input.selectionStart) Fb(a, jd.selectAll)(a); else if (c++ < 10) oc = setTimeout(f, 500); else Mb(a);
                };
                oc = setTimeout(f, 200);
            }
        }
        if (b && !d) l();
        if (u) {
            Ie(c);
            var n = function() {
                Me(window, "mouseup", n);
                setTimeout(m, 20);
            };
            Le(window, "mouseup", n);
        } else setTimeout(m, 50);
    }
    var qc = x.changeEnd = function(a) {
        if (!a.text) return a.to;
        return Bc(a.from.line + a.text.length - 1, _e(a.text).length + (1 == a.text.length ? a.from.ch : 0));
    };
    function rc(a, b, c) {
        if (!Dc(b.from, c)) return Gc(a, c);
        var d = b.text.length - 1 - (b.to.line - b.from.line);
        if (c.line > b.to.line + d) {
            var e = c.line - d, f = a.first + a.size - 1;
            if (e > f) return Bc(f, le(a, f).text.length);
            return Hc(c, le(a, e).text.length);
        }
        if (c.line == b.to.line + d) return Hc(c, _e(b.text).length + (1 == b.text.length ? b.from.ch : 0) + le(a, b.to.line).text.length - b.to.ch);
        var g = c.line - b.from.line;
        return Hc(c, b.text[g].length + (g ? 0 : b.from.ch));
    }
    function sc(a, b, c) {
        if (c && "object" == typeof c) return {
            anchor: rc(a, b, c.anchor),
            head: rc(a, b, c.head)
        };
        if ("start" == c) return {
            anchor: b.from,
            head: b.from
        };
        var d = qc(b);
        if ("around" == c) return {
            anchor: b.from,
            head: d
        };
        if ("end" == c) return {
            anchor: d,
            head: d
        };
        var e = function(a) {
            if (Dc(a, b.from)) return a;
            if (!Dc(b.to, a)) return d;
            var c = a.line + b.text.length - (b.to.line - b.from.line) - 1, e = a.ch;
            if (a.line == b.to.line) e += d.ch - b.to.ch;
            return Bc(c, e);
        };
        return {
            anchor: e(a.sel.anchor),
            head: e(a.sel.head)
        };
    }
    function tc(a, b, c) {
        var d = {
            canceled: false,
            from: b.from,
            to: b.to,
            text: b.text,
            origin: b.origin,
            cancel: function() {
                this.canceled = true;
            }
        };
        if (c) d.update = function(b, c, d, e) {
            if (b) this.from = Gc(a, b);
            if (c) this.to = Gc(a, c);
            if (d) this.text = d;
            if (void 0 !== e) this.origin = e;
        };
        Ne(a, "beforeChange", a, d);
        if (a.cm) Ne(a.cm, "beforeChange", a.cm, d);
        if (d.canceled) return null;
        return {
            from: d.from,
            to: d.to,
            text: d.text,
            origin: d.origin
        };
    }
    function uc(a, b, c, d) {
        if (a.cm) {
            if (!a.cm.curOp) return Fb(a.cm, uc)(a, b, c, d);
            if (a.cm.state.suppressEdits) return;
        }
        if (Te(a, "beforeChange") || a.cm && Te(a.cm, "beforeChange")) {
            b = tc(a, b, true);
            if (!b) return;
        }
        var e = v && !d && Bd(a, b.from, b.to);
        if (e) {
            for (var f = e.length - 1; f >= 1; --f) vc(a, {
                from: e[f].from,
                to: e[f].to,
                text: [ "" ]
            });
            if (e.length) vc(a, {
                from: e[0].from,
                to: e[0].to,
                text: b.text
            }, c);
        } else vc(a, b, c);
    }
    function vc(a, b, c) {
        if (1 == b.text.length && "" == b.text[0] && Cc(b.from, b.to)) return;
        var d = sc(a, b, c);
        we(a, b, d, a.cm ? a.cm.curOp.id : 0/0);
        yc(a, b, d, zd(a, b));
        var e = [];
        je(a, function(a, c) {
            if (!c && bf(e, a.history) == -1) {
                Ce(a.history, b);
                e.push(a.history);
            }
            yc(a, b, null, zd(a, b));
        });
    }
    function wc(a, b) {
        if (a.cm && a.cm.state.suppressEdits) return;
        var c = a.history;
        var d = ("undo" == b ? c.done : c.undone).pop();
        if (!d) return;
        var e = {
            changes: [],
            anchorBefore: d.anchorAfter,
            headBefore: d.headAfter,
            anchorAfter: d.anchorBefore,
            headAfter: d.headBefore,
            generation: c.generation
        };
        ("undo" == b ? c.undone : c.done).push(e);
        c.generation = d.generation || ++c.maxGeneration;
        var f = Te(a, "beforeChange") || a.cm && Te(a.cm, "beforeChange");
        for (var g = d.changes.length - 1; g >= 0; --g) {
            var h = d.changes[g];
            h.origin = b;
            if (f && !tc(a, h, false)) {
                ("undo" == b ? c.done : c.undone).length = 0;
                return;
            }
            e.changes.push(ve(a, h));
            var i = g ? sc(a, h, null) : {
                anchor: d.anchorBefore,
                head: d.headBefore
            };
            yc(a, h, i, Ad(a, h));
            var j = [];
            je(a, function(a, b) {
                if (!b && bf(j, a.history) == -1) {
                    Ce(a.history, h);
                    j.push(a.history);
                }
                yc(a, h, null, Ad(a, h));
            });
        }
    }
    function xc(a, b) {
        function c(a) {
            return Bc(a.line + b, a.ch);
        }
        a.first += b;
        if (a.cm) Ib(a.cm, a.first, a.first, b);
        a.sel.head = c(a.sel.head);
        a.sel.anchor = c(a.sel.anchor);
        a.sel.from = c(a.sel.from);
        a.sel.to = c(a.sel.to);
    }
    function yc(a, b, c, d) {
        if (a.cm && !a.cm.curOp) return Fb(a.cm, yc)(a, b, c, d);
        if (b.to.line < a.first) {
            xc(a, b.text.length - 1 - (b.to.line - b.from.line));
            return;
        }
        if (b.from.line > a.lastLine()) return;
        if (b.from.line < a.first) {
            var e = b.text.length - 1 - (a.first - b.from.line);
            xc(a, e);
            b = {
                from: Bc(a.first, 0),
                to: Bc(b.to.line + e, b.to.ch),
                text: [ _e(b.text) ],
                origin: b.origin
            };
        }
        var f = a.lastLine();
        if (b.to.line > f) b = {
            from: b.from,
            to: Bc(f, le(a, f).text.length),
            text: [ b.text[0] ],
            origin: b.origin
        };
        b.removed = me(a, b.from, b.to);
        if (!c) c = sc(a, b, null);
        if (a.cm) zc(a.cm, b, d, c); else ce(a, b, d, c);
    }
    function zc(a, b, c, d) {
        var e = a.doc, f = a.display, g = b.from, h = b.to;
        var i = false, j = g.line;
        if (!a.options.lineWrapping) {
            j = pe(Fd(e, le(e, g.line)));
            e.iter(j, h.line + 1, function(a) {
                if (a == f.maxLine) {
                    i = true;
                    return true;
                }
            });
        }
        if (!Dc(e.sel.head, b.from) && !Dc(b.to, e.sel.head)) a.curOp.cursorActivity = true;
        ce(e, b, c, d, B(a));
        if (!a.options.lineWrapping) {
            e.iter(j, g.line + b.text.length, function(a) {
                var b = H(e, a);
                if (b > f.maxLineLength) {
                    f.maxLine = a;
                    f.maxLineLength = b;
                    f.maxLineChanged = true;
                    i = false;
                }
            });
            if (i) a.curOp.updateMaxLine = true;
        }
        e.frontier = Math.min(e.frontier, g.line);
        bb(a, 400);
        var k = b.text.length - (h.line - g.line) - 1;
        Ib(a, g.line, h.line + 1, k);
        if (Te(a, "change")) {
            var l = {
                from: g,
                to: h,
                text: b.text,
                removed: b.removed,
                origin: b.origin
            };
            if (a.curOp.textChanged) {
                for (var m = a.curOp.textChanged; m.next; m = m.next) ;
                m.next = l;
            } else a.curOp.textChanged = l;
        }
    }
    function Ac(a, b, c, d, e) {
        if (!d) d = c;
        if (Dc(d, c)) {
            var f = d;
            d = c;
            c = f;
        }
        if ("string" == typeof b) b = wf(b);
        uc(a, {
            from: c,
            to: d,
            text: b,
            origin: e
        }, null);
    }
    function Bc(a, b) {
        if (!(this instanceof Bc)) return new Bc(a, b);
        this.line = a;
        this.ch = b;
    }
    x.Pos = Bc;
    function Cc(a, b) {
        return a.line == b.line && a.ch == b.ch;
    }
    function Dc(a, b) {
        return a.line < b.line || a.line == b.line && a.ch < b.ch;
    }
    function Ec(a) {
        return Bc(a.line, a.ch);
    }
    function Fc(a, b) {
        return Math.max(a.first, Math.min(b, a.first + a.size - 1));
    }
    function Gc(a, b) {
        if (b.line < a.first) return Bc(a.first, 0);
        var c = a.first + a.size - 1;
        if (b.line > c) return Bc(c, le(a, c).text.length);
        return Hc(b, le(a, b.line).text.length);
    }
    function Hc(a, b) {
        var c = a.ch;
        if (null == c || c > b) return Bc(a.line, b); else if (c < 0) return Bc(a.line, 0); else return a;
    }
    function Ic(a, b) {
        return b >= a.first && b < a.first + a.size;
    }
    function Jc(a, b, c, d) {
        if (a.sel.shift || a.sel.extend) {
            var e = a.sel.anchor;
            if (c) {
                var f = Dc(b, e);
                if (f != Dc(c, e)) {
                    e = b;
                    b = c;
                } else if (f != Dc(b, c)) b = c;
            }
            Lc(a, e, b, d);
        } else Lc(a, b, c || b, d);
        if (a.cm) a.cm.curOp.userSelChange = true;
    }
    function Kc(a, b, c) {
        var d = {
            anchor: b,
            head: c
        };
        Ne(a, "beforeSelectionChange", a, d);
        if (a.cm) Ne(a.cm, "beforeSelectionChange", a.cm, d);
        d.anchor = Gc(a, d.anchor);
        d.head = Gc(a, d.head);
        return d;
    }
    function Lc(a, b, c, d, e) {
        if (!e && Te(a, "beforeSelectionChange") || a.cm && Te(a.cm, "beforeSelectionChange")) {
            var f = Kc(a, b, c);
            c = f.head;
            b = f.anchor;
        }
        var g = a.sel;
        g.goalColumn = null;
        if (null == d) d = Dc(c, g.head) ? -1 : 1;
        if (e || !Cc(b, g.anchor)) b = Nc(a, b, d, "push" != e);
        if (e || !Cc(c, g.head)) c = Nc(a, c, d, "push" != e);
        if (Cc(g.anchor, b) && Cc(g.head, c)) return;
        g.anchor = b;
        g.head = c;
        var h = Dc(c, b);
        g.from = h ? c : b;
        g.to = h ? b : c;
        if (a.cm) a.cm.curOp.updateInput = a.cm.curOp.selectionChanged = a.cm.curOp.cursorActivity = true;
        Qe(a, "cursorActivity", a);
    }
    function Mc(a) {
        Lc(a.doc, a.doc.sel.from, a.doc.sel.to, null, "push");
    }
    function Nc(a, b, c, d) {
        var e = false, f = b;
        var g = c || 1;
        a.cantEdit = false;
        a: for (;;) {
            var h = le(a, f.line);
            if (h.markedSpans) for (var i = 0; i < h.markedSpans.length; ++i) {
                var j = h.markedSpans[i], k = j.marker;
                if ((null == j.from || (k.inclusiveLeft ? j.from <= f.ch : j.from < f.ch)) && (null == j.to || (k.inclusiveRight ? j.to >= f.ch : j.to > f.ch))) {
                    if (d) {
                        Ne(k, "beforeCursorEnter");
                        if (k.explicitlyCleared) if (!h.markedSpans) break; else {
                            --i;
                            continue;
                        }
                    }
                    if (!k.atomic) continue;
                    var l = k.find()[g < 0 ? "from" : "to"];
                    if (Cc(l, f)) {
                        l.ch += g;
                        if (l.ch < 0) if (l.line > a.first) l = Gc(a, Bc(l.line - 1)); else l = null; else if (l.ch > h.text.length) if (l.line < a.first + a.size - 1) l = Bc(l.line + 1, 0); else l = null;
                        if (!l) {
                            if (e) {
                                if (!d) return Nc(a, b, c, true);
                                a.cantEdit = true;
                                return Bc(a.first, 0);
                            }
                            e = true;
                            l = b;
                            g = -g;
                        }
                    }
                    f = l;
                    continue a;
                }
            }
            return f;
        }
    }
    function Oc(a) {
        var b = Pc(a, a.doc.sel.head, null, a.options.cursorScrollMargin);
        if (!a.state.focused) return;
        var c = a.display, d = pf(c.sizer), e = null;
        if (b.top + d.top < 0) e = true; else if (b.bottom + d.top > (window.innerHeight || document.documentElement.clientHeight)) e = false;
        if (null != e && !n) {
            var f = "none" == c.cursor.style.display;
            if (f) {
                c.cursor.style.display = "";
                c.cursor.style.left = b.left + "px";
                c.cursor.style.top = b.top - c.viewOffset + "px";
            }
            c.cursor.scrollIntoView(e);
            if (f) c.cursor.style.display = "none";
        }
    }
    function Pc(a, b, c, d) {
        if (null == d) d = 0;
        for (;;) {
            var e = false, f = vb(a, b);
            var g = !c || c == b ? f : vb(a, c);
            var h = Rc(a, Math.min(f.left, g.left), Math.min(f.top, g.top) - d, Math.max(f.left, g.left), Math.max(f.bottom, g.bottom) + d);
            var i = a.doc.scrollTop, j = a.doc.scrollLeft;
            if (null != h.scrollTop) {
                _b(a, h.scrollTop);
                if (Math.abs(a.doc.scrollTop - i) > 1) e = true;
            }
            if (null != h.scrollLeft) {
                ac(a, h.scrollLeft);
                if (Math.abs(a.doc.scrollLeft - j) > 1) e = true;
            }
            if (!e) return f;
        }
    }
    function Qc(a, b, c, d, e) {
        var f = Rc(a, b, c, d, e);
        if (null != f.scrollTop) _b(a, f.scrollTop);
        if (null != f.scrollLeft) ac(a, f.scrollLeft);
    }
    function Rc(a, b, c, d, e) {
        var f = a.display, g = Ab(a.display);
        if (c < 0) c = 0;
        var h = f.scroller.clientHeight - Ve, i = f.scroller.scrollTop, j = {};
        var k = a.doc.height + gb(f);
        var l = c < g, m = e > k - g;
        if (c < i) j.scrollTop = l ? 0 : c; else if (e > i + h) {
            var n = Math.min(c, (m ? k : e) - h);
            if (n != i) j.scrollTop = n;
        }
        var o = f.scroller.clientWidth - Ve, p = f.scroller.scrollLeft;
        b += f.gutters.offsetWidth;
        d += f.gutters.offsetWidth;
        var q = f.gutters.offsetWidth;
        var r = b < q + 10;
        if (b < p + q || r) {
            if (r) b = 0;
            j.scrollLeft = Math.max(0, b - 10 - q);
        } else if (d > o + p - 3) j.scrollLeft = d + 10 - o;
        return j;
    }
    function Sc(a, b, c) {
        a.curOp.updateScrollPos = {
            scrollLeft: null == b ? a.doc.scrollLeft : b,
            scrollTop: null == c ? a.doc.scrollTop : c
        };
    }
    function Tc(a, b, c) {
        var d = a.curOp.updateScrollPos || (a.curOp.updateScrollPos = {
            scrollLeft: a.doc.scrollLeft,
            scrollTop: a.doc.scrollTop
        });
        var e = a.display.scroller;
        d.scrollTop = Math.max(0, Math.min(e.scrollHeight - e.clientHeight, d.scrollTop + c));
        d.scrollLeft = Math.max(0, Math.min(e.scrollWidth - e.clientWidth, d.scrollLeft + b));
    }
    function Uc(a, b, c, d) {
        var e = a.doc;
        if (null == c) c = "add";
        if ("smart" == c) if (!a.doc.mode.indent) c = "prev"; else var f = eb(a, b);
        var g = a.options.tabSize;
        var h = le(e, b), i = Ye(h.text, null, g);
        var j = h.text.match(/^\s*/)[0], k;
        if ("smart" == c) {
            k = a.doc.mode.indent(f, h.text.slice(j.length), h.text);
            if (k == We) {
                if (!d) return;
                c = "prev";
            }
        }
        if ("prev" == c) if (b > e.first) k = Ye(le(e, b - 1).text, null, g); else k = 0; else if ("add" == c) k = i + a.options.indentUnit; else if ("subtract" == c) k = i - a.options.indentUnit; else if ("number" == typeof c) k = i + c;
        k = Math.max(0, k);
        var l = "", m = 0;
        if (a.options.indentWithTabs) for (var n = Math.floor(k / g); n; --n) {
            m += g;
            l += "	";
        }
        if (m < k) l += $e(k - m);
        if (l != j) Ac(a.doc, l, Bc(b, 0), Bc(b, j.length), "+input"); else if (e.sel.head.line == b && e.sel.head.ch < j.length) Lc(e, Bc(b, j.length), Bc(b, j.length), 1);
        h.stateAfter = null;
    }
    function Vc(a, b, c) {
        var d = b, e = b, f = a.doc;
        if ("number" == typeof b) e = le(f, Fc(f, b)); else d = pe(b);
        if (null == d) return null;
        if (c(e, d)) Ib(a, d, d + 1); else return null;
        return e;
    }
    function Wc(a, b, c, d, e) {
        var f = b.line, g = b.ch, h = c;
        var i = le(a, f);
        var j = true;
        function k() {
            var b = f + c;
            if (b < a.first || b >= a.first + a.size) return j = false;
            f = b;
            return i = le(a, b);
        }
        function l(a) {
            var b = (e ? Lf : Mf)(i, g, c, true);
            if (null == b) if (!a && k()) if (e) g = (c < 0 ? Ef : Df)(i); else g = c < 0 ? i.text.length : 0; else return j = false; else g = b;
            return true;
        }
        if ("char" == d) l(); else if ("column" == d) l(true); else if ("word" == d || "group" == d) {
            var m = null, n = "group" == d;
            for (var o = true; ;o = false) {
                if (c < 0 && !l(!o)) break;
                var p = i.text.charAt(g) || "\n";
                var q = hf(p) ? "w" : !n ? null : /\s/.test(p) ? null : "p";
                if (m && m != q) {
                    if (c < 0) {
                        c = 1;
                        l();
                    }
                    break;
                }
                if (q) m = q;
                if (c > 0 && !l(!o)) break;
            }
        }
        var r = Nc(a, Bc(f, g), h, true);
        if (!j) r.hitSide = true;
        return r;
    }
    function Xc(a, b, c, d) {
        var e = a.doc, f = b.left, g;
        if ("page" == d) {
            var h = Math.min(a.display.wrapper.clientHeight, window.innerHeight || document.documentElement.clientHeight);
            g = b.top + c * (h - (c < 0 ? 1.5 : .5) * Ab(a.display));
        } else if ("line" == d) g = c > 0 ? b.bottom + 3 : b.top - 3;
        for (;;) {
            var i = xb(a, f, g);
            if (!i.outside) break;
            if (c < 0 ? g <= 0 : g >= e.height) {
                i.hitSide = true;
                break;
            }
            g += 5 * c;
        }
        return i;
    }
    function Yc(a, b) {
        var c = b.ch, d = b.ch;
        if (a) {
            if ((b.xRel < 0 || d == a.length) && c) --c; else ++d;
            var e = a.charAt(c);
            var f = hf(e) ? hf : /\s/.test(e) ? function(a) {
                return /\s/.test(a);
            } : function(a) {
                return !/\s/.test(a) && !hf(a);
            };
            while (c > 0 && f(a.charAt(c - 1))) --c;
            while (d < a.length && f(a.charAt(d))) ++d;
        }
        return {
            from: Bc(b.line, c),
            to: Bc(b.line, d)
        };
    }
    function Zc(a, b) {
        Jc(a.doc, Bc(b, 0), Gc(a.doc, Bc(b + 1, 0)));
    }
    x.prototype = {
        constructor: x,
        focus: function() {
            window.focus();
            Nb(this);
            Kb(this);
        },
        setOption: function(a, b) {
            var c = this.options, d = c[a];
            if (c[a] == b && "mode" != a) return;
            c[a] = b;
            if ($c.hasOwnProperty(a)) Fb(this, $c[a])(this, b, d);
        },
        getOption: function(a) {
            return this.options[a];
        },
        getDoc: function() {
            return this.doc;
        },
        addKeyMap: function(a, b) {
            this.state.keyMaps[b ? "push" : "unshift"](a);
        },
        removeKeyMap: function(a) {
            var b = this.state.keyMaps;
            for (var c = 0; c < b.length; ++c) if (b[c] == a || "string" != typeof b[c] && b[c].name == a) {
                b.splice(c, 1);
                return true;
            }
        },
        addOverlay: Fb(null, function(a, b) {
            var c = a.token ? a : x.getMode(this.options, a);
            if (c.startState) throw new Error("Overlays may not be stateful.");
            this.state.overlays.push({
                mode: c,
                modeSpec: a,
                opaque: b && b.opaque
            });
            this.state.modeGen++;
            Ib(this);
        }),
        removeOverlay: Fb(null, function(a) {
            var b = this.state.overlays;
            for (var c = 0; c < b.length; ++c) {
                var d = b[c].modeSpec;
                if (d == a || "string" == typeof a && d.name == a) {
                    b.splice(c, 1);
                    this.state.modeGen++;
                    Ib(this);
                    return;
                }
            }
        }),
        indentLine: Fb(null, function(a, b, c) {
            if ("string" != typeof b && "number" != typeof b) if (null == b) b = this.options.smartIndent ? "smart" : "prev"; else b = b ? "add" : "subtract";
            if (Ic(this.doc, a)) Uc(this, a, b, c);
        }),
        indentSelection: Fb(null, function(a) {
            var b = this.doc.sel;
            if (Cc(b.from, b.to)) return Uc(this, b.from.line, a);
            var c = b.to.line - (b.to.ch ? 0 : 1);
            for (var d = b.from.line; d <= c; ++d) Uc(this, d, a);
        }),
        getTokenAt: function(a, b) {
            var c = this.doc;
            a = Gc(c, a);
            var d = eb(this, a.line, b), e = this.doc.mode;
            var f = le(c, a.line);
            var g = new pd(f.text, this.options.tabSize);
            while (g.pos < a.ch && !g.eol()) {
                g.start = g.pos;
                var h = e.token(g, d);
            }
            return {
                start: g.start,
                end: g.pos,
                string: g.current(),
                className: h || null,
                type: h || null,
                state: d
            };
        },
        getTokenTypeAt: function(a) {
            a = Gc(this.doc, a);
            var b = Td(this, le(this.doc, a.line));
            var c = 0, d = (b.length - 1) / 2, e = a.ch;
            if (0 == e) return b[2];
            for (;;) {
                var f = c + d >> 1;
                if ((f ? b[2 * f - 1] : 0) >= e) d = f; else if (b[2 * f + 1] < e) c = f + 1; else return b[2 * f + 2];
            }
        },
        getModeAt: function(a) {
            var b = this.doc.mode;
            if (!b.innerMode) return b;
            return x.innerMode(b, this.getTokenAt(a).state).mode;
        },
        getHelper: function(a, b) {
            if (!gd.hasOwnProperty(b)) return;
            var c = gd[b], d = this.getModeAt(a);
            return d[b] && c[d[b]] || d.helperType && c[d.helperType] || c[d.name];
        },
        getStateAfter: function(a, b) {
            var c = this.doc;
            a = Fc(c, null == a ? c.first + c.size - 1 : a);
            return eb(this, a + 1, b);
        },
        cursorCoords: function(a, b) {
            var c, d = this.doc.sel;
            if (null == a) c = d.head; else if ("object" == typeof a) c = Gc(this.doc, a); else c = a ? d.from : d.to;
            return vb(this, c, b || "page");
        },
        charCoords: function(a, b) {
            return ub(this, Gc(this.doc, a), b || "page");
        },
        coordsChar: function(a, b) {
            a = tb(this, a, b || "page");
            return xb(this, a.left, a.top);
        },
        lineAtHeight: function(a, b) {
            a = tb(this, {
                top: a,
                left: 0
            }, b || "page").top;
            return qe(this.doc, a + this.display.viewOffset);
        },
        heightAtLine: function(a, b) {
            var c = false, d = this.doc.first + this.doc.size - 1;
            if (a < this.doc.first) a = this.doc.first; else if (a > d) {
                a = d;
                c = true;
            }
            var e = le(this.doc, a);
            return sb(this, le(this.doc, a), {
                top: 0,
                left: 0
            }, b || "page").top + (c ? e.height : 0);
        },
        defaultTextHeight: function() {
            return Ab(this.display);
        },
        defaultCharWidth: function() {
            return Bb(this.display);
        },
        setGutterMarker: Fb(null, function(a, b, c) {
            return Vc(this, a, function(a) {
                var d = a.gutterMarkers || (a.gutterMarkers = {});
                d[b] = c;
                if (!c && jf(d)) a.gutterMarkers = null;
                return true;
            });
        }),
        clearGutter: Fb(null, function(a) {
            var b = this, c = b.doc, d = c.first;
            c.iter(function(c) {
                if (c.gutterMarkers && c.gutterMarkers[a]) {
                    c.gutterMarkers[a] = null;
                    Ib(b, d, d + 1);
                    if (jf(c.gutterMarkers)) c.gutterMarkers = null;
                }
                ++d;
            });
        }),
        addLineClass: Fb(null, function(a, b, c) {
            return Vc(this, a, function(a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass";
                if (!a[d]) a[d] = c; else if (new RegExp("(?:^|\\s)" + c + "(?:$|\\s)").test(a[d])) return false; else a[d] += " " + c;
                return true;
            });
        }),
        removeLineClass: Fb(null, function(a, b, c) {
            return Vc(this, a, function(a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass";
                var e = a[d];
                if (!e) return false; else if (null == c) a[d] = null; else {
                    var f = e.match(new RegExp("(?:^|\\s+)" + c + "(?:$|\\s+)"));
                    if (!f) return false;
                    var g = f.index + f[0].length;
                    a[d] = e.slice(0, f.index) + (!f.index || g == e.length ? "" : " ") + e.slice(g) || null;
                }
                return true;
            });
        }),
        addLineWidget: Fb(null, function(a, b, c) {
            return Nd(this, a, b, c);
        }),
        removeLineWidget: function(a) {
            a.clear();
        },
        lineInfo: function(a) {
            if ("number" == typeof a) {
                if (!Ic(this.doc, a)) return null;
                var b = a;
                a = le(this.doc, a);
                if (!a) return null;
            } else {
                var b = pe(a);
                if (null == b) return null;
            }
            return {
                line: b,
                handle: a,
                text: a.text,
                gutterMarkers: a.gutterMarkers,
                textClass: a.textClass,
                bgClass: a.bgClass,
                wrapClass: a.wrapClass,
                widgets: a.widgets
            };
        },
        getViewport: function() {
            return {
                from: this.display.showingFrom,
                to: this.display.showingTo
            };
        },
        addWidget: function(a, b, c, d, e) {
            var f = this.display;
            a = vb(this, Gc(this.doc, a));
            var g = a.bottom, h = a.left;
            b.style.position = "absolute";
            f.sizer.appendChild(b);
            if ("over" == d) g = a.top; else if ("above" == d || "near" == d) {
                var i = Math.max(f.wrapper.clientHeight, this.doc.height), j = Math.max(f.sizer.clientWidth, f.lineSpace.clientWidth);
                if (("above" == d || a.bottom + b.offsetHeight > i) && a.top > b.offsetHeight) g = a.top - b.offsetHeight; else if (a.bottom + b.offsetHeight <= i) g = a.bottom;
                if (h + b.offsetWidth > j) h = j - b.offsetWidth;
            }
            b.style.top = g + "px";
            b.style.left = b.style.right = "";
            if ("right" == e) {
                h = f.sizer.clientWidth - b.offsetWidth;
                b.style.right = "0px";
            } else {
                if ("left" == e) h = 0; else if ("middle" == e) h = (f.sizer.clientWidth - b.offsetWidth) / 2;
                b.style.left = h + "px";
            }
            if (c) Qc(this, h, g, h + b.offsetWidth, g + b.offsetHeight);
        },
        triggerOnKeyDown: Fb(null, kc),
        execCommand: function(a) {
            return jd[a](this);
        },
        findPosH: function(a, b, c, d) {
            var e = 1;
            if (b < 0) {
                e = -1;
                b = -b;
            }
            for (var f = 0, g = Gc(this.doc, a); f < b; ++f) {
                g = Wc(this.doc, g, e, c, d);
                if (g.hitSide) break;
            }
            return g;
        },
        moveH: Fb(null, function(a, b) {
            var c = this.doc.sel, d;
            if (c.shift || c.extend || Cc(c.from, c.to)) d = Wc(this.doc, c.head, a, b, this.options.rtlMoveVisually); else d = a < 0 ? c.from : c.to;
            Jc(this.doc, d, d, a);
        }),
        deleteH: Fb(null, function(a, b) {
            var c = this.doc.sel;
            if (!Cc(c.from, c.to)) Ac(this.doc, "", c.from, c.to, "+delete"); else Ac(this.doc, "", c.from, Wc(this.doc, c.head, a, b, false), "+delete");
            this.curOp.userSelChange = true;
        }),
        findPosV: function(a, b, c, d) {
            var e = 1, f = d;
            if (b < 0) {
                e = -1;
                b = -b;
            }
            for (var g = 0, h = Gc(this.doc, a); g < b; ++g) {
                var i = vb(this, h, "div");
                if (null == f) f = i.left; else i.left = f;
                h = Xc(this, i, e, c);
                if (h.hitSide) break;
            }
            return h;
        },
        moveV: Fb(null, function(a, b) {
            var c = this.doc.sel;
            var d = vb(this, c.head, "div");
            if (null != c.goalColumn) d.left = c.goalColumn;
            var e = Xc(this, d, a, b);
            if ("page" == b) Tc(this, 0, ub(this, e, "div").top - d.top);
            Jc(this.doc, e, e, a);
            c.goalColumn = d.left;
        }),
        toggleOverwrite: function(a) {
            if (null != a && a == this.state.overwrite) return;
            if (this.state.overwrite = !this.state.overwrite) this.display.cursor.className += " CodeMirror-overwrite"; else this.display.cursor.className = this.display.cursor.className.replace(" CodeMirror-overwrite", "");
        },
        hasFocus: function() {
            return this.state.focused;
        },
        scrollTo: Fb(null, function(a, b) {
            Sc(this, a, b);
        }),
        getScrollInfo: function() {
            var a = this.display.scroller, b = Ve;
            return {
                left: a.scrollLeft,
                top: a.scrollTop,
                height: a.scrollHeight - b,
                width: a.scrollWidth - b,
                clientHeight: a.clientHeight - b,
                clientWidth: a.clientWidth - b
            };
        },
        scrollIntoView: Fb(null, function(a, b) {
            if (null == a) a = {
                from: this.doc.sel.head,
                to: null
            }; else if ("number" == typeof a) a = {
                from: Bc(a, 0),
                to: null
            }; else if (null == a.from) a = {
                from: a,
                to: null
            };
            if (!a.to) a.to = a.from;
            if (!b) b = 0;
            var c = a;
            if (null != a.from.line) {
                this.curOp.scrollToPos = {
                    from: a.from,
                    to: a.to,
                    margin: b
                };
                c = {
                    from: vb(this, a.from),
                    to: vb(this, a.to)
                };
            }
            var d = Rc(this, Math.min(c.from.left, c.to.left), Math.min(c.from.top, c.to.top) - b, Math.max(c.from.right, c.to.right), Math.max(c.from.bottom, c.to.bottom) + b);
            Sc(this, d.scrollLeft, d.scrollTop);
        }),
        setSize: Fb(null, function(a, b) {
            function c(a) {
                return "number" == typeof a || /^\d+$/.test(String(a)) ? a + "px" : a;
            }
            if (null != a) this.display.wrapper.style.width = c(a);
            if (null != b) this.display.wrapper.style.height = c(b);
            if (this.options.lineWrapping) this.display.measureLineCache.length = this.display.measureLineCachePos = 0;
            this.curOp.forceUpdate = true;
        }),
        operation: function(a) {
            return Hb(this, a);
        },
        refresh: Fb(null, function() {
            var a = null == this.display.cachedTextHeight;
            pb(this);
            Sc(this, this.doc.scrollLeft, this.doc.scrollTop);
            Ib(this);
            if (a) C(this);
        }),
        swapDoc: Fb(null, function(a) {
            var b = this.doc;
            b.cm = null;
            ke(this, a);
            pb(this);
            Mb(this, true);
            Sc(this, a.scrollLeft, a.scrollTop);
            Qe(this, "swapDoc", this, b);
            return b;
        }),
        getInputField: function() {
            return this.display.input;
        },
        getWrapperElement: function() {
            return this.display.wrapper;
        },
        getScrollerElement: function() {
            return this.display.scroller;
        },
        getGutterElement: function() {
            return this.display.gutters;
        }
    };
    Ue(x);
    var $c = x.optionHandlers = {};
    var _c = x.defaults = {};
    function ad(a, b, c, d) {
        x.defaults[a] = b;
        if (c) $c[a] = d ? function(a, b, d) {
            if (d != bd) c(a, b, d);
        } : c;
    }
    var bd = x.Init = {
        toString: function() {
            return "CodeMirror.Init";
        }
    };
    ad("value", "", function(a, b) {
        a.setValue(b);
    }, true);
    ad("mode", null, function(a, b) {
        a.doc.modeOption = b;
        z(a);
    }, true);
    ad("indentUnit", 2, z, true);
    ad("indentWithTabs", false);
    ad("smartIndent", true);
    ad("tabSize", 4, function(a) {
        z(a);
        pb(a);
        Ib(a);
    }, true);
    ad("specialChars", /[\t\u0000-\u0019\u00ad\u200b\u2028\u2029\ufeff]/g, function(a, b) {
        a.options.specialChars = new RegExp(b.source + (b.test("	") ? "" : "|	"), "g");
        a.refresh();
    }, true);
    ad("specialCharPlaceholder", Yd, function(a) {
        a.refresh();
    }, true);
    ad("electricChars", true);
    ad("rtlMoveVisually", !r);
    ad("wholeLineUpdateBefore", true);
    ad("theme", "default", function(a) {
        E(a);
        F(a);
    }, true);
    ad("keyMap", "default", D);
    ad("extraKeys", null);
    ad("onKeyEvent", null);
    ad("onDragEvent", null);
    ad("lineWrapping", false, A, true);
    ad("gutters", [], function(a) {
        J(a.options);
        F(a);
    }, true);
    ad("fixedGutter", true, function(a, b) {
        a.display.gutters.style.left = b ? P(a.display) + "px" : "0";
        a.refresh();
    }, true);
    ad("coverGutterNextToScrollbar", false, K, true);
    ad("lineNumbers", false, function(a) {
        J(a.options);
        F(a);
    }, true);
    ad("firstLineNumber", 1, F, true);
    ad("lineNumberFormatter", function(a) {
        return a;
    }, F, true);
    ad("showCursorWhenSelecting", false, Z, true);
    ad("resetSelectionOnContextMenu", true);
    ad("readOnly", false, function(a, b) {
        if ("nocursor" == b) {
            nc(a);
            a.display.input.blur();
            a.display.disabled = true;
        } else {
            a.display.disabled = false;
            if (!b) Mb(a, true);
        }
    });
    ad("dragDrop", true);
    ad("cursorBlinkRate", 530);
    ad("cursorScrollMargin", 0);
    ad("cursorHeight", 1);
    ad("workTime", 100);
    ad("workDelay", 100);
    ad("flattenSpans", true);
    ad("pollInterval", 100);
    ad("undoDepth", 40, function(a, b) {
        a.doc.history.undoDepth = b;
    });
    ad("historyEventDelay", 500);
    ad("viewportMargin", 10, function(a) {
        a.refresh();
    }, true);
    ad("maxHighlightLength", 1e4, function(a) {
        z(a);
        a.refresh();
    }, true);
    ad("crudeMeasuringFrom", 1e4);
    ad("moveInputWithCursor", true, function(a, b) {
        if (!b) a.display.inputDiv.style.top = a.display.inputDiv.style.left = 0;
    });
    ad("tabindex", null, function(a, b) {
        a.display.input.tabIndex = b || "";
    });
    ad("autofocus", null);
    var cd = x.modes = {}, dd = x.mimeModes = {};
    x.defineMode = function(a, b) {
        if (!x.defaults.mode && "null" != a) x.defaults.mode = a;
        if (arguments.length > 2) {
            b.dependencies = [];
            for (var c = 2; c < arguments.length; ++c) b.dependencies.push(arguments[c]);
        }
        cd[a] = b;
    };
    x.defineMIME = function(a, b) {
        dd[a] = b;
    };
    x.resolveMode = function(a) {
        if ("string" == typeof a && dd.hasOwnProperty(a)) a = dd[a]; else if (a && "string" == typeof a.name && dd.hasOwnProperty(a.name)) {
            var b = dd[a.name];
            a = cf(b, a);
            a.name = b.name;
        } else if ("string" == typeof a && /^[\w\-]+\/[\w\-]+\+xml$/.test(a)) return x.resolveMode("application/xml");
        if ("string" == typeof a) return {
            name: a
        }; else return a || {
            name: "null"
        };
    };
    x.getMode = function(a, b) {
        var b = x.resolveMode(b);
        var c = cd[b.name];
        if (!c) return x.getMode(a, "text/plain");
        var d = c(a, b);
        if (ed.hasOwnProperty(b.name)) {
            var e = ed[b.name];
            for (var f in e) {
                if (!e.hasOwnProperty(f)) continue;
                if (d.hasOwnProperty(f)) d["_" + f] = d[f];
                d[f] = e[f];
            }
        }
        d.name = b.name;
        return d;
    };
    x.defineMode("null", function() {
        return {
            token: function(a) {
                a.skipToEnd();
            }
        };
    });
    x.defineMIME("text/plain", "null");
    var ed = x.modeExtensions = {};
    x.extendMode = function(a, b) {
        var c = ed.hasOwnProperty(a) ? ed[a] : ed[a] = {};
        df(b, c);
    };
    x.defineExtension = function(a, b) {
        x.prototype[a] = b;
    };
    x.defineDocExtension = function(a, b) {
        ge.prototype[a] = b;
    };
    x.defineOption = ad;
    var fd = [];
    x.defineInitHook = function(a) {
        fd.push(a);
    };
    var gd = x.helpers = {};
    x.registerHelper = function(a, b, c) {
        if (!gd.hasOwnProperty(a)) gd[a] = x[a] = {};
        gd[a][b] = c;
    };
    x.isWordChar = hf;
    function hd(a, b) {
        if (true === b) return b;
        if (a.copyState) return a.copyState(b);
        var c = {};
        for (var d in b) {
            var e = b[d];
            if (e instanceof Array) e = e.concat([]);
            c[d] = e;
        }
        return c;
    }
    x.copyState = hd;
    function id(a, b, c) {
        return a.startState ? a.startState(b, c) : true;
    }
    x.startState = id;
    x.innerMode = function(a, b) {
        while (a.innerMode) {
            var c = a.innerMode(b);
            if (!c || c.mode == a) break;
            b = c.state;
            a = c.mode;
        }
        return c || {
            mode: a,
            state: b
        };
    };
    var jd = x.commands = {
        selectAll: function(a) {
            a.setSelection(Bc(a.firstLine(), 0), Bc(a.lastLine()));
        },
        killLine: function(a) {
            var b = a.getCursor(true), c = a.getCursor(false), d = !Cc(b, c);
            if (!d && a.getLine(b.line).length == b.ch) a.replaceRange("", b, Bc(b.line + 1, 0), "+delete"); else a.replaceRange("", b, d ? c : Bc(b.line), "+delete");
        },
        deleteLine: function(a) {
            var b = a.getCursor().line;
            a.replaceRange("", Bc(b, 0), Bc(b), "+delete");
        },
        delLineLeft: function(a) {
            var b = a.getCursor();
            a.replaceRange("", Bc(b.line, 0), b, "+delete");
        },
        undo: function(a) {
            a.undo();
        },
        redo: function(a) {
            a.redo();
        },
        goDocStart: function(a) {
            a.extendSelection(Bc(a.firstLine(), 0));
        },
        goDocEnd: function(a) {
            a.extendSelection(Bc(a.lastLine()));
        },
        goLineStart: function(a) {
            a.extendSelection(Ff(a, a.getCursor().line));
        },
        goLineStartSmart: function(a) {
            var b = a.getCursor(), c = Ff(a, b.line);
            var d = a.getLineHandle(c.line);
            var e = se(d);
            if (!e || 0 == e[0].level) {
                var f = Math.max(0, d.text.search(/\S/));
                var g = b.line == c.line && b.ch <= f && b.ch;
                a.extendSelection(Bc(c.line, g ? 0 : f));
            } else a.extendSelection(c);
        },
        goLineEnd: function(a) {
            a.extendSelection(Gf(a, a.getCursor().line));
        },
        goLineRight: function(a) {
            var b = a.charCoords(a.getCursor(), "div").top + 5;
            a.extendSelection(a.coordsChar({
                left: a.display.lineDiv.offsetWidth + 100,
                top: b
            }, "div"));
        },
        goLineLeft: function(a) {
            var b = a.charCoords(a.getCursor(), "div").top + 5;
            a.extendSelection(a.coordsChar({
                left: 0,
                top: b
            }, "div"));
        },
        goLineUp: function(a) {
            a.moveV(-1, "line");
        },
        goLineDown: function(a) {
            a.moveV(1, "line");
        },
        goPageUp: function(a) {
            a.moveV(-1, "page");
        },
        goPageDown: function(a) {
            a.moveV(1, "page");
        },
        goCharLeft: function(a) {
            a.moveH(-1, "char");
        },
        goCharRight: function(a) {
            a.moveH(1, "char");
        },
        goColumnLeft: function(a) {
            a.moveH(-1, "column");
        },
        goColumnRight: function(a) {
            a.moveH(1, "column");
        },
        goWordLeft: function(a) {
            a.moveH(-1, "word");
        },
        goGroupRight: function(a) {
            a.moveH(1, "group");
        },
        goGroupLeft: function(a) {
            a.moveH(-1, "group");
        },
        goWordRight: function(a) {
            a.moveH(1, "word");
        },
        delCharBefore: function(a) {
            a.deleteH(-1, "char");
        },
        delCharAfter: function(a) {
            a.deleteH(1, "char");
        },
        delWordBefore: function(a) {
            a.deleteH(-1, "word");
        },
        delWordAfter: function(a) {
            a.deleteH(1, "word");
        },
        delGroupBefore: function(a) {
            a.deleteH(-1, "group");
        },
        delGroupAfter: function(a) {
            a.deleteH(1, "group");
        },
        indentAuto: function(a) {
            a.indentSelection("smart");
        },
        indentMore: function(a) {
            a.indentSelection("add");
        },
        indentLess: function(a) {
            a.indentSelection("subtract");
        },
        insertTab: function(a) {
            a.replaceSelection("	", "end", "+input");
        },
        defaultTab: function(a) {
            if (a.somethingSelected()) a.indentSelection("add"); else a.replaceSelection("	", "end", "+input");
        },
        transposeChars: function(a) {
            var b = a.getCursor(), c = a.getLine(b.line);
            if (b.ch > 0 && b.ch < c.length - 1) a.replaceRange(c.charAt(b.ch) + c.charAt(b.ch - 1), Bc(b.line, b.ch - 1), Bc(b.line, b.ch + 1));
        },
        newlineAndIndent: function(a) {
            Fb(a, function() {
                a.replaceSelection("\n", "end", "+input");
                a.indentLine(a.getCursor().line, null, true);
            })();
        },
        toggleOverwrite: function(a) {
            a.toggleOverwrite();
        }
    };
    var kd = x.keyMap = {};
    kd.basic = {
        Left: "goCharLeft",
        Right: "goCharRight",
        Up: "goLineUp",
        Down: "goLineDown",
        End: "goLineEnd",
        Home: "goLineStartSmart",
        PageUp: "goPageUp",
        PageDown: "goPageDown",
        Delete: "delCharAfter",
        Backspace: "delCharBefore",
        "Shift-Backspace": "delCharBefore",
        Tab: "defaultTab",
        "Shift-Tab": "indentAuto",
        Enter: "newlineAndIndent",
        Insert: "toggleOverwrite"
    };
    kd.pcDefault = {
        "Ctrl-A": "selectAll",
        "Ctrl-D": "deleteLine",
        "Ctrl-Z": "undo",
        "Shift-Ctrl-Z": "redo",
        "Ctrl-Y": "redo",
        "Ctrl-Home": "goDocStart",
        "Alt-Up": "goDocStart",
        "Ctrl-End": "goDocEnd",
        "Ctrl-Down": "goDocEnd",
        "Ctrl-Left": "goGroupLeft",
        "Ctrl-Right": "goGroupRight",
        "Alt-Left": "goLineStart",
        "Alt-Right": "goLineEnd",
        "Ctrl-Backspace": "delGroupBefore",
        "Ctrl-Delete": "delGroupAfter",
        "Ctrl-S": "save",
        "Ctrl-F": "find",
        "Ctrl-G": "findNext",
        "Shift-Ctrl-G": "findPrev",
        "Shift-Ctrl-F": "replace",
        "Shift-Ctrl-R": "replaceAll",
        "Ctrl-[": "indentLess",
        "Ctrl-]": "indentMore",
        fallthrough: "basic"
    };
    kd.macDefault = {
        "Cmd-A": "selectAll",
        "Cmd-D": "deleteLine",
        "Cmd-Z": "undo",
        "Shift-Cmd-Z": "redo",
        "Cmd-Y": "redo",
        "Cmd-Up": "goDocStart",
        "Cmd-End": "goDocEnd",
        "Cmd-Down": "goDocEnd",
        "Alt-Left": "goGroupLeft",
        "Alt-Right": "goGroupRight",
        "Cmd-Left": "goLineStart",
        "Cmd-Right": "goLineEnd",
        "Alt-Backspace": "delGroupBefore",
        "Ctrl-Alt-Backspace": "delGroupAfter",
        "Alt-Delete": "delGroupAfter",
        "Cmd-S": "save",
        "Cmd-F": "find",
        "Cmd-G": "findNext",
        "Shift-Cmd-G": "findPrev",
        "Cmd-Alt-F": "replace",
        "Shift-Cmd-Alt-F": "replaceAll",
        "Cmd-[": "indentLess",
        "Cmd-]": "indentMore",
        "Cmd-Backspace": "delLineLeft",
        fallthrough: [ "basic", "emacsy" ]
    };
    kd["default"] = q ? kd.macDefault : kd.pcDefault;
    kd.emacsy = {
        "Ctrl-F": "goCharRight",
        "Ctrl-B": "goCharLeft",
        "Ctrl-P": "goLineUp",
        "Ctrl-N": "goLineDown",
        "Alt-F": "goWordRight",
        "Alt-B": "goWordLeft",
        "Ctrl-A": "goLineStart",
        "Ctrl-E": "goLineEnd",
        "Ctrl-V": "goPageDown",
        "Shift-Ctrl-V": "goPageUp",
        "Ctrl-D": "delCharAfter",
        "Ctrl-H": "delCharBefore",
        "Alt-D": "delWordAfter",
        "Alt-Backspace": "delWordBefore",
        "Ctrl-K": "killLine",
        "Ctrl-T": "transposeChars"
    };
    function ld(a) {
        if ("string" == typeof a) return kd[a]; else return a;
    }
    function md(a, b, c) {
        function d(b) {
            b = ld(b);
            var e = b[a];
            if (false === e) return "stop";
            if (null != e && c(e)) return true;
            if (b.nofallthrough) return "stop";
            var f = b.fallthrough;
            if (null == f) return false;
            if ("[object Array]" != Object.prototype.toString.call(f)) return d(f);
            for (var g = 0, h = f.length; g < h; ++g) {
                var i = d(f[g]);
                if (i) return i;
            }
            return false;
        }
        for (var e = 0; e < b.length; ++e) {
            var f = d(b[e]);
            if (f) return "stop" != f;
        }
    }
    function nd(a) {
        var b = zf[a.keyCode];
        return "Ctrl" == b || "Alt" == b || "Shift" == b || "Mod" == b;
    }
    function od(a, b) {
        if (i && 34 == a.keyCode && a["char"]) return false;
        var c = zf[a.keyCode];
        if (null == c || a.altGraphKey) return false;
        if (a.altKey) c = "Alt-" + c;
        if (t ? a.metaKey : a.ctrlKey) c = "Ctrl-" + c;
        if (t ? a.ctrlKey : a.metaKey) c = "Cmd-" + c;
        if (!b && a.shiftKey) c = "Shift-" + c;
        return c;
    }
    x.lookupKey = md;
    x.isModifierKey = nd;
    x.keyName = od;
    x.fromTextArea = function(a, b) {
        if (!b) b = {};
        b.value = a.value;
        if (!b.tabindex && a.tabindex) b.tabindex = a.tabindex;
        if (!b.placeholder && a.placeholder) b.placeholder = a.placeholder;
        if (null == b.autofocus) {
            var c = document.body;
            try {
                c = document.activeElement;
            } catch (d) {}
            b.autofocus = c == a || null != a.getAttribute("autofocus") && c == document.body;
        }
        function e() {
            a.value = i.getValue();
        }
        if (a.form) {
            Le(a.form, "submit", e);
            if (!b.leaveSubmitMethodAlone) {
                var f = a.form, g = f.submit;
                try {
                    var h = f.submit = function() {
                        e();
                        f.submit = g;
                        f.submit();
                        f.submit = h;
                    };
                } catch (d) {}
            }
        }
        a.style.display = "none";
        var i = x(function(b) {
            a.parentNode.insertBefore(b, a.nextSibling);
        }, b);
        i.save = e;
        i.getTextArea = function() {
            return a;
        };
        i.toTextArea = function() {
            e();
            a.parentNode.removeChild(i.getWrapperElement());
            a.style.display = "";
            if (a.form) {
                Me(a.form, "submit", e);
                if ("function" == typeof a.form.submit) a.form.submit = g;
            }
        };
        return i;
    };
    function pd(a, b) {
        this.pos = this.start = 0;
        this.string = a;
        this.tabSize = b || 8;
        this.lastColumnPos = this.lastColumnValue = 0;
    }
    pd.prototype = {
        eol: function() {
            return this.pos >= this.string.length;
        },
        sol: function() {
            return 0 == this.pos;
        },
        peek: function() {
            return this.string.charAt(this.pos) || void 0;
        },
        next: function() {
            if (this.pos < this.string.length) return this.string.charAt(this.pos++);
        },
        eat: function(a) {
            var b = this.string.charAt(this.pos);
            if ("string" == typeof a) var c = b == a; else var c = b && (a.test ? a.test(b) : a(b));
            if (c) {
                ++this.pos;
                return b;
            }
        },
        eatWhile: function(a) {
            var b = this.pos;
            while (this.eat(a)) ;
            return this.pos > b;
        },
        eatSpace: function() {
            var a = this.pos;
            while (/[\s\u00a0]/.test(this.string.charAt(this.pos))) ++this.pos;
            return this.pos > a;
        },
        skipToEnd: function() {
            this.pos = this.string.length;
        },
        skipTo: function(a) {
            var b = this.string.indexOf(a, this.pos);
            if (b > -1) {
                this.pos = b;
                return true;
            }
        },
        backUp: function(a) {
            this.pos -= a;
        },
        column: function() {
            if (this.lastColumnPos < this.start) {
                this.lastColumnValue = Ye(this.string, this.start, this.tabSize, this.lastColumnPos, this.lastColumnValue);
                this.lastColumnPos = this.start;
            }
            return this.lastColumnValue;
        },
        indentation: function() {
            return Ye(this.string, null, this.tabSize);
        },
        match: function(a, b, c) {
            if ("string" == typeof a) {
                var d = function(a) {
                    return c ? a.toLowerCase() : a;
                };
                var e = this.string.substr(this.pos, a.length);
                if (d(e) == d(a)) {
                    if (false !== b) this.pos += a.length;
                    return true;
                }
            } else {
                var f = this.string.slice(this.pos).match(a);
                if (f && f.index > 0) return null;
                if (f && false !== b) this.pos += f[0].length;
                return f;
            }
        },
        current: function() {
            return this.string.slice(this.start, this.pos);
        }
    };
    x.StringStream = pd;
    function qd(a, b) {
        this.lines = [];
        this.type = b;
        this.doc = a;
    }
    x.TextMarker = qd;
    Ue(qd);
    qd.prototype.clear = function() {
        if (this.explicitlyCleared) return;
        var a = this.doc.cm, b = a && !a.curOp;
        if (b) Db(a);
        if (Te(this, "clear")) {
            var c = this.find();
            if (c) Qe(this, "clear", c.from, c.to);
        }
        var d = null, e = null;
        for (var f = 0; f < this.lines.length; ++f) {
            var g = this.lines[f];
            var h = ud(g.markedSpans, this);
            if (null != h.to) e = pe(g);
            g.markedSpans = vd(g.markedSpans, h);
            if (null != h.from) d = pe(g); else if (this.collapsed && !Gd(this.doc, g) && a) oe(g, Ab(a.display));
        }
        if (a && this.collapsed && !a.options.lineWrapping) for (var f = 0; f < this.lines.length; ++f) {
            var i = Fd(a.doc, this.lines[f]), j = H(a.doc, i);
            if (j > a.display.maxLineLength) {
                a.display.maxLine = i;
                a.display.maxLineLength = j;
                a.display.maxLineChanged = true;
            }
        }
        if (null != d && a) Ib(a, d, e + 1);
        this.lines.length = 0;
        this.explicitlyCleared = true;
        if (this.atomic && this.doc.cantEdit) {
            this.doc.cantEdit = false;
            if (a) Mc(a);
        }
        if (b) Eb(a);
    };
    qd.prototype.find = function() {
        var a, b;
        for (var c = 0; c < this.lines.length; ++c) {
            var d = this.lines[c];
            var e = ud(d.markedSpans, this);
            if (null != e.from || null != e.to) {
                var f = pe(d);
                if (null != e.from) a = Bc(f, e.from);
                if (null != e.to) b = Bc(f, e.to);
            }
        }
        if ("bookmark" == this.type) return a;
        return a && {
            from: a,
            to: b
        };
    };
    qd.prototype.changed = function() {
        var a = this.find(), b = this.doc.cm;
        if (!a || !b) return;
        if ("bookmark" != this.type) a = a.from;
        var c = le(this.doc, a.line);
        kb(b, c);
        if (a.line >= b.display.showingFrom && a.line < b.display.showingTo) {
            for (var d = b.display.lineDiv.firstChild; d; d = d.nextSibling) if (d.lineObj == c) {
                if (d.offsetHeight != c.height) oe(c, d.offsetHeight);
                break;
            }
            Hb(b, function() {
                b.curOp.selectionChanged = b.curOp.forceUpdate = b.curOp.updateMaxLine = true;
            });
        }
    };
    qd.prototype.attachLine = function(a) {
        if (!this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            if (!b.maybeHiddenMarkers || bf(b.maybeHiddenMarkers, this) == -1) (b.maybeUnhiddenMarkers || (b.maybeUnhiddenMarkers = [])).push(this);
        }
        this.lines.push(a);
    };
    qd.prototype.detachLine = function(a) {
        this.lines.splice(bf(this.lines, a), 1);
        if (!this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            (b.maybeHiddenMarkers || (b.maybeHiddenMarkers = [])).push(this);
        }
    };
    function rd(a, b, c, d, e) {
        if (d && d.shared) return td(a, b, c, d, e);
        if (a.cm && !a.cm.curOp) return Fb(a.cm, rd)(a, b, c, d, e);
        var f = new qd(a, e);
        if (Dc(c, b) || Cc(b, c) && "range" == e && !(d.inclusiveLeft && d.inclusiveRight)) return f;
        if (d) df(d, f);
        if (f.replacedWith) {
            f.collapsed = true;
            f.replacedWith = lf("span", [ f.replacedWith ], "CodeMirror-widget");
            if (!d.handleMouseEvents) f.replacedWith.ignoreEvents = true;
        }
        if (f.collapsed) w = true;
        if (f.addToHistory) we(a, {
            from: b,
            to: c,
            origin: "markText"
        }, {
            head: a.sel.head,
            anchor: a.sel.anchor
        }, 0/0);
        var g = b.line, h = 0, i, j, k = a.cm, l;
        a.iter(g, c.line + 1, function(d) {
            if (k && f.collapsed && !k.options.lineWrapping && Fd(a, d) == k.display.maxLine) l = true;
            var e = {
                from: null,
                to: null,
                marker: f
            };
            h += d.text.length;
            if (g == b.line) {
                e.from = b.ch;
                h -= b.ch;
            }
            if (g == c.line) {
                e.to = c.ch;
                h -= d.text.length - c.ch;
            }
            if (f.collapsed) {
                if (g == c.line) j = Cd(d, c.ch);
                if (g == b.line) i = Cd(d, b.ch); else oe(d, 0);
            }
            wd(d, e);
            ++g;
        });
        if (f.collapsed) a.iter(b.line, c.line + 1, function(b) {
            if (Gd(a, b)) oe(b, 0);
        });
        if (f.clearOnEnter) Le(f, "beforeCursorEnter", function() {
            f.clear();
        });
        if (f.readOnly) {
            v = true;
            if (a.history.done.length || a.history.undone.length) a.clearHistory();
        }
        if (f.collapsed) {
            if (i != j) throw new Error("Inserting collapsed marker overlapping an existing one");
            f.size = h;
            f.atomic = true;
        }
        if (k) {
            if (l) k.curOp.updateMaxLine = true;
            if (f.className || f.title || f.startStyle || f.endStyle || f.collapsed) Ib(k, b.line, c.line + 1);
            if (f.atomic) Mc(k);
        }
        return f;
    }
    function sd(a, b) {
        this.markers = a;
        this.primary = b;
        for (var c = 0, d = this; c < a.length; ++c) {
            a[c].parent = this;
            Le(a[c], "clear", function() {
                d.clear();
            });
        }
    }
    x.SharedTextMarker = sd;
    Ue(sd);
    sd.prototype.clear = function() {
        if (this.explicitlyCleared) return;
        this.explicitlyCleared = true;
        for (var a = 0; a < this.markers.length; ++a) this.markers[a].clear();
        Qe(this, "clear");
    };
    sd.prototype.find = function() {
        return this.primary.find();
    };
    function td(a, b, c, d, e) {
        d = df(d);
        d.shared = false;
        var f = [ rd(a, b, c, d, e) ], g = f[0];
        var h = d.replacedWith;
        je(a, function(a) {
            if (h) d.replacedWith = h.cloneNode(true);
            f.push(rd(a, Gc(a, b), Gc(a, c), d, e));
            for (var i = 0; i < a.linked.length; ++i) if (a.linked[i].isParent) return;
            g = _e(f);
        });
        return new sd(f, g);
    }
    function ud(a, b) {
        if (a) for (var c = 0; c < a.length; ++c) {
            var d = a[c];
            if (d.marker == b) return d;
        }
    }
    function vd(a, b) {
        for (var c, d = 0; d < a.length; ++d) if (a[d] != b) (c || (c = [])).push(a[d]);
        return c;
    }
    function wd(a, b) {
        a.markedSpans = a.markedSpans ? a.markedSpans.concat([ b ]) : [ b ];
        b.marker.attachLine(a);
    }
    function xd(a, b, c) {
        if (a) for (var d = 0, e; d < a.length; ++d) {
            var f = a[d], g = f.marker;
            var h = null == f.from || (g.inclusiveLeft ? f.from <= b : f.from < b);
            if (h || (g.inclusiveLeft && g.inclusiveRight || "bookmark" == g.type) && f.from == b && (!c || !f.marker.insertLeft)) {
                var i = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
                (e || (e = [])).push({
                    from: f.from,
                    to: i ? null : f.to,
                    marker: g
                });
            }
        }
        return e;
    }
    function yd(a, b, c) {
        if (a) for (var d = 0, e; d < a.length; ++d) {
            var f = a[d], g = f.marker;
            var h = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
            if (h || "bookmark" == g.type && f.from == b && (!c || f.marker.insertLeft)) {
                var i = null == f.from || (g.inclusiveLeft ? f.from <= b : f.from < b);
                (e || (e = [])).push({
                    from: i ? null : f.from - b,
                    to: null == f.to ? null : f.to - b,
                    marker: g
                });
            }
        }
        return e;
    }
    function zd(a, b) {
        var c = Ic(a, b.from.line) && le(a, b.from.line).markedSpans;
        var d = Ic(a, b.to.line) && le(a, b.to.line).markedSpans;
        if (!c && !d) return null;
        var e = b.from.ch, f = b.to.ch, g = Cc(b.from, b.to);
        var h = xd(c, e, g);
        var i = yd(d, f, g);
        var j = 1 == b.text.length, k = _e(b.text).length + (j ? e : 0);
        if (h) for (var l = 0; l < h.length; ++l) {
            var m = h[l];
            if (null == m.to) {
                var n = ud(i, m.marker);
                if (!n) m.to = e; else if (j) m.to = null == n.to ? null : n.to + k;
            }
        }
        if (i) for (var l = 0; l < i.length; ++l) {
            var m = i[l];
            if (null != m.to) m.to += k;
            if (null == m.from) {
                var n = ud(h, m.marker);
                if (!n) {
                    m.from = k;
                    if (j) (h || (h = [])).push(m);
                }
            } else {
                m.from += k;
                if (j) (h || (h = [])).push(m);
            }
        }
        if (j && h) {
            for (var l = 0; l < h.length; ++l) if (null != h[l].from && h[l].from == h[l].to && "bookmark" != h[l].marker.type) h.splice(l--, 1);
            if (!h.length) h = null;
        }
        var o = [ h ];
        if (!j) {
            var p = b.text.length - 2, q;
            if (p > 0 && h) for (var l = 0; l < h.length; ++l) if (null == h[l].to) (q || (q = [])).push({
                from: null,
                to: null,
                marker: h[l].marker
            });
            for (var l = 0; l < p; ++l) o.push(q);
            o.push(i);
        }
        return o;
    }
    function Ad(a, b) {
        var c = ye(a, b);
        var d = zd(a, b);
        if (!c) return d;
        if (!d) return c;
        for (var e = 0; e < c.length; ++e) {
            var f = c[e], g = d[e];
            if (f && g) a: for (var h = 0; h < g.length; ++h) {
                var i = g[h];
                for (var j = 0; j < f.length; ++j) if (f[j].marker == i.marker) continue a;
                f.push(i);
            } else if (g) c[e] = g;
        }
        return c;
    }
    function Bd(a, b, c) {
        var d = null;
        a.iter(b.line, c.line + 1, function(a) {
            if (a.markedSpans) for (var b = 0; b < a.markedSpans.length; ++b) {
                var c = a.markedSpans[b].marker;
                if (c.readOnly && (!d || bf(d, c) == -1)) (d || (d = [])).push(c);
            }
        });
        if (!d) return null;
        var e = [ {
            from: b,
            to: c
        } ];
        for (var f = 0; f < d.length; ++f) {
            var g = d[f], h = g.find();
            for (var i = 0; i < e.length; ++i) {
                var j = e[i];
                if (Dc(j.to, h.from) || Dc(h.to, j.from)) continue;
                var k = [ i, 1 ];
                if (Dc(j.from, h.from) || !g.inclusiveLeft && Cc(j.from, h.from)) k.push({
                    from: j.from,
                    to: h.from
                });
                if (Dc(h.to, j.to) || !g.inclusiveRight && Cc(j.to, h.to)) k.push({
                    from: h.to,
                    to: j.to
                });
                e.splice.apply(e, k);
                i += k.length - 1;
            }
        }
        return e;
    }
    function Cd(a, b) {
        var c = w && a.markedSpans, d;
        if (c) for (var e, f = 0; f < c.length; ++f) {
            e = c[f];
            if (!e.marker.collapsed) continue;
            if ((null == e.from || e.from < b) && (null == e.to || e.to > b) && (!d || d.width < e.marker.width)) d = e.marker;
        }
        return d;
    }
    function Dd(a) {
        return Cd(a, -1);
    }
    function Ed(a) {
        return Cd(a, a.text.length + 1);
    }
    function Fd(a, b) {
        var c;
        while (c = Dd(b)) b = le(a, c.find().from.line);
        return b;
    }
    function Gd(a, b) {
        var c = w && b.markedSpans;
        if (c) for (var d, e = 0; e < c.length; ++e) {
            d = c[e];
            if (!d.marker.collapsed) continue;
            if (null == d.from) return true;
            if (d.marker.replacedWith) continue;
            if (0 == d.from && d.marker.inclusiveLeft && Hd(a, b, d)) return true;
        }
    }
    function Hd(a, b, c) {
        if (null == c.to) {
            var d = c.marker.find().to, e = le(a, d.line);
            return Hd(a, e, ud(e.markedSpans, c.marker));
        }
        if (c.marker.inclusiveRight && c.to == b.text.length) return true;
        for (var f, g = 0; g < b.markedSpans.length; ++g) {
            f = b.markedSpans[g];
            if (f.marker.collapsed && !f.marker.replacedWith && f.from == c.to && (f.marker.inclusiveLeft || c.marker.inclusiveRight) && Hd(a, b, f)) return true;
        }
    }
    function Id(a) {
        var b = a.markedSpans;
        if (!b) return;
        for (var c = 0; c < b.length; ++c) b[c].marker.detachLine(a);
        a.markedSpans = null;
    }
    function Jd(a, b) {
        if (!b) return;
        for (var c = 0; c < b.length; ++c) b[c].marker.attachLine(a);
        a.markedSpans = b;
    }
    var Kd = x.LineWidget = function(a, b, c) {
        if (c) for (var d in c) if (c.hasOwnProperty(d)) this[d] = c[d];
        this.cm = a;
        this.node = b;
    };
    Ue(Kd);
    function Ld(a) {
        return function() {
            var b = !this.cm.curOp;
            if (b) Db(this.cm);
            try {
                var c = a.apply(this, arguments);
            } finally {
                if (b) Eb(this.cm);
            }
            return c;
        };
    }
    Kd.prototype.clear = Ld(function() {
        var a = this.line.widgets, b = pe(this.line);
        if (null == b || !a) return;
        for (var c = 0; c < a.length; ++c) if (a[c] == this) a.splice(c--, 1);
        if (!a.length) this.line.widgets = null;
        var d = re(this.cm, this.line) < this.cm.doc.scrollTop;
        oe(this.line, Math.max(0, this.line.height - Md(this)));
        if (d) Tc(this.cm, 0, -this.height);
        Ib(this.cm, b, b + 1);
    });
    Kd.prototype.changed = Ld(function() {
        var a = this.height;
        this.height = null;
        var b = Md(this) - a;
        if (!b) return;
        oe(this.line, this.line.height + b);
        var c = pe(this.line);
        Ib(this.cm, c, c + 1);
    });
    function Md(a) {
        if (null != a.height) return a.height;
        if (!a.node.parentNode || 1 != a.node.parentNode.nodeType) nf(a.cm.display.measure, lf("div", [ a.node ], null, "position: relative"));
        return a.height = a.node.offsetHeight;
    }
    function Nd(a, b, c, d) {
        var e = new Kd(a, c, d);
        if (e.noHScroll) a.display.alignWidgets = true;
        Vc(a, b, function(b) {
            var c = b.widgets || (b.widgets = []);
            if (null == e.insertAt) c.push(e); else c.splice(Math.min(c.length - 1, Math.max(0, e.insertAt)), 0, e);
            e.line = b;
            if (!Gd(a.doc, b) || e.showIfHidden) {
                var d = re(a, b) < a.doc.scrollTop;
                oe(b, b.height + Md(e));
                if (d) Tc(a, 0, e.height);
            }
            return true;
        });
        return e;
    }
    var Od = x.Line = function(a, b, c) {
        this.text = a;
        Jd(this, b);
        this.height = c ? c(this) : 1;
    };
    Ue(Od);
    Od.prototype.lineNo = function() {
        return pe(this);
    };
    function Pd(a, b, c, d) {
        a.text = b;
        if (a.stateAfter) a.stateAfter = null;
        if (a.styles) a.styles = null;
        if (null != a.order) a.order = null;
        Id(a);
        Jd(a, c);
        var e = d ? d(a) : 1;
        if (e != a.height) oe(a, e);
    }
    function Qd(a) {
        a.parent = null;
        Id(a);
    }
    function Rd(a, b, c, d, e, f) {
        var g = c.flattenSpans;
        if (null == g) g = a.options.flattenSpans;
        var h = 0, i = null;
        var j = new pd(b, a.options.tabSize), k;
        if ("" == b && c.blankLine) c.blankLine(d);
        while (!j.eol()) {
            if (j.pos > a.options.maxHighlightLength) {
                g = false;
                if (f) Ud(a, b, d, j.pos);
                j.pos = b.length;
                k = null;
            } else k = c.token(j, d);
            if (!g || i != k) {
                if (h < j.start) e(j.start, i);
                h = j.start;
                i = k;
            }
            j.start = j.pos;
        }
        while (h < j.pos) {
            var l = Math.min(j.pos, h + 5e4);
            e(l, i);
            h = l;
        }
    }
    function Sd(a, b, c, d) {
        var e = [ a.state.modeGen ];
        Rd(a, b.text, a.doc.mode, c, function(a, b) {
            e.push(a, b);
        }, d);
        for (var f = 0; f < a.state.overlays.length; ++f) {
            var g = a.state.overlays[f], h = 1, i = 0;
            Rd(a, b.text, g.mode, true, function(a, b) {
                var c = h;
                while (i < a) {
                    var d = e[h];
                    if (d > a) e.splice(h, 1, a, e[h + 1], d);
                    h += 2;
                    i = Math.min(a, d);
                }
                if (!b) return;
                if (g.opaque) {
                    e.splice(c, h - c, a, b);
                    h = c + 2;
                } else for (;c < h; c += 2) {
                    var f = e[c + 1];
                    e[c + 1] = f ? f + " " + b : b;
                }
            });
        }
        return e;
    }
    function Td(a, b) {
        if (!b.styles || b.styles[0] != a.state.modeGen) b.styles = Sd(a, b, b.stateAfter = eb(a, pe(b)));
        return b.styles;
    }
    function Ud(a, b, c, d) {
        var e = a.doc.mode;
        var f = new pd(b, a.options.tabSize);
        f.start = f.pos = d || 0;
        if ("" == b && e.blankLine) e.blankLine(c);
        while (!f.eol() && f.pos <= a.options.maxHighlightLength) {
            e.token(f, c);
            f.start = f.pos;
        }
    }
    var Vd = {};
    function Wd(a, b) {
        if (!a) return null;
        for (;;) {
            var c = a.match(/(?:^|\s)line-(background-)?(\S+)/);
            if (!c) break;
            a = a.slice(0, c.index) + a.slice(c.index + c[0].length);
            var d = c[1] ? "bgClass" : "textClass";
            if (null == b[d]) b[d] = c[2]; else if (!new RegExp("(?:^|s)" + c[2] + "(?:$|s)").test(b[d])) b[d] += " " + c[2];
        }
        return Vd[a] || (Vd[a] = "cm-" + a.replace(/ +/g, " cm-"));
    }
    function Xd(a, c, d, g) {
        var h, i = c, j = true;
        while (h = Dd(i)) i = le(a.doc, h.find().from.line);
        var k = {
            pre: lf("pre"),
            col: 0,
            pos: 0,
            measure: null,
            measuredSomething: false,
            cm: a,
            copyWidgets: g
        };
        do {
            if (i.text) j = false;
            k.measure = i == c && d;
            k.pos = 0;
            k.addToken = k.measure ? $d : Zd;
            if ((b || f) && a.getOption("lineWrapping")) k.addToken = _d(k.addToken);
            var l = be(i, k, Td(a, i));
            if (d && i == c && !k.measuredSomething) {
                d[0] = k.pre.appendChild(vf(a.display.measure));
                k.measuredSomething = true;
            }
            if (l) i = le(a.doc, l.to.line);
        } while (l);
        if (d && !k.measuredSomething && !d[0]) d[0] = k.pre.appendChild(j ? lf("span", " ") : vf(a.display.measure));
        if (!k.pre.firstChild && !Gd(a.doc, c)) k.pre.appendChild(document.createTextNode(" "));
        var m;
        if (d && (b || e) && (m = se(i))) {
            var n = m.length - 1;
            if (m[n].from == m[n].to) --n;
            var o = m[n], p = m[n - 1];
            if (o.from + 1 == o.to && p && o.level < p.level) {
                var q = d[k.pos - 1];
                if (q) q.parentNode.insertBefore(q.measureRight = vf(a.display.measure), q.nextSibling);
            }
        }
        var r = k.textClass ? k.textClass + " " + (c.textClass || "") : c.textClass;
        if (r) k.pre.className = r;
        Ne(a, "renderLine", a, c, k.pre);
        return k;
    }
    function Yd(a) {
        var b = lf("span", "�", "cm-invalidchar");
        b.title = "\\u" + a.charCodeAt(0).toString(16);
        return b;
    }
    function Zd(a, b, c, d, e, f) {
        if (!b) return;
        var g = a.cm.options.specialChars;
        if (!g.test(b)) {
            a.col += b.length;
            var h = document.createTextNode(b);
        } else {
            var h = document.createDocumentFragment(), i = 0;
            while (true) {
                g.lastIndex = i;
                var j = g.exec(b);
                var k = j ? j.index - i : b.length - i;
                if (k) {
                    h.appendChild(document.createTextNode(b.slice(i, i + k)));
                    a.col += k;
                }
                if (!j) break;
                i += k + 1;
                if ("	" == j[0]) {
                    var l = a.cm.options.tabSize, m = l - a.col % l;
                    h.appendChild(lf("span", $e(m), "cm-tab"));
                    a.col += m;
                } else {
                    var n = a.cm.options.specialCharPlaceholder(j[0]);
                    h.appendChild(n);
                    a.col += 1;
                }
            }
        }
        if (c || d || e || a.measure) {
            var o = c || "";
            if (d) o += d;
            if (e) o += e;
            var n = lf("span", [ h ], o);
            if (f) n.title = f;
            return a.pre.appendChild(n);
        }
        a.pre.appendChild(h);
    }
    function $d(a, c, d, e, f) {
        var g = a.cm.options.lineWrapping;
        for (var h = 0; h < c.length; ++h) {
            var i = c.charAt(h), j = 0 == h;
            if (i >= "???" && i < "???" && h < c.length - 1) {
                i = c.slice(h, h + 2);
                ++h;
            } else if (h && g && rf(c, h)) a.pre.appendChild(lf("wbr"));
            var k = a.measure[a.pos];
            var l = a.measure[a.pos] = Zd(a, i, d, j && e, h == c.length - 1 && f);
            if (k) l.leftSide = k.leftSide || k;
            if (b && g && " " == i && h && !/\s/.test(c.charAt(h - 1)) && h < c.length - 1 && !/\s/.test(c.charAt(h + 1))) l.style.whiteSpace = "normal";
            a.pos += i.length;
        }
        if (c.length) a.measuredSomething = true;
    }
    function _d(a) {
        function b(a) {
            var b = " ";
            for (var c = 0; c < a.length - 2; ++c) b += c % 2 ? " " : " ";
            b += " ";
            return b;
        }
        return function(c, d, e, f, g, h) {
            return a(c, d.replace(/ {3,}/g, b), e, f, g, h);
        };
    }
    function ae(a, b, c, d) {
        var e = !d && c.replacedWith;
        if (e) {
            if (a.copyWidgets) e = e.cloneNode(true);
            a.pre.appendChild(e);
            if (a.measure) {
                if (b) a.measure[a.pos] = e; else {
                    var f = vf(a.cm.display.measure);
                    if ("bookmark" == c.type && !c.insertLeft) a.measure[a.pos] = a.pre.appendChild(f); else if (a.measure[a.pos]) return; else a.measure[a.pos] = a.pre.insertBefore(f, e);
                }
                a.measuredSomething = true;
            }
        }
        a.pos += b;
    }
    function be(a, b, c) {
        var d = a.markedSpans, e = a.text, f = 0;
        if (!d) {
            for (var g = 1; g < c.length; g += 2) b.addToken(b, e.slice(f, f = c[g]), Wd(c[g + 1], b));
            return;
        }
        var h = e.length, i = 0, g = 1, j = "", k;
        var l = 0, m, n, o, p, q;
        for (;;) {
            if (l == i) {
                m = n = o = p = "";
                q = null;
                l = 1/0;
                var r = [];
                for (var s = 0; s < d.length; ++s) {
                    var t = d[s], u = t.marker;
                    if (t.from <= i && (null == t.to || t.to > i)) {
                        if (null != t.to && l > t.to) {
                            l = t.to;
                            n = "";
                        }
                        if (u.className) m += " " + u.className;
                        if (u.startStyle && t.from == i) o += " " + u.startStyle;
                        if (u.endStyle && t.to == l) n += " " + u.endStyle;
                        if (u.title && !p) p = u.title;
                        if (u.collapsed && (!q || q.marker.size < u.size)) q = t;
                    } else if (t.from > i && l > t.from) l = t.from;
                    if ("bookmark" == u.type && t.from == i && u.replacedWith) r.push(u);
                }
                if (q && (q.from || 0) == i) {
                    ae(b, (null == q.to ? h : q.to) - i, q.marker, null == q.from);
                    if (null == q.to) return q.marker.find();
                }
                if (!q && r.length) for (var s = 0; s < r.length; ++s) ae(b, 0, r[s]);
            }
            if (i >= h) break;
            var v = Math.min(h, l);
            while (true) {
                if (j) {
                    var w = i + j.length;
                    if (!q) {
                        var x = w > v ? j.slice(0, v - i) : j;
                        b.addToken(b, x, k ? k + m : m, o, i + x.length == l ? n : "", p);
                    }
                    if (w >= v) {
                        j = j.slice(v - i);
                        i = v;
                        break;
                    }
                    i = w;
                    o = "";
                }
                j = e.slice(f, f = c[g++]);
                k = Wd(c[g++], b);
            }
        }
    }
    function ce(a, b, c, d, e) {
        function f(a) {
            return c ? c[a] : null;
        }
        function g(a, c, d) {
            Pd(a, c, d, e);
            Qe(a, "change", a, b);
        }
        var h = b.from, i = b.to, j = b.text;
        var k = le(a, h.line), l = le(a, i.line);
        var m = _e(j), n = f(j.length - 1), o = i.line - h.line;
        if (0 == h.ch && 0 == i.ch && "" == m && (!a.cm || a.cm.options.wholeLineUpdateBefore)) {
            for (var p = 0, q = j.length - 1, r = []; p < q; ++p) r.push(new Od(j[p], f(p), e));
            g(l, l.text, n);
            if (o) a.remove(h.line, o);
            if (r.length) a.insert(h.line, r);
        } else if (k == l) if (1 == j.length) g(k, k.text.slice(0, h.ch) + m + k.text.slice(i.ch), n); else {
            for (var r = [], p = 1, q = j.length - 1; p < q; ++p) r.push(new Od(j[p], f(p), e));
            r.push(new Od(m + k.text.slice(i.ch), n, e));
            g(k, k.text.slice(0, h.ch) + j[0], f(0));
            a.insert(h.line + 1, r);
        } else if (1 == j.length) {
            g(k, k.text.slice(0, h.ch) + j[0] + l.text.slice(i.ch), f(0));
            a.remove(h.line + 1, o);
        } else {
            g(k, k.text.slice(0, h.ch) + j[0], f(0));
            g(l, m + l.text.slice(i.ch), n);
            for (var p = 1, q = j.length - 1, r = []; p < q; ++p) r.push(new Od(j[p], f(p), e));
            if (o > 1) a.remove(h.line + 1, o - 1);
            a.insert(h.line + 1, r);
        }
        Qe(a, "change", a, b);
        Lc(a, d.anchor, d.head, null, true);
    }
    function de(a) {
        this.lines = a;
        this.parent = null;
        for (var b = 0, c = a.length, d = 0; b < c; ++b) {
            a[b].parent = this;
            d += a[b].height;
        }
        this.height = d;
    }
    de.prototype = {
        chunkSize: function() {
            return this.lines.length;
        },
        removeInner: function(a, b) {
            for (var c = a, d = a + b; c < d; ++c) {
                var e = this.lines[c];
                this.height -= e.height;
                Qd(e);
                Qe(e, "delete");
            }
            this.lines.splice(a, b);
        },
        collapse: function(a) {
            a.splice.apply(a, [ a.length, 0 ].concat(this.lines));
        },
        insertInner: function(a, b, c) {
            this.height += c;
            this.lines = this.lines.slice(0, a).concat(b).concat(this.lines.slice(a));
            for (var d = 0, e = b.length; d < e; ++d) b[d].parent = this;
        },
        iterN: function(a, b, c) {
            for (var d = a + b; a < d; ++a) if (c(this.lines[a])) return true;
        }
    };
    function ee(a) {
        this.children = a;
        var b = 0, c = 0;
        for (var d = 0, e = a.length; d < e; ++d) {
            var f = a[d];
            b += f.chunkSize();
            c += f.height;
            f.parent = this;
        }
        this.size = b;
        this.height = c;
        this.parent = null;
    }
    ee.prototype = {
        chunkSize: function() {
            return this.size;
        },
        removeInner: function(a, b) {
            this.size -= b;
            for (var c = 0; c < this.children.length; ++c) {
                var d = this.children[c], e = d.chunkSize();
                if (a < e) {
                    var f = Math.min(b, e - a), g = d.height;
                    d.removeInner(a, f);
                    this.height -= g - d.height;
                    if (e == f) {
                        this.children.splice(c--, 1);
                        d.parent = null;
                    }
                    if (0 == (b -= f)) break;
                    a = 0;
                } else a -= e;
            }
            if (this.size - b < 25) {
                var h = [];
                this.collapse(h);
                this.children = [ new de(h) ];
                this.children[0].parent = this;
            }
        },
        collapse: function(a) {
            for (var b = 0, c = this.children.length; b < c; ++b) this.children[b].collapse(a);
        },
        insertInner: function(a, b, c) {
            this.size += b.length;
            this.height += c;
            for (var d = 0, e = this.children.length; d < e; ++d) {
                var f = this.children[d], g = f.chunkSize();
                if (a <= g) {
                    f.insertInner(a, b, c);
                    if (f.lines && f.lines.length > 50) {
                        while (f.lines.length > 50) {
                            var h = f.lines.splice(f.lines.length - 25, 25);
                            var i = new de(h);
                            f.height -= i.height;
                            this.children.splice(d + 1, 0, i);
                            i.parent = this;
                        }
                        this.maybeSpill();
                    }
                    break;
                }
                a -= g;
            }
        },
        maybeSpill: function() {
            if (this.children.length <= 10) return;
            var a = this;
            do {
                var b = a.children.splice(a.children.length - 5, 5);
                var c = new ee(b);
                if (!a.parent) {
                    var d = new ee(a.children);
                    d.parent = a;
                    a.children = [ d, c ];
                    a = d;
                } else {
                    a.size -= c.size;
                    a.height -= c.height;
                    var e = bf(a.parent.children, a);
                    a.parent.children.splice(e + 1, 0, c);
                }
                c.parent = a.parent;
            } while (a.children.length > 10);
            a.parent.maybeSpill();
        },
        iterN: function(a, b, c) {
            for (var d = 0, e = this.children.length; d < e; ++d) {
                var f = this.children[d], g = f.chunkSize();
                if (a < g) {
                    var h = Math.min(b, g - a);
                    if (f.iterN(a, h, c)) return true;
                    if (0 == (b -= h)) break;
                    a = 0;
                } else a -= g;
            }
        }
    };
    var fe = 0;
    var ge = x.Doc = function(a, b, c) {
        if (!(this instanceof ge)) return new ge(a, b, c);
        if (null == c) c = 0;
        ee.call(this, [ new de([ new Od("", null) ]) ]);
        this.first = c;
        this.scrollTop = this.scrollLeft = 0;
        this.cantEdit = false;
        this.history = te();
        this.cleanGeneration = 1;
        this.frontier = c;
        var d = Bc(c, 0);
        this.sel = {
            from: d,
            to: d,
            head: d,
            anchor: d,
            shift: false,
            extend: false,
            goalColumn: null
        };
        this.id = ++fe;
        this.modeOption = b;
        if ("string" == typeof a) a = wf(a);
        ce(this, {
            from: d,
            to: d,
            text: a
        }, null, {
            head: d,
            anchor: d
        });
    };
    ge.prototype = cf(ee.prototype, {
        constructor: ge,
        iter: function(a, b, c) {
            if (c) this.iterN(a - this.first, b - a, c); else this.iterN(this.first, this.first + this.size, a);
        },
        insert: function(a, b) {
            var c = 0;
            for (var d = 0, e = b.length; d < e; ++d) c += b[d].height;
            this.insertInner(a - this.first, b, c);
        },
        remove: function(a, b) {
            this.removeInner(a - this.first, b);
        },
        getValue: function(a) {
            var b = ne(this, this.first, this.first + this.size);
            if (false === a) return b;
            return b.join(a || "\n");
        },
        setValue: function(a) {
            var b = Bc(this.first, 0), c = this.first + this.size - 1;
            uc(this, {
                from: b,
                to: Bc(c, le(this, c).text.length),
                text: wf(a),
                origin: "setValue"
            }, {
                head: b,
                anchor: b
            }, true);
        },
        replaceRange: function(a, b, c, d) {
            b = Gc(this, b);
            c = c ? Gc(this, c) : b;
            Ac(this, a, b, c, d);
        },
        getRange: function(a, b, c) {
            var d = me(this, Gc(this, a), Gc(this, b));
            if (false === c) return d;
            return d.join(c || "\n");
        },
        getLine: function(a) {
            var b = this.getLineHandle(a);
            return b && b.text;
        },
        setLine: function(a, b) {
            if (Ic(this, a)) Ac(this, b, Bc(a, 0), Gc(this, Bc(a)));
        },
        removeLine: function(a) {
            if (a) Ac(this, "", Gc(this, Bc(a - 1)), Gc(this, Bc(a))); else Ac(this, "", Bc(0, 0), Gc(this, Bc(1, 0)));
        },
        getLineHandle: function(a) {
            if (Ic(this, a)) return le(this, a);
        },
        getLineNumber: function(a) {
            return pe(a);
        },
        getLineHandleVisualStart: function(a) {
            if ("number" == typeof a) a = le(this, a);
            return Fd(this, a);
        },
        lineCount: function() {
            return this.size;
        },
        firstLine: function() {
            return this.first;
        },
        lastLine: function() {
            return this.first + this.size - 1;
        },
        clipPos: function(a) {
            return Gc(this, a);
        },
        getCursor: function(a) {
            var b = this.sel, c;
            if (null == a || "head" == a) c = b.head; else if ("anchor" == a) c = b.anchor; else if ("end" == a || false === a) c = b.to; else c = b.from;
            return Ec(c);
        },
        somethingSelected: function() {
            return !Cc(this.sel.head, this.sel.anchor);
        },
        setCursor: Gb(function(a, b, c) {
            var d = Gc(this, "number" == typeof a ? Bc(a, b || 0) : a);
            if (c) Jc(this, d); else Lc(this, d, d);
        }),
        setSelection: Gb(function(a, b, c) {
            Lc(this, Gc(this, a), Gc(this, b || a), c);
        }),
        extendSelection: Gb(function(a, b, c) {
            Jc(this, Gc(this, a), b && Gc(this, b), c);
        }),
        getSelection: function(a) {
            return this.getRange(this.sel.from, this.sel.to, a);
        },
        replaceSelection: function(a, b, c) {
            uc(this, {
                from: this.sel.from,
                to: this.sel.to,
                text: wf(a),
                origin: c
            }, b || "around");
        },
        undo: Gb(function() {
            wc(this, "undo");
        }),
        redo: Gb(function() {
            wc(this, "redo");
        }),
        setExtending: function(a) {
            this.sel.extend = a;
        },
        historySize: function() {
            var a = this.history;
            return {
                undo: a.done.length,
                redo: a.undone.length
            };
        },
        clearHistory: function() {
            this.history = te(this.history.maxGeneration);
        },
        markClean: function() {
            this.cleanGeneration = this.changeGeneration();
        },
        changeGeneration: function() {
            this.history.lastOp = this.history.lastOrigin = null;
            return this.history.generation;
        },
        isClean: function(a) {
            return this.history.generation == (a || this.cleanGeneration);
        },
        getHistory: function() {
            return {
                done: ze(this.history.done),
                undone: ze(this.history.undone)
            };
        },
        setHistory: function(a) {
            var b = this.history = te(this.history.maxGeneration);
            b.done = a.done.slice(0);
            b.undone = a.undone.slice(0);
        },
        markText: function(a, b, c) {
            return rd(this, Gc(this, a), Gc(this, b), c, "range");
        },
        setBookmark: function(a, b) {
            var c = {
                replacedWith: b && (null == b.nodeType ? b.widget : b),
                insertLeft: b && b.insertLeft
            };
            a = Gc(this, a);
            return rd(this, a, a, c, "bookmark");
        },
        findMarksAt: function(a) {
            a = Gc(this, a);
            var b = [], c = le(this, a.line).markedSpans;
            if (c) for (var d = 0; d < c.length; ++d) {
                var e = c[d];
                if ((null == e.from || e.from <= a.ch) && (null == e.to || e.to >= a.ch)) b.push(e.marker.parent || e.marker);
            }
            return b;
        },
        getAllMarks: function() {
            var a = [];
            this.iter(function(b) {
                var c = b.markedSpans;
                if (c) for (var d = 0; d < c.length; ++d) if (null != c[d].from) a.push(c[d].marker);
            });
            return a;
        },
        posFromIndex: function(a) {
            var b, c = this.first;
            this.iter(function(d) {
                var e = d.text.length + 1;
                if (e > a) {
                    b = a;
                    return true;
                }
                a -= e;
                ++c;
            });
            return Gc(this, Bc(c, b));
        },
        indexFromPos: function(a) {
            a = Gc(this, a);
            var b = a.ch;
            if (a.line < this.first || a.ch < 0) return 0;
            this.iter(this.first, a.line, function(a) {
                b += a.text.length + 1;
            });
            return b;
        },
        copy: function(a) {
            var b = new ge(ne(this, this.first, this.first + this.size), this.modeOption, this.first);
            b.scrollTop = this.scrollTop;
            b.scrollLeft = this.scrollLeft;
            b.sel = {
                from: this.sel.from,
                to: this.sel.to,
                head: this.sel.head,
                anchor: this.sel.anchor,
                shift: this.sel.shift,
                extend: false,
                goalColumn: this.sel.goalColumn
            };
            if (a) {
                b.history.undoDepth = this.history.undoDepth;
                b.setHistory(this.getHistory());
            }
            return b;
        },
        linkedDoc: function(a) {
            if (!a) a = {};
            var b = this.first, c = this.first + this.size;
            if (null != a.from && a.from > b) b = a.from;
            if (null != a.to && a.to < c) c = a.to;
            var d = new ge(ne(this, b, c), a.mode || this.modeOption, b);
            if (a.sharedHist) d.history = this.history;
            (this.linked || (this.linked = [])).push({
                doc: d,
                sharedHist: a.sharedHist
            });
            d.linked = [ {
                doc: this,
                isParent: true,
                sharedHist: a.sharedHist
            } ];
            return d;
        },
        unlinkDoc: function(a) {
            if (a instanceof x) a = a.doc;
            if (this.linked) for (var b = 0; b < this.linked.length; ++b) {
                var c = this.linked[b];
                if (c.doc != a) continue;
                this.linked.splice(b, 1);
                a.unlinkDoc(this);
                break;
            }
            if (a.history == this.history) {
                var d = [ a.id ];
                je(a, function(a) {
                    d.push(a.id);
                }, true);
                a.history = te();
                a.history.done = ze(this.history.done, d);
                a.history.undone = ze(this.history.undone, d);
            }
        },
        iterLinkedDocs: function(a) {
            je(this, a);
        },
        getMode: function() {
            return this.mode;
        },
        getEditor: function() {
            return this.cm;
        }
    });
    ge.prototype.eachLine = ge.prototype.iter;
    var he = "iter insert remove copy getEditor".split(" ");
    for (var ie in ge.prototype) if (ge.prototype.hasOwnProperty(ie) && bf(he, ie) < 0) x.prototype[ie] = function(a) {
        return function() {
            return a.apply(this.doc, arguments);
        };
    }(ge.prototype[ie]);
    Ue(ge);
    function je(a, b, c) {
        function d(a, e, f) {
            if (a.linked) for (var g = 0; g < a.linked.length; ++g) {
                var h = a.linked[g];
                if (h.doc == e) continue;
                var i = f && h.sharedHist;
                if (c && !i) continue;
                b(h.doc, i);
                d(h.doc, a, i);
            }
        }
        d(a, null, true);
    }
    function ke(a, b) {
        if (b.cm) throw new Error("This document is already in use.");
        a.doc = b;
        b.cm = a;
        C(a);
        z(a);
        if (!a.options.lineWrapping) I(a);
        a.options.mode = b.modeOption;
        Ib(a);
    }
    function le(a, b) {
        b -= a.first;
        while (!a.lines) for (var c = 0; ;++c) {
            var d = a.children[c], e = d.chunkSize();
            if (b < e) {
                a = d;
                break;
            }
            b -= e;
        }
        return a.lines[b];
    }
    function me(a, b, c) {
        var d = [], e = b.line;
        a.iter(b.line, c.line + 1, function(a) {
            var f = a.text;
            if (e == c.line) f = f.slice(0, c.ch);
            if (e == b.line) f = f.slice(b.ch);
            d.push(f);
            ++e;
        });
        return d;
    }
    function ne(a, b, c) {
        var d = [];
        a.iter(b, c, function(a) {
            d.push(a.text);
        });
        return d;
    }
    function oe(a, b) {
        var c = b - a.height;
        for (var d = a; d; d = d.parent) d.height += c;
    }
    function pe(a) {
        if (null == a.parent) return null;
        var b = a.parent, c = bf(b.lines, a);
        for (var d = b.parent; d; b = d, d = d.parent) for (var e = 0; ;++e) {
            if (d.children[e] == b) break;
            c += d.children[e].chunkSize();
        }
        return c + b.first;
    }
    function qe(a, b) {
        var c = a.first;
        a: do {
            for (var d = 0, e = a.children.length; d < e; ++d) {
                var f = a.children[d], g = f.height;
                if (b < g) {
                    a = f;
                    continue a;
                }
                b -= g;
                c += f.chunkSize();
            }
            return c;
        } while (!a.lines);
        for (var d = 0, e = a.lines.length; d < e; ++d) {
            var h = a.lines[d], i = h.height;
            if (b < i) break;
            b -= i;
        }
        return c + d;
    }
    function re(a, b) {
        b = Fd(a.doc, b);
        var c = 0, d = b.parent;
        for (var e = 0; e < d.lines.length; ++e) {
            var f = d.lines[e];
            if (f == b) break; else c += f.height;
        }
        for (var g = d.parent; g; d = g, g = d.parent) for (var e = 0; e < g.children.length; ++e) {
            var h = g.children[e];
            if (h == d) break; else c += h.height;
        }
        return c;
    }
    function se(a) {
        var b = a.order;
        if (null == b) b = a.order = Nf(a.text);
        return b;
    }
    function te(a) {
        return {
            done: [],
            undone: [],
            undoDepth: 1/0,
            lastTime: 0,
            lastOp: null,
            lastOrigin: null,
            generation: a || 1,
            maxGeneration: a || 1
        };
    }
    function ue(a, b, c, d) {
        var e = b["spans_" + a.id], f = 0;
        a.iter(Math.max(a.first, c), Math.min(a.first + a.size, d), function(c) {
            if (c.markedSpans) (e || (e = b["spans_" + a.id] = {}))[f] = c.markedSpans;
            ++f;
        });
    }
    function ve(a, b) {
        var c = {
            line: b.from.line,
            ch: b.from.ch
        };
        var d = {
            from: c,
            to: qc(b),
            text: me(a, b.from, b.to)
        };
        ue(a, d, b.from.line, b.to.line + 1);
        je(a, function(a) {
            ue(a, d, b.from.line, b.to.line + 1);
        }, true);
        return d;
    }
    function we(a, b, c, d) {
        var e = a.history;
        e.undone.length = 0;
        var f = +new Date(), g = _e(e.done);
        if (g && (e.lastOp == d || e.lastOrigin == b.origin && b.origin && ("+" == b.origin.charAt(0) && a.cm && e.lastTime > f - a.cm.options.historyEventDelay || "*" == b.origin.charAt(0)))) {
            var h = _e(g.changes);
            if (Cc(b.from, b.to) && Cc(b.from, h.to)) h.to = qc(b); else g.changes.push(ve(a, b));
            g.anchorAfter = c.anchor;
            g.headAfter = c.head;
        } else {
            g = {
                changes: [ ve(a, b) ],
                generation: e.generation,
                anchorBefore: a.sel.anchor,
                headBefore: a.sel.head,
                anchorAfter: c.anchor,
                headAfter: c.head
            };
            e.done.push(g);
            e.generation = ++e.maxGeneration;
            while (e.done.length > e.undoDepth) e.done.shift();
        }
        e.lastTime = f;
        e.lastOp = d;
        e.lastOrigin = b.origin;
    }
    function xe(a) {
        if (!a) return null;
        for (var b = 0, c; b < a.length; ++b) if (a[b].marker.explicitlyCleared) {
            if (!c) c = a.slice(0, b);
        } else if (c) c.push(a[b]);
        return !c ? a : c.length ? c : null;
    }
    function ye(a, b) {
        var c = b["spans_" + a.id];
        if (!c) return null;
        for (var d = 0, e = []; d < b.text.length; ++d) e.push(xe(c[d]));
        return e;
    }
    function ze(a, b) {
        for (var c = 0, d = []; c < a.length; ++c) {
            var e = a[c], f = e.changes, g = [];
            d.push({
                changes: g,
                anchorBefore: e.anchorBefore,
                headBefore: e.headBefore,
                anchorAfter: e.anchorAfter,
                headAfter: e.headAfter
            });
            for (var h = 0; h < f.length; ++h) {
                var i = f[h], j;
                g.push({
                    from: i.from,
                    to: i.to,
                    text: i.text
                });
                if (b) for (var k in i) if (j = k.match(/^spans_(\d+)$/)) if (bf(b, Number(j[1])) > -1) {
                    _e(g)[k] = i[k];
                    delete i[k];
                }
            }
        }
        return d;
    }
    function Ae(a, b, c, d) {
        if (c < a.line) a.line += d; else if (b < a.line) {
            a.line = b;
            a.ch = 0;
        }
    }
    function Be(a, b, c, d) {
        for (var e = 0; e < a.length; ++e) {
            var f = a[e], g = true;
            for (var h = 0; h < f.changes.length; ++h) {
                var i = f.changes[h];
                if (!f.copied) {
                    i.from = Ec(i.from);
                    i.to = Ec(i.to);
                }
                if (c < i.from.line) {
                    i.from.line += d;
                    i.to.line += d;
                } else if (b <= i.to.line) {
                    g = false;
                    break;
                }
            }
            if (!f.copied) {
                f.anchorBefore = Ec(f.anchorBefore);
                f.headBefore = Ec(f.headBefore);
                f.anchorAfter = Ec(f.anchorAfter);
                f.readAfter = Ec(f.headAfter);
                f.copied = true;
            }
            if (!g) {
                a.splice(0, e + 1);
                e = 0;
            } else {
                Ae(f.anchorBefore);
                Ae(f.headBefore);
                Ae(f.anchorAfter);
                Ae(f.headAfter);
            }
        }
    }
    function Ce(a, b) {
        var c = b.from.line, d = b.to.line, e = b.text.length - (d - c) - 1;
        Be(a.done, c, d, e);
        Be(a.undone, c, d, e);
    }
    function De() {
        Ie(this);
    }
    function Ee(a) {
        if (!a.stop) a.stop = De;
        return a;
    }
    function Fe(a) {
        if (a.preventDefault) a.preventDefault(); else a.returnValue = false;
    }
    function Ge(a) {
        if (a.stopPropagation) a.stopPropagation(); else a.cancelBubble = true;
    }
    function He(a) {
        return null != a.defaultPrevented ? a.defaultPrevented : false == a.returnValue;
    }
    function Ie(a) {
        Fe(a);
        Ge(a);
    }
    x.e_stop = Ie;
    x.e_preventDefault = Fe;
    x.e_stopPropagation = Ge;
    function Je(a) {
        return a.target || a.srcElement;
    }
    function Ke(a) {
        var b = a.which;
        if (null == b) if (1 & a.button) b = 1; else if (2 & a.button) b = 3; else if (4 & a.button) b = 2;
        if (q && a.ctrlKey && 1 == b) b = 3;
        return b;
    }
    function Le(a, b, c) {
        if (a.addEventListener) a.addEventListener(b, c, false); else if (a.attachEvent) a.attachEvent("on" + b, c); else {
            var d = a._handlers || (a._handlers = {});
            var e = d[b] || (d[b] = []);
            e.push(c);
        }
    }
    function Me(a, b, c) {
        if (a.removeEventListener) a.removeEventListener(b, c, false); else if (a.detachEvent) a.detachEvent("on" + b, c); else {
            var d = a._handlers && a._handlers[b];
            if (!d) return;
            for (var e = 0; e < d.length; ++e) if (d[e] == c) {
                d.splice(e, 1);
                break;
            }
        }
    }
    function Ne(a, b) {
        var c = a._handlers && a._handlers[b];
        if (!c) return;
        var d = Array.prototype.slice.call(arguments, 2);
        for (var e = 0; e < c.length; ++e) c[e].apply(null, d);
    }
    var Oe, Pe = 0;
    function Qe(a, b) {
        var c = a._handlers && a._handlers[b];
        if (!c) return;
        var d = Array.prototype.slice.call(arguments, 2);
        if (!Oe) {
            ++Pe;
            Oe = [];
            setTimeout(Se, 0);
        }
        function e(a) {
            return function() {
                a.apply(null, d);
            };
        }
        for (var f = 0; f < c.length; ++f) Oe.push(e(c[f]));
    }
    function Re(a, b, c) {
        Ne(a, c || b.type, a, b);
        return He(b) || b.codemirrorIgnore;
    }
    function Se() {
        --Pe;
        var a = Oe;
        Oe = null;
        for (var b = 0; b < a.length; ++b) a[b]();
    }
    function Te(a, b) {
        var c = a._handlers && a._handlers[b];
        return c && c.length > 0;
    }
    x.on = Le;
    x.off = Me;
    x.signal = Ne;
    function Ue(a) {
        a.prototype.on = function(a, b) {
            Le(this, a, b);
        };
        a.prototype.off = function(a, b) {
            Me(this, a, b);
        };
    }
    var Ve = 30;
    var We = x.Pass = {
        toString: function() {
            return "CodeMirror.Pass";
        }
    };
    function Xe() {
        this.id = null;
    }
    Xe.prototype = {
        set: function(a, b) {
            clearTimeout(this.id);
            this.id = setTimeout(b, a);
        }
    };
    function Ye(a, b, c, d, e) {
        if (null == b) {
            b = a.search(/[^\s\u00a0]/);
            if (b == -1) b = a.length;
        }
        for (var f = d || 0, g = e || 0; f < b; ++f) if ("	" == a.charAt(f)) g += c - g % c; else ++g;
        return g;
    }
    x.countColumn = Ye;
    var Ze = [ "" ];
    function $e(a) {
        while (Ze.length <= a) Ze.push(_e(Ze) + " ");
        return Ze[a];
    }
    function _e(a) {
        return a[a.length - 1];
    }
    function af(a) {
        if (o) {
            a.selectionStart = 0;
            a.selectionEnd = a.value.length;
        } else try {
            a.select();
        } catch (b) {}
    }
    function bf(a, b) {
        if (a.indexOf) return a.indexOf(b);
        for (var c = 0, d = a.length; c < d; ++c) if (a[c] == b) return c;
        return -1;
    }
    function cf(a, b) {
        function c() {}
        c.prototype = a;
        var d = new c();
        if (b) df(b, d);
        return d;
    }
    function df(a, b) {
        if (!b) b = {};
        for (var c in a) if (a.hasOwnProperty(c)) b[c] = a[c];
        return b;
    }
    function ef(a) {
        for (var b = [], c = 0; c < a; ++c) b.push(void 0);
        return b;
    }
    function ff(a) {
        var b = Array.prototype.slice.call(arguments, 1);
        return function() {
            return a.apply(null, b);
        };
    }
    var gf = /[\u3040-\u309f\u30a0-\u30ff\u3400-\u4db5\u4e00-\u9fcc\uac00-\ud7af]/;
    function hf(a) {
        return /\w/.test(a) || a > "?" && (a.toUpperCase() != a.toLowerCase() || gf.test(a));
    }
    function jf(a) {
        for (var b in a) if (a.hasOwnProperty(b) && a[b]) return false;
        return true;
    }
    var kf = /[\u0300-\u036F\u0483-\u0487\u0488-\u0489\u0591-\u05BD\u05BF\u05C1-\u05C2\u05C4-\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7-\u06E8\u06EA-\u06ED\uA66F\u1DC0–\u1DFF\u20D0–\u20FF\uA670-\uA672\uA674-\uA67D\uA69F\udc00-\udfff\uFE20–\uFE2F]/;
    function lf(a, b, c, d) {
        var e = document.createElement(a);
        if (c) e.className = c;
        if (d) e.style.cssText = d;
        if ("string" == typeof b) of(e, b); else if (b) for (var f = 0; f < b.length; ++f) e.appendChild(b[f]);
        return e;
    }
    function mf(a) {
        for (var b = a.childNodes.length; b > 0; --b) a.removeChild(a.firstChild);
        return a;
    }
    function nf(a, b) {
        return mf(a).appendChild(b);
    }
    function of(a, b) {
        if (d) {
            a.innerHTML = "";
            a.appendChild(document.createTextNode(b));
        } else a.textContent = b;
    }
    function pf(a) {
        return a.getBoundingClientRect();
    }
    x.replaceGetRect = function(a) {
        pf = a;
    };
    var qf = function() {
        if (d) return false;
        var a = lf("div");
        return "draggable" in a || "dragDrop" in a;
    }();
    function rf() {
        return false;
    }
    if (a) rf = function(a, b) {
        return 36 == a.charCodeAt(b - 1) && 39 == a.charCodeAt(b);
    }; else if (j && !/Version\/([6-9]|\d\d)\b/.test(navigator.userAgent)) rf = function(a, b) {
        return /\-[^ \-?]|\?[^ !\'\"\),.\-\/:;\?\]\}]/.test(a.slice(b - 1, b + 1));
    }; else if (f && /Chrome\/(?:29|[3-9]\d|\d\d\d)\./.test(navigator.userAgent)) rf = function(a, b) {
        var c = a.charCodeAt(b - 1);
        return c >= 8208 && c <= 8212;
    }; else if (f) rf = function(a, b) {
        if (b > 1 && 45 == a.charCodeAt(b - 1)) {
            if (/\w/.test(a.charAt(b - 2)) && /[^\-?\.]/.test(a.charAt(b))) return true;
            if (b > 2 && /[\d\.,]/.test(a.charAt(b - 2)) && /[\d\.,]/.test(a.charAt(b))) return false;
        }
        return /[~!#%&*)=+}\]\\|\"\.>,:;][({[<]|-[^\-?\.\u2010-\u201f\u2026]|\?[\w~`@#$%\^&*(_=+{[|><]|…[\w~`@#$%\^&*(_=+{[><]/.test(a.slice(b - 1, b + 1));
    };
    var sf;
    function tf(a) {
        if (null != sf) return sf;
        var b = lf("div", null, null, "width: 50px; height: 50px; overflow-x: scroll");
        nf(a, b);
        if (b.offsetWidth) sf = b.offsetHeight - b.clientHeight;
        return sf || 0;
    }
    var uf;
    function vf(a) {
        if (null == uf) {
            var b = lf("span", "?");
            nf(a, lf("span", [ b, document.createTextNode("x") ]));
            if (0 != a.firstChild.offsetHeight) uf = b.offsetWidth <= 1 && b.offsetHeight > 2 && !c;
        }
        if (uf) return lf("span", "?"); else return lf("span", " ", null, "display: inline-block; width: 1px; margin-right: -1px");
    }
    var wf = 3 != "\n\nb".split(/\n/).length ? function(a) {
        var b = 0, c = [], d = a.length;
        while (b <= d) {
            var e = a.indexOf("\n", b);
            if (e == -1) e = a.length;
            var f = a.slice(b, "\r" == a.charAt(e - 1) ? e - 1 : e);
            var g = f.indexOf("\r");
            if (g != -1) {
                c.push(f.slice(0, g));
                b += g + 1;
            } else {
                c.push(f);
                b = e + 1;
            }
        }
        return c;
    } : function(a) {
        return a.split(/\r\n?|\n/);
    };
    x.splitLines = wf;
    var xf = window.getSelection ? function(a) {
        try {
            return a.selectionStart != a.selectionEnd;
        } catch (b) {
            return false;
        }
    } : function(a) {
        try {
            var b = a.ownerDocument.selection.createRange();
        } catch (c) {}
        if (!b || b.parentElement() != a) return false;
        return 0 != b.compareEndPoints("StartToEnd", b);
    };
    var yf = function() {
        var a = lf("div");
        if ("oncopy" in a) return true;
        a.setAttribute("oncopy", "return;");
        return "function" == typeof a.oncopy;
    }();
    var zf = {
        3: "Enter",
        8: "Backspace",
        9: "Tab",
        13: "Enter",
        16: "Shift",
        17: "Ctrl",
        18: "Alt",
        19: "Pause",
        20: "CapsLock",
        27: "Esc",
        32: "Space",
        33: "PageUp",
        34: "PageDown",
        35: "End",
        36: "Home",
        37: "Left",
        38: "Up",
        39: "Right",
        40: "Down",
        44: "PrintScrn",
        45: "Insert",
        46: "Delete",
        59: ";",
        91: "Mod",
        92: "Mod",
        93: "Mod",
        109: "-",
        107: "=",
        127: "Delete",
        186: ";",
        187: "=",
        188: ",",
        189: "-",
        190: ".",
        191: "/",
        192: "`",
        219: "[",
        220: "\\",
        221: "]",
        222: "'",
        63276: "PageUp",
        63277: "PageDown",
        63275: "End",
        63273: "Home",
        63234: "Left",
        63232: "Up",
        63235: "Right",
        63233: "Down",
        63302: "Insert",
        63272: "Delete"
    };
    x.keyNames = zf;
    !function() {
        for (var a = 0; a < 10; a++) zf[a + 48] = String(a);
        for (var a = 65; a <= 90; a++) zf[a] = String.fromCharCode(a);
        for (var a = 1; a <= 12; a++) zf[a + 111] = zf[a + 63235] = "F" + a;
    }();
    function Af(a, b, c, d) {
        if (!a) return d(b, c, "ltr");
        var e = false;
        for (var f = 0; f < a.length; ++f) {
            var g = a[f];
            if (g.from < c && g.to > b || b == c && g.to == b) {
                d(Math.max(g.from, b), Math.min(g.to, c), 1 == g.level ? "rtl" : "ltr");
                e = true;
            }
        }
        if (!e) d(b, c, "ltr");
    }
    function Bf(a) {
        return a.level % 2 ? a.to : a.from;
    }
    function Cf(a) {
        return a.level % 2 ? a.from : a.to;
    }
    function Df(a) {
        var b = se(a);
        return b ? Bf(b[0]) : 0;
    }
    function Ef(a) {
        var b = se(a);
        if (!b) return a.text.length;
        return Cf(_e(b));
    }
    function Ff(a, b) {
        var c = le(a.doc, b);
        var d = Fd(a.doc, c);
        if (d != c) b = pe(d);
        var e = se(d);
        var f = !e ? 0 : e[0].level % 2 ? Ef(d) : Df(d);
        return Bc(b, f);
    }
    function Gf(a, b) {
        var c, d;
        while (c = Ed(d = le(a.doc, b))) b = c.find().to.line;
        var e = se(d);
        var f = !e ? d.text.length : e[0].level % 2 ? Df(d) : Ef(d);
        return Bc(b, f);
    }
    function Hf(a, b, c) {
        var d = a[0].level;
        if (b == d) return true;
        if (c == d) return false;
        return b < c;
    }
    var If;
    function Jf(a, b) {
        for (var c = 0, d; c < a.length; ++c) {
            var e = a[c];
            if (e.from < b && e.to > b) {
                If = null;
                return c;
            }
            if (e.from == b || e.to == b) if (null == d) d = c; else if (Hf(a, e.level, a[d].level)) {
                If = d;
                return c;
            } else {
                If = c;
                return d;
            }
        }
        If = null;
        return d;
    }
    function Kf(a, b, c, d) {
        if (!d) return b + c;
        do b += c; while (b > 0 && kf.test(a.text.charAt(b)));
        return b;
    }
    function Lf(a, b, c, d) {
        var e = se(a);
        if (!e) return Mf(a, b, c, d);
        var f = Jf(e, b), g = e[f];
        var h = Kf(a, b, g.level % 2 ? -c : c, d);
        for (;;) {
            if (h > g.from && h < g.to) return h;
            if (h == g.from || h == g.to) {
                if (Jf(e, h) == f) return h;
                g = e[f += c];
                return c > 0 == g.level % 2 ? g.to : g.from;
            } else {
                g = e[f += c];
                if (!g) return null;
                if (c > 0 == g.level % 2) h = Kf(a, g.to, -1, d); else h = Kf(a, g.from, 1, d);
            }
        }
    }
    function Mf(a, b, c, d) {
        var e = b + c;
        if (d) while (e > 0 && kf.test(a.text.charAt(e))) e += c;
        return e < 0 || e > a.text.length ? null : e;
    }
    var Nf = function() {
        var a = "bbbbbbbbbtstwsbbbbbbbbbbbbbbssstwNN%%%NNNNNN,N,N1111111111NNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNbbbbbbsbbbbbbbbbbbbbbbbbbbbbbbbbb,N%%%%NNNNLNNNNN%%11NLNNN1LNNNNNLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLL";
        var b = "rrrrrrrrrrrr,rNNmmmmmmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmrrrrrrrnnnnnnnnnn%nnrrrmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmmmmmmNmmmmrrrrrrrrrrrrrrrrrr";
        function c(c) {
            if (c <= 255) return a.charAt(c); else if (1424 <= c && c <= 1524) return "R"; else if (1536 <= c && c <= 1791) return b.charAt(c - 1536); else if (1792 <= c && c <= 2220) return "r"; else return "L";
        }
        var d = /[\u0590-\u05f4\u0600-\u06ff\u0700-\u08ac]/;
        var e = /[stwN]/, f = /[LRr]/, g = /[Lb1n]/, h = /[1n]/;
        var i = "L";
        return function(a) {
            if (!d.test(a)) return false;
            var b = a.length, j = [];
            for (var k = 0, l; k < b; ++k) j.push(l = c(a.charCodeAt(k)));
            for (var k = 0, m = i; k < b; ++k) {
                var l = j[k];
                if ("m" == l) j[k] = m; else m = l;
            }
            for (var k = 0, n = i; k < b; ++k) {
                var l = j[k];
                if ("1" == l && "r" == n) j[k] = "n"; else if (f.test(l)) {
                    n = l;
                    if ("r" == l) j[k] = "R";
                }
            }
            for (var k = 1, m = j[0]; k < b - 1; ++k) {
                var l = j[k];
                if ("+" == l && "1" == m && "1" == j[k + 1]) j[k] = "1"; else if ("," == l && m == j[k + 1] && ("1" == m || "n" == m)) j[k] = m;
                m = l;
            }
            for (var k = 0; k < b; ++k) {
                var l = j[k];
                if ("," == l) j[k] = "N"; else if ("%" == l) {
                    for (var o = k + 1; o < b && "%" == j[o]; ++o) ;
                    var p = k && "!" == j[k - 1] || o < b - 1 && "1" == j[o] ? "1" : "N";
                    for (var q = k; q < o; ++q) j[q] = p;
                    k = o - 1;
                }
            }
            for (var k = 0, n = i; k < b; ++k) {
                var l = j[k];
                if ("L" == n && "1" == l) j[k] = "L"; else if (f.test(l)) n = l;
            }
            for (var k = 0; k < b; ++k) if (e.test(j[k])) {
                for (var o = k + 1; o < b && e.test(j[o]); ++o) ;
                var r = "L" == (k ? j[k - 1] : i);
                var s = "L" == (o < b - 1 ? j[o] : i);
                var p = r || s ? "L" : "R";
                for (var q = k; q < o; ++q) j[q] = p;
                k = o - 1;
            }
            var t = [], u;
            for (var k = 0; k < b; ) if (g.test(j[k])) {
                var v = k;
                for (++k; k < b && g.test(j[k]); ++k) ;
                t.push({
                    from: v,
                    to: k,
                    level: 0
                });
            } else {
                var w = k, x = t.length;
                for (++k; k < b && "L" != j[k]; ++k) ;
                for (var q = w; q < k; ) if (h.test(j[q])) {
                    if (w < q) t.splice(x, 0, {
                        from: w,
                        to: q,
                        level: 1
                    });
                    var y = q;
                    for (++q; q < k && h.test(j[q]); ++q) ;
                    t.splice(x, 0, {
                        from: y,
                        to: q,
                        level: 2
                    });
                    w = q;
                } else ++q;
                if (w < k) t.splice(x, 0, {
                    from: w,
                    to: k,
                    level: 1
                });
            }
            if (1 == t[0].level && (u = a.match(/^\s+/))) {
                t[0].from = u[0].length;
                t.unshift({
                    from: 0,
                    to: u[0].length,
                    level: 0
                });
            }
            if (1 == _e(t).level && (u = a.match(/\s+$/))) {
                _e(t).to -= u[0].length;
                t.push({
                    from: b - u[0].length,
                    to: b,
                    level: 0
                });
            }
            if (t[0].level != _e(t).level) t.push({
                from: b,
                to: b,
                level: t[0].level
            });
            return t;
        };
    }();
    x.version = "3.20.0";
    return x;
}();