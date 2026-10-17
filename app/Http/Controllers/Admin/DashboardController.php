<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin dashboard: workflow stats + latest submissions.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'all' => Event::count(),
                'pending' => Event::where('status', Event::STATUS_PENDING_REVIEW)->count(),
                'active' => Event::where('status', Event::STATUS_ACTIVE)->count(),
                'draft' => Event::where('status', Event::STATUS_DRAFT)->count(),
                'expired' => Event::where('status', Event::STATUS_EXPIRED)->count(),
            ],
            'rsvpCount' => Rsvp::count(),
            'recentEvents' => Event::latest()->take(8)->get(),
        ]);
    }
}
