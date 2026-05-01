<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'description',
    'location',
    'starts_at',
    'ends_at',
    'is_published',
])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Generate a unique URL-friendly slug from the event title.
     *
     * For example "Spring Market" becomes "spring-market".
     * If that slug is already taken, we try "spring-market-1", "spring-market-2", etc.
     *
     * The $excludeEventId parameter lets us skip the current event's own row
     * when checking for duplicates during an update (otherwise an event would
     * always collide with itself).
     */
    public static function generateUniqueSlugFromTitle(string $title, ?int $excludeEventId = null): string
    {
        // Convert the title to a URL-safe string, e.g. "Spring Market!" → "spring-market"
        $baseSlug = Str::slug($title);

        // If the title produced an empty slug (e.g. only special characters), use a safe fallback
        if ($baseSlug === '') {
            $baseSlug = 'event';
        }

        // Start by trying the plain slug with no number suffix
        $currentSlug = $baseSlug;
        $duplicateCounter = 1;

        // Keep trying slugs until we find one that is not already in the database
        while (true) {
            $slugAlreadyExists = static::query()
                ->where('slug', $currentSlug)
                ->when($excludeEventId !== null, function ($query) use ($excludeEventId) {
                    // Ignore the event we are currently editing so it does not block itself
                    $query->where('id', '!=', $excludeEventId);
                })
                ->exists();

            if (! $slugAlreadyExists) {
                // This slug is available — stop searching
                break;
            }

            // Slug is taken — append a number and try again (e.g. "spring-market-1")
            $currentSlug = $baseSlug . '-' . $duplicateCounter;
            $duplicateCounter++;
        }

        return $currentSlug;
    }
}
