<template>
    <div class="card mb-3" id="events-table">
        <div class="card-header">
            <i class="fa fa-table"></i> Cities
	        <a class="float-right text-dark" href="/admin/cities/create">New City</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row p-2 pb-3">
                    <div class="col-4">
                        <span class="pl-3 align-text-button pr-1">Show</span>
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown">
                            {{showing}}
                        </button>
                        <span class="pl-1 align-text-button">cities</span>
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
                    <div class="col-3 ml-auto">Showing {{firstRecord}} to {{lastRecord}} of {{cities.length}} cities</div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Id
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': idDesc}" @click="sortCities('-id')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': idAsc}" @click="sortCities('id')"></i>
                        </th>
                        <th>
                            City
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': nameDesc}" @click="sortCities('-name')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': nameAsc}" @click="sortCities('name')"></i>
                        </th>
                        <th>
                            # of Upcoming Events
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': eventsLeftDesc}" @click="sortCities('-eventsLeft')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': eventsLeftAsc}" @click="sortCities('eventsLeft')"></i>
                        </th>
                        <th>
                            # of Past Events
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': eventsPastDesc}" @click="sortCities('-eventsPast')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': eventsPastAsc}" @click="sortCities('eventsPast')"></i>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="city in citiesShowing">
                        <tr>
                            <td><a class="text-dark" :href="'/admin/cities/' + city.id + '/edit'">{{city.id}}</a></td>
                            <td>{{city.name}}</td>
                            <td>{{city.eventsLeft}}</td>
                            <td>{{city.eventsPast}}</td>
                            <td>
                                <button class="btn btn-link" @click="deleteCity(city)">Delete</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>City</th>
                        <th># of Upcoming Events</th>
                        <th># of Past Events</th>
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
        <div class="card-footer small text-muted">Last city created {{lastCreated}}.</div>
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

            axios.get('/api/admin/cities').then(response => {
                this.cities = response.data;
            }, response => {
                // Use sweetalert!
                swal('Oh no!', "An error occurred with the API", "error");
            });

            axios.get('/api/admin/cities/lastCreated')
                .then(response => {
                    this.lastCreated = response.data.lastCreated;
                })
                .catch(response => {
                    swal('Oh no!', "An error occurred with the API", "error");
                });
        },

        data(){
            return{
                cities: [],
                sort: 'name-asc',
                showing: 10,
                page: 1,
                token: ''
            }
        },

        computed: {
            idAsc: function(){
                return (this.sort === 'id-asc');
            },

            idDesc: function(){
                return (this.sort === 'id-desc');
            },

            nameAsc: function(){
                return (this.sort === 'name-asc');
            },

            nameDesc: function(){
                return (this.sort === 'name-desc');
            },

            eventsLeftAsc: function(){
                return (this.sort === 'eventsLeft-asc');
            },

            eventsLeftDesc: function(){
                return (this.sort === 'eventsLeft-desc');
            },

            eventsPastAsc: function(){
                return (this.sort === 'eventsPast-asc');
            },

            eventsPastDesc: function(){
                return (this.sort === 'eventsPast-desc');
            },

            citiesShowing: function(){
                let beginning = this.showing * (this.page - 1);
                if(beginning > this.cities.length){
                    this.pageOutOfRange();
                    return this.cities.slice(this.showing* (this.lastPage - 1), this.showing* (this.lastPage - 1) + this.showing);
                }
                return this.cities.slice(beginning, beginning + this.showing);
            },
            lastPage: function(){
                return Math.ceil(this.cities.length / this.showing);
            },
	        lastRecord: function(){
				return Math.min(this.firstRecord + this.showing - 1, this.cities.length);
	        }
        },

        methods: {
            sortCities: function(property){
                this.cities.sort(this.dynamicSort(property));
            },
            pageOutOfRange: function(){
                this.page = this.lastPage;
            },

            deleteCity: function(city){
                let self = this;
                let index = this.cities.indexOf(city);
                if(index === -1){
                    swal("Error", "Couldn't find index of this city", "error");
                }else{
                    swal({
                        title: "Warning",
                        text: "Delete " + city.name + "?",
                        dangerMode: true,
                        buttons: true,
                        icon: 'warning'
                    }).then((willDelete) => {
                        if(willDelete){
                            axios.post('/admin/cities/' + city.id, {
                                _method: 'DELETE',
                                _token: this.token
                            }).then(function(response){
                                swal("Success", "City was deleted", "success");
                                self.cities.splice(index, 1);
                            }).catch(function(response){
                                swal("Oh no!", "Looks like something went wrong.", "error");
                            });
                        }
                    });
                }
            }
        }

    }
</script>
