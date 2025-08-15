<?php
return [
    'permissions' => [
        'groupOwner' => [
            'group-role' => [
                ["group-role.create", "Create Group Role", "Create a new role within the group"],
                ["group-role.update", "Update Group Role", "Update an existing role within the group"],
                ["group-role.delete", "Delete Group Role", "Delete a role within the group"],
                ["group-role.read", "Read Group Role", "View roles within the group"],
                ["group.update", "Update Group", "Update group details"],
                ["group.delete", "Delete Group", "Delete the group"],
                ["group.read", "Read Group", "View group details"],
            ],
            'hospital' => [
                ["hospital.create", "Create Hospital", "Create a new hospital within the group"],
                ["hospital.update", "Update Hospital", "Update an existing hospital within the group"],
                ["hospital.delete", "Delete Hospital", "Delete a hospital within the group"],
                ["hospital.read", "Read Hospital", "View hospitals within the group"],
            ],
            'doctor' => [
                ["doctor.create", "Create Doctor", "Create a new doctor within the group"],
                ["doctor.update", "Update Doctor", "Update an existing doctor within the group"],
                ["doctor.delete", "Delete Doctor", "Delete a doctor within the group"],
                ["doctor.read", "Read Doctor", "View doctors within the group"],
            ],
            'tag' => [
                ["tag.create", "Create Tag", "Create a new tag within the group"],
                ["tag.update", "Update Tag", "Update an existing tag within the group"],
                ["tag.delete", "Delete Tag", "Delete a tag within the group"],
                ["tag.read", "Read Tag", "View tags within the group"],
            ],
            'record-type' => [
                ["record-type.create", "Create Record Type", "Create a new record type within the group"],
                ["record-type.update", "Update Record Type", "Update an existing record type within the group"],
                ["record-type.delete", "Delete Record Type", "Delete a record type within the group"],
                ["record-type.read", "Read Record Type", "View record types within the group"],
            ],
            'invite-link' => [
                ["invite-link.read", "Read Invite Link", "View invite links within the group"],
                ["invite-link.create", "Create Invite Link", "Create a new invite link for the group"],
                ["invite-link.delete", "Delete Invite Link", "Delete an existing invite link for the group"],
            ],
            'record-link' => [
                ["record-link.create", "Create Record Link", "Create a new link to a record within the group"],
                ["record-link.delete", "Delete Record Link", "Delete an existing link to a record within the group"],
                ["record-link.read", "Read Record Link", "View links to records within the group"],
            ],
            'permission' => [
                ["permission.read", "Read Permission", "View permissions within the group"],
                ["permission.create", "Create Permission", "Assign permissions to roles within the group"],
            ],
            'user-group-role' => [
                ["user-group-role.create", "Assign Role To Users", "Assign a role to a user within the group"],
            ],
            'group-profile' => [
                ["group-profile.read", "Read Group Profile", "View the group's profile image"],
            ],
            'group' => [
                ["group.read", "Read Group", "View group details"],
                ["group.delete", "Delete Group", "Delete the group"],
            ],
            'user-group-permission' => [
                ["user-group-permission.read", "Read User Group Permission", "View permissions assigned to users in the group"],
            ],
        ],
        'groupMember' => [
            'user-group-permission' => [
                ["user-group-permission.read", "Read User Group Permission", "View permissions assigned to users in the group", false],
            ],
            'group-role' => [
                ["group-role.read", "Read Group Role", "View roles within the group", $isDeletable = false],
            ],
            'hospital' => [
                ["hospital.read", "Read Hospital", "View hospitals within the group", false],
            ],
            'doctor' => [
                ["doctor.read", "Read Doctor", "View doctors within the group", false],
            ],
            'tag' => [
                ["tag.read", "Read Tag", "View tags within the group", false],
            ],
            'record-type' => [
                ["record-type.read", "Read Record Type", "View record types within the group", false],
            ],
            'invite-link' => [
                ["invite-link.read", "Read Invite Link", "View invite links within the group", false],
            ],
            'record-link' => [
                ["record-link.read", "Read Record Link", "View links to records within the group", false],
            ],
            'group-profile' => [
                ["group-profile.read", "Read Group Profile", "View the group's profile image", false],
            ],
            'group' => [
                ["group.read", "Read Group", "View group details", false],
            ]
        ],
    ],
    'blood-types' => [
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-'
    ]
];