<?php

namespace Database\Seeders;

use App\Models\Record;
use App\Models\RecordType;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupRole;
use App\Models\Tag;
use App\Models\Doctor;
use App\Models\Hospital;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'phone' => '1234567890',
            'email' => 'mint@gmail.com',
            'password' => '123456789',
            'username' => 'mint'
        ]);

        $user1 = User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'phone' => '1234567890',
            'email' => 'mint1@gmail.com',
            'password' => '123456789',
            'username' => 'mintos'
        ]);

        $group = Group::create([
            'name' => 'Test Group',
            'description' => 'This is a test group',
        ]);

        $role = GroupRole::create([
            'name' => 'Admin',
            'group_id' => $group->id,
        ]);

        Tag::create([
            'group_id' => $group->id,
            'name' => 'Test Tag',
            'color' => '#FF5733',
        ]);

        Tag::create([
            'group_id' => $group->id,
            'name' => 'Test Tag 2',
            'color' => '#FF5733',
        ]);

        $user->groups()->attach($group->id, ['role_id' => $role->id]);

        $role->permissions()->createMany([
            ['name' => 'group.read'],
            ['name' => 'group.create'],
            ['name' => 'group.update'],
            ['name' => 'group.delete'],
            ['name' => 'tag.read'],
            ['name' => 'tag.create'],
            ['name' => 'tag.update'],
            ['name' => 'tag.delete'],
            ['name' => 'record-type.create'],
            ['name' => 'record-type.update'],
            ['name' => 'record-type.delete'],
            ['name' => 'record-type.read'],
            ['name' => 'record-link.create'],
            ['name' => 'record-link.delete'],
            ['name' => 'record-link.read'],
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Smith',
            'group_id' => $group->id
        ]);

        $hospital = Hospital::create([
            'name' => 'Test Hospital',
            'group_id' => $group->id
        ]);

        $recordType = RecordType::create([
            'name' => 'Test Record Type',
            'group_id' => $group->id
        ]);

        Record::create([
            'group_id' => $group->id,
            'records_type_id' => $recordType->id,
            'hospital_id' => $hospital->id,
            'doctor_id' => $doctor->id,
            'name' => 'Test Record',
            'note' => 'This is a test record',
            'visit_date' => now(),
            'next_visit_date' => now()->addDays(30),
        ]);
    }
}
