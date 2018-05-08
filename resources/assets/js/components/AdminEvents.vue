<template>
    <div class="card mb-3" id="events-table">
        <div class="card-header">
            <i class="fa fa-table"></i> Events
            <a class="float-right text-dark" href="/admin/events/create">New Event</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row p-2 pb-3" id="table-header-info">
                    <div class="col-4">
	                    <span class="pl-3 align-text-button pr-1">Show</span>
	                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown">
		                    {{showing}}
	                    </button>
	                    <span class="pl-1 align-text-button">events</span>
	                    <div class="dropdown-menu">
		                    <button class="dropdown-item" @click="showing = 5">5</button>
		                    <button class="dropdown-item" @click="showing = 10">10</button>
		                    <button class="dropdown-item" @click="showing = 15">15</button>
		                    <button class="dropdown-item" @click="showing = 20">20</button>
		                    <button class="dropdown-item" @click="showing = 25">25</button>
	                    </div>
                    </div>
	                <div class="input-group col-4" >
		                <input id="search-query" type="text" class="form-control" placeholder="Search for an event...">
		                <span class="input-group-btn">
                            <button id="search-submit" class="btn btn-dark" type="button">Search</button>
                        </span>
	                </div>
	                <div class="col-3 ml-auto">Showing {{firstRecord}} to {{lastRecord}} of {{events.length}} events</div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Event Name
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': eventDesc}" @click="sortEvents('-name')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': eventAsc}" @click="sortEvents('name')"></i>
                        </th>
                        <th>
                            City
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': cityDesc}" @click="sortEvents('-cityName')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': cityAsc}" @click="sortEvents('cityName')"></i>
                        </th>
                        <th>
                            Venue
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': venueDesc}" @click="sortEvents('-venueName')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': venueAsc}" @click="sortEvents('venueName')"></i>
                        </th>
                        <th>
                            Date
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': dateDesc}" @click="sortEvents('-date')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': dateAsc}" @click="sortEvents('date')"></i>
                        </th>
                        <th>
	                        Url Clicks
	                        <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': urlClicksDesc}" @click="sortEvents('-urlClicks')"></i>
	                        <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': urlClicksAsc}" @click="sortEvents('urlClicks')"></i>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <template v-for="event in eventsShowing">
                            <tr>
	                            <td><a class="text-dark" :href="'/admin/events/' + event.id + '/edit'">{{event.name}}</a></td>
	                            <td><a class="text-dark" :href="'/admin/cities/' + event.city.id + '/edit'">{{event.cityName}}</a></td>
	                            <td>{{event.venueName}}</td>
	                            <td>{{event.dateFormatted}}</td>
	                            <td>{{event.urlClicks}}</td>
	                            <td><a :href="'/admin/eventTicketSeller/' + event.id">Add TicketSeller</a></td>
	                            <td>
                                    <button class="btn btn-link" @click="deleteEvent(event)">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Event Name</th>
                        <th>City</th>
                        <th>Venue</th>
                        <th>Date</th>
                        <th>Url Clicks</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0 pt-1">
                        <li class="page-item" @click="previousPage" :class="{'disabled': isFirstPage}">
	                        <button class="page-link">Previous</button>
                        </li>
                        <li class="page-item" :class="{'active': leftActive}" @click="page = leftPage">
	                        <button class="page-link">{{leftPage}}</button>
                        </li>
	                    <template v-if="lastPage > 1">
		                    <li class="page-item" :class="{'active': middleActive}" @click="page = middlePage">
			                    <button class="page-link">{{middlePage}}</button>
		                    </li>
	                    </template>
                        <template v-if="lastPage > 2">
	                        <li class="page-item" :class="{'active': rightActive}" @click="page = rightPage">
		                        <button class="page-link">{{rightPage}}</button>
	                        </li>
                        </template>
                        <li class="page-item" @click="nextPage" :class="{'disabled': isLastPage}">
	                        <button class="page-link">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="card-footer small text-muted">Last event created {{lastCreated}}.</div>
    </div>
</template>

<script>
    import axios from 'axios';
    import pagination from '../mixins/pagination';
    import dynamicSorting from '../mixins/dynamicSorting';
    import swal from 'sweetalert';
    export default{

        mixins: [pagination, dynamicSorting],

        created(){

            this.token = document.querySelector("meta[name='csrf-token']").content;
            axios.defaults.headers.common = {
              'X-Requested-With': 'XMLHttpRequest',
            };

            axios.get('/api/admin/events').then(response => {
                this.events = response.data;
            }, response => {
                swal('Oh no!', "An error occurred with the API", "error");
            });

            axios.get('/api/admin/events/lastCreated')
                .then(response => {
                    this.lastCreated = response.data.lastCreated;
                })
                .catch(response => {
                    swal('Oh no!', "An error occurred with the API", "error");
                });

        },

        data(){
            return{
                events: [],
                sort: 'name-asc',
	            showing: 10,
	            page: 1,
                token: '',
	            lastCreated: ''
            }
        },

        computed: {
            eventAsc: function(){
                return (this.sort === 'name-asc');
            },

            eventDesc: function(){
                return (this.sort === 'name-desc');
            },

            cityAsc: function(){
                return (this.sort === 'cityName-asc');
            },

            cityDesc: function(){
                return (this.sort === 'cityName-desc');
            },

            dateAsc: function(){
                return (this.sort === 'date-asc');
            },

            dateDesc: function(){
                return (this.sort === 'date-desc');
            },

            venueAsc: function(){
                return (this.sort === 'venueName-asc');
            },

            venueDesc: function(){
                return (this.sort === 'venueName-desc');
            },

            urlClicksAsc: function(){
                return (this.sort === 'urlClicks-asc');
            },

            urlClicksDesc: function(){
                return (this.sort === 'urlClicks-desc');
            },

	        eventsShowing: function(){
                let beginning = this.showing * (this.page - 1);
                if(beginning > this.events.length){
                    this.pageOutOfRange();
                    return this.events.slice(this.showing* (this.lastPage - 1), this.showing* (this.lastPage - 1) + this.showing);
                }
                return this.events.slice(beginning, beginning + this.showing);
	        },
            lastPage: function(){
                return Math.ceil(this.events.length / this.showing);
            },
            lastRecord: function(){
                return Math.min(this.firstRecord + this.showing - 1, this.events.length);
            }
        },

        methods: {
            sortEvents: function(property){
                this.events.sort(this.dynamicSort(property));
            },

	        pageOutOfRange: function(){
                this.page = this.lastPage;
	        },
            deleteEvent: function(lj_event){
                let self = this;
                let index = this.events.indexOf(lj_event);
                if(index === -1){
                    swal("Error", "Couldn't find index of this Event", "error");
                }else{
                    swal({
                        title: "Warning",
                        text: "Delete " + lj_event.name + "?",
                        dangerMode: true,
                        buttons: true,
                        icon: 'warning'
                    }).then((willDelete) => {
                        if(willDelete){
                            axios.post('/admin/events/' + lj_event.id, {
                                _method: 'DELETE',
                                _token: this.token
                            }).then((response) => {
                                swal("Success", "Venue was deleted", "success");
                                self.events.splice(index, 1);
                            }).catch((response) => {
                                swal("Oh no!", "Looks like something went wrong.", "error");
                            });
                        }
                    });
                }
            }
        }

    }
</script>

<style scoped>
    #table-header-info{
        font-size: 0.9rem;
    }
    td{
        font-size: 0.85rem;
    }
</style>
