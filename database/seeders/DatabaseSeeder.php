<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyCampaign;
use App\Models\CustomCollection;
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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $this->call(CustomCollectionsSeeder::class);
        // $company = Company::first();
        
        // $companyPsychosocialCollection = CustomCollection::where('company_id', $company->id)
        //     ->where('collection_id', 1)
        //     ->with('tests.questions.options')
        //     ->first();

        // $campaign = CompanyCampaign::create([
        //     'company_id' => $company['id'],
        //     'name' => "Campanha de Riscos Psicossociais",
        //     'description' => "Descrição da minha campanha",
        //     'start_date' => "2025-05-23 14:54:00",
        //     'end_date' => "2025-05-23 14:54:00",
        //     'collection_id' => $companyPsychosocialCollection['id'],
        // ]);

        // $users = User::factory(150)->create();

        // $users->each(function($user) use($company, $companyPsychosocialCollection) {
        //     DB::transaction(function() use($company, $user, $companyPsychosocialCollection){
        //         $newUserCollection = UserCollection::create([
        //             'user_id' => $user->id,
        //             'collection_id' => $companyPsychosocialCollection->id,
        //             'company_id' => $company->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);

        //         foreach($companyPsychosocialCollection->tests as $test){
        //             $userTest = UserTest::create([
        //                 'user_collection_id' => $newUserCollection->id,
        //                 'test_id' => $test->id,
        //             ]);

        //             foreach($test->questions as $question){
        //                 $answer = $question->options[rand(0, 4)];

        //                 UserAnswer::create([
        //                     'question_option_id' => $answer->id,
        //                     'question_id' => $question->id,
        //                     'user_test_id' => $userTest->id,
        //                     'user_id' => $user->id,
        //                     'value' => $answer->value,
        //                 ]);
        //             }
        //         }
        //     });
        // });   


        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // DB::table('users')->truncate();
        // DB::table('company_users')->truncate();
        // DB::table('user_collections')->truncate();
        // DB::table('user_tests')->truncate();
        // DB::table('user_answers')->truncate();

        // DB::table('companies')->truncate();
        // DB::table('action_plans')->truncate();
        // DB::table('custom_control_actions')->truncate();
        // DB::table('company_campaigns')->truncate();
        // DB::table('company_metrics')->truncate();
        // DB::table('custom_collections')->truncate();
        // DB::table('custom_tests')->truncate();
        // DB::table('custom_questions')->truncate();
        // DB::table('custom_question_options')->truncate();
        // DB::table('user_custom_permissions')->truncate();
        // DB::table('user_department_permissions')->truncate();
        // DB::table('user_feedback')->truncate();

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
