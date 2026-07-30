<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = \App\Models\User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($this->adminUser);
    }

    public function test_it_can_display_the_partners_list_page()
    {
        $partner = Partner::create([
            'name' => 'Tech Partner',
            'logo_url' => 'partners/logo.png'
        ]);

        $response = $this->get(route('admin.partners.index'));

        $response->assertStatus(200);
        $response->assertSee('Tech Partner');
        $response->assertSee('ID');
        $response->assertSee('Logo');
    }

    public function test_it_can_display_the_create_partner_page()
    {
        $response = $this->get(route('admin.partners.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Partner');
        $response->assertSee('Nama Partner');
    }

    public function test_it_can_store_a_new_partner_with_logo()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('partner_logo.png');

        $response = $this->post(route('admin.partners.store'), [
            'name' => 'Innovate Inc',
            'logo' => $logo
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil ditambahkan');

        // Get the latest partner to verify path
        $partner = Partner::latest()->first();
        $this->assertNotNull($partner->logo_url);
        
        // Assert file exists in fake public storage
        Storage::disk('public')->assertExists($partner->logo_url);

        $this->assertDatabaseHas('partners', [
            'name' => 'Innovate Inc',
            'logo_url' => $partner->logo_url
        ]);
    }

    public function test_it_validates_required_fields_on_store()
    {
        $response = $this->post(route('admin.partners.store'), [
            'name' => '',
            'logo' => null
        ]);

        $response->assertSessionHasErrors(['name', 'logo']);
        $this->assertDatabaseEmpty('partners');
    }

    public function test_it_validates_unique_name_on_store()
    {
        Partner::create([
            'name' => 'UniqPartner',
            'logo_url' => 'partners/logo.png'
        ]);

        $response = $this->post(route('admin.partners.store'), [
            'name' => 'UniqPartner',
            'logo' => UploadedFile::fake()->image('logo.png')
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_can_display_the_edit_partner_page()
    {
        $partner = Partner::create([
            'name' => 'Design Group',
            'logo_url' => 'partners/design.png'
        ]);

        $response = $this->get(route('admin.partners.edit', $partner->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Partner');
        $response->assertSee('Design Group');
    }

    public function test_it_can_update_a_partner_name_only()
    {
        $partner = Partner::create([
            'name' => 'Old Name',
            'logo_url' => 'partners/logo.png'
        ]);

        $response = $this->put(route('admin.partners.update', $partner->id), [
            'name' => 'Updated Name'
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil diperbarui');

        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Updated Name',
            'logo_url' => 'partners/logo.png'
        ]);
    }

    public function test_it_can_update_a_partner_with_new_logo_and_deletes_old_logo()
    {
        Storage::fake('public');

        // Put an initial file in storage
        $oldLogoPath = 'partners/old_logo.png';
        Storage::disk('public')->put($oldLogoPath, 'dummy contents');

        $partner = Partner::create([
            'name' => 'Tech Corp',
            'logo_url' => $oldLogoPath
        ]);

        $newLogo = UploadedFile::fake()->image('new_logo.png');

        $response = $this->put(route('admin.partners.update', $partner->id), [
            'name' => 'Tech Corp Inc',
            'logo' => $newLogo
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        
        $partner->refresh();
        
        // Assert old file was deleted
        Storage::disk('public')->assertMissing($oldLogoPath);
        // Assert new file exists
        Storage::disk('public')->assertExists($partner->logo_url);
        
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Tech Corp Inc',
            'logo_url' => $partner->logo_url
        ]);
    }

    public function test_it_can_delete_a_partner_and_its_logo()
    {
        Storage::fake('public');

        $logoPath = 'partners/logo_to_delete.png';
        Storage::disk('public')->put($logoPath, 'dummy contents');

        $partner = Partner::create([
            'name' => 'To Be Deleted',
            'logo_url' => $logoPath
        ]);

        $response = $this->delete(route('admin.partners.destroy', $partner->id));

        $response->assertRedirect(route('admin.partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil dihapus');

        // Assert file was deleted
        Storage::disk('public')->assertMissing($logoPath);

        $this->assertDatabaseMissing('partners', [
            'id' => $partner->id
        ]);
    }

    public function test_it_can_filter_partners_by_search_term()
    {
        Partner::create(['name' => 'Google Indonesia', 'logo_url' => 'logo.png']);
        Partner::create(['name' => 'Microsoft Indonesia', 'logo_url' => 'logo.png']);

        $response = $this->get(route('admin.partners.index', ['search' => 'Google']));

        $response->assertStatus(200);
        $response->assertSee('Google Indonesia');
        $response->assertDontSee('Microsoft Indonesia');
    }
}
