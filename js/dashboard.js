if (window.Chart) {
    const ctx = document.getElementById('illnessChart');
    if (ctx && Array.isArray(window.illnessData)) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: window.illnessData.map((item) => item.label),
                datasets: [{
                    data: window.illnessData.map((item) => item.value),
                    backgroundColor: ['#2f9e44', '#74c69d', '#95d5b2', '#52b788', '#1b4332']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
}
