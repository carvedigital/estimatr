@extends('layouts.standard')

@section('page')

    <section id="account" user-endpoint="{{ route('api-get-user') }}" class="standard-page">
        <header>
            <h1>Your Account</h1>
        </header>

        <hr />

        <div class="account-form-item">
            <dl>
                <dt>Your Name</dt>
                <dd>Tell us your full name that you want associated with your account.</dd>
            </dl>
            <input class="styled-input" name="name" v-model="user.name" type="text" />
        </div>
        <div class="account-form-item">
            <dl>
                <dt>Email Address</dt>
                <dd>The email address you want use to use as a primary point of contact.</dd>
            </dl>
            <input class="styled-input" name="email" v-model="user.email" type="email" />
        </div>
        <div class="account-form-item">
            <dl>
                <dt>Company Name</dt>
                <dd>Tell us your company name that you want associated with your account.</dd>
            </dl>
            <input class="styled-input" name="company" v-model="user.company" type="text" />
        </div>
        <hr class="smaller" />
        <div class="account-form-item">
            <dl>
                <dt>Currency Format</dt>
                <dd>Choose a locale for your currency formatting. Changing this will not convert any figures, but simply change the symbol and monetary formatting.</dd>
            </dl>
            <div class="tablet-selector">
                <a v-bind:class="{ 'active':isGbp }" v-on:click.prevent="setLocale('GBP')" href="#">&pound;</a>
                <a v-bind:class="{ 'active':isUsd }" v-on:click.prevent="setLocale('USD')" href="#">&dollar;</a>
                <a v-bind:class="{ 'active':isEur }" v-on:click.prevent="setLocale('EUR')" href="#">&euro;</a>
            </div>
        </div>
        <div class="account-form-item">
            <dl>
                <dt>Preferred Time Unit</dt>
                <dd>Choose a time unit that you will set your rates in, hourly or daily.</dd>
            </dl>
            <div class="tablet-selector small">
                <a data-value="hourly" v-bind:class="{ 'active':isHourly }" v-on:click.prevent="setTimeUnit('hourly')" href="#">Hourly</a>
                <a data-value="daily" v-bind:class="{ 'active':isDaily }" v-on:click.prevent="setTimeUnit('daily')" href="#">Daily</a>
            </div>
        </div>
        <div class="account-form-item">
            <dl>
                <dt>Hours In Working Day</dt>
                <dd>How many hours are in a typical working day for you or your team. This allows us to more accurately produce costs for estimates that are based on hourly rates.</dd>
            </dl>
            <input class="styled-input" min="1" max="24" v-model="user.hours_in_day" type="number" />
        </div>
        <div class="account-form-item">
            <dl>
                <dt>Default Rate</dt>
                <dd>Enter your default working rate to be auto-filled for each new estimate.</dd>
            </dl>
            <input class="styled-input" v-model="user.default_rate" type="number" />
        </div>

        <div class="account-form-submit">
            <a href="#" v-bind:class="{ 'inactive':!requiresSaving }" v-on:click.prevent="updateAccount()" >Update Account</a>
        </div>

    </section>

@stop