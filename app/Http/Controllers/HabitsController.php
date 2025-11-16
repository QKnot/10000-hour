<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\habits;
use App\Services\BadgeService;
use Carbon\Carbon;

class HabitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $record = habits::recordHabit($id);
        $habit = habits::findHabitsByUser($id, auth()->user()->id);
        
        if (!$habit) {
            return redirect()->route('dashboard')->with('error', 'Habit not found or unauthorized.');
        }
        
        return view('habits.index', compact('habit', 'record'));
    }

    public function updatepage($id)
    {
        $habit = habits::findHabitsByUser($id, auth()->user()->id);
        return view('habits.update', compact('habit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'description' => 'nullable|max:500',
            'daily_count' => 'required|numeric|between:1,10000'
        ]);

        if (habits::isDuplicate($request->name, auth()->user()->id)) {
            return redirect()->back()->withErrors(["name" => "Habit name already exists."])->withInput();
        }

        habits::create([
            'id' => Str::random(12),
            'name' => $request->name,
            'description' => $request->description ?? "Description has not been set yet",
            'daily_count' => $request->daily_count,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->back()->withSuccess('Successfully added a new habit!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            'description' => 'nullable',
        ]);

        $habit = habits::findHabitsByUser($id, auth()->user()->id);

        if ($habit) {
            $habit->update([
            'name' => $request->name,
            'description' => $request->description,
            ]);

            return redirect()->route('dashboard')->withSuccess("Your habit has been updated.");
        } else {
            return redirect()->route('dashboard')->with('warn', "Your habit is not valid.");
        }
    }

    public function destroy($id)
    {
        $habit = habits::findHabitsByUser($id, auth()->user()->id);

        if ($habit) {
            $habit->delete();
            return redirect()->route('dashboard')->withSuccess("Your habit has been successfully deleted.");
        } else {
            return response()->json(['message' => "Habit not found or unauthorized"], 404);
        }
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'habit_id' => 'required|size:12',
            'date' => 'nullable|date'
        ]);

        $habit = habits::findOrFail($request->habit_id);
        
        if ($habit && $habit->user_id === auth()->user()->id) {
            if ($habit->logs()->whereDate('date', '=', now()->toDateString())->count() >= $habit->daily_count) {
                return redirect()->route('habits.index', $habit->id)->with('warn', "You cannot record more activities than the previously set schedule.");
            } else {
                $habit->logs()->create([
                    "id" => Str::random(12),
                    "date" => $request->date ?? now('Asia/Dhaka'),
                    "user_id" => auth()->user()->id,
                    "habit_id" => $habit->id
                ]);
                
                // Check and award badges
                $awardedBadges = BadgeService::checkAndAwardBadges(auth()->user()->id, $habit->id);
                
                if (!empty($awardedBadges)) {
                    $badgeNames = collect($awardedBadges)->pluck('name')->implode(', ');
                    return redirect()->route('habits.index', $habit->id)
                        ->with('success', 'Activity recorded! 🏆 New badge(s) earned: ' . $badgeNames);
                }
                
                return redirect()->route('habits.index', $habit->id);
            }
        } else {
            return redirect()->back()->with('error', 'Habit not found or unauthorized.');
        }
    }

    public function analisis ($id) {
        $habit = habits::findHabitsByUser($id, auth()->user()->id);
        return view('habits.analisis', compact('habit'));
    }

    public function logTime(Request $request)
    {
        $request->validate([
            'habit_id' => 'required|size:12',
            'duration' => 'required|integer|min:1',
            'date' => 'nullable|date'
        ]);

        $habit = habits::findOrFail($request->habit_id);

        if ($habit && $habit->user_id === auth()->user()->id) {
            $habit->logs()->create([
                "id" => Str::random(12),
                "date" => $request->date ?? now('Asia/Dhaka')->toDateString(),
                "duration" => $request->duration,
                "user_id" => auth()->user()->id,
                "habit_id" => $habit->id
            ]);
            
            // Refresh and compute totals
            $habit->refresh();
            $totalHours = habits::getTotalHours($habit->id);
            $goalHours = $habit->getGoalHours();
            $goalReachedBefore = $habit->goal_reached_at !== null;
            
            // Check and award badges
            $awardedBadges = BadgeService::checkAndAwardBadges(auth()->user()->id, $habit->id);
            
            // Update goal status if threshold met
            $habit->markGoalReachedIfNeeded();
            $goalReached = $habit->goal_reached_at !== null;
            
            $badgeMessage = '';
            if (!empty($awardedBadges)) {
                $badgeNames = collect($awardedBadges)->pluck('name')->implode(', ');
                $badgeMessage = ' 🏆 New badge(s) earned: ' . $badgeNames;
            }
            
            if ($goalReached && !$goalReachedBefore) {
                $badgeMessage .= ' 🎉 You have completed the 10,000-hour mastery journey!';
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Time logged successfully!' . $badgeMessage,
                'duration' => $request->duration,
                'badges' => $awardedBadges,
                'total_hours' => $totalHours,
                'goal_hours' => $goalHours,
                'goal_progress' => $habit->goalProgressPercentage(),
                'goal_reached' => $goalReached,
                'goal_reached_at' => optional($habit->goal_reached_at)->toDateTimeString(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or habit not found'
            ], 403);
        }
    }
}
