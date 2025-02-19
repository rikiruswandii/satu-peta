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
am5.ready(function () {
    // Create root element
    var root = am5.Root.new("chartdiv");

    // Set themes
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    var chart = root.container.children.push(am5percent.PieChart.new(root, {
        startAngle: 180,
        endAngle: 360,
        layout: root.verticalLayout,
        innerRadius: am5.percent(50)
    }));

    // Create series
    var series = chart.series.push(am5percent.PieSeries.new(root, {
        startAngle: 180,
        endAngle: 360,
        valueField: "value",
        categoryField: "category",
        alignLabels: true
    }));

    series.states.create("hidden", {
        startAngle: 180,
        endAngle: 180
    });

    series.slices.template.setAll({
        cornerRadius: 5
    });

    series.ticks.template.setAll({
        forceHidden: false
    });

    // Create legend
    var legend = chart.children.push(am5.Legend.new(root, {
        centerX: am5.percent(50),
        x: am5.percent(50),
        marginTop: 15,
        marginBottom: 15
    }));

    legend.labels.template.setAll({
        fontSize: 14
    });

    // Set data dari backend
    var chartData = categories.map(function (category) {
        return {
            value: category.map.length,  // Sesuaikan dengan jumlah terkait
            category: category.name
        };
    }).filter(item => item.value > 0); // Hanya gunakan kategori dengan nilai lebih dari 0

    if (chartData.length === 0) {
        chartData.push({ value: 1, category: "Tidak Ada Data" });
    }

    series.data.setAll(chartData);
    legend.data.setAll(series.dataItems); // Pastikan legend mendapatkan data dari series

    series.appear(1000, 100);
});

