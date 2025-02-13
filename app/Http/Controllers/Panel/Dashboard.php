<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class Dashboard extends Controller
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent;
    }

    public function index(Request $request)
    {
        $ip = $request->ip();

        $os = $this->agent->platform();
        $browser = $this->agent->browser();

        $data = [
            'ip' => $ip,
            'city' => $location['city'] ?? '-',
            'region' => $location['region'] ?? '-',
            'country' => $location['country'] ?? '-',
            'os' => $os.' '.$this->agent->version($os),
            'browser' => $browser.' '.$this->agent->version($browser),
        ];

        $title = 'Dashboard';
        $description = $title.' page!';

        return view('panel.dashboard', compact('data', 'title', 'description'));
    }
}
