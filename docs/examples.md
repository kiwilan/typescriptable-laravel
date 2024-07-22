# Examples

## Eloquent Models

An example of Eloquent model.

```php
<?php

namespace App\Models;

use Kiwilan\Steward\Enums\PublishStatusEnum;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'abstract',
        'original_link',
        'picture',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $appends = [
        'time_to_read',
    ];

    protected $withCount = [
        'chapters',
    ];

    protected $casts = [
        'status' => PublishStatusEnum::class,
        'published_at' => 'datetime:Y-m-d',
    ];

    public function getTimeToReadAttribute(): int

    public function chapters(): HasMany

    public function category(): BelongsTo

    public function author(): BelongsTo

    public function tags(): BelongsToMany
}
```

TS file generated at `resources/js/types-eloquent.d.ts`

```typescript
declare namespace App {
    declare namespace Models {
        export type Story = {
            id: number;
            title: string;
            slug?: string;
            abstract?: string;
            original_link?: string;
            picture?: string;
            status: "draft" | "scheduled" | "published";
            published_at?: Date;
            meta_title?: string;
            meta_description?: string;
            created_at?: Date;
            updated_at?: Date;
            author_id?: number;
            category_id?: number;
            time_to_read?: number;
            chapters?: Chapter[];
            category?: Category;
            author?: Author;
            tags?: Tag[];
            chapters_count?: number;
            tags_count?: number;
        };
    }
    // With `paginate` option.
    export type PaginateLink = {
        url: string;
        label: string;
        active: boolean;
    };
    export type Paginate<T = any> = {
        data: T[];
        current_page: number;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        links: App.PaginateLink[];
        next_page_url: string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: number;
        total: number;
    };
}
```

## Vue component

Usage in Vue component.

```vue
<script lang="ts" setup>
defineProps<{
    story?: App.Models.Story;
}>();
</script>
```

Or with paginate option.

```vue
<script lang="ts" setup>
defineProps<{
    stories?: App.Paginate<App.Models.Story>;
}>();
</script>
```
