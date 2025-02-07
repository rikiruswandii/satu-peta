<x-guest-layout>
    @section('title', $title)
    @section('description', $description)
    <x-breadcrumb :title="$title" :description="$description"></x-breadcrumb>

    <!-- Shop Meta -->
    <div class="shop-with-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-5 col-md-4">
                    <div class="shop-sidebar-area mb-5">
                        <!-- Single Widget -->
                        <div class="shop-widget mb-4 mb-lg-5">
                            <h5 class="widget-title mb-4">Product Categories</h5>
                            <x-map-category :category_name="'Diskominfo'" :category_count="'80'" />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-7 col-md-8">
                    <div class="row g-4 g-lg-5">
                        <x-map-card :card_id="'1'" :card_title="'Title Card'" :card_opd="'DPMPTSP'" :card_filename="'PJR_2025'" />
                        <x-map-card :card_id="'2'" :card_title="'Another Card'" :card_opd="'Another OPD'" :card_filename="'PJR_2026'" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
