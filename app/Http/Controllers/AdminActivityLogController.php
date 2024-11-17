<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index()
    {
        $logs = AdminActivityLog::latest()->paginate(10); // Atur jumlah per halaman jika diperlukan

        return view('admin-activity-logs.index', compact('logs'));
    }
}
