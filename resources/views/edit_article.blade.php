@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    <form action='/update_article' method='post'>
                    <table>
                        <tr>
                           <th width="20%"></th>
                           <th></th>
                         </tr>
                        <tr>
                            <td>Title</td><td>{{ $article->title }}</td>
                        </tr>
                        <tr>
                            <td>Description</td><td>{{ $article->description }}</td>
                        </tr>
                        <tr>
                            <td>Author</td><td>{{ $article->author }}</td>
                        </tr>
                        <tr>
                            <td>URL</td><td>{{ $article->url }}</td>
                        </tr>
                        <tr>
                            <td>Published At</td><td>{{ $article->publishedAt }}</td>
                        </tr>
                        <tr>
                            <td>Site Name</td><td>{{ $article->siteName }}</td>
                        </tr>
                        <tr>

                            <td>Status</td>
                            <td>
                                <select name="status">
                                  <option value="0" <?php if ($article->status == 0) { echo 'selected'; } ?>>Disabled</option>
                                  <option value="1" <?php if ($article->status == 1) { echo 'selected'; } ?>>Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
