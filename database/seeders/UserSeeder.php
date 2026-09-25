<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@marketlink.test'],
            ['name' => 'Market Operations', 'role' => 'admin', 'phone' => '555-0100', 'password' => 'password'],
        );
        $admin->fill(['role' => 'admin', 'status' => 'active'])->save();

        $farmer = User::firstOrCreate(
            ['email' => 'farmer@marketlink.test'],
            ['name' => 'John Doe', 'role' => 'farmer', 'phone' => '555-0145', 'password' => 'password'],
        );
        $farmer->fill(['role' => 'farmer', 'status' => 'active'])->save();

        // Prefer an already-linked profile. Otherwise claim the seeded Green Valley
        // profile. If neither exists, create the seeded farmer profile.
        $farmProfile = Farmer::where('user_id', $farmer->id)->first()
            ?? Farmer::where('name', 'Green Valley Farm')->whereNull('user_id')->first();

        if ($farmProfile === null) {
            $farmProfile = Farmer::create([
                'user_id' => $farmer->id,
                'name' => 'Green Valley Farm',
                'owner_name' => $farmer->name,
                'location' => 'North District',
                'specialty' => 'Organic Heirloom Vegetables & Herbs',
                'rating' => 4.9,
                'status' => 'verified',
                'slots' => 'Sat 8:00-10:00 AM, Sat 10:30-12:30 PM',
                'is_demo' => false,
            ]);
        } else {
            $farmProfile->update([
                'user_id' => $farmer->id,
                'owner_name' => $farmer->name,
                'status' => 'verified',
                'is_demo' => false,
            ]);
        }

        // Remove duplicate seeded profiles only when they are linked to this
        // seeded farmer; never delete the profile just created/claimed above.
        Farmer::where('user_id', $farmer->id)
            ->where('id', '!=', $farmProfile->id)
            ->delete();

        User::firstOrCreate(
            ['email' => 'customer@marketlink.test'],
            ['name' => 'Sarah Jenkins', 'role' => 'customer', 'phone' => '555-0177', 'password' => 'password'],
        );
    }
}
