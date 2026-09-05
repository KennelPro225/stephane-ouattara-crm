<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryItem;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function programmePayload(): array
    {
        return [
            'title' => 'Programme Test', 'audience' => 'adultes', 'type' => 'group',
            'description' => 'Description de test.', 'price_label' => '10 000 FCFA',
            'start_date' => now()->addMonth()->toDateString(), 'end_date' => now()->addMonths(2)->toDateString(),
            'duration_label' => '4 séances', 'ages_label' => 'Adultes',
            'max_participants' => 20, 'status' => 'published',
        ];
    }

    public function test_admin_can_upload_a_programme_image_on_create(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.programmes.store'), $this->programmePayload() + [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertRedirect(route('admin.programmes.index'));
        $programme = Programme::where('title', 'Programme Test')->firstOrFail();
        $this->assertNotNull($programme->image_path);
        Storage::disk('public')->assertExists($programme->image_path);
        $this->assertNotNull($programme->image_url);
    }

    public function test_replacing_a_programme_image_deletes_the_old_one(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $programme = Programme::create($this->programmePayload() + ['created_by' => $admin->id]);
        $programme->update(['image_path' => 'programmes/old.jpg']);
        Storage::disk('public')->put('programmes/old.jpg', 'fake');

        $this->actingAs($admin)->put(route('admin.programmes.update', $programme), $this->programmePayload() + [
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

        Storage::disk('public')->assertMissing('programmes/old.jpg');
        $this->assertNotSame('programmes/old.jpg', $programme->fresh()->image_path);
    }

    public function test_admin_can_upload_hero_and_coach_images(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.content.images'), [
            'hero_image' => UploadedFile::fake()->image('hero.jpg'),
            'coach_image' => UploadedFile::fake()->image('coach.jpg'),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertNotNull(setting('hero_image_path'));
        $this->assertNotNull(setting('coach_image_path'));
        Storage::disk('public')->assertExists(setting('hero_image_path'));

        $home = $this->get('/');
        $home->assertInertia(fn ($page) => $page->where('content.heroImage', fn ($url) => ! empty($url)));
    }

    public function test_admin_can_replace_a_gallery_item_image(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $gallery = GalleryItem::create(['title' => 'Atelier', 'sort_order' => 0]);

        $response = $this->actingAs($admin)->put(route('admin.gallery.update', $gallery), [
            'title' => 'Atelier mis à jour',
            'subtitle' => 'Nouvelle légende',
            'image' => UploadedFile::fake()->image('gallery.jpg'),
        ]);

        $response->assertSessionHasNoErrors();
        $gallery->refresh();
        $this->assertSame('Atelier mis à jour', $gallery->title);
        $this->assertNotNull($gallery->image_path);
        Storage::disk('public')->assertExists($gallery->image_path);
    }

    public function test_guests_cannot_upload_images(): void
    {
        $this->post(route('admin.content.images'), [])->assertRedirect(route('login'));
    }
}
