@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    <table>
                        <tr>
                           <th width="10%">Id</th>
                           <th width="80%">Title</th>
                           <th>Status</th>
                         </tr>

                    @foreach($all_articles as $article)
                        <tr>
                            <td>{{ $article->nid }}</td>
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
                            <td><?php echo $status ?></td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
