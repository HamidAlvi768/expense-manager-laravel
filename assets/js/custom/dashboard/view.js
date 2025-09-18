$(document).ready(function() {
    "use strict";

    // Function to render the Line Chart
    function LineChart(year) {
        let siteUrl = $('meta[name="site-url"]').attr('content');
        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        // Initialize the chart with empty data
        const incomeExpenseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Income',
                        data: Array(12).fill(0), // Empty data initially
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Expenses',
                        data: Array(12).fill(0), // Empty data initially
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: true,
                    },
                },
                aspectRatio: 2,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        },
                        beginAtZero: true,
                        max: 10000,
                        ticks: {
                            stepSize: 250,
                        }
                    }
                }
            }
        });

        // Fetch dynamic data with AJAX, passing the selected year
        $.ajax({
            url: siteUrl + '/dashboard/income-expense', // Assuming the route is correctly defined
            method: 'GET',
            data: { year: year }, // Send the year as a query parameter
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                // Update chart data dynamically
                incomeExpenseChart.data.datasets[0].data = response.income; // Set income data
                incomeExpenseChart.data.datasets[1].data = response.expenses; // Set expense data

                // Update chart
                incomeExpenseChart.update();
            },
            error: function(error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    // Initialize the chart with the current year when the page loads
    console.log("about to happen")
    const currentYear = new Date().getFullYear();
    LineChart(currentYear); // Initial chart render

    // Trigger chart update when year is selected from the dropdown
    $('#yearFilter').change(function() {
        const selectedYear = $(this).val(); // Get the selected year from dropdown
        LineChart(selectedYear); // Trigger chart update with the selected year
    });


    $('#date-filter-db').on('change', function() {
        console.log('i am called');
        var filter = $(this).val(); // Get the selected filter value
        $.ajax({
            url: countUrl, // Route to fetch updated counts
            type: 'GET',
            data: { filter: filter },
            success: function(data) {
                // Format the numbers with commas and remove decimals
                $('#total-incomes-count').text(data.incomes.toLocaleString());
                $('#total-income-amount').text(parseInt(data.income_total).toLocaleString());
                $('#total-expenses-count').text(data.expenses.toLocaleString());
                $('#total-expense-amount').text(parseInt(data.expense_total).toLocaleString());
                $('#total-transfers-count').text(data.transfers.toLocaleString());
                $('#total-transfer-amount').text(parseInt(data.transfer_total).toLocaleString());
            }
        });
    });
    
    
});
