<?php

namespace Tests\Feature;

use App\Http\Controllers\SettingsController;
use App\Models\Setting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GcashQrUploadTest extends TestCase
{
    private string $testPublicPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testPublicPath = storage_path('framework/testing/gcash-qr-' . uniqid());
        File::ensureDirectoryExists($this->testPublicPath . '/images');
        $this->app->usePublicPath($this->testPublicPath);

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        File::deleteDirectory($this->testPublicPath);

        parent::tearDown();
    }

    public function test_png_upload_keeps_its_extension_and_updates_the_active_qr_path(): void
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );

        $request = Request::create('/settings/gcash-qr', 'POST', [], [], [
            'gcash_qr' => UploadedFile::fake()->createWithContent('customer-qr.png', $png),
        ]);

        app(SettingsController::class)->uploadGcashQr($request);

        $this->assertSame('images/gcash-qr.png', Setting::get('gcash_qr_image'));
        $this->assertFileExists($this->testPublicPath . '/images/gcash-qr.png');
        $this->assertFileDoesNotExist($this->testPublicPath . '/images/gcash-qr.jpg');
    }
}
