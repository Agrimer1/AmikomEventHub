<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_it_can_display_the_categories_list_page()
    {
        $category = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development'
        ]);

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Web Development');
        $response->assertSee('web-development');
        $response->assertSee('ID');
        $response->assertSee('Created At');
    }

    public function test_it_can_display_the_create_category_page()
    {
        $response = $this->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Kategori');
        $response->assertSee('Nama Kategori');
    }

    public function test_it_can_store_a_new_category()
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Data Science'
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan');

        $this->assertDatabaseHas('categories', [
            'name' => 'Data Science',
            'slug' => 'data-science'
        ]);
    }

    public function test_it_validates_required_name_on_store()
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => ''
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseEmpty('categories');
    }

    public function test_it_validates_unique_name_on_store()
    {
        Category::create([
            'name' => 'Design',
            'slug' => 'design'
        ]);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Design'
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_it_can_display_the_edit_category_page()
    {
        $category = Category::create([
            'name' => 'Design',
            'slug' => 'design'
        ]);

        $response = $this->get(route('admin.categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Kategori');
        $response->assertSee('Design');
    }

    public function test_it_can_update_a_category()
    {
        $category = Category::create([
            'name' => 'UI Design',
            'slug' => 'ui-design'
        ]);

        $response = $this->put(route('admin.categories.update', $category->id), [
            'name' => 'UI/UX Design'
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Kategori berhasil diperbarui');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'UI/UX Design',
            'slug' => 'uiux-design'
        ]);
    }

    public function test_it_can_delete_a_category()
    {
        $category = Category::create([
            'name' => 'Mobile Development',
            'slug' => 'mobile-development'
        ]);

        $response = $this->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Kategori berhasil dihapus');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_it_can_filter_categories_by_search_term()
    {
        Category::create(['name' => 'Web Design', 'slug' => 'web-design']);
        Category::create(['name' => 'Mobile Development', 'slug' => 'mobile-development']);

        $response = $this->get(route('admin.categories.index', ['search' => 'Design']));

        $response->assertStatus(200);
        $response->assertSee('Web Design');
        $response->assertDontSee('Mobile Development');
    }
}
