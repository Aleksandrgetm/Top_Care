<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('site_pages.pages', []) as $slug => $definition) {
            $page = Page::firstOrNew(['slug' => $slug]);
            $page->title = $definition['title'];
            $page->is_active = true;

            if (empty($page->content)) {
                $page->content = $definition['defaults'] ?? [];
            }

            $page->save();
        }
    }
}
