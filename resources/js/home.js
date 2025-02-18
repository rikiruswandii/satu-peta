var $jq = jQuery.noConflict();
            $jq(document).ready(function() {
                $jq('#dropdownTrigger').on('click', function(event) {
                    event.stopPropagation();

                    let dropdownMenu = $jq('#dropdownMenu');
                    let extraOptions = $jq('#extraOptions');
                    let inputSearch = $jq('#input-search');

                    $jq('#app-name').toggleClass('d-none').toggleClass('d-block');

                    // Toggle kelas d-block untuk dropdown dan extraOptions
                    dropdownMenu.toggleClass('d-none').toggleClass('d-block');
                    extraOptions.toggleClass('d-none').toggleClass('d-block');
                    inputSearch.toggleClass('expanded');
                });

                $jq('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });

                initMap('searchMapId', '', {
                    scale: true,
                    fullScreen: true
                }, {});
            });

            am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
// start and end angle must be set both for chart and series
var chart = root.container.children.push(am5percent.PieChart.new(root, {
  startAngle: 180,
  endAngle: 360,
  layout: root.verticalLayout,
  innerRadius: am5.percent(50)
}));

// Create series
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
// start and end angle must be set both for chart and series
var series = chart.series.push(am5percent.PieSeries.new(root, {
  startAngle: 180,
  endAngle: 360,
  valueField: "value",
  categoryField: "category",
  alignLabels: false
}));

series.states.create("hidden", {
  startAngle: 180,
  endAngle: 180
});

series.slices.template.setAll({
  cornerRadius: 5
});

series.ticks.template.setAll({
  forceHidden: true
});

// Set data
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
series.data.setAll([
  { value: 10, category: "One" },
  { value: 9, category: "Two" },
  { value: 6, category: "Three" },
  { value: 5, category: "Four" },
  { value: 4, category: "Five" },
  { value: 3, category: "Six" },
  { value: 1, category: "Seven" }
]);

series.appear(1000, 100);

}); // end am5.ready()
