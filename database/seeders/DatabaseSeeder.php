<?php

namespace Database\Seeders;

use App\Models\Permission;
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

        Permission::insert([
            ['slug' => 'group.read', 'name' => 'Read Group', 'group_id' => $group->id],
            ['slug' => 'group.create', 'name' => 'Create Group', 'group_id' => $group->id],
            ['slug' => 'group.update', 'name' => 'Update Group', 'group_id' => $group->id],
            ['slug' => 'group.delete', 'name' => 'Delete Group', 'group_id' => $group->id],
            ['slug' => 'tag.read', 'name' => 'Read Tag', 'group_id' => $group->id],
            ['slug' => 'tag.create', 'name' => 'Create Tag', 'group_id' => $group->id],
            ['slug' => 'tag.update', 'name' => 'Update Tag', 'group_id' => $group->id],
            ['slug' => 'tag.delete', 'name' => 'Delete Tag', 'group_id' => $group->id],
            ['slug' => 'record-type.create', 'name' => 'Create Record Type', 'group_id' => $group->id],
            ['slug' => 'record-type.update', 'name' => 'Update Record Type', 'group_id' => $group->id],
            ['slug' => 'record-type.delete', 'name' => 'Delete Record Type', 'group_id' => $group->id],
            ['slug' => 'record-type.read', 'name' => 'Read Record Type', 'group_id' => $group->id],
            ['slug' => 'record-link.create', 'name' => 'Create Record Link', 'group_id' => $group->id],
            ['slug' => 'record-link.delete', 'name' => 'Delete Record Link', 'group_id' => $group->id],
            ['slug' => 'record-link.read', 'name' => 'Read Record Link', 'group_id' => $group->id],
            ['slug' => 'invite-link.create', 'name' => 'Create Invite Link', 'group_id' => $group->id],
            ['slug' => 'invite-link.delete', 'name' => 'Delete Invite Link', 'group_id' => $group->id],
            ['slug' => 'invite-link.read', 'name' => 'Read Invite Link', 'group_id' => $group->id],
            ['slug' => 'hospital.create', 'name' => 'Create Hospital', 'group_id' => $group->id],
            ['slug' => 'hospital.update', 'name' => 'Update Hospital', 'group_id' => $group->id],
            ['slug' => 'hospital.delete', 'name' => 'Delete Hospital', 'group_id' => $group->id],
            ['slug' => 'hospital.read', 'name' => 'Read Hospital', 'group_id' => $group->id],
        ]);

        foreach (Permission::all() as $permission) {
            $role->permissions()->attach($permission->id);
        }

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
