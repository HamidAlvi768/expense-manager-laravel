@extends('layouts.layout')

@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">

    <style>
        /* Desktop styles (min-width to ensure it doesn't affect mobile) */
        @media (min-width: 768px) {
            table.table td:first-child,
            table.table th:first-child {
                width: 5%;
                text-align: center;
            }

            .content-header .breadcrumb {
                display: flex;
                justify-content: end;
            }
        }

        /* Mobile Form Styles */
        .mobile-form-container {
            background-color: #ebe6e6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .mobile-form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .mobile-form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /* Mobile-specific styles */
        @media (max-width: 767px) {
            .desktop-header {
                display: none;
            }
            
            .mobile-header {
                display: block;
            }

            .mobile-form-row {
                flex-direction: row;
                gap: 10px;
            }
            
            .mobile-form-row .form-group {
                margin-bottom: 10px;
                width: 1%;
            }

            .mobile-form-row label {
                display: none;
            }

            /* Show only date labels on mobile */
            .mobile-form-row .form-group label[for="date_from"],
            .mobile-form-row .form-group label[for="date_to"] {
                display: block;
                font-size: 0.9rem;
                margin-bottom: 5px;
            }
        }

        /* Desktop-specific styles */
        @media (min-width: 768px) {
            .mobile-header {
                display: none;
            }
            
            .desktop-header {
                display: block;
            }
        }

        .submit-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>

    <!-- Mobile Content Header -->
    <section class="content-header mobile-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="ml-auto d-flex gap-2">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                                <li class="breadcrumb-item active">@lang('Income Reports')</li>
                            </ol>
                            <a class="btn btn-sm btn-default" target="_blank" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a>
                            <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                                <i class="fas fa-filter"></i> @lang('Filter')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Desktop Content Header -->
    <section class="content-header desktop-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="ml-auto d-flex gap-2">
                            <a class="btn btn-sm btn-default" target="_blank" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a>
                            <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                                <i class="fas fa-filter"></i> @lang('Filter')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Income Reports')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Income Report')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('income-report.index') }}" method="get">
                        <div class="mobile-form-container">
                            <!-- Date From and Date To -->
                            <div class="mobile-form-row">
                                <div class="form-group">
                                    <label for="date_from">@lang('Date From')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" name="date_from" id="date_from" class="form-control" placeholder="dd/mm/yyyy" value="{{ old('date_from', request()->date_from) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_to">@lang('Date To')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" name="date_to" id="date_to" class="form-control" placeholder="dd/mm/yyyy" value="{{ old('date_to', request()->date_to) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Account and Category -->
                            <div class="mobile-form-row">
                                <div class="form-group">
                                    <label for="account_id">@lang('Account')</label>
                                    <select name="account_id" id="account_id" class="form-control">
                                        <option value="">@lang('Select Account')</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="income_category_id">@lang('Income Category')</label>
                                    <select name="income_category_id" id="income_category_id" class="form-control">
                                        <option value="">@lang('Select Category')</option>
                                        @foreach($incomeCategories as $category)
                                            <option value="{{ $category->id }}" {{ request('income_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Amount and Description -->
                            <div class="mobile-form-row">
                                <div class="form-group">
                                    <label for="amount">@lang('Amount')</label>
                                    <input type="number" name="amount" id="amount" class="form-control" placeholder="@lang('Amount')" value="{{ request('amount') }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <input type="text" name="description" id="description" class="form-control" placeholder="@lang('Description')" value="{{ old('description', request()->description) }}">
                                </div>
                            </div>

                            <!-- Submit and Clear buttons -->
                            <div class="submit-container">
                                <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                @if(request()->hasAny(['date_from', 'date_to', 'amount', 'account_id','income_category_id', 'description']))
                                    <a href="{{ route('income-report.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <br>
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">@lang('Income Items')</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center">
                                        @foreach ($usedCategories as $category)
                                            <div class="mr-3 mb-2">
                                                <span class="d-inline-block" style="width: 15px; height: 15px; background-color: {{ $categoryColors[$category->id] ?? '#FFFFFF' }}; border-radius: 50%;"></span>
                                                <span class="ml-1">{{ $category->title }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <table class="table table-bordered custom-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('S.No')</th>
                                            <th>@lang('Account')</th>
                                            <th>@lang('Income Category')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Description')</th>
                                            <th>@lang('Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($incomeItems as $index => $item)
                                        <tr style="background: linear-gradient(to right, rgba(255,255,255,0) 0%, {{ $categoryColors[$item->income_category_id] ?? '#FFFFFF' }} 50%, rgba(255,255,255,0) 100%);">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->account->account_title ?? '-' }}</td>
                                            <td>{{ $item->incomeCategory->title ?? '-' }}</td>
                                            <td>{{ isset($item->amount) ? formatAmount($item->amount) : '-' }}</td>
                                            <td>{{ $item->description ?? '-' }}</td>
                                            <td>{{ $item->income_date ?? '-' }}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">@lang('No Records Found')</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
