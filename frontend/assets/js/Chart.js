let populationChart;

// Function to render Chart
function renderChart(labels, aliveData, deadData) {
    const ctx = document.getElementById('populationChart').getContext('2d');
    if (populationChart) populationChart.destroy(); // Destroy old chart

    populationChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Alive (Born)',
                    data: aliveData,
                    borderColor: 'rgba(34,197,94,1)',
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(34,197,94,1)'
                },
                {
                    label: 'Dead (Deceased)',
                    data: deadData,
                    borderColor: 'rgba(239,68,68,1)',
                    backgroundColor: 'rgba(239,68,68,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(239,68,68,1)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: { title: { display: true, text: 'Period' } },
                y: { beginAtZero: true, title: { display: true, text: 'Count' } }
            }
        }
    });
}

// ========================
// Load initial chart (last 12 months)
document.addEventListener('DOMContentLoaded', function() {
   fetchPopulationData({ filter_type: 'year', year: new Date().getFullYear() });

});

// ========================
// Toggle filter inputs visibility
document.getElementById('filter_type').addEventListener('change', function () {
    ['monthFilter','yearFilter','dayFilter','rangeFilter'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });

    const val = this.value;
    if(val === 'month') document.getElementById('monthFilter').classList.remove('hidden');
    if(val === 'year') document.getElementById('yearFilter').classList.remove('hidden');
    if(val === 'day') document.getElementById('dayFilter').classList.remove('hidden');
    if(val === 'range') document.getElementById('rangeFilter').classList.remove('hidden');
});

// ========================
// Apply filter
document.getElementById('applyFilter').addEventListener('click', function () {
    const filterType = document.getElementById('filter_type').value;
    let params = { filter_type: filterType };

    if(filterType==='month') params.month = document.querySelector('#monthFilter input')?.value;
    if(filterType==='year') params.year = document.querySelector('#yearFilter input')?.value;
    if(filterType==='day') params.day = document.querySelector('#dayFilter input')?.value;
    if(filterType==='range'){
        params.start = document.getElementById('startDate').value;
        params.end = document.getElementById('endDate').value;
    }

    fetchPopulationData(params);
});

// ========================
// Fetch population data from controller
function fetchPopulationData(params) {
    fetch("../../../backend/controllers/PopulationController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(params)
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            Swal.fire('No Data', data.error, 'info');
            if (populationChart) populationChart.destroy();
            return;
        }

        if(data.labels && data.alive && data.dead){
            renderChart(data.labels, data.alive, data.dead);
        }
    })
    .catch(err => {
        console.error('Error fetching population data:', err);
        Swal.fire('Error', 'Failed to load population data', 'error');
    });
}
