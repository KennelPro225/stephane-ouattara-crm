<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ContentSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_survive_a_cache_flush(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.content.update'), [
            'hero_title' => 'Titre personnalisé de test',
            'hero_subtitle' => 'Sous-titre personnalisé de test',
            'about_text' => 'Texte à propos personnalisé.',
            'contact_email' => 'nouveau@stephane-ouattara.com',
            'contact_phone' => '+225 01 02 03 04',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', ['key' => 'hero_title', 'value' => 'Titre personnalisé de test']);

        Cache::flush();

        $this->assertSame('Titre personnalisé de test', setting('hero_title'));

        $response = $this->get('/');
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->where('settings.hero_title', 'Titre personnalisé de test'));
    }
}
