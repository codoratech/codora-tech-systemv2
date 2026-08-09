/**
 * Codora Tech — Dashboard charts (ApexCharts)
 */

'use strict';

(function () {
  let cardColor, headingColor, axisColor, borderColor, legendColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;
  legendColor = config.colors.axisColor;

  // Projects by Month — Bar Chart
  // --------------------------------------------------------------------
  const projectsByMonthEl = document.querySelector('#projectsByMonthChart'),
    projectsByMonthOptions = {
      series: [
        { name: 'Novos projetos', data: [4, 6, 5, 8, 7, 9, 6] },
        { name: 'Concluídos', data: [2, 3, 4, 5, 6, 6, 7] }
      ],
      chart: { height: 280, stacked: false, type: 'bar', toolbar: { show: false } },
      plotOptions: {
        bar: { horizontal: false, columnWidth: '45%', borderRadius: 6, startingShape: 'rounded', endingShape: 'rounded' }
      },
      colors: [config.colors.primary, config.colors.accent],
      dataLabels: { enabled: false },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.35,
          gradientToColors: [config.colors.primary, config.colors.accent],
          inverseColors: false,
          opacityFrom: 0.95,
          opacityTo: 0.75,
          stops: [0, 100]
        }
      },
      stroke: { curve: 'smooth', width: 0 },
      legend: {
        show: true,
        horizontalAlign: 'left',
        position: 'top',
        markers: { height: 8, width: 8, radius: 12, offsetX: -3 },
        labels: { colors: legendColor },
        itemMargin: { horizontal: 10 }
      },
      grid: { borderColor: borderColor, padding: { top: 0, bottom: -8, left: 10, right: 10 } },
      xaxis: {
        categories: ['Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago'],
        axisTicks: { show: false },
        axisBorder: { color: borderColor },
        labels: { style: { colors: axisColor, fontSize: '13px' } }
      },
      yaxis: { labels: { style: { colors: axisColor, fontSize: '13px' } } }
    };
  if (typeof projectsByMonthEl !== 'undefined' && projectsByMonthEl !== null) {
    new ApexCharts(projectsByMonthEl, projectsByMonthOptions).render();
  }

  // Revenue / Growth — Radial Bar
  // --------------------------------------------------------------------
  const revenueRadialEl = document.querySelector('#revenueRadialChart'),
    revenueRadialOptions = {
      series: [68],
      chart: { height: 200, type: 'radialBar' },
      plotOptions: {
        radialBar: {
          startAngle: -150,
          endAngle: 150,
          hollow: { size: '65%' },
          track: { background: borderColor, strokeWidth: '100%' },
          dataLabels: {
            name: { show: false },
            value: {
              offsetY: 10,
              color: headingColor,
              fontSize: '22px',
              fontWeight: 700,
              formatter: function (val) { return val + '%'; }
            }
          }
        }
      },
      colors: [config.colors.accent],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          type: 'horizontal',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 1,
          stops: [0, 100]
        }
      },
      stroke: { dashArray: 5 },
      grid: { padding: { top: -10 } }
    };
  if (typeof revenueRadialEl !== 'undefined' && revenueRadialEl !== null) {
    new ApexCharts(revenueRadialEl, revenueRadialOptions).render();
  }

  // Project Status — Donut Chart
  // --------------------------------------------------------------------
  const projectStatusEl = document.querySelector('#projectStatusChart'),
    projectStatusOptions = {
      series: [8, 4, 6],
      labels: ['Em andamento', 'Em revisão', 'Concluídos'],
      chart: { height: 220, type: 'donut' },
      colors: [config.colors.primary, config.colors.accent, config.colors.success],
      stroke: { width: 0 },
      dataLabels: { enabled: true, formatter: function (val) { return Math.round(val) + '%'; } },
      legend: {
        show: true,
        position: 'bottom',
        fontSize: '12px',
        markers: { width: 8, height: 8, radius: 12 },
        itemMargin: { horizontal: 8, vertical: 4 },
        labels: { colors: legendColor }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '70%',
            labels: {
              show: true,
              value: { fontSize: '1.3rem', fontWeight: 600, color: headingColor, offsetY: -10 },
              name: { offsetY: 20 },
              total: {
                show: true,
                fontSize: '0.8125rem',
                color: axisColor,
                label: 'Total',
                formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => a + b, 0); }
              }
            }
          }
        }
      }
    };
  if (typeof projectStatusEl !== 'undefined' && projectStatusEl !== null) {
    new ApexCharts(projectStatusEl, projectStatusOptions).render();
  }

  // Accounts Receivable Summary — Donut Chart
  // --------------------------------------------------------------------
  const receivablesEl = document.querySelector('#receivablesChart'),
    receivablesOptions = {
      series: [6, 4, 2],
      labels: ['Pago', 'Pendente', 'Atrasado'],
      chart: { height: 220, type: 'donut' },
      colors: [config.colors.success, config.colors.warning, config.colors.danger],
      stroke: { width: 0 },
      dataLabels: { enabled: true, formatter: function (val) { return Math.round(val) + '%'; } },
      legend: {
        show: true,
        position: 'bottom',
        fontSize: '12px',
        markers: { width: 8, height: 8, radius: 12 },
        itemMargin: { horizontal: 8, vertical: 4 },
        labels: { colors: legendColor }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '70%',
            labels: {
              show: true,
              value: { fontSize: '1.3rem', fontWeight: 600, color: headingColor, offsetY: -10 },
              name: { offsetY: 20 },
              total: {
                show: true,
                fontSize: '0.8125rem',
                color: axisColor,
                label: 'Total',
                formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => a + b, 0); }
              }
            }
          }
        }
      }
    };
  if (typeof receivablesEl !== 'undefined' && receivablesEl !== null) {
    new ApexCharts(receivablesEl, receivablesOptions).render();
  }
})();
