<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure qknot0.3@gmail.com is set as admin
        $adminEmail = 'qknot0.3@gmail.com';
        $user = User::where('email', $adminEmail)->first();
        
        if ($user) {
            // Update existing user to admin
            $user->role = 'admin';
            $user->save();
        } else {
            // Create admin user if doesn't exist
            User::create([
                'id' => Str::random(13),
                'username' => 'qknot',
                'email' => $adminEmail,
                'password' => Hash::make('73939133'),
                'role' => 'admin',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert admin to member (commented out for safety)
        // $user = User::where('email', 'qknot0.3@gmail.com')->first();
        // if ($user) {
        //     $user->role = 'member';
        //     $user->save();
        // }
    }
};
