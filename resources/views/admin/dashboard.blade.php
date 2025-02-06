@extends('LayoutAdmin.master')

@section('title')
    Dashboard
@endsection

@section('content_admin')
    <a href="/" class="btn btn-primary">Quay lại trang chủ</a>
    <div class="container my-5">
        <h2 class="text-center mb-4">Doanh Thu và Vé Bán Chạy</h2>

        <div class="d-flex justify-content-around">
            <div class="chart-container">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="quantityChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            var revenueData = @json($revenueData);
            var revenueLabels = revenueData.map(data => data.ticket_name);
            var revenueTotals = revenueData.map(data => data.total_revenue);

            var revenueChart = new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh Thu',
                        data: revenueTotals,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxQuantity = document.getElementById('quantityChart').getContext('2d');
            var quantityData = @json($quantityData);
            var quantityLabels = quantityData.map(data => data.ticket_name);
            var quantityTotals = quantityData.map(data => data.total_quantity);

            var quantityChart = new Chart(ctxQuantity, {
                type: 'bar',
                data: {
                    labels: quantityLabels,
                    datasets: [{
                        label: 'Vé Bán Chạy',
                        data: quantityTotals,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <style>
        .chart-container {
            width: 45%;
        }
    </style>
@endsection
