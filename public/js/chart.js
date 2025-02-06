const ctxArea = document.getElementById('myAreaChart').getContext('2d');
const myAreaChart = new Chart(ctxArea, {
    type: 'line', // Kiểu biểu đồ
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // Nhãn cho trục X
        datasets: [{
            label: 'Thu nhập', // Nhãn cho đường biểu đồ
            data: [65, 59, 80, 81, 56, 55, 40], // Dữ liệu cho biểu đồ
            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Màu nền
            borderColor: 'rgba(54, 162, 235, 1)', // Màu đường
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Dữ liệu cho Pie Chart
const ctxPie = document.getElementById('myPieChart').getContext('2d');
const myPieChart = new Chart(ctxPie, {
    type: 'pie', // Kiểu biểu đồ
    data: {
        labels: ['Chuyển khoản', 'Thanh toán khi nhận hàng', 'Khác'], // Nhãn cho Pie Chart
        datasets: [{
            data: [300, 50, 100], // Dữ liệu cho biểu đồ
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)', // Màu cho Chuyển khoản
                'rgba(75, 192, 192, 0.6)', // Màu cho Thanh toán khi nhận hàng
                'rgba(255, 206, 86, 0.6)'  // Màu cho Khác
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (context.parsed !== null) {
                            label += ': ' + context.parsed;
                        }
                        return label;
                    }
                }
            }
        }
    }
});