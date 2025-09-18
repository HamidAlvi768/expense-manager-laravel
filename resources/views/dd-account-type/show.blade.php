@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>@lang('Account Type Information')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dd-account-type.index') }}">@lang('Account Types')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Account Type Information')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Account Type Information')</h3>
                <div class="card-tools">
                <a href="{{ route('dd-account-type.edit', $DdAccountType) }}" class="btn btn-info">@lang('Edit')</a>
            </div>
            </div>
            <div class="card-body">
                <div class="bg-custom">
                    <div class="row col-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">@lang('Title')</label>
                                <p>{{  $DdAccountType->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">@lang('Status')</label>
                                <p>
                                    @if( $DdAccountType->status == 1)
                                        @lang('Active')
                                    @elseif($DdAccountType->status == 0)
                                        @lang('Inactive')
                                    @else
                                        @lang('Unknown')
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
