@extends('layouts.standard')

@section('page')

    <section time-unit="{{ $user->rate_time_unit }}" locale="{{ $user->locale }}" name="{{ $estimate->name }}" get-url="{{ route('api-get-estimate',[$estimate->id]) }}" update-url="{{ route('api-update-estimate',[$estimate->id]) }}" delete-url="{{ route('api-delete-estimate',[$estimate->id]) }}" estimates-url="{{ route('estimates') }}" class="standard-page estimate-editor" id="editor" user-rate="{{ $estimate->rate }}" hoursinday="{{ $user->hours_in_day }}" >

        <header>
            <h1>
                <a v-if="!editingName" href="#" v-on:click.prevent="editName(true)">@{{ name }}</a>
                <input id="nameEditor" autocomplete="off" class="styled-input" @keyup.enter="editName(false)" v-on:blur="editName(false)" type="text" v-if="editingName" placeholder="Estimate Name..." v-model="name" />
                <small>
                    <a v-if="!editingRate" href="#" v-on:click.prevent="editRate(true)">@{{ rateDisplay }} @{{ rateUnitDisplay }}</a>
                    <input id="rateEditor" autocomplete="off" class="styled-input" @keyup.enter="editRate(false)" v-on:blur="editRate(false)" type="text" v-if="editingRate" placeholder="Estimate Rate..." v-model="userRate" />
                </small>
            </h1>
            <h2><span v-if="items.length">@{{ totalCost }} / @{{ totalTime }} </span><a href="#" v-on:click.prevent="deleteEstimate()"><i class="fa fa-trash"></i></a></h2>
        </header>

        <hr />

        <section class="editor">

            <div class="items" v-if="items.length">
                <div class="item-listing item-listing-header">
                    <div class="item-listing-name">Name</div>
                    <div class="item-listing-estimate-number item-listing-best">Best Case</div>
                    <div class="item-listing-estimate-number item-listing-standard">Normal</div>
                    <div class="item-listing-estimate-number item-listing-worst">Worst Case</div>
                    <div class="item-listing-total">Total</div>
                </div>
                <div class="item-listing" v-for="item in items">
                    <div v-if="!item.editing" class="item-listing-display">
                        <div class="item-listing-name"><a href="#" v-on:click.prevent="item.setEditing(true)">@{{ item.getName() }}</a></div>
                        <div class="item-listing-estimate-number item-listing-best"><a href="#" v-on:click.prevent="item.setEditing(true)">@{{ item.getBestHuman() }}</a></div>
                        <div class="item-listing-estimate-number item-listing-standard"><a href="#" v-on:click.prevent="item.setEditing(true)">@{{ item.getStandardHuman() }}</a></div>
                        <div class="item-listing-estimate-number item-listing-worst"><a href="#" v-on:click.prevent="item.setEditing(true)">@{{ item.getWorstHuman() }}</a></div>
                        <div class="item-listing-total">@{{ item.getHumanReadableEstimate() }}<br />@{{ item.getCostDisplay(rate,hoursinday) }}</div>
                    </div>
                    <div v-if="item.editing" class="item-listing-editing">
                        <div class="item-listing-name"><input id="item-listing-item-name-id-@{{ item.id }}" @keyup.enter="saveItem(item,$event)" type="text" v-model="item.name" placeholder="Item Name..." /></div>
                        <div class="item-listing-estimate-number item-listing-best"><input @keyup.enter="saveItem(item,$event)" type="text" v-model="item.bestEditing" placeholder="Best Case (eg. 1d, 1d 3h)..." /></div>
                        <div class="item-listing-estimate-number item-listing-standard"><input @keyup.enter="saveItem(item,$event)" type="text" v-model="item.standardEditing" placeholder="Normal (eg. 2d, 2d 6h)..." /></div>
                        <div class="item-listing-estimate-number item-listing-worst"><input @keyup.enter="saveItem(item,$event)" type="text" v-model="item.worstEditing" placeholder="Worst Case (eg. 5d, 6d 5h)..." /></div>
                        <div class="item-listing-actions">
                            <a href="#" v-on:click.prevent="saveItem(item,$event)"><i class="fa fa-check"></i></a>
                            <a href="#" v-on:click.prevent="removeItem(item,$event)"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="section-creator">
                <a href="#" v-on:click.prevent="addItem($event)"><i class="fa fa-plus"></i> New Item</a>
            </section>

            <a href="#" v-bind:class="{ inactive:currentWorkSaved }" v-on:click.prevent="saveEstimate()" class="save-estimate-button">Save Estimate</a>

        </section>

    </section>

@stop

