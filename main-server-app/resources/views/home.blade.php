@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="d-inline-block">
                        Servers:
                    </h2>
                    <a href="{{ route('create') }}" class="btn btn-info float-end">
                        + Add server 
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        @dump($servers)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
