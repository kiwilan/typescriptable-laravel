<?php

namespace Kiwilan\TypeableModels\Services\TypeableService\Utils;

use Kiwilan\TypeableModels\Services\TypeableService\TypeableProperty;

class TypeableTeam
{
    /**
     * @return TypeableProperty[]
     */
    public static function setUserFakeTeam(): array
    {
        $interface = [
            'two_factor_enabled' => 'boolean',
            'all_teams' => 'Team[]',
            'current_team_id' => 'number',
            'profile_photo_url' => 'string',
            'current_team' => 'Team',
            'teams' => 'Team[]',
        ];

        $properties = [];

        foreach ($interface as $field => $type) {
            $properties[$field] = TypeableProperty::make('users', new TypeableDbColumn($field, $type), true);
        }

        return $properties;
    }

    /**
     * @return TypeableProperty[]
     */
    public static function setFakeTeam()
    {
        $interface = [
            'name' => 'string',
            'owner_id' => 'number',
            'personal_team' => 'boolean',
            'created_at' => 'string',
            'updated_at' => 'string',
            'id' => 'number',
        ];

        $properties = [];

        foreach ($interface as $field => $type) {
            $properties[$field] = TypeableProperty::make('teams', new TypeableDbColumn($field, $type), true);
        }

        return $properties;
    }
}
