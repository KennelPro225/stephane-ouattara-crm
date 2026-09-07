<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryItem;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guests_cannot_add_or_delete_content(): void
    {
        $testimonial = Testimonial::create(['name' => 'A', 'quote' => 'B']);
        $gallery = GalleryItem::create(['title' => 'C']);

        $this->post(route('admin.testimonials.store'), ['name' => 'X', 'quote' => 'Y'])->assertRedirect(route('login'));
        $this->delete(route('admin.testimonials.destroy', $testimonial))->assertRedirect(route('login'));
        $this->post(route('admin.gallery.store'), ['title' => 'X'])->assertRedirect(route('login'));
        $this->delete(route('admin.gallery.destroy', $gallery))->assertRedirect(route('login'));

        $this->assertDatabaseCount('testimonials', 1);
        $this->assertDatabaseCount('gallery_items', 1);
    }

    public function test_admin_can_add_a_testimonial(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.testimonials.store'), [
                'name' => 'Aminata K.',
                'role' => "Parent d'élève, Abidjan",
                'quote' => 'Une transformation en trois mois.',
                'featured' => true,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Aminata K.',
            'quote' => 'Une transformation en trois mois.',
            'featured' => true,
        ]);
    }

    public function test_a_new_testimonial_lands_at_the_end_of_the_list(): void
    {
        Testimonial::create(['name' => 'Premier', 'quote' => 'x', 'sort_order' => 4]);

        $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'name' => 'Dernier', 'quote' => 'y',
        ])->assertSessionHasNoErrors();

        $this->assertSame(5, Testimonial::where('name', 'Dernier')->value('sort_order'));
    }

    public function test_a_testimonial_needs_a_name_and_a_quote(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.testimonials.store'), ['role' => 'Parent'])
            ->assertSessionHasErrors(['name', 'quote']);

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_admin_can_delete_a_testimonial(): void
    {
        $testimonial = Testimonial::create(['name' => 'À retirer', 'quote' => 'x']);

        $this->actingAs($this->admin)
            ->delete(route('admin.testimonials.destroy', $testimonial))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_admin_can_add_a_gallery_image(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.gallery.store'), [
                'title' => 'Teambuilding',
                'subtitle' => "Cohésion d'équipe",
                'image' => UploadedFile::fake()->image('galerie.jpg'),
            ])
            ->assertSessionHasNoErrors();

        $item = GalleryItem::firstWhere('title', 'Teambuilding');

        $this->assertNotNull($item);
        $this->assertNotNull($item->image_path);
        Storage::disk('public')->assertExists($item->image_path);
    }

    /** What is added must come back to the screen that manages it. */
    public function test_the_content_screen_lists_what_was_just_added(): void
    {
        $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'name' => 'Nouveau témoin', 'quote' => 'Un retour tout frais.',
        ]);
        $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Nouvelle photo', 'image' => UploadedFile::fake()->image('x.jpg'),
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.content.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                // false: assert the component name only — resolving the .vue
                // file on disk needs an inertia page-path config this app has no
                // other reason to carry.
                ->component('Admin/Contenus/Index', false)
                ->where('testimonials.0.name', 'Nouveau témoin')
                ->where('gallery.0.title', 'Nouvelle photo')
                ->whereNot('gallery.0.image_url', null)
            );
    }

    public function test_a_gallery_item_cannot_be_created_without_an_image(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.gallery.store'), ['title' => 'Sans visuel'])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('gallery_items', 0);
    }

    public function test_deleting_a_gallery_item_also_removes_its_file(): void
    {
        $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'À retirer',
            'image' => UploadedFile::fake()->image('vieux.jpg'),
        ]);

        $item = GalleryItem::firstWhere('title', 'À retirer');
        $path = $item->image_path;
        Storage::disk('public')->assertExists($path);

        $this->actingAs($this->admin)
            ->delete(route('admin.gallery.destroy', $item))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('gallery_items', 0);
        Storage::disk('public')->assertMissing($path);
    }
}
