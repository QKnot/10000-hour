<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\habits;
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
        $habit = habits::with('logs')->findOrFail($id);
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

        if ($habit) {
            if ($habit->logs()->whereDate('date', '=', now()->toDateString())->count() >= $habit->daily_count) {
                return redirect()->route('habits.index', $habit->id)->with('warn', "You cannot record more activities than the previously set schedule.");
            } else {
                $habit->logs()->create([
                    "id" => Str::random(12),
                    "date" => $request->date ?? now('Asia/Dhaka'),
                    "user_id" => auth()->user()->id,
                    "habit_id" => $habit->id
                ]);
                return redirect()->route('habits.index', $habit->id);
            }
        } else {
            return redirect()->back()->with('eror', "Sorry Habit not found");
        }
    }

    public function analisis ($id) {
        $habit = habits::findHabitsByUser($id, auth()->user()->id);
        return view('habits.analisis', compact('habit'));
    }
}
