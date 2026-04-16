@extends('admin.layouts.master')
@section('title', __('messages.overview_chart'))
@section('content')


<h2 class="sr-only">Overview chart dashboard matching Blade template with 4 stat cards and chart</h2>

<div style="padding: 1rem 0;">

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 1.5rem;">

    <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden;">
      <div style="padding: 1rem; display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: var(--border-radius-md); background: #E6F1FB; display: flex; align-items: center; justify-content: center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#378ADD" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        </div>
        <div>
          <div style="font-size: 12px; color: var(--color-text-secondary);">Total products</div>
          <div style="font-size: 22px; font-weight: 500; color: var(--color-text-primary);">1,284</div>
        </div>
      </div>
      <div style="border-top: 0.5px solid var(--color-border-tertiary); padding: 8px 1rem; text-align: center;">
        <span style="font-size: 12px; color: #378ADD;">More info &#8594;</span>
      </div>
    </div>

    <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden;">
      <div style="padding: 1rem; display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: var(--border-radius-md); background: #EAF3DE; display: flex; align-items: center; justify-content: center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#639922" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/><path d="M9 7h6M9 17h6"/></svg>
        </div>
        <div>
          <div style="font-size: 12px; color: var(--color-text-secondary);">Total sales</div>
          <div style="font-size: 22px; font-weight: 500; color: var(--color-text-primary);">$84,320</div>
        </div>
      </div>
      <div style="border-top: 0.5px solid var(--color-border-tertiary); padding: 8px 1rem; text-align: center;">
        <span style="font-size: 12px; color: #639922;">More info &#8594;</span>
      </div>
    </div>

    <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden;">
      <div style="padding: 1rem; display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: var(--border-radius-md); background: #E1F5EE; display: flex; align-items: center; justify-content: center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <div>
          <div style="font-size: 12px; color: var(--color-text-secondary);">Product sold</div>
          <div style="font-size: 22px; font-weight: 500; color: var(--color-text-primary);">3,670</div>
        </div>
      </div>
      <div style="border-top: 0.5px solid var(--color-border-tertiary); padding: 8px 1rem; text-align: center;">
        <span style="font-size: 12px; color: #1D9E75;">More info &#8594;</span>
      </div>
    </div>

    <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden;">
      <div style="padding: 1rem; display: flex; align-items: center; gap: 12px;">
        <div style="width: 36px; height: 36px; border-radius: var(--border-radius-md); background: #FAEEDA; display: flex; align-items: center; justify-content: center;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA7517" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
        <div>
          <div style="font-size: 12px; color: var(--color-text-secondary);">Purchases</div>
          <div style="font-size: 22px; font-weight: 500; color: var(--color-text-primary);">920</div>
        </div>
      </div>
      <div style="border-top: 0.5px solid var(--color-border-tertiary); padding: 8px 1rem; text-align: center;">
        <span style="font-size: 12px; color: #BA7517;">More info &#8594;</span>
      </div>
    </div>

  </div>

  <div style="display: flex; gap: 16px; margin-bottom: 10px; font-size: 12px; color: var(--color-text-secondary);">
    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 10px; height: 10px; border-radius: 2px; background: #639922;"></span>Total sales</span>
    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 10px; height: 10px; border-radius: 2px; background: #BA7517;"></span>Purchases</span>
    <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 10px; height: 10px; border-radius: 2px; background: #378ADD;"></span>Products sold</span>
  </div>

  <div style="position: relative; width: 100%; height: 260px;">
    <canvas id="overviewChart" role="img" aria-label="Bar chart showing total sales, purchases, and products sold per month">Monthly overview data for Jan through Jun.</canvas>
  </div>

</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
new Chart(document.getElementById('overviewChart'), {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun'],
    datasets: [
      {
        label: 'Total sales',
        data: [14000, 21000, 17000, 25000, 20000, 28000],
        backgroundColor: 'rgba(99,153,34,0.75)',
        borderRadius: 4,
        borderSkipped: false
      },
      {
        label: 'Purchases',
        data: [8000, 12000, 9000, 14000, 11000, 16000],
        backgroundColor: 'rgba(186,117,23,0.75)',
        borderRadius: 4,
        borderSkipped: false
      },
      {
        label: 'Products sold',
        data: [320, 480, 390, 560, 430, 610],
        backgroundColor: 'rgba(55,138,221,0.75)',
        borderRadius: 4,
        borderSkipped: false,
        yAxisID: 'y2'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: ctx => {
            if (ctx.datasetIndex === 2) return ' ' + ctx.parsed.y.toLocaleString() + ' units';
            return ' $' + ctx.parsed.y.toLocaleString();
          }
        }
      }
    },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 12 } } },
      y: {
        grid: { color: 'rgba(128,128,128,0.1)' },
        ticks: { font: { size: 11 }, callback: v => '$' + (v/1000) + 'k' }
      },
      y2: {
        position: 'right',
        grid: { display: false },
        ticks: { font: { size: 11 }, callback: v => v + ' u' }
      }
    }
  }
});
</script>

@endpush
