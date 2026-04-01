@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <h1 class="title-outline dashboard-title">REPORTES SAGE</h1>

        <div class="d-flex justify-content-end mb-4">
            <button class="btn-action-green" id="btn-descargar-pdf">
                <i class="bi bi-download me-2"></i> Descargar PDF
            </button>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="neon-box p-4 text-center h-100">
                    <h3 class="chart-title">Sitios Más Reservados</h3>
                    <p class="chart-subtitle">Por estudiantes este mes</p>
                    <canvas id="barChart" style="width:100%; max-height: 300px;"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="neon-box p-4 text-center h-100">
                    <h3 class="chart-title">Horarios Más Solicitados</h3>
                    <p class="chart-subtitle">Distribución de carga de red/espacios</p>
                    <canvas id="pieChart" style="width:100%; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        let barChart, pieChart;

        async function cargarEstadisticas() {
            try {
                // Obtener datos de espacios más reservados
                const espaciosResponse = await fetch('/api/estadisticas/espacios-mas-reservados');
                const espaciosData = await espaciosResponse.json();

                // Obtener datos de horarios más solicitados
                const horariosResponse = await fetch('/api/estadisticas/horarios-demanda');
                const horariosData = await horariosResponse.json();

                // Configurar gráfico de barras
                if (barChart) barChart.destroy();
                const ctxBar = document.getElementById('barChart').getContext('2d');
                barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: espaciosData.map(item => item.nombre),
                        datasets: [{
                            label: 'Reservas',
                            data: espaciosData.map(item => item.total),
                            backgroundColor: '#3b82f6',
                            borderColor: '#60a5fa',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Número de reservas' } },
                            x: { title: { display: true, text: 'Espacio' } }
                        }
                    }
                });

                // Configurar gráfico de pastel
                if (pieChart) pieChart.destroy();
                const ctxPie = document.getElementById('pieChart').getContext('2d');
                pieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: horariosData.map(item => item.hora),
                        datasets: [{
                            data: horariosData.map(item => item.total),
                            backgroundColor: ['#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#1e40af']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
                document.getElementById('barChart').parentElement.innerHTML = '<p class="text-danger">Error al cargar datos</p>';
            }
        }

        document.getElementById('btn-descargar-pdf').addEventListener('click', async () => {
            const { jsPDF } = window.jspdf;
            const element = document.querySelector('.container-fluid');
            const canvas = await html2canvas(element, { scale: 2 });
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgWidth = 210;
            const pageHeight = 297;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            let heightLeft = imgHeight;
            let position = 0;

            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }
            pdf.save('reporte_sage.pdf');
        });

        document.addEventListener('DOMContentLoaded', cargarEstadisticas);
    </script>
@endsection