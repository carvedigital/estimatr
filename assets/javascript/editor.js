if (document.querySelector('#editor')) {
    var estimateEditor = new Vue({

        props: ['timeUnit','locale', 'rate', 'hoursinday', 'name', 'deleteUrl', 'estimatesUrl', 'updateUrl', 'getUrl'],
        el: '#editor',

        data: {
            items: [],
            sections: [],
            editingName: false,
            editingRate: false,
            currentWorkSaved: true
        },


        created: function () {
            this.getEstimate();
        },
        watch: {
            items: function () {
                this.currentWorkSaved = false;
            },
            rate: function () {
                this.currentWorkSaved = false;
            },
            name: function () {
                this.currentWorkSaved = false;
            },
        },
        computed: {
            rateUnitDisplay: function(){
                return this.timeUnit == 'daily' ? 'p/day' : 'p/hour';
            },
            rateDisplay: function () {
                var rate = this.timeUnit == 'daily' ? parseFloat((this.rate * this.hoursinday), 10)  : parseFloat(this.rate, 10);
                return EstimateUtils.outputMoney(rate, this.locale);
            },
            userRate: {
                get: function () {
                    return this.timeUnit == 'daily' ? parseFloat((this.rate * this.hoursinday), 10)  : parseFloat(this.rate, 10);
                },
                set: function (newValue) {
                    if (parseInt(newValue, 10) < 0) {
                        this.rate = 0;
                    } else {
                        this.rate = this.timeUnit == 'daily' ? parseInt(newValue, 10) / this.hoursinday : parseInt(newValue, 10);
                    }
                }
            },
            totalCost: function () {
                var totals = 0;
                for (var i = 0, tot = this.items.length; i < tot; i++) {
                    totals = totals + parseFloat(this.items[i].getCost());
                }
                for (var i = 0, tot = this.sections.length; i < tot; i++) {
                    for (var b = 0, seclen = this.sections[i].items.length; b < seclen; b++) {
                        totals = parseFloat(totals) + parseFloat(this.sections[i].items[b].getCost());
                    }
                }

                return EstimateUtils.outputMoney(totals, this.locale);
            },
            totalTime: function () {
                var totals = 0;
                for (var i = 0, tot = this.items.length; i < tot; i++) {
                    totals = parseFloat(totals) + parseFloat(this.items[i].getEstimateTotal());
                }
                for (var i = 0, tot = this.sections.length; i < tot; i++) {
                    for (var b = 0, seclen = this.sections[i].items.length; b < seclen; b++) {
                        totals = parseFloat(totals) + parseFloat(this.sections[i].items[b].getEstimateTotal());
                    }
                }

                return EstimateUtils.getHumanReadableFromHourFigure(parseFloat(totals), this.hoursinday).trim();
            }
        },

        methods: {
            getEstimate: function () {
                this.items = [];
                var self = this;
                this.$http.get(this.getUrl).then(function (data) {
                    var estimateData = data.data;
                    self.name = estimateData.name;
                    self.rate = estimateData.rate;

                    $.each(estimateData.items, function (key, item) {
                        var newItem = new EstimateItem({
                            id: item.id,
                            name: item.name,
                            best: item.best + 'h',
                            worst: item.worst + 'h',
                            standard: item.standard + 'h',
                            locale: self.locale
                        }, self.rate, self.hoursinday);
                        newItem.setEditing(false);
                        self.items.push(newItem);
                    });
                    this.currentWorkSaved = true;
                });
            },
            editName: function (editing) {
                this.editingName = editing;
                if (editing) {
                    Vue.nextTick(function () {
                        document.getElementById("nameEditor").focus();
                    });
                }
            },
            editRate: function (editing) {
                this.editingRate = editing;
                if (editing) {
                    Vue.nextTick(function () {
                        document.getElementById("rateEditor").focus();
                    });
                } else {
                    this.updateRateOnItems();
                }
            },
            deleteEstimate: function () {
                var self = this;
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete this estimate?',
                    callback: function (value) {
                        if (value) {
                            self.$http.delete(self.deleteUrl).then(function () {
                                window.location.href = self.estimatesUrl;
                            });
                        }
                    }
                });
            },
            removeItem: function (item, $event) {
                $event.preventDefault();
                this.items.$remove(item);
                this.currentWorkSaved = false;
            },
            addItem: function ($event) {
                $event.preventDefault();
                var newItem = new EstimateItem({
                    name: "",
                    best: undefined,
                    worst: undefined,
                    standard: undefined,
                    locale: this.locale
                }, this.rate, this.hoursinday);
                newItem.setEditing(true);
                this.items.push(newItem);
                Vue.nextTick(function () {
                    document.getElementById("item-listing-item-name-id-" + newItem.getId()).focus();
                });
            },
            updateRateOnItems: function () {
                var rate = this.rate;
                $.each(this.items, function (key, item) {
                    item.updateRate(rate);
                });
            },
            saveItem: function (item, $event) {
                $event.preventDefault();
                item.parseValues();
                item.setEditing(false);
                if (item.isNewItem()) {
                    this.addItem($event);
                }
                item.setNotNewItem();
                this.currentWorkSaved = false;
            },
            saveEstimate: function () {
                var postObject = {
                    name: this.name,
                    rate: this.rate,
                    items: this.items
                }
                this.$http.put(this.updateUrl, postObject);
                this.currentWorkSaved = true;
            }
        }
    });
}
