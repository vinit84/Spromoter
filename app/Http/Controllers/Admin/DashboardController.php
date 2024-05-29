<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SupportTicket;
use Spatie\Analytics\Period;
use Spatie\Analytics\Facades\Analytics;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            ...$this->getWeeklyEarnings(),
            ...$this->getSupportTracker(),
        ]);
    }

    private function getWeeklyEarnings()
    {
        // This Week Earnings
        $totalEarning = Invoice::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('amount');

        // Last Week Earnings
        $previousWeekEarning = Invoice::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(14))
            ->where('created_at', '<=', now()->subDays(7))
            ->sum('amount');

        // Earning Percentage
        $earningPercentage = nullSafeDivide($totalEarning - $previousWeekEarning, $previousWeekEarning, 0, true);

        // Is Earning Positive
        $isEarningPositive = $totalEarning > $previousWeekEarning;

        // Chart Data
        $weeklyReviewsCollection = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])
            ->selectRaw('DATE_FORMAT(created_at, "%a") as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw('FIELD(day, "'.implode('", "', daysInWeek()).'")')
            ->pluck('count', 'day');

        $weeklyReviews = [];
        foreach (daysInWeek() as $day) {
            $weeklyReviews[$day] = $weeklyReviewsCollection[$day] ?? 0;
        }

        return [
            'earningReportTotalEarning' => $totalEarning,
            'earningReportEarningPercentage' => $earningPercentage,
            'earningReportIsEarningPositive' => $isEarningPositive,
            'earningReportChartData' => collect($weeklyReviews)
        ];
    }

    private function getSupportTracker()
    {
        // Support Tracker
        $totalTickets = SupportTicket::count();
        $newTickets = SupportTicket::where('created_at', '>=', now()->subDays(7))->count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $averageClosedTickets = nullSafeDivide(SupportTicket::where('status', 'closed')->count(), $totalTickets, 0,true);

        return [
            'supportTrackerTotalTickets' => $totalTickets,
            'supportTrackerNewTickets' => $newTickets,
            'supportTrackerOpenTickets' => $openTickets,
            'supportTrackerAverageClosedTickets' => $averageClosedTickets,
        ];
    }
}
