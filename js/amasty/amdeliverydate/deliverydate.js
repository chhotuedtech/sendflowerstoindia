/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/

var amDeliverydateCondition = new Class.create();

amDeliverydateCondition.prototype = {
    conditions: null,
    initialize: function(conditions)
    {
        this.conditions = conditions;
    },
    check: function()
    {
       if (this.conditions && this.conditions['shipping_methods']) {
            var shippingForm = $$('.sp-methods')[0];
            if (shippingForm && !shippingForm.getAttribute('shipping_conditions_initialized')) {
                this.initShippingMethodsCondition();
                shippingForm.setAttribute('shipping_conditions_initialized', 1);
            }
        } 
    },
    initShippingMethodsCondition: function()
    {
        var _caller = this;
        var allowed_shipping_methods = this.conditions['shipping_methods'];
        $$('#co-shipping-method-form input').each(function(el) {
            if (el.id.indexOf('s_method_') != -1) {
                el.observe('change', _caller.onShippingMethodChange.bind(_caller, el, allowed_shipping_methods));
            }
        })
        var checked = $$('#co-shipping-method-form input:checked')[0];
        if (checked) {
            this.onShippingMethodChange(checked, allowed_shipping_methods);
        } else {
            this.hideOnStart(allowed_shipping_methods);
        }
    },
    onShippingMethodChange: function(input, shipping_methods)
    {
        var current_shipping_method = input.id.replace('s_method_', '');
        var available = $H(shipping_methods[current_shipping_method]);
        for(var shipping_method in shipping_methods) {
            for(var ind in shipping_methods[shipping_method]) {
                var attribute = shipping_methods[shipping_method][ind];
                if (typeof(attribute) !== 'function') {
                    var objects = $$('[id="anchor_' + attribute + '"]');
                    if (objects) {
                        objects.each(function(object) {
                            var row = object.up();
                            row.setStyle({
                                'display': (!available.get(attribute) ? 'none' : '')
                            })
                            row.setAttribute('noshow', 1);
                        })
                    }
                }
            }
        }
    },
    hideOnStart: function(shipping_methods)
    {
        for(var shipping_method in shipping_methods) {
            for(var ind in shipping_methods[shipping_method]) {
                var attribute = shipping_methods[shipping_method][ind];
                if (typeof(attribute) !== 'function'){
                    var objects = $$('[id="anchor_' + attribute + '"]');
                    if (objects) {
                        objects.each(function(object) {
                            var row = object.up();
                            row.setStyle({
                                'display': 'none'
                            })
                            row.setAttribute('noshow', 1);
                        })
                    }
                }
            }
        }
    }
}

Calendar.prototype._init = function (firstDayOfWeek, date) {
    var today = new CalendarDateObject(),
        TY = today.getFullYear(),
        TM = today.getMonth(),
        TD = today.getDate();
    this.table.style.visibility = "hidden";
    var year = date.getFullYear();
    if (year < this.minYear) {
        year = this.minYear;
        date.setFullYear(year);
    } else if (year > this.maxYear) {
        year = this.maxYear;
        date.setFullYear(year);
    }
    this.firstDayOfWeek = firstDayOfWeek;
    this.date = new CalendarDateObject(date);
    var month = date.getMonth();
    var mday = date.getDate();
    var no_days = date.getMonthDays();

    date.setDate(1);
    var day1 = (date.getDay() - this.firstDayOfWeek) % 7;
    if (day1 < 0)
        day1 += 7;
    date.setDate(-day1);
    date.setDate(date.getDate() + 1);

    var row = this.tbody.firstChild;
    var MN = Calendar._SMN[month];
    var ar_days = this.ar_days = new Array();
    var weekend = Calendar._TT["WEEKEND"];
    var dates = this.multiple ? (this.datesCells = {}) : null;
    for (var i = 0; i < 6; ++i, row = row.nextSibling) {
        var cell = row.firstChild;
        if (this.weekNumbers) {
            cell.className = "day wn";
            cell.innerHTML = date.getWeekNumber();
            cell = cell.nextSibling;
        }
        row.className = "daysrow";
        var hasdays = false, iday, dpos = ar_days[i] = [];
        for (var j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(iday + 1)) {
            iday = date.getDate();
            var wday = date.getDay();
            cell.className = "day";
            cell.pos = i << 4 | j;
            dpos[j] = cell;
            var current_month = (date.getMonth() == month);
            if (!current_month) {
                if (this.showsOtherMonths) {
                    cell.className += " othermonth";
                    cell.otherMonth = true;
                } else {
                    cell.className = "emptycell";
                    cell.innerHTML = "&nbsp;";
                    cell.disabled = true;
                    continue;
                }
            } else {
                cell.otherMonth = false;
                hasdays = true;
            }
            cell.disabled = false;
            cell.innerHTML = this.getDateText ? this.getDateText(date, iday) : iday;
            if (dates)
                dates[date.print("%Y%m%d")] = cell;
            if (this.getDateStatus) {
                var status = this.getDateStatus(date, year, month, iday);
                if (this.getDateToolTip) {
                    var toolTip = this.getDateToolTip(date, year, month, iday);
                    if (toolTip)
                        cell.title = toolTip;
                }
                if (status === true) {
                    cell.className += " disabled";
                    cell.disabled = true;
                } else {
                    if (/disabled/i.test(status))
                        cell.disabled = true;
                    cell.className += " " + status;
                }
            }
            
            cell.caldate = new CalendarDateObject(date);
            cell.ttip = "_";
            if (!this.multiple && current_month
                && iday == mday && this.hiliteToday) {
                cell.className += " selected";
                this.currentDateEl = cell;
            }
            if (date.getFullYear() == TY &&
                date.getMonth() == TM &&
                iday == TD) {
                cell.className += " today";
                cell.ttip += Calendar._TT["PART_TODAY"];
            }
            if ((weekend.indexOf(wday.toString()) != -1) && (cell.disabled != true))
                cell.className += cell.otherMonth ? " oweekend" : " weekend";
        }
        if (!(hasdays || this.showsOtherMonths))
            row.className = "emptyrow";
    }
    this.title.innerHTML = Calendar._MN[month] + ", " + year;
    this.onSetTime();
    this.table.style.visibility = "visible";
    this._initMultipleDates();
};

Calendar.prototype.callHandler = function () {
    if (this.onSelected) {
        if (typeof(this.params.disableFunc) === 'function') {
            if (!this.params.disableFunc(this.date)) {
                this.onSelected(this, this.date.print(this.dateFormat));
                if ($(this.params.inputField.id + '_hidden')) {
                    $(this.params.inputField.id + '_hidden').value = this.date.print("%Y-%m-%d");
                }
            }
        } else {
            this.onSelected(this, this.date.print(this.dateFormat));
            if ($(this.params.inputField.id + '_hidden')) {
                $(this.params.inputField.id + '_hidden').value = this.date.print("%Y-%m-%d");
            }
        }
    }
};