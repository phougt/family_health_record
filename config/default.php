<?php
return [
    'permissions' => [
            'Role' => [
                ["group-role.create", "Create Group Role", "Create a new role within the group"],
                ["group-role.update", "Update Group Role", "Update an existing role within the group"],
                ["group-role.delete", "Delete Group Role", "Delete a role within the group"],
                ["group-role.read", "Read Group Role", "View roles within the group"],
                ["user-group-role.create", "Assign Role To Users", "Assign a role to a user within the group"],
            ],
            'Hospital' => [
                ["hospital.create", "Create Hospital", "Create a new hospital within the group"],
                ["hospital.update", "Update Hospital", "Update an existing hospital within the group"],
                ["hospital.delete", "Delete Hospital", "Delete a hospital within the group"],
                ["hospital.read", "Read Hospital", "View hospitals within the group"],
            ],
            'Doctor' => [
                ["doctor.create", "Create Doctor", "Create a new doctor within the group"],
                ["doctor.update", "Update Doctor", "Update an existing doctor within the group"],
                ["doctor.delete", "Delete Doctor", "Delete a doctor within the group"],
                ["doctor.read", "Read Doctor", "View doctors within the group"],
            ],
            'Tag' => [
                ["tag.create", "Create Tag", "Create a new tag within the group"],
                ["tag.update", "Update Tag", "Update an existing tag within the group"],
                ["tag.delete", "Delete Tag", "Delete a tag within the group"],
                ["tag.read", "Read Tag", "View tags within the group"],
            ],
            'Record Type' => [
                ["record-type.create", "Create Record Type", "Create a new record type within the group"],
                ["record-type.update", "Update Record Type", "Update an existing record type within the group"],
                ["record-type.delete", "Delete Record Type", "Delete a record type within the group"],
                ["record-type.read", "Read Record Type", "View record types within the group"],
            ],
            'Invite Link' => [
                ["invite-link.read", "Read Invite Link", "View invite links within the group"],
                ["invite-link.create", "Create Invite Link", "Create a new invite link for the group"],
                ["invite-link.delete", "Delete Invite Link", "Delete an existing invite link for the group"],
            ],
            'Record Link' => [
                ["record-link.create", "Create Record Link", "Create a new link to a record within the group"],
                ["record-link.delete", "Delete Record Link", "Delete an existing link to a record within the group"],
                ["record-link.read", "Read Record Link", "View links to records within the group"],
            ],
            'Group' => [
                ["group.update", "Update Group", "Update group information"],
                ["group-user.delete", "Delete Group User", "Remove a user from the group"],
                ["group-user.read", "Read Group User", "View users in the group"],
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