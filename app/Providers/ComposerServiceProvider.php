<?php


	namespace App\Providers;


	use App\Models\City;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;

	class ComposerServiceProvider extends ServiceProvider
	{
		public function boot(){

			View::composer(['public.navigation'], function($view){

				$cities = City::whereHas('events', function ($query){
				$query->upcoming();
				})->orderBy('name')->pluck('name');
				if($cities->count() <= 9){
					$view->with('cities_prox', $cities);
				}
			});
		}
	}