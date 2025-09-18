@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('keywords.index') }}">@lang('Keywords')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Keyword Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">@lang('Keyword Info')</h3>
            <div class="card-tools">
                {{-- @can('keyword-update') --}}
                    <a href="{{ route('keywords.edit', $keyword) }}" class="btn btn-info">@lang('Edit')</a>
                {{-- @endcan --}}
            </div>
        </div>
        <div class="card-body">
            <div class="bg-custom">
                <div class="row col-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title">@lang('Title')</label>
                            <p>{{ $keyword->title ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category_id">@lang('Category')</label>
                            <p>{{ $keyword->category->title ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">@lang('Type')</label>
                            <p>{{ ucfirst($keyword->type) ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">@lang('Status')</label>
                            <p>
                                @if ($keyword->status == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="created_by">@lang('Created By')</label>
                            <p>{{ $keyword->created_by ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
