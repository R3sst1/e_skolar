import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const {
        labels, countPaid, countUnpaid, amountPaid, amountUnpaid, selectedYear
    } = window.chartData;

    const ctx = document.getElementById('trendChart').getContext('2d');
    let mode = 'count';

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: getCountDatasets()
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    title: {
                        display: true,
                        text: 'Count'
                    }
                }
            }
        }
    });

    document.getElementById('viewToggle')?.addEventListener('change', function () {
        mode = this.checked ? 'amount' : 'count';
        chart.data.datasets = mode === 'amount' ? getAmountDatasets() : getCountDatasets();
        chart.options.scales.y.ticks.stepSize = mode === 'count' ? 1 : undefined;
        chart.options.scales.y.title.text = mode === 'amount' ? 'Amount (₱)' : 'Count';
        chart.update();

        document.getElementById('chartTitle').textContent = 
            mode === 'count' 
            ? `Monthly Payment Trend (${selectedYear})`
            : `Monthly Payment Amount Trend (${selectedYear})`;

        this.nextElementSibling.textContent = mode === 'amount'
            ? 'Switch to Count View'
            : 'Switch to Amount View (₱)';
    });

    function getCountDatasets() {
        return [
            {
                label: 'Paid',
                data: countPaid,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Unpaid/Overdue',
                data: countUnpaid,
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ];
    }

    function getAmountDatasets() {
        return [
            {
                label: 'Amount Paid (₱)',
                data: amountPaid,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Amount Unpaid (₱)',
                data: amountUnpaid,
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ];
    }
});
