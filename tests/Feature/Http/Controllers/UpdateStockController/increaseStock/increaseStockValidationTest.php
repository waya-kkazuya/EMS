<?php

namespace Tests\Feature\Http\Controllers\UpdateStockController\increaseStock;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class increaseStockValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = FakerFactory::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    //　IncreaseStockRequestのバリデーションのテスト
    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションoperator_nameがnullで無効値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['operator_name' => null]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->has('errors.operator_name')
                ->where('errors.operator_name', '実施者は必ず指定してください。')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションoperator_nameが1文字で有効値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['operator_name' => str_repeat('あ', 1)]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->missing('errors.operator_name')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションoperator_nameが10文字で有効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['operator_name' => str_repeat('あ', 10)]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->missing('errors.operator_name')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションoperator_nameが11文字で無効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['operator_name' => str_repeat('あ', 11)]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->has('errors.operator_name')
                ->where('errors.operator_name', '実施者は、10文字以下で指定してください。')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションquantityがnullで無効値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['quantity' => null]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->has('errors.quantity')
                ->where('errors.quantity', '数量は必ず指定してください。')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションquantityが0で無効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['quantity' => 0]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->has('errors.quantity')
                ->where('errors.quantity', '数量には、1以上の数字を指定してください。')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションquantityが1で有効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['quantity' => 1]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->missing('errors.quantity')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションquantityが500で有効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['quantity' => 500]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->missing('errors.quantity')
                // ->dump()
        );
    }

    /** @test */
    public function 入出庫モーダルでの入庫処理バリデーションquantityが501で無効境界値な場合()
    {
        // categoriesテーブルをトランケートして連番をリセット
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 世界を構築
        $category = Category::factory()->create(['id' => 1]);

        $user = User::factory()->role(1)->create();
        $this->actingAs($user);

        // テスト用の備品を作成、消耗品(category_id=1)のみ入出庫できる
        $item = Item::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->from('consumable_items')
            ->put(route('increaseStock', $item->id), ['quantity' => 501]);
        $response->assertStatus(302);
        $response->assertRedirect('consumable_items');

        $response = $this->followRedirects($response);

        $response->assertInertia(fn(Assert $page) => $page
                ->component('ConsumableItems/Index')
                ->has('errors.quantity')
                ->where('errors.quantity', '数量には、500以下の数字を指定してください。')
                // ->dump()
        );
    }
}
