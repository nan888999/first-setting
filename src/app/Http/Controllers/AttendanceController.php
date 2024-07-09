<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function attendance(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        // 現在の日付と一致するデータを取得
        $attendances = Attendance::with('user')->whereDate('created_at', $date)->paginate(5);

        $breaks = BreakTime::with('user')->whereDate('created_at', $date)->get();

        // 休憩時間計算
        $userBreakTimes = [];
        foreach($breaks as $break) {
            if(!is_null($break->updated_at)) {
                $userId = $break->user_id;
                $breakTime = $break->created_at->diffInSeconds($break->updated_at);
                if(!isset($userBreakTimes[$userId])) {
                    $userBreakTimes[$userId] = 0;
                }
                $userBreakTimes[$userId] += $breakTime;
            }
        }
        $formattedBreakTimes = [];
        foreach($userBreakTimes as $userId => $totalBreakSeconds) {
            $breakHours = floor($totalBreakSeconds / 3600);
            $breakMinutes = floor(($totalBreakSeconds %3600)/ 60);
            $breakSeconds = $totalBreakSeconds % 60;
            $formattedBreakTimes[$userId] = sprintf('%02d:%02d:%02d', $breakHours, $breakMinutes, $breakSeconds);
        }

        // 勤務時間計算
        $formattedWorkTimes = [];
        foreach($attendances as $attendance){
            $userId = $attendance->user_id;
            $userWorkSeconds = $attendance->created_at->diffInSeconds($attendance->updated_at);
            if(isset($userBreakTimes[$userId])){
                $userWorkSeconds -= $userBreakTimes[$userId];
            }
        $workHours = floor($userWorkSeconds / 3600);
        $workMinutes = floor(($userWorkSeconds %3600)/ 60);
        $workSeconds = $userWorkSeconds % 60;
        $formattedWorkTimes[$userId] = sprintf('%02d:%02d:%02d', $workHours, $workMinutes, $workSeconds);
        }

        return view ('attendance', compact('attendances', 'date', 'breaks', 'formattedBreakTimes', 'formattedWorkTimes'));
    }

    public function changeDate(Request $request)
    {
        $date = $request->input('date');
        // 日付変更のリクエストがあれば、日付を変更
        if ($request->has('change_date')) {
        $direction = $request->input('change_date');
            if (is_string($date)) {
            $date = strtotime($date);
            }
            if ($direction == 'previous') {
                $date = strtotime("-1 day", $date);
            } elseif ($direction == 'next') {
                $date = strtotime('+1 day', $date);
            }
            $date = date('Y-m-d', $date);
        }

        // 現在の日付と一致するデータを取得
        $attendances = Attendance::with('user')->whereDate('created_at', $date)->paginate(5);

        $breaks = BreakTime::with('user')->whereDate('created_at', $date)->get();

        // 休憩時間計算
        $userBreakTimes = [];
        foreach($breaks as $break) {
            if(!is_null($break->updated_at)) {
                $userId = $break->user_id;
                $breakTime = $break->created_at->diffInSeconds($break->updated_at);
                if(!isset($userBreakTimes[$userId])) {
                    $userBreakTimes[$userId] = 0;
                }
                $userBreakTimes[$userId] += $breakTime;
            }
        }
        $formattedBreakTimes = [];
        foreach($userBreakTimes as $userId => $totalBreakSeconds) {
            $breakHours = floor($totalBreakSeconds / 3600);
            $breakMinutes = floor(($totalBreakSeconds %3600)/ 60);
            $breakSeconds = $totalBreakSeconds % 60;
            $formattedBreakTimes[$userId] = sprintf('%02d:%02d:%02d', $breakHours, $breakMinutes, $breakSeconds);
        }

        // 勤務時間計算
        $formattedWorkTimes = [];
        foreach($attendances as $attendance){
            $userId = $attendance->user_id;
            $userWorkSeconds = $attendance->created_at->diffInSeconds($attendance->updated_at);
            if(isset($userBreakTimes[$userId])){
                $userWorkSeconds -= $userBreakTimes[$userId];
            }
        $workHours = floor($userWorkSeconds / 3600);
        $workMinutes = floor(($userWorkSeconds %3600)/ 60);
        $workSeconds = $userWorkSeconds % 60;
        $formattedWorkTimes[$userId] = sprintf('%02d:%02d:%02d', $workHours, $workMinutes, $workSeconds);
        }
        return view ('attendance', compact('attendances', 'date', 'breaks', 'formattedBreakTimes', 'formattedWorkTimes'));
    }

    public function workStart(Request $request)
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // レコードの挿入
        Attendance::create([
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/');
    }

    public function workEnd(Request $request)
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        Attendance::where('user_id', $userId)->whereDate('created_at', Carbon::today())->update(['updated_at' => now()]);

        return redirect ('/');
    }

    public function breakStart(Request $request)
    {
        $userId = Auth::id();
        BreakTime::create([
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/');
    }

    public function breakEnd(Request $request)
    {
        $userId = Auth::id();
        BreakTime::where('user_id', $userId)->whereDate('created_at', Carbon::today())->update(['updated_at' => now()]);

        return redirect('/');
    }
}
