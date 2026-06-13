<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterItem;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeforeAfterItemController extends Controller
{
    public function index(): RedirectResponse
    {
        $page = Page::query()->where('slug', 'pirms-pec')->first();

        return $page
            ? redirect()->to(route('admin.pages.edit', $page) . '#before-after-items')
            : redirect()->route('admin.pages.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $item = new BeforeAfterItem([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        if ($request->hasFile('image')) {
            $item->image = $request->file('image')->store('before-after', 'public');
        }

        $item->save();

        return $this->redirectToBeforeAfterEditor('Before/after item created successfully.');
    }

    public function update(Request $request, BeforeAfterItem $beforeAfterItem): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $payload = [];

        foreach (['title', 'description', 'sort_order'] as $field) {
            if ($request->exists($field)) {
                $payload[$field] = $validated[$field] ?? null;
            }
        }

        if ($request->exists('is_active')) {
            $payload['is_active'] = (bool) ($validated['is_active'] ?? false);
        }

        foreach (['image'] as $field) {
            if ($request->hasFile($field)) {
                $existingPath = $beforeAfterItem->{$field};

                if (is_string($existingPath) && str_starts_with($existingPath, 'before-after/')) {
                    Storage::disk('public')->delete($existingPath);
                }

                $payload[$field] = $request->file($field)->store('before-after', 'public');
            }
        }

        $beforeAfterItem->update($payload);

        return $this->redirectToBeforeAfterEditor('Before/after item updated successfully.');
    }

    public function destroy(BeforeAfterItem $beforeAfterItem): RedirectResponse
    {
        foreach (['image'] as $field) {
            $value = $beforeAfterItem->{$field};

            if (is_string($value) && str_starts_with($value, 'before-after/')) {
                Storage::disk('public')->delete($value);
            }
        }

        $beforeAfterItem->delete();

        return $this->redirectToBeforeAfterEditor('Before/after item deleted successfully.');
    }

    private function redirectToBeforeAfterEditor(string $status): RedirectResponse
    {
        $page = Page::query()->where('slug', 'pirms-pec')->first();

        if (! $page) {
            return redirect()->route('admin.pages.index')->with('status', $status);
        }

        return redirect()->to(route('admin.pages.edit', $page) . '#before-after-items')->with('status', $status);
    }
}
