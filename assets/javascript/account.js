if(document.querySelector('#account')) {
    var accountScreen = new Vue({
        props: ['userEndpoint'],
        el: '#account',
        data: {
            user:{},
            requiresSaving:false,
        },
        created: function () {
            this.retrieveUser();
        },
        watch: {
            user: {
                handler: function () {
                    this.requiresSaving = true;
                },
                deep: true
            }
        },
        computed:{
            isEur: function(){
                return (this.user.locale == 'EUR');
            },
            isUsd: function(){
                return (this.user.locale == 'USD');
            },
            isGbp: function(){
                return (this.user.locale == 'GBP');
            },
            isHourly: function(){
                return (this.user.rate_time_unit == 'hourly');
            },
            isDaily: function(){
                return (this.user.rate_time_unit == 'daily');
            }
        },
        methods: {
            setTimeUnit: function(val)
            {
                this.user.rate_time_unit = val;
            },
            setLocale: function(val)
            {
                this.user.locale = val;
            },
            retrieveUser: function(){
                var self = this;
                this.$http.get(this.userEndpoint).then(function(data){
                    self.user = data.data;
                    window.setTimeout(function(){ self.requiresSaving = false; }, 100);
                });
            },
            updateAccount: function(){
                var self = this;
                this.$http.put(this.userEndpoint,this.user).then(function(data){
                    self.retrieveUser();
                },function(data){
                    var messageString = "";
                    _.each(data.data.errors,function(field){
                        _.each(field,function(error) {
                            messageString = messageString + error + "<br />";
                        });
                    });
                    vex.dialog.alert(messageString);
                });
            }
        }
    });
}