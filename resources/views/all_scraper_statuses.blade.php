@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
<!--                     <form method="get" action="{{ action('HomeController@search_article') }}">
                        <input type="text" name="search">
                        <button type="submit" class="btn btn-primary">
                            Search by Title
                        </button>
                    </form> -->
                    <div><a href="/home">Back</a></div>
                </div>

                <div class="card-body">
                    <table>
                        <tr>
                           <th width="5%">Id</th>
                           <th>Source URL</th>
                           <th width="12%">Date Added</th>
                           <th width="12%">Articles Added</th>
                           <th width="12%">Articles Inserted</th>
                           <th width="8%">Status Code</th>
                         </tr>
                    <?php $idx = 1; ?>
                    @foreach($all_scraper_statuses as $scraper_status)
                        <tr>
                            <td>{{ $idx }}</td>
                            <td>{{ $scraper_status->source_url }}</td>
                            <td>{{ $scraper_status->created_at }}</td>
                            <td>{{ $scraper_status->number_of_articles_crawled }}</td>
                            <td>{{ $scraper_status->number_of_articles_inserted }}</td>
                            <td>{{ $scraper_status->status_code }}</td>
                        </tr>
                        <?php $idx++; ?>
                    @endforeach
                    </table>
                    {{ $all_scraper_statuses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
