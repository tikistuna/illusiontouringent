<?php

	namespace App\Contracts\UrlShortener;


	use Illuminate\Http\Request;

	Interface UrlShortener{
		public function oauth(Request $request);

		public function getClicks($url);

		public function shortenUrl($url, $useOauth = false);

		public function shortenUrlWithOauth($url);

		public function isAccessTokenExpired();
	}