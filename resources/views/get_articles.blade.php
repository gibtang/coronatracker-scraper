@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form method="get" action="{{ action('HomeController@search_article') }}">
                        <input type="text" name="search">
                        <button type="submit" class="btn btn-primary">
                            Search by Title
                        </button>
                    </form>
                    <a href="/get_articles">View All Articles</a>
                    <div><a href="/home">Back</a></div>
                </div>

                <div class="card-body">
                    <table>
                        <tr>
                           <th width="5%">Id</th>
                           <th>Title</th>
                           <th width="12%">Date Added</th>
                           <th width="8%">Status</th>
                         </tr>
                    <?php $idx = 1; ?>
                    @foreach($all_articles as $article)
                        <tr>
                            <td>{{ $idx }}</td>
                            <td><a href ="/edit_article/{{$article->nid}}">{{ $article->title }}</a></td>
                            <?php
                                if ($article->status == 1)
                                {
                                    $status = 'Enabled';
                                }
                                else
                                {
                                    $status = '<div style="color:red;">Disabled</div>';
                                }
                            ?>
                            <td>{{ date('d-M-Y H:i:s', strtotime($article->addedOn)) }}</td>
                            <td><?php echo $status ?></td>
                        </tr>
                        <?php $idx++; ?>
                    @endforeach
                    </table>
                    {{ $all_articles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
