var options = {
  chart: {
    height: 310,
    type: 'donut',
  },
  labels: ['tegister', 'unregister'],
  series: [60000, 45000],
  legend: {
    position: 'bottom',
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    width: 8,
    colors: ['#ffffff'],
  },
  colors: ['#435EEF', '#59a2fb'],
  tooltip: {
    y: {
      formatter: function (val) {
        return "$" + val
      }
    }
  },
}
var chart = new ApexCharts(
  document.querySelector("#byDevice"),
  options
);
chart.render();