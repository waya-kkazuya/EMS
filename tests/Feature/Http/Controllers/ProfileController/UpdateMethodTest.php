<?php

namespace Tests\Feature\Http\Controllers\ProfileController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Location;
use App\Models\UsageStatus;
use App\Models\AcquisitionMethod;
use App\Models\Edithistory;
use App\Models\Inspection;
use App\Models\EditReason;
use App\Models\RequestStatus;
use App\Models\StockTransaction;
use App\Services\ImageService;
use Faker\Factory as FakerFactory;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use App\Services\ManagementIdService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Inertia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Session;

class UpdateMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    /** @test */
    public function プロフィール画面が表示される(): void
    {
        // adminユーザー
        $user = User::factory()->role(1)->create();

        $response = $this->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    /** @test */
    public function プロフィール情報を更新出来る(): void
    {
        // フェイクの画像ファイルを作成
        $this->fakeImage = UploadedFile::fake()->image('test_profile_image.jpg');

        // ImageServiceのモックを作成
        $this->imageService = Mockery::mock(ImageService::class);
        $this->imageService->shouldReceive('profileImageResizeUpload')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof UploadedFile && $arg->getClientOriginalName() === 'test_profile_image.jpg';
            }))
            ->andReturn('mocked_profile_image.jpg');
        // サービスコンテナにモックを登録
        $this->app->instance(ImageService::class, $this->imageService);

        $user = User::factory()->role(1)->create();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'profile_image_file' => $this->fakeImage
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'profile_image' => 'mocked_profile_image.jpg'
        ]);

        $user->refresh();
        $this->assertSame('Test User', $user->name);
        $this->assertSame('mocked_profile_image.jpg', $user->profile_image);
    }

    // public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this
    //         ->actingAs($user)
    //         ->patch('/profile', [
    //             'name' => 'Test User',
    //             'email' => $user->email,
    //         ]);

    //     $response
    //         ->assertSessionHasNoErrors()
    //         ->assertRedirect('/profile');

    //     $this->assertNotNull($user->refresh()->email_verified_at);
    // }

    // public function test_user_can_delete_their_account(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this
    //         ->actingAs($user)
    //         ->delete('/profile', [
    //             'password' => 'password',
    //         ]);

    //     $response
    //         ->assertSessionHasNoErrors()
    //         ->assertRedirect('/');

    //     $this->assertGuest();
    //     $this->assertNull($user->fresh());
    // }

    // public function test_correct_password_must_be_provided_to_delete_account(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this
    //         ->actingAs($user)
    //         ->from('/profile')
    //         ->delete('/profile', [
    //             'password' => 'wrong-password',
    //         ]);

    //     $response
    //         ->assertSessionHasErrors('password')
    //         ->assertRedirect('/profile');

    //     $this->assertNotNull($user->fresh());
    // }
}
