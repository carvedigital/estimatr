String.prototype.isNumber = function () {
    return /^\d+$/.test(this);
}

var EstimateUtils = (function () {

    return {
        isFloat: function (n) {
            return n === Number(n) && n % 1 !== 0;
        },
        isInt: function (n) {
            return Number(n) === n && n % 1 === 0;
        },
        outputMoney: function(value,locale)
        {
            var symbol = '';
            var format = '';
            switch(locale)
            {
                case 'GBP':
                    symbol = '£';
                    format = '%s%v';
                    break;
                case 'USD':
                    symbol = '$';
                    format = '%s%v';
                    break;
                case 'EUR':
                    symbol = '€';
                    format = '%v %s';
                    break;
            }
            return accounting.formatMoney(value, { symbol: symbol,  format: format });
        },
        forceToNumber: function (number) {
            if (this.isInt(number)) {
                return parseInt(number, 10);
            }

            return parseFloat(number);
        },
        generateUUID: function () {
            var d = new Date().getTime();
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = (d + Math.random() * 16) % 16 | 0;
                d = Math.floor(d / 16);
                return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
            });
            return uuid;
        },
        getHumanReadableFromHourFigure: function (hourFigure, hoursInDay) {
            var days = Math.floor(hourFigure / hoursInDay);
            var hours = Math.ceil( hourFigure - ( days * hoursInDay ) );
            var dayString = (days > 0 ? ( days > 1 ? days + ' days' : days + ' day' ) : '');
            var hoursString = (hours > 0 ? ( hours > 1 ? hours + ' hours' : hours + ' hours' ) : (days < 1 ? '0 Hours' : ''));

            return dayString + ' ' + hoursString;
        },
        ensureUnsigned: function(figure){
            if( figure < 0 )
            {
                return 0;
            }

            return figure;
        },
        parseIncomingFigure: function (figures, hoursInDay) {
            if (!figures) {
                return 0;
            }

            // If it's already a number then we just return it as it should be fine
            if (_.isNumber(figures)) {
                return this.ensureUnsigned(figures * hoursInDay);
            }

            // If we have a . it implies it's already a float and we should respect that
            if (figures.indexOf(".") != -1) {
                // If this passes, it means we have just a float value and no other text
                if (/^[\d\.]{3,}$/.test(figures)) {
                    return this.ensureUnsigned(parseInt(_.toNumber(figures*hoursInDay),10));
                } else {
                    var hourFigure = 0.00;

                    var days = figures.match(/(\d+)\.(\d+)\s*d/);
                    var hours = figures.match(/(\d+)\.(\d+)\s*h/);
                    var hoursNoDecimal = figures.match(/(\d+)\s*h/);

                    if (days) {
                        hourFigure = this.ensureUnsigned(Math.ceil(parseFloat("" + days[1] + "." + days[2])*hoursInDay));
                    }

                    if (hours) {
                        var newHourFigure = Math.ceil(parseFloat("" + hours[1] + "." + hours[2]));
                        hourFigure = hourFigure + this.ensureUnsigned(newHourFigure)
                    }
                    if (hoursNoDecimal && !hours) {
                        hourFigure = hourFigure + this.ensureUnsigned(parseInt(_.toNumber(hoursNoDecimal[1]),10))
                    }

                    return this.ensureUnsigned(parseInt(hourFigure,10));
                }

            }

            // If the string contains just numbers we will presume this means days and return it as a real number representation
            if (/^\d+$/.test(figures)) {
                return this.ensureUnsigned(_.toNumber(figures) * hoursInDay);
            }


            // If we get here then it means we have a string that is not a float or plain number
            // but most like something like "5 days 2 hours". Let's parse that and work out the appropriate
            // float value for such a scenario.
            var hourFigure = 0.00;

            var days = figures.match(/(\d+)\s*d/);
            var hours = figures.match(/(\d+)\s*h/);

            if (days) {
                hourFigure = parseInt(days[1] * hoursInDay,10);
            }

            if (hours) {
                hourFigure = hourFigure + parseInt(hours[1],10);
            }

            return this.ensureUnsigned(parseInt(hourFigure,10));
        }
    };
})();


function EstimateItem(itemdetails, rate, hoursInDay) {
    this.id = EstimateUtils.generateUUID();

    if (itemdetails.hasOwnProperty('id')) {
        this.id = itemdetails.id;
    }

    this.name = itemdetails.name;
    this.locale = itemdetails.locale;
    this.best = itemdetails.best;
    this.worst = itemdetails.worst;
    this.standard = itemdetails.standard;
    this.rate = rate;
    this.hoursInDay = hoursInDay;
    this.editing = false;
    this.new_item = true;

    this.bestEditing = itemdetails.best;
    this.worstEditing = itemdetails.worst;
    this.standardEditing = itemdetails.standard;

    this.getId = function () {
        return this.id;
    },
    this.updateRate = function (rate) {
        this.rate = rate;
    },
    this.setEditing = function (value) {
        this.editing = value ? true : false;
    },
    this.getName = function () {
        return this.name ? this.name : 'Unnamed';
    },
    this.getBest = function () {
        return EstimateUtils.forceToNumber(this.best);
    },
    this.getBestHuman = function () {
        return EstimateUtils.getHumanReadableFromHourFigure(this.getBest(), this.hoursInDay);
    },
    this.getWorst = function () {
        return EstimateUtils.forceToNumber(this.worst);
    },
    this.getWorstHuman = function () {
        return EstimateUtils.getHumanReadableFromHourFigure(this.getWorst(), this.hoursInDay);
    },
    this.getStandard = function () {
        return EstimateUtils.forceToNumber(this.standard);
    },
    this.getStandardHuman = function () {
        return EstimateUtils.getHumanReadableFromHourFigure(this.getStandard(), this.hoursInDay);
    },
    this.getEstimateTotal = function () {
        return parseInt(Math.ceil((this.getBest() + parseFloat(4 * this.getStandard()) + this.getWorst() ) / 6),10);
    },
    this.getHumanReadableEstimate = function () {
        return EstimateUtils.getHumanReadableFromHourFigure(this.getEstimateTotal(), this.hoursInDay);
    },
    this.getEstimatedDays = function () {
        return Math.floor(this.getEstimateTotal() * this.hoursInDay);
    },
    this.getEstimatedHours = function () {
        var hoursInDayFigure = EstimateUtils.getOneHourInDayFigures(this.hoursInDay);
        return Math.ceil((this.getEstimateTotal() - this.getEstimatedDays()) / hoursInDayFigure);
    },
    this.getCost = function () {
        return parseFloat(this.getEstimateTotal() * this.rate).toFixed(2);
    },
    this.getCostDisplay = function(){
        return EstimateUtils.outputMoney(this.getCost(),this.locale);
    },
    this.parseValues = function () {
        this.best = EstimateUtils.parseIncomingFigure(this.bestEditing, this.hoursInDay);
        this.worst = EstimateUtils.parseIncomingFigure(this.worstEditing, this.hoursInDay);
        this.standard = EstimateUtils.parseIncomingFigure(this.standardEditing, this.hoursInDay);

        this.standard = this.standard < this.best ? this.best : this.standard;
        this.worst = this.worst < this.standard ? this.standard : this.worst;

        this.bestEditing = !this.best ? null : this.getBestHuman();
        this.worstEditing = !this.worst ? null : this.getWorstHuman();
        this.standardEditing = !this.standard ? null : this.getStandardHuman();
    },
    this.isNewItem = function () {
        return this.new_item;
    },
    this.setNotNewItem = function () {
        this.new_item = false;
    }

    this.parseValues();
}