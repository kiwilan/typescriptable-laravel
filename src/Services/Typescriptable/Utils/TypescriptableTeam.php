<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Utils;

use Kiwilan\Typescriptable\Services\Typescriptable\TypescriptableProperty;

class TypescriptableTeam
{
    /**
     * @return TypescriptableProperty[]
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
            $properties[$field] = TypescriptableProperty::make('users', new TypescriptableDbColumn($field, $type), true);
        }

        return $properties;
    }

    /**
     * @return TypescriptableProperty[]
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
            $properties[$field] = TypescriptableProperty::make('teams', new TypescriptableDbColumn($field, $type), true);
        }

        return $properties;
    }
}
