<template>
	<form :action="appUrl + '/admin/events/' + id" accept-charset="UTF-8" method="POST">
		<input name="_token" type="hidden" :value="token">
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="illusion" :value="illusion">
		<div class="form-group">
			<label for="name" class="font-weight-bold">Name:</label>
			<input class="form-control" name="name" type="text" id="name" v-model="name">
		</div>
		<div class="form-group">
			<label for="city_id" class="font-weight-bold">City:</label>
			<select class="form-control" id="city_id" name="city_id" v-model="selectedCity">
				<option value="" selected="selected">Select a City</option>
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
			<input class="form-control" name="prices" type="text" id="prices" v-model="prices">
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
			<input type="checkbox" name="illusionCheckBox" id="illusionCheckBox" v-model="illusionCheckBox">
			<label for="illusionCheckBox">Belongs to Illusion?</label>
		</div>
		<div class="form-group">
			<a :href="appUrl + '/admin/events'" class="btn btn-link text-light">Cancel</a>
			<input class="btn btn-primary" type="submit" value="Edit Event">
		</div>
	</form>
</template>

<script>
    import axios from 'axios';
    export default {
        created() {
            this.token = document.querySelector("meta[name='csrf-token']").content;
            this.appUrl = document.querySelector("meta[name='appUrl']").content;
            this.id = document.querySelector("meta[name='id']").content;

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

            axios.get('/api/admin/events/id/' + this.id).then(response => {
                let event = response.data;
                this.date = event.date;
                this.selectedCity = event.venue.city.id;
                this.selectedVenue = event.venue.id;
                this.prices = event.pricesAsString;
                this.illusionCheckBox = event.illusion;
                this.name = event.name;
                this.description = event.description;
                this.reminder_description = event.reminder_description;
            }, response => {
                swal('Oh no!', "An error occurred with the API", "error");
            });


        },

        data() {
            return {
                token: '',
                appUrl: '',
                date: '',
                cities: [],
                venues: [],
                selectedCity: '',
                selectedVenue: '',
                description: '',
	            illusionCheckBox: false,
	            id: '',
	            reminder_description: '',
	            prices: '',
	            name: '',
            }
        },

        watch: {

            selectedCity: function(newCity, oldCity){

                this.hasChosenCity = true;

                if(this.description !== '' && oldCity !== ''){
                    let city = this.cities.find(city => {return city.id === this.selectedCity});
	                let previousCity = this.cities.find(city => {return city.id === oldCity});
	                this.replaceInDescriptions(previousCity.name, city.name);
                }
            },

            selectedVenue: function(newVenue, oldVenue){

                if(this.description !== '' && oldVenue !== ''){
                    let venue = this.venues.find(venue => {return venue.id === this.selectedVenue});
	                let previousVenue = this.venues.find(venue => {return venue.id === oldVenue});
	                this.replaceInDescriptions(previousVenue.name, venue.name);
                }
            }
        },

        computed: {

            filteredVenues: function(){
                if(this.selectedCity === ''){
                    return this.venues;
                }else{
                    let cityId = this.selectedCity;
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
            },

	        illusion: function(){
              return this.illusionCheckBox ? 1 : 0;
	        }
        },

	    methods: {
            replaceInDescriptions: function(needle, replacement){
                this.description = this.description.replace(needle, replacement);
                this.reminder_description = this.reminder_description.replace(needle, replacement);
                console.log('I ran with: ' + needle + replacement);
            }
	    }
    }
</script>

<style scoped>
	#charsLeft{
		font-size: 0.7rem;
	}
</style>