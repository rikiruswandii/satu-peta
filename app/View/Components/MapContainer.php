<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MapContainer extends Component
{
    public $geoJsonPath;
    public $mapId;
    public $controls;
    public $interactions;
    public $baseLayerType;

    /**
     * Create a new component instance.
     *
     * @param string $geoJsonPath
     * @param string $mapId
     * @param array $controls
     * @param array $interactions
     * @param string $baseLayerType
     */
    public function __construct($geoJsonPath = '', $mapId = 'map', $controls = [], $interactions = [], $baseLayerType = 'osm')
    {
        $this->geoJsonPath = $geoJsonPath;
        $this->mapId = $mapId;
        $this->controls = $controls;
        $this->interactions = $interactions;
        $this->baseLayerType = $baseLayerType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.map-container');
    }
}