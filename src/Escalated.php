<?php

namespace Escalated\Laravel;

class Escalated
{
    /**
     * The user model class.
     */
    public static string $userModel = 'App\\Models\\User';

    /**
     * The user display column.
     */
    public static string $userDisplayColumn = 'name';

    /**
     * Set the user model class.
     */
    public static function useUserModel(string $model): void
    {
        static::$userModel = $model;
    }

    /**
     * Get the user model class.
     */
    public static function userModel(): string
    {
        return config('escalated.user_model', static::$userModel);
    }

    /**
     * Create a new user model instance.
     */
    public static function newUserModel(): mixed
    {
        $model = static::userModel();

        return new $model;
    }

    /**
     * Set the user display column.
     */
    public static function useUserDisplayColumn(string $column): void
    {
        static::$userDisplayColumn = $column;
    }

    /**
     * Get the user display column.
     */
    public static function userDisplayColumn(): string
    {
        return config('escalated.user_display_column', static::$userDisplayColumn);
    }

    /**
     * Get users as id => display_column array for select options.
     */
    public static function userOptions(): array
    {
        $model = static::userModel();
        $column = static::userDisplayColumn();

        return $model::pluck($column, 'id')->toArray();
    }

    /**
     * Get the table prefix.
     */
    public static function tablePrefix(): string
    {
        return config('escalated.table_prefix', 'escalated_');
    }

    /**
     * Get the prefixed table name.
     */
    public static function table(string $name): string
    {
        return static::tablePrefix().$name;
    }
}
