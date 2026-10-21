<?php

namespace Tests\Feature;

use App\Models\Troubleshoot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_troubleshoot_can_store_multiple_photos_and_a_video(): void
    {
        Storage::fake('public');

        $response = $this->post(route('troubleshoot.store'), [
            'judul_masalah' => 'Video dan foto test',
            'kategori' => 'Testing',
            'gejala' => 'Ada pesan error',
            'solusi' => 'Ikuti video panduan',
            'fotos' => array_map(
                fn ($number) => UploadedFile::fake()->create("layar-{$number}.jpg", 20, 'image/jpeg'),
                range(1, 8)
            ),
            'video' => UploadedFile::fake()->create('panduan.mp4', 100, 'video/mp4'),
        ]);

        $response->assertRedirect(route('troubleshoot.index'));

        $troubleshoot = Troubleshoot::query()->firstOrFail();

        $this->assertCount(8, $troubleshoot->fotos);
        $this->assertNotNull($troubleshoot->video);

        foreach ($troubleshoot->fotos as $photo) {
            $this->assertTrue(Storage::disk('public')->exists($photo));
        }

        $this->assertTrue(Storage::disk('public')->exists($troubleshoot->video));
    }

    public function test_a_troubleshoot_can_be_created_with_only_category_and_note(): void
    {
        $response = $this->post(route('troubleshoot.store'), [
            'kategori' => 'Jaringan',
            'catatan' => 'Periksa koneksi router.',
        ]);

        $response->assertRedirect(route('troubleshoot.index'));

        $troubleshoot = Troubleshoot::query()->firstOrFail();

        $this->assertSame('Jaringan', $troubleshoot->judul_masalah);
        $this->assertSame('Periksa koneksi router.', $troubleshoot->catatan);
        $this->assertNull($troubleshoot->gejala);
        $this->assertNull($troubleshoot->solusi);
        $this->get(route('troubleshoot.show', $troubleshoot))
            ->assertOk()
            ->assertSee('Periksa koneksi router.')
            ->assertDontSee('Gejala atau pesan error')
            ->assertDontSee('Solusi</h2>');
    }
}
