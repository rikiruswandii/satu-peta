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
    // Membuat root elemen
    var root = am5.Root.new("chartdiv");

    // Mengatur tema
    root.setThemes([am5themes_Animated.new(root)]);

    // Membuat container
    var container = root.container.children.push(am5.Container.new(root, {
        width: am5.percent(100),
        height: am5.percent(100),
        layout: root.verticalLayout
    }));

    // Membuat seri Force-Directed
    var series = container.children.push(am5hierarchy.ForceDirected.new(root, {
        singleBranchOnly: false,
        downDepth: 1,
        initialDepth: 1, // Mengatur kedalaman awal tampilan
        valueField: "value",
        categoryField: "name",
        childDataField: "children",
        centerStrength: 0.5,
        minRadius: 20,  // Ukuran terkecil untuk anak
        maxRadius: 80,  // Ukuran terbesar untuk induk
        nodePadding: 10
    }));

    // Mengatur data ke dalam seri
    series.data.setAll([chartData]);

    // Animasi muncul
    series.appear(1000, 100);
});

