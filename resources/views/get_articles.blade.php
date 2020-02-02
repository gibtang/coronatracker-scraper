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
                           <th width="3%">Id</th>
                           <th width="80%">Title</th>
                           <th width="12%">Date Added</th>
                           <th>Status</th>
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
