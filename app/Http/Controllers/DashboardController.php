<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepositoryInterface;

class DashboardController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    
    public function index()
    {
        $dashboardData = $this->dashboardRepository->getDashboardData();

        return view('pages.dashboard', $dashboardData);
    }
}
