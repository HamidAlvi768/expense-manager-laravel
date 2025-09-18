@php
    $c = Request::segment(1);
    $m = Request::segment(2);
    $roleName = Auth::user()->getRoleNames();
@endphp

<aside class="main-sidebar sidebar-light-info elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link sidebar-light-info">
        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ $ApplicationSetting->item_name }}"
            id="custom-opacity-sidebar" class="brand-image">
        <span class="brand-text font-weight-light" style="font-size:12px;">{{ $ApplicationSetting->item_name }}</span>
    </a>
    <div class="sidebar">
        <?php
        if (Auth::user()->photo == null) {
            $photo = 'assets/images/profile/male.png';
        } else {
            $photo = Auth::user()->photo;
        }
        ?>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @canany(['dashboard-read'])
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link @if ($c == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('Dashboard') }}</p>
                        </a>
                    </li>
                @endcanany
               
                <li class="nav-item">
                    <a href="{{ route('accounts.index') }}"
                        class="nav-link @if ($c == 'accounts') active @endif">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>@lang('Accounts')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('incomes.index') }}"
                        class="nav-link @if ($c == 'incomes') active @endif">
                        <i class="nav-icon fas fa-arrow-up"></i>
                        <p>@lang('Incomes')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}"
                        class="nav-link @if ($c == 'expenses') active @endif">
                        <i class="nav-icon fas fa-arrow-down"></i>
                        <p>@lang('Expenses')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('transfers.index') }}"
                        class="nav-link @if ($c == 'transfers') active @endif">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>@lang('Transfers')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('budgets.index') }}"
                        class="nav-link @if ($c == 'budgets') active @endif">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>@lang('Budgets')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bank-statements.index') }}"
                        class="nav-link @if ($c == 'bank-statements') active @endif">
                        <i class="nav-icon fas fa-university"></i>
                        <p>@lang('Bank Statements')</p>
                    </a>
                </li>




                <li class="nav-item has-treeview @if ($c == 'transfer-report' || $c == 'income-report' || $c == 'expense-report') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if ($c == 'transfer-report' || $c == 'income-report' || $c == 'expense-report') active @endif">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            @lang('Reports')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['payment-read', 'payment-create', 'payment-update', 'payment-delete'])
                            <li class="nav-item">
                                <a href="{{ route('transfer-report.index') }}" 
                                    class="nav-link @if ($c == 'transfer-report') active @endif">
                                    <i class="fas fa-money-check"></i>
                                    <p>@lang('Transfer Reports')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['account-header-read', 'account-header-create', 'account-header-update',
                            'account-header-delete'])
                            <li class="nav-item">
                                <a href="{{ route('income-report.index') }}" 
                                    class="nav-link @if ($c == 'income-report') active @endif">
                                    <i class="fas fa-dollar-sign"></i>
                                    <p>@lang('Income Reports')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['invoice-read', 'invoice-create', 'invoice-update', 'invoice-delete'])
                            <li class="nav-item">
                                <a href="{{ route('expense-report.index') }}" 
                                    class="nav-link @if ($c == 'expense-report') active @endif">
                                    <i class="fas fa-money-bill-alt"></i>
                                    <p>@lang('Expense Reports')</p>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            

                
          


                @canany(['task-read', 'task-create', 'task-update', 'task-delete'])
                    <li class="nav-item">
                        <a href="{{ route('tasks.index') }}"
                            class="nav-link @if ($c == 'tasks') active @endif">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>@lang('Tasks')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['dropdown-read', 'dropdown-create', 'dropdown-update', 'dropdown-delete'])
                    <li class="nav-item has-treeview @if (
                        $c == 'dd-blood-groups' ||
                            $c == 'dd-procedures' ||
                            $c == 'dd-medicine' ||
                            $c == 'subcategories' ||
                            $c == 'keywords' ||
                            $c == 'categories' ||
                            $c == 'dd-social-history' ||
                            $c == 'dd-medical-history' ||
                            $c == 'dd-procedure-categories' ||
                            $c == 'dd-dental-history' ||
                            $c == 'marital-statuses' ||
                            $c == 'dd-drug-history' ||
                            $c == 'dd-expense-category' ||
                            $c == 'dd-income-category' ||
                            $c == 'dd-account-type' ||
                            $c == 'appointment-statuses' ||
                            $c == 'dd-investigations' ||
                            $c == 'dd-treatment-plans' ||
                            $c == 'dd-examinations' ||
                            $c == 'dd-diagnoses' ||
                            $c == 'dd-task-action' ||
                            $c == 'dd-task-status' ||
                            $c == 'dd-task-type' ||
                            $c == 'dd-task-priority' ||
                            $c == 'dd-medicine-types' ||
                            $c == 'chief-complaints' ||
                            $c == 'extra-orals' ||
                            $c == 'intra-orals' ||
                            $c == 'soft-tissues'  || 
                            $c == 'hard-tissues'  
                               ) menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if (
                            $c == 'chief-complaints' ||
                            $c == 'dd-blood-groups' ||
                                $c == 'dd-procedures' ||
                                $c == 'dd-medicine' ||
                                $c == 'dd-diagnosis' ||
                                $c == 'subcategories' ||
                                $c == 'keywords' ||
                                $c == 'categories' ||
                                $c == 'dd-social-history' ||
                                $c == 'dd-procedure-categories' ||
                                $c == 'dd-dental-history' ||
                                $c == 'dd-medical-history' ||
                                $c == 'dd-examinations' ||
                                $c == 'appointment-statuses' ||
                                $c == 'dd-investigations' ||
                                $c == 'dd-drug-history' ||
                                $c == 'dd-expense-category' ||
                                $c == 'dd-income-category' ||
                                $c == 'dd-account-type' ||
                                $c == 'dd-treatment-plans' ||
                                $c == 'marital-statuses' ||
                                $c == 'dd-diagnoses' ||
                                $c == 'dd-task-priority' ||
                                $c == 'dd-task-action' ||
                                $c == 'dd-task-status' ||
                                $c == 'dd-task-type' ||
                                $c == 'extra-orals' ||
                                $c == 'soft-tissues'  ||
                                $c == 'hard-tissues' ||
                                $c == 'dd-medicine-types' || $c == 'intra-orals') active @endif">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                @lang('Dropdowns Settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('keywords.index') }}"
                                    class="nav-link @if ($c == 'keywords') active @endif">
                                    <i class="nav-icon fas fa-key"></i>
                                    <p>@lang('Keywords')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-expense-category.index') }}"
                                    class="nav-link @if ($c == 'dd-expense-category') active @endif ">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>@lang('Settings Expense Category')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-income-category.index') }}"
                                    class="nav-link @if ($c == 'dd-income-category') active @endif ">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>@lang('Settings Income Category')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-account-type.index') }}"
                                    class="nav-link @if ($c == 'dd-account-type') active @endif ">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>@lang('Settings Account Type')</p>
                                </a>
                            </li>
                        
                        </ul>


                    </li>
                @endcanany






            {{-- Hidden On Purpose --}}
                {{-- @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'user-read', 'user-create',
                    'user-update', 'user-delete', 'smtp-read', 'smtp-create', 'smtp-update', 'smtp-delete', 'company-read',
                    'company-create', 'company-update', 'company-delete', 'currencies-read', 'currencies-create',
                    'currencies-update', 'currencies-delete', 'tax-rate-read', 'tax-rate-create', 'tax-rate-update',
                    'tax-rate-delete'])
                    <li class="nav-item has-treeview @if (
                        $c == 'roles' ||
                            $c == 'users' ||
                            $c == 'apsetting' ||
                            $c == 'smtp-configurations' ||
                            $c == 'general' ||
                            $c == 'currency' ||
                            $c == 'tax') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if (
                            $c == 'roles' ||
                                $c == 'users' ||
                                $c == 'apsetting' ||
                                $c == 'smtp-configurations' ||
                                $c == 'general' ||
                                $c == 'currency' ||
                                $c == 'tax') active @endif">
                            <i class="nav-icon fa fa-cogs"></i>
                            <p>
                                @lang('Settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['role-read', 'role-create', 'role-update', 'role-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link @if ($c == 'roles') active @endif ">
                                        <i class="fas fa-cube nav-icon"></i>
                                        <p>@lang('Role Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['user-read', 'user-create', 'user-update', 'user-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link @if ($c == 'users') active @endif ">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>@lang('User Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @if ($roleName['0'] = 'Super Admin')
                                @canany(['apsetting-read', 'apsetting-create', 'apsetting-update', 'apsetting-delete'])
                                    <li class="nav-item">
                                        <a href="{{ route('apsetting') }}"
                                            class="nav-link @if ($c == 'apsetting' && $m == null) active @endif ">
                                            <i class="fa fa-globe nav-icon"></i>
                                            <p>@lang('Application Settings')</p>
                                        </a>
                                    </li>
                                @endcanany
                            @endif

                            @canany(['company-read', 'company-create', 'company-update', 'company-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('general') }}"
                                        class="nav-link @if ($c == 'general') active @endif ">
                                        <i class="fas fa-align-left nav-icon"></i>
                                        <p>@lang('General Settings')</p>
                                    </a>
                                </li>
                            @endcanany



                        </ul>
                    </li>
                @endcanany --}}
            {{-- Hidden On Purpose --}}
   
            </ul>
        </nav>
    </div>
</aside>
