<template>
	<form method="POST" :action="appUrl + '/admin/events'" accept-charset="UTF-8">
		<input name="_token" type="hidden" :value="token">
		<div class="form-group">
			<label for="name" class="font-weight-bold">Name:</label>
			<input class="form-control" name="name" type="text" id="name" v-model="name">
		</div>
		<div class="form-group">
			<label for="city_id" class="font-weight-bold">City:</label>
			<select class="form-control" id="city_id" name="city_id" v-model="selectedCity">
				<template v-if="!hasChosenCity">
					<option value="" selected="selected">Select a City</option>
				</template>
				<template v-for="city in cities">
					<option :value="city.id">{{city.name}}</option>
				</template>
			</select>
		</div>
		<div class="form-group">
			<label for="venue_id" class="font-weight-bold">Venue:</label>
			<select class="form-control" id="venue_id" name="venue_id" v-model="selectedVenue">
				<option value="" selected="selected">Select a Venue</option>
				<template v-for="venue in filteredVenues">
					<option :value="venue.id">{{venue.name}}</option>
				</template>
			</select>
		</div>
		<div class="form-group">
			<label for="date" class="font-weight-bold">Date:</label>
			<input class="form-control" name="date" type="text" v-model="date" id="date">
		</div>
		<div class="form-group">
			<label for="prices" class="font-weight-bold">Prices:</label>
			<input class="form-control" name="prices" type="text" id="prices">
		</div>
		<div class="form-group">
			<label for="description" class="font-weight-bold">Description:</label>
			<textarea class="form-control" rows="3" name="description" cols="50" id="description" v-model="description"></textarea>
		</div>
		<div class="form-group">
			<label for="reminder_description" class="font-weight-bold">Description for Reminder:</label>
			<textarea class="form-control" rows="3" name="reminder_description" cols="50" id="reminder_description" v-model="reminder_description"></textarea>
			<span :class="{'text-danger': maxCharsExceeded}" class="float-right pt-2" id="charsLeft">{{charactersLeft}}</span>
		</div>
		<div class="form-group">
			<input type="checkbox" value="1" name="illusion" id="illusion">
			<label for="illusion">Illusion Touring Entertainment?</label>
		</div>
		<div class="form-group">
			<a :href="appUrl + '/admin/events'" class="btn btn-link text-light">Cancel</a>
			<input class="btn btn-primary" type="submit" value="Create Event">
		</div>
	</form>
</template>

<script>
	import axios from 'axios';
	import _ from 'lodash';
	export default {
	    created(){
            this.token = document.querySelector("meta[name='csrf-token']").content;
            this.appUrl = document.querySelector("meta[name='appUrl']").content;
            this.date = document.querySelector("meta[name='date']").content;

            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
            };

            axios.get('/api/admin/cities').then(response => {
                this.cities = response.data;
            }, response => {
                swal('Oh no!', "An error occurred with the API", "error");
            });

            axios.get('/api/admin/venues').then(response => {
                this.venues = response.data;
            }, response => {
                swal('Oh no!', "An error occurred with the API", "error");
            });

            this.debouncedGetDescriptions = _.debounce(this.getDescriptions, 500);
	    },

		data(){
	        return {
	            token: '',
		        appUrl: '',
		        date: '',
		        cities: [],
		        venues: [],
		        selectedCity: '',
		        selectedVenue: '',
		        description: '',
		        name: '',
		        reminder_description: '',
		        descriptionEvent: {},
		        hasChosenCity: false,
	        }
		},

		computed: {

	        filteredVenues: function(){
	            if(this.selectedCity === ''){
	                return this.venues;
	            }else{
	                return this.venues.filter(venue => {
	                    return venue.city_id === this.selectedCity;
	                });
	            }
	        },

			charactersLeft: function(){
	            let length = this.reminder_description.length;
	            return length + ' / 160 ';
			},

			maxCharsExceeded: function(){
	            return this.reminder_description.length > 160;
			}
		},

		watch: {
			name: function(newName, oldName){
			    this.debouncedGetDescriptions();
			},

			selectedCity: function(newCity, oldCity){

				this.hasChosenCity = true;

				if(this.description !== ''){
                    let city = this.cities.find(city => {return city.id === this.selectedCity});

                    if(typeof this.descriptionEvent.city.name !== 'undefined'){
                        this.replaceInDescriptions(this.descriptionEvent.city.name, city.name);
                        this.descriptionEvent.city = {};
                    }else{
                        let previousCity = this.cities.find(city => {return city.id === oldCity});
                        this.replaceInDescriptions(previousCity.name, city.name);
                    }
				}
			},

            selectedVenue: function(newVenue, oldVenue){

                if(this.description !== ''){
                    let venue = this.venues.find(venue => {return venue.id === this.selectedVenue});

                    if(typeof this.descriptionEvent.venue.name !== 'undefined'){
                        this.replaceInDescriptions(this.descriptionEvent.venue.name, venue.name);
                        this.descriptionEvent.venue = {};
                    }else{
                        let previousVenue = this.venues.find(venue => {return venue.id === oldVenue});
                        this.replaceInDescriptions(previousVenue.name, venue.name);
                    }
                }
            }
		},

		methods: {
	        getDescriptions: function(){
                axios.get('/api/admin/events/name/' + this.name).then(response => {
                    let event = response.data;
                    this.description = event.description;
                    this.reminder_description = event.reminder_description;
					this.descriptionEvent = event;
                }, response => {
                    this.description = '';
                    this.reminder_description = '';
                });
	        },

			replaceInDescriptions: function(needle, replacement){
                this.description = this.description.replace(needle, replacement);
	            this.reminder_description = this.reminder_description.replace(needle, replacement);
			}
		}
	}
</script>

<style scoped>
	#charsLeft{
		font-size: 0.7rem;
	}
</style>