<?php

namespace App\Services;

use App\Contracts\UrlShortener\UrlShortener;
use App\Models\User;
use App\Notifications\GoogleShortenerFailure;
use App\Services\Url\Exceptions\UrlGetClicksException;
use App\Services\Url\Exceptions\UrlShorteningException;
use Google_Client;
use Google_Service_Urlshortener;
use Google_Service_Urlshortener_Url;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleUrlShortenerService implements UrlShortener{

	private $client;
	private $urlShortenerService;
	private $urlShortenerServiceUrl;
	private $guzzle;
	private $shortenRequestUrl;
	private $getClicksUrl;

    /**
     * GoogleUrlShortenerService constructor.
     * @param Google_Client $client
     * @param Client $guzzle
     * @throws \Google_Exception
     */
	public function __construct(Google_Client $client, Client $guzzle){
		$client->setAuthConfig(storage_path('app/private/client_secret.json'));
		$client->addScope('https://www.googleapis.com/auth/urlshortener');
		$client->setAccessType('offline');
		$client->setApprovalPrompt('force');
		$client->setRedirectUri(env('APP_URL') . '/admin/oauth');
		$this->client = $client;
		$this->getClicksUrl = 'https://www.googleapis.com/urlshortener/v1/url?key=' . env('GOOGLE_URL_SHORTENER_API_KEY') . '&';
		if(Cache::has('upload_token')){
			$this->client->setAccessToken(Cache::get('upload_token'));
		}
		$this->urlShortenerService = new Google_Service_Urlshortener($this->client);
		$this->urlShortenerServiceUrl = new Google_Service_Urlshortener_Url();
		$this->guzzle = $guzzle;
		$this->shortenRequestUrl = 'https://www.googleapis.com/urlshortener/v1/url?key=' . env('GOOGLE_URL_SHORTENER_API_KEY');
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function oauth(Request $request){
		if ($request->query('code') !== null) {
			$token = $this->client->fetchAccessTokenWithAuthCode($request->query('code'));
			$this->client->setAccessToken($token);
			Cache::forever('upload_token', $token);
			return redirect(session('url', 'admin/events'));
		}

		if (!$this->client->isAccessTokenExpired()) {
			$this->client->setAccessToken(Cache::get('upload_token'));
			return redirect(session('url', 'admin/events'));
		} else {
			return redirect()->away($this->client->createAuthUrl());
		}
	}

    /**
     * @param $url
     * @return int
     * @throws UrlGetClicksException
     */
	public function getClicks($url){
        try {
            $request = $this->guzzle->request('GET', $this->getClicksUrl . http_build_query(array('shortUrl' => $url, 'projection' => 'FULL')));
        } catch (GuzzleException $e) {
            throw new UrlGetClicksException('Could not get clicks from google');
        }
        $body = json_decode($request->getBody());
		return $body->analytics->allTime->shortUrlClicks;
	}

	/**
	 * @param $url
	 * @param bool $useOauth
	 * @return mixed
     * @throws UrlShorteningException
	 */
	public function shortenUrl($url, $useOauth = false){
		if($useOauth){
			return $this->shortenUrlWithOauth($url);
		}
		try{
			$response = $this->guzzle->request('POST', $this->shortenRequestUrl, ['json'=>['longUrl' => $url]]);
		}catch(GuzzleException $e){
            throw new UrlShorteningException('Url Could not be shortened without oauth');
        }

		return json_decode($response->getBody())->id;
	}

	/**
	 * @param $url
	 */
	public function shortenUrlWithOauth($url){
		$this->urlShortenerServiceUrl->setLongUrl($url);
		$url = $this->urlShortenerService->url->insert($this->urlShortenerServiceUrl);
		return $url->id;
	}

	public function isAccessTokenExpired(){
		return $this->client->isAccessTokenExpired();
	}


}