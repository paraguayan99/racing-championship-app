<?php
namespace App\Helpers;

class CompetitionHelper
{
    // Teste le type dans la table season et category
    public static function resolveType(?string $seasonType, ?string $categoryType): string
    {
        return $seasonType ?? $categoryType ?? 'grands_prix';
    }

    // Tous les labels utilisables dans les VIEWS
    public static function label(string $type): string
    {
        return $type === 'courses' ? 'Courses' : 'Grands Prix';
    }

    public static function labelSingular(string $type): string
    {
        return $type === 'courses' ? 'Course' : 'Grand Prix';
    }

    public static function labelSubtitle(string $type): string
    {
        return $type === 'courses' ? 'la Course' : 'le GP';
    }

    public static function labelModalGpDetails(string $type): string
    {
        return $type === 'courses' ? 'Course' : 'GP';
    }

    public static function labelLong(string $type): string
    {
        return $type === 'courses' ? 'Courses' : 'GP';
    }

    public static function labelMedium(string $type): string
    {
        return $type === 'courses' ? 'Cou' : 'GP';
    }

    public static function labelShort(string $type): string
    {
        return $type === 'courses' ? 'Co' : 'GP';
    }

    public static function labelVeryShort(string $type): string
    {
        return $type === 'courses' ? 'C' : 'GP';
    }
}