<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Test;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserCollection;
use App\Models\UserFeedback;
use App\Models\UserTest;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testTypes = Test::all();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('user_tests')->truncate();
        DB::table('user_collections')->truncate();
        DB::table('company_users')->truncate();
        DB::table('users')->truncate();
        DB::table('user_answers')->truncate();
        DB::table('user_custom_answers')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // User::factory(50)
        //     ->has(
        //         UserCollection::factory()->withCollectionIdOne()->count(1),
        //         'collections'
        //     )
        //     ->has(
        //         UserCollection::factory()
        //         ->withCollectionIdTwo()->count(1),
        //         'collections'
        //     )
        //     ->create();

        $company = Company::first();

        $users = User::factory(150)->create();
        

        $users->each(function ($user) use ($testTypes, $company) {
            $userCollection1 = UserCollection::factory()->create([
                'user_id' => $user->id,
                'collection_id' => 1,
                'company_id' => $company->id,
                'created_at' => Carbon::now()->subDays(rand(10, 200)),
            ]);

            $userCollection2 = UserCollection::factory()->create([
                'user_id' => $user->id,
                'collection_id' => 2,
                'company_id' => $company->id,
                'created_at' => Carbon::now()->subDays(rand(10, 200)),
            ]);

            $collection1Tests = $testTypes->where('collection_id', 1);
            $collection2Tests = $testTypes->where('collection_id', 2);

            $collection1Tests->each(function ($test) use ($user, $userCollection1) {
                $userTest = UserTest::factory()->create([
                    'user_collection_id' => $userCollection1->id,
                    'test_id' => $test->id,
                ]);

                $userTest->testType->questions->each(function ($question) use ($user, $userTest) {
                    $option = $question->options[rand(0, 4)];
                    UserAnswer::factory()->create([
                        'user_test_id' => $userTest->id,
                        'user_id' => $user->id,
                        'value' => $option->value,
                        'question_id' => $question->id,
                        'question_option_id' => $option->id,
                    ]);
                });
            });

            $collection2Tests->each(function ($test) use ($user, $userCollection2) {
                $userTest = UserTest::factory()->create([
                    'user_collection_id' => $userCollection2->id,
                    'test_id' => $test->id,
                ]);

                $userTest->testType->questions->each(function ($question) use ($user, $userTest) {
                    $option = $question->options[rand(0, 4)];
                    UserAnswer::factory()->create([
                        'user_test_id' => $userTest->id,
                        'user_id' => $user->id,
                        'value' => $option->value,
                        'question_id' => $question->id,
                        'question_option_id' => $option->id,
                    ]);
                });
            });

            UserFeedback::factory()->count(rand(0, 2))->create([
                'user_id' => $user->id,
                'company_id' => Company::first()->id,
            ]);

        });

        User::factory(50)->create();
    }
}
