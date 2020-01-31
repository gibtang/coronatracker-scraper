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
    
    $insert = 0;
    $update = 0;
	foreach($articles as $article)
	{
		$existing_article = (Array) \DB::table($table)->where('url', $article['url'])->first();
		$data = array(
				'title' => $article['title'], 
				'description' => $article['description'], 
				'author' => $article['author'], 
				'url' => $article['url'], 
				'content' => $article['content'],
				'urlToImage' => $article['urlToImage'],
				'publishedAt' => gmdate('Y-m-d g:i:s', strtotime($article['publishedAt'])), 
				'addedOn' => gmdate('Y-m-d g:i:s'), 
				'siteName' => $article['source']['name'], 
				'language' => 'en', 
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
	$scrape_response['inserted_articles'] = $insert;
	$scrape_response['updated_articles'] = $update;
	$scrape_response['articles'] = $articles;
    //$current_rows = \DB::table('newsapi')->get()->toArray();
	return $scrape_response;
});
