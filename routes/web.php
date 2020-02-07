<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('scrape_sg_news', function () {
    $url = "curl \"https://newsapi.org/v2/top-headlines?q=corona&country=sg&apiKey=2188be1bbdde4d16b2a536861d012433\"";

	$scraper_status = new \App\ScraperStatus;
	$scraper_status->source_url = $url;
	$scraper_status->description = "Get from API all news for country Singapore and with keyword 'corona'. http://54.251.169.120/scrape_sg_news will call this API https://newsapi.org/v2/top-headlines?q=corona&country=sg&apiKey=2188be1bbdde4d16b2a536861d012433 to scrape the news";
	$insert = 0;
	$update = 0;
	$articles = array();
	try
	{
	    $response = shell_exec($url);
	    $array = json_decode($response, true);

	    if ($array['status'] == 'ok')
	    {
	    	\Log::info($url . ' scraping ok');
	    }
	    else
	    {
	    	\Log::info($url . ' scraping has errors');
	    }
	    $articles = $array['articles'];
	    $table = 'newsapi_n';
	    
		foreach($articles as $article)
		{
			$existing_article = (Array) \DB::table($table)->where('title', $article['title'])->first();
			$data = array(
					'title' => $article['title'], 
					'description' => $article['description'], 
					'author' => $article['author'], 
					'url' => $article['url'], 
					'content' => $article['content'],
					'urlToImage' => $article['urlToImage'],
					'publishedAt' => gmdate('Y-m-d H:i:s', strtotime($article['publishedAt'])), 
					'addedOn' => gmdate('Y-m-d H:i:s'), 
					'siteName' => $article['source']['name'], 
					'language' => 'en', 
					'countryCode' => 'sg', 
				);
			if (count($existing_article) == 0)
			{
				\DB::table($table)->insert(
				    $data
				);
				$insert++;
				\Log::info($data);
				\Log::info('stored into DB');
			}
			else
			{
				\DB::table($table)->where('nid', $existing_article['nid'])->update(
				    $data
				);
				$update++;
				\Log::info($article['url'] . ' already exists in database');
			}
		}
		$scraper_status->status_code = 'ok';
		$scrape_response['inserted_articles'] = $insert;
		$scrape_response['updated_articles'] = $update;
		$scrape_response['articles'] = $articles;
	}
	catch(Exception $e) {
	  	$scraper_status->status_code = $e->getMessage();
	}

	$scraper_status->number_of_articles_crawled = count($articles);
	$scraper_status->number_of_articles_inserted = $insert;
	$scraper_status->save();
    //$current_rows = \DB::table('newsapi')->get()->toArray();
	return $scrape_response;
});

Route::get('scrape_wuflu', function () {
	$url = "curl https://wuflu.live/processeddata.json?nocache=".time();
	$response = shell_exec($url);
	$array = json_decode($response, true);
	//$num_regions = count($array['regions']);

	$wuflu_countries = array();
	$datetime_string = date('d-M-Y g:i:s a');

	$country_names = array();
	foreach($array['regions'] as $region) 
	{ 
		$country_name = $region[0]['en_name'];
		$confirmed = $region[1];
		$deaths = $region[2];
		$recovered = $region[3];

		array_push($country_names, $country_name);
		$country = array('country_name' => $country_name, 'confirmed' => $confirmed, 'deaths' => $deaths, 'recovered' => $recovered, 'datetime_string' => $datetime_string, 'created_at' => gmdate('Y-m-d H:i:s'), 'updated_at' => gmdate('Y-m-d H:i:s'));

		$data = new \App\DatasourceWuFlu;
		$data->country_name = $country_name;
		$data->confirmed = $confirmed;
		$data->deaths = $deaths;
		$data->recovered = $recovered;
		$data->datetime_string = $datetime_string;
		$data->save();
		$wuflu_countries[$country_name] = $country;
		//array_push($countries, $country);
	}
	////////////////////GET DATA FOR ARCGIS TO COMPARE////////////////////////////////////
	$arcgis_posted_date = \App\DatasourceArcgis::groupBy('posted_date')->select('posted_date')->orderBy('posted_date', 'desc')->first()->posted_date;
	$arcgis_countries = \App\DatasourceArcgis::select(\DB::raw('sum(confirmed) as confirmed'), \DB::raw('sum(recovered) as recovered'), \DB::raw('sum(deaths) as deaths'), 'country', 'posted_date')->where('posted_date', $arcgis_posted_date)->whereIn('country', $country_names)->groupBy('country')->groupBy('posted_date')->get();

	$aggregated_countries = array();
	$posted_at = date('y-m-d H:i:s');
	foreach($arcgis_countries as $arcgis_country)
	{
		$country_name = $arcgis_country['country'];
		$wuflu_country = $wuflu_countries[$country_name];
		if ($wuflu_country['deaths'] > $arcgis_country['deaths'])
		{
			$aggregated_deaths = $wuflu_country['deaths'];
			$source_deaths = 'wuflu';
		}
		else
		{
			$aggregated_deaths = $arcgis_country['deaths'];
			$source_deaths = 'arcgis';
		}
		/////////////////////////////////////////////
		if ($wuflu_country['confirmed'] > $arcgis_country['confirmed'])
		{
			$aggregated_confirmed = $wuflu_country['confirmed'];
			$source_confirmed = 'wuflu';
		}
		else
		{
			$aggregated_confirmed = $arcgis_country['confirmed'];
			$source_confirmed = 'arcgis';
		}
		//////////////////////////////////////
		if ($wuflu_country['recovered'] > $arcgis_country['recovered'])
		{
			$aggregated_recovered = $wuflu_country['recovered'];
			$source_recovered = 'wuflu';
		}
		else
		{
			$aggregated_recovered = $arcgis_country['recovered'];
			$source_recovered = 'arcgis';
		}

		$aggregated_countries[$country_name] = array('aggregated_deaths' => $aggregated_deaths, 'source_deaths' => $source_deaths, 'aggregated_recovered' => $aggregated_recovered, 'source_recovered' => $source_recovered, 'aggregated_confirmed' => $aggregated_confirmed, 'source_confirmed' => $source_confirmed, 'posted_at' => $posted_at);

		$data_aggregated = new \App\DataAggregated;
		$data_aggregated->deaths = $aggregated_deaths;
		$data_aggregated->source_deaths = $source_deaths;
		$data_aggregated->recovered = $aggregated_recovered;
		$data_aggregated->source_recovered = $source_recovered;
		$data_aggregated->confirmed = $aggregated_confirmed;
		$data_aggregated->source_confirmed = $source_confirmed;
		$data_aggregated->posted_at = $posted_at;
		$data_aggregated->save();
	}
	return $aggregated_countries;
});

// route to show the login form
Route::get('login', array('uses' => 'HomeController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'HomeController@doLogin'));

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/get_articles', 'HomeController@get_articles');//->name('home');
Route::get('/edit_article/{id}', 'HomeController@edit_article');
Route::put('/update_article/{id}', 'HomeController@update_article')->name('articles.update');
Route::get('/search_article', array('uses' => 'HomeController@search_article'));

Route::get('/get_scraper_statuses', 'HomeController@get_scraper_statuses');
