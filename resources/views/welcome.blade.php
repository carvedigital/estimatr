@extends('layouts.standard')

@section('page')
    <section class="messaging">
        <h1>Create Accurate Software Estimates</h1>
        <h2>A rapid, pain-free, accurate software estimation tool. Best of all, it's totally <strong>free</strong>.</h2>
        @if(!$user)
            <a href="{{ route('login') }}"><i class="fa fa-github"></i> Get Started With Github</a>
        @endif
    </section>

    <section class="explanation">
        <div>
            <div>
                <div class="embed-container">
                    <iframe src='https://player.vimeo.com/video/161171765' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="free-explanation">
        <div>
            <h5>It's Free (As In Beer)</h5>

            <p>Hi, I'm <a href="https://twitter.com/theskaterdev">David Thorpe</a>. I created Estimatr because I was fed up with using messy spreadsheets and equations to calculate accurate software estimates. Do you know what else really grinds my gears? The ever-growing costs of utilising SaaS products. This is why I've decided to release Estimatr as a free product.</p>
            <p>Over time, Estimatr will offer paid plans for business-like features, but rest assured; what you see currently will always be free. No-one should have to suffer the fallout of a bad estimate.</p>

        </div>
    </section>
@stop