<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ContentSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_survive_a_cache_flush_and_reach_the_public_home_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.content.update'), [
            'hero_title' => 'Titre personnalisé de test',
            'tagline' => 'Tagline de test',
            'cta_title' => 'CTA de test',
            'cta_text' => 'Texte CTA de test',
            'contact_email' => 'nouveau@stephane-ouattara.com',
            'contact_phone' => '+225 01 02 03 04',
            'contact_hours' => 'Lun-Ven',
            'contact_hours_2' => 'Sam sur RDV',
            'contact_address' => 'Abidjan',
            'contact_coverage' => 'CI',
            'footer_bio' => 'Bio de test',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', ['key' => 'hero_title', 'value' => 'Titre personnalisé de test']);

        Cache::flush();

        $this->assertSame('Titre personnalisé de test', setting('hero_title'));

        $response = $this->get('/');
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->where('content.heroTitle', 'Titre personnalisé de test'));
    }
}
