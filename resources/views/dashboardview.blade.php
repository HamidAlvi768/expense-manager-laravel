@extends('layouts.layout')

@section('one_page_css')
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
    <style>
        .dashboard-filter-section {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            padding-top: 10px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-top: 1.5rem;
        }
        .dashboard-filter-section .filter-header {
            margin-bottom: 5px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .dashboard-filter-section .info-box {
            height: 100%;
            transition: transform 0.2s;
        }
        .dashboard-filter-section .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .dashboard-filter-section .custom-box {
            margin-top: 15px !important;
        }
        #date-filter-db {
            max-width: 200px;
        }

        .expense-legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .expense-legend-color {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 2px;
        }
        .expense-legend-title {
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 14px;
        }
        .expense-legend-amount {
            font-weight: bold;
            margin-left: 12px;
            text-align: right;
            font-size: 14px;
        }
    </style>
@endsection

@section('one_page_js')
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
@endsection

@section('content')
<div class="dashboard-filter-section">
    <div class="filter-header">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label for="date-filter" class="form-label mb-2">@lang('Filter By Date:')</label>
                <select id="date-filter-db" class="form-control select2">
                    <option value="all">@lang('All Time')</option>
                    <option value="today">@lang('Today')</option>
                    <option value="yesterday">@lang('Yesterday')</option>
                    <option value="last_3_days">@lang('Last 3 Days')</option>
                    <option value="last_7_days">@lang('Last 7 Days')</option>
                    <option value="last_15_days">@lang('Last 15 Days')</option>
                    <option value="last_30_days">@lang('Last 30 Days')</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 custom-box">
            <div class="info-box">
                <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text d-flex">@lang('Total Incomes:')<span class="info-box-number" id="total-incomes-count">{{ $dashboardCounts['incomes'] }}</span></span>
                    <span class="info-box-text d-flex">@lang('Total Amount:')<span class="info-box-number" id="total-income-amount">{{ formatAmount($dashboardCounts['income_total']) }}</span></span>
                    <a href='{{ route('incomes.index') }}'>View All</a><br/>
                    <a href='{{ route('incomes.create') }}'>Create New Income</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 custom-box">
            <div class="info-box">
                <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text d-flex">@lang('Total Expenses: ')<span class="info-box-number" id="total-expenses-count">{{ $dashboardCounts['expenses'] }}</span></span>
                    <span class="info-box-text d-flex">@lang('Total Amount: ')<span class="info-box-number" id="total-expense-amount">{{ formatAmount($dashboardCounts['expense_total']) }}</span></span>
                    <a href='{{ route('expenses.index') }}'>View All</a><br/>
                    <a href='{{ route('expenses.create') }}'>Create New Expense</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 custom-box">
            <div class="info-box">
                <span class="info-box-icon"><i class="fas fa-arrow-right"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text d-flex">@lang('Total Transfers'):<span class="info-box-number" id="total-transfers-count">{{ $dashboardCounts['transfers'] }}</span></span>
                    <span class="info-box-text d-flex">@lang('Transfers Amount'):<span class="info-box-number" id="total-transfer-amount">{{ formatAmount($dashboardCounts['transfer_total']) }}</span></span>
                    <a href='{{ route('transfers.index') }}'>View All</a><br/>
                    <a href='{{ route('transfers.create') }}'>Create New Transfer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-header">
                Income vs Expenses
                <select id="yearFilter" class="form-select" style="float: right; width: auto;">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                    <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                </select>
            </div>
            <canvas id="incomeExpenseChart"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="width: max-content;">
            <div class="card-header">
                Expenses Breakdown
                <select id="expenseDateFilter" class="form-control" style="float: right; width: 40%; height: auto; font-size: 10px;">
                    <option value="today">Today</option>
                    <option value="last_3_days">Last 3 Days</option>
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="last_15_days">Last 15 Days</option>
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="all" selected>All</option>
                </select>
            </div>
            <div class="card-body" style="min-height: 380px;">
                <div class="row">
                    <div class="col-md-7">
                        <canvas id="expenseDoughnut" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-5">
                        <div id="expenseLegend" style="padding: 1rem; max-height: 400px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden FOr Now --}}
{{-- <div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Monthly Budget vs Expenses
                <label for="budgetMonthFilter" style="float: right; font-size: 12px;">Select Month</label>
                <select id="budgetMonthFilter" class="form-control" style="float: right; width: auto; height: 30px; font-size: 12px;">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="card-body" style="padding: 15px;">
                <canvas id="monthlyBudgetChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
</div> --}}










<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var countUrl = '{{ route("dashboard.updateCounts") }}';
</script>
<script>
    $(document).ready(function () {
        let siteUrl = $('meta[name="site-url"]').attr('content');
        const ctx = $('#expenseDoughnut')[0].getContext('2d');
    
        // Function to fetch and render the pie chart
        function DonutChart(filter = 'all') {
            $.get(siteUrl + '/dashboard/get-expense-donut-data', { filter: filter })
                .done(function (data) {
                    if (data.length === 1 && data[0].value === 0) {
                        const labels = ['Expenses'];
                        const values = [0];
                        const colors = ['#CCCCCC']; // Light grey for zero value
    
                        if (window.expenseDoughnutChart) {
                            window.expenseDoughnutChart.destroy();
                        }
    
                        window.expenseDoughnutChart = new Chart(ctx, {
                            type: 'pie', // Change to 'pie' for a pie chart
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Expenses',
                                    data: values,
                                    backgroundColor: colors,
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false // Disable default legend
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                // Adding percentage to tooltip
                                                const percentage = Math.round(tooltipItem.raw / tooltipItem.dataset._meta[0].total * 100);
                                                return tooltipItem.label + ': ' + percentage + '%';
                                            }
                                        }
                                    }
                                },
                                rotation: Math.PI, // Optional: Rotate the pie chart
                            }
                        });
                    } else {
                        const labels = data.map(item => item.label);
                        const values = data.map(item => item.value);
                        const colors = [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                            '#8E44AD', '#3498DB', '#2ECC71', '#E74C3C', '#F1C40F', '#1ABC9C',
                            '#C0392B', '#7D3C98', '#16A085', '#F39C12', '#D35400', '#27AE60',
                            '#2980B9', '#9B59B6', '#34495E', '#95A5A6', '#BDC3C7', '#E67E22'
                        ];
    
                        if (window.expenseDoughnutChart) {
                            window.expenseDoughnutChart.destroy();
                        }
    
                        window.expenseDoughnutChart = new Chart(ctx, {
                            type: 'pie', // Change to 'pie' for a pie chart
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Expenses',
                                    data: values,
                                    backgroundColor: colors.slice(0, labels.length),
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                // Adding percentage to tooltip
                                                const percentage = Math.round(tooltipItem.raw / tooltipItem.dataset._meta[0].total * 100);
                                                return tooltipItem.label + ': ' + percentage + '%';
                                            }
                                        }
                                    }
                                },
                                rotation: Math.PI, // Optional: Rotate the pie chart
                            }
                        });
    
                        // Custom legend below the chart
                        let legendHtml = '';
                        data.forEach((item, index) => {
                            const percentage = ((item.value / values.reduce((a, b) => a + b, 0)) * 100).toFixed(2);
                            legendHtml += `
                                <div class="expense-legend-item">
                                    <div class="expense-legend-color" style="background-color: ${colors[index]}"></div>
                                    <div class="expense-legend-title">${item.label}</div>
                                    <div class="expense-legend-amount">${item.value}</div>
                                </div>
                            `;
                        });
                        $('#expenseLegend').html(legendHtml); // Inject the custom legend into a container below the chart
                    }
                })
                .fail(function (error) {
                    console.error('Error fetching expense data:', error);
                });
        }
    
        // Trigger the initial chart render with the 'all' filter
        DonutChart('all');
    
        // Handle filter change to re-render the pie chart with the selected filter
        $('#expenseDateFilter').change(function () {
            const selectedFilter = $(this).val();
            DonutChart(selectedFilter); // Re-trigger with the selected filter
        });
    });
</script>
{{-- Hidden FOr Now --}}

{{-- <script>
$(document).ready(function () {
    let siteUrl = $('meta[name="site-url"]').attr('content');
    const ctx = $('#monthlyBudgetChart')[0].getContext('2d');

    // Function to fetch and render the budget vs expenses chart
    function renderBudgetVsExpensesChart(month = '01') {
        let year = new Date().getFullYear();  // Use current year

        $.get(siteUrl + '/dashboard/get-budget-vs-expenses', { month: month, year: year })
            .done(function (data) {
                // If the chart already exists, destroy it before creating a new one
                // if (window.monthlyBudgetChart) {
                //     window.monthlyBudgetChart.destroy();
                // }

                if (data.length === 1 && data[0].budgeted === 0 && data[0].actual === 0) {
                    const labels = ['No Data'];
                    const budgetedValues = [0];
                    const actualValues = [0];

                    // Initialize new chart
                    window.monthlyBudgetChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Budgeted Amount',
                                    data: budgetedValues,
                                    backgroundColor: '#27AE60',  // Green for budget
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                },
                                {
                                    label: 'Actual Expenses',
                                    data: actualValues,
                                    backgroundColor: '#E74C3C',  // Red for actual expenses
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            const value = tooltipItem.raw;
                                            return `${tooltipItem.dataset.label}: $${value}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const labels = data.map(item => item.label);
                    const budgetedValues = data.map(item => item.budgeted);
                    const actualValues = data.map(item => item.actual);

                    // Initialize new chart
                    window.monthlyBudgetChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Budgeted Amount',
                                    data: budgetedValues,
                                    backgroundColor: '#27AE60',  // Green for budget
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                },
                                {
                                    label: 'Actual Expenses',
                                    data: actualValues,
                                    backgroundColor: '#E74C3C',  // Red for actual expenses
                                    borderWidth: 1,
                                    borderColor: '#fff',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            const value = tooltipItem.raw;
                                            return `${tooltipItem.dataset.label}: $${value}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            })
            .fail(function (error) {
                console.error('Error fetching budget data:', error);
            });
    }

    // Initial render with the current month
    renderBudgetVsExpensesChart('01');

    // Handle month change to re-render the chart
    $('#budgetMonthFilter').change(function () {
        const selectedMonth = $('#budgetMonthFilter').val();
        renderBudgetVsExpensesChart(selectedMonth); // Re-trigger with selected month
    });
});

</script> --}}

{{-- Hidden FOr Now --}}

@endsection

@push('footer')
    <script src="{{ asset('assets/js/custom/dashboard/view.js') }}"></script>
@endpush
