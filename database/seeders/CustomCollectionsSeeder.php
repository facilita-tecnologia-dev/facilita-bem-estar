<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Collection;
use App\Models\Test;
use App\Models\CustomCollection;
use App\Models\CustomTest;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomCollectionsSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $this->createCustomCollections($company);
        }
    }

    private function createCustomCollections(Company $company): void
    {
        DB::transaction(function () use ($company) {
            $collections = Collection::all();

            foreach ($collections as $collection) {
                $customCollection = CustomCollection::create([
                    'company_id'     => $company->id,
                    'collection_id'  => $collection->id,
                    'name'           => $collection->name,
                    'is_default'     => true,
                ]);

                $tests = Test::where('collection_id', $collection->id)
                    ->with('questions.options')
                    ->get();

                foreach ($tests as $test) {
                    $customTest = CustomTest::create([
                        'custom_collection_id' => $customCollection->id,
                        'key_name'             => $test->key_name,
                        'display_name'         => $test->display_name,
                        'statement'            => $test->statement,
                        'order'                => $test->order,
                        'handler_type'         => $test->handler_type,
                        'reference'            => $test->reference,
                        'number_of_questions'  => $test->number_of_questions,
                    ]);

                    foreach ($test->questions as $question) {
                        $customQuestion = CustomQuestion::create([
                            'custom_test_id' => $customTest->id,
                            'statement'      => $question->statement,
                        ]);

                        foreach ($question->options as $option) {
                            CustomQuestionOption::create([
                                'custom_question_id' => $customQuestion->id,
                                'content'            => $option->content,
                                'value'              => $option->value,
                            ]);
                        }
                    }
                }
            }
        });
    }
}