<?php

namespace Database\Seeders;

use App\Enums\RoleType;
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

        $group1 = Group::create([
            'name' => 'Group 2',
            'description' => 'This is a test group 2',
        ]);

        $role = GroupRole::create([
            'name' => 'Owner',
            'group_id' => $group->id,
            'type' => RoleType::OWNER
        ]);

        $role1 = GroupRole::create([
            'name' => 'Member',
            'group_id' => $group->id,
            'type' => RoleType::MEMBER
        ]);

        $role2 = GroupRole::create([
            'name' => 'Observer',
            'group_id' => $group1->id,
            'type' => RoleType::CUSTOM
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
        $user1->groups()->attach($group1->id, ['role_id' => $role2->id]);

        foreach (config('default.permissions') as $permissionPrefix => $permissions) {
            foreach ($permissions as $permission) {
                $tempPermission = Permission::create([
                    'slug' => $permission[0],
                    'name' => $permission[1],
                    'description' => $permission[2],
                    'kind' => $permissionPrefix
                ]);

                $role->permissions()->attach($tempPermission->id);
            }
        }

        $role1->permissions()->attach(Permission::whereIn('slug', [
            'hospital.read',
            'doctor.read',
            'record-type.read',
            'tag.read',
            'invite-link.read',
            'record-link.read',
            'group-user.read',
        ])->pluck('id')->toArray());

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
