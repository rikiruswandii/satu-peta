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
        initialDepth: 5,
        nodePadding: 20,
        valueField: "value",
        categoryField: "name",
        childDataField: "children",
        minRadius: 30, // Atur ukuran minimal node agar level 1 tidak terlalu kecil
    maxRadius: 80, // Pastikan level 1 lebih besar
    }));

    series.linkBullets.push(function (root, source, target) {
        const bullet = am5.Bullet.new(root, {
            locationX: 0.5,
            autoRotate: true,
            autoRotateAngle: 180,
            sprite: am5.Graphics.new(root, {
                fill: source.get("fill"),
                centerY: am5.percent(50),
                centerX: am5.percent(50),
                draw: function (display) {
                    display.moveTo(0, -6);
                    display.lineTo(16, 0);
                    display.lineTo(0, 6);
                    display.lineTo(3, 0);
                    display.lineTo(0, -6);
                }
            })
        });

        bullet.animate({
            key: "locationX",
            to: -0.1,
            from: 1.1,
            duration: Math.random() * 500 + 1000,
            loops: Infinity,
            easing: am5.ease.quad
        });

        return bullet;
    });

    series.labels.template.set("minScale", 0);

    // Mengatur data ke dalam seri
    series.data.setAll([chartData]);

    // Make stuff animate on load
    series.appear(1000, 100);
});

$jq(document).ready(function () {
    $jq('.partner-logo-action').click(function () {
        console.log('Tombol diklik!'); // Debugging

        // Cari form terdekat dan submit
        $jq(this).closest('.search-form').submit();
    });
});

