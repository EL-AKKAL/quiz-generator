<?php

use App\Models\Quiz;
use App\Models\Question;
use function Pest\Laravel\{actingAs, post, assertDatabaseHas, assertDatabaseMissing, delete, get, json, put};
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe("Quiz Creation", function () {
    it('creates a quiz with questions and picture', function () {
        Storage::fake('public');

        actingAs(createUser());

        $file = UploadedFile::fake()->create('quiz.png', 100);

        $payload = quizPayload([
            'picture' => $file,
        ]);

        post(route('quizzes.store'), $payload)->assertRedirect()->assertSessionHasNoErrors();

        Storage::disk('public')->assertExists('quizzes/' . $file->hashName());

        $quiz = Quiz::latest()->first();
        expect($quiz->picture)->toBe('quizzes/' . $file->hashName());

        expect($quiz->title)->toBe($payload['title']);
        expect($quiz->description)->toBe($payload['description']);
        expect($quiz->status)->toBe($payload['status']);
        expect($quiz->expire_date)->not->toBeNull();

        expect($quiz->slug)->toBeString()->not->toBeEmpty();

        expect($quiz->questions)->toHaveCount(count($payload['questions']));

        $firstQuestion = $payload['questions'][0];

        assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'question' => $firstQuestion['question'],
            'type' => $firstQuestion['type'],
            'data' => null,
        ]);
    });

    it('fails to create a quiz with invalid data', function () {
        actingAs(createUser());

        post(route('quizzes.store'), [])
            ->assertSessionHasErrors(['title']);
    });

    it('converts status string to boolean', function () {
        actingAs(createUser());

        post(route('quizzes.store'), quizPayload(['status' => 'false']))
            ->assertRedirect();

        assertDatabaseHas('quizzes', [
            'status' => false,
        ]);
    });

    it('creates a quiz without questions', function () {
        actingAs(createUser());

        post(route('quizzes.store'), quizPayload(['questions' => []]))
            ->assertRedirect();

        $quiz = Quiz::latest()->first();

        expect($quiz->questions()->count())->toBe(0);
    });

});

describe('Quiz : Deletion', function () {
    it('deletes a quiz', function () {
        Storage::fake('public');
        $user = createUser();
        actingAs($user);

        $file = UploadedFile::fake()->create('quiz.png', 100);

        post(route('quizzes.store'), quizPayload([
            'picture' => $file,
        ]))->assertRedirect()
            ->assertSessionHasNoErrors();

        $quiz = Quiz::latest()->first();

        $questionIds = $quiz->questions()->pluck('id')->toArray();
        expect($questionIds)->not->toBeEmpty();

        Storage::disk('public')->assertExists($quiz->picture);

        delete(route('quizzes.destroy', $quiz))
            ->assertStatus(302);


        assertDatabaseMissing('quizzes', ['id' => $quiz->id]);

        Storage::disk('public')->assertMissing($quiz->picture);

        assertDatabaseMissing('questions', ['quiz_id' => $quiz->id]);

    });

    it('prevents deleting a quiz owned by another user', function () {
        $owner = createUser();
        $attacker = createUser();

        $quiz = $owner->quizzes()->create(quizPayload());

        actingAs($attacker);

        delete(route('quizzes.destroy', $quiz))
            ->assertNotFound();
    });

});

describe('Quiz : Updating', function () {

    it('updates a quiz and its questions/picture', function () {
        Storage::fake('public');

        $user = createUser();

        actingAs($user);
        $qId = 1;
        $oldFile = UploadedFile::fake()->create('quiz.png', 100);

        $oldPayload = quizPayload([
            'title' => 'Old Title',
            'status' => false,
            'questions' => [
                [
                    'id' => $qId,
                    'question' => 'Old question',
                    'type' => 'select',
                ]
            ],
            'picture' => $oldFile,
        ]);


        post(route('quizzes.store'), $oldPayload)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $quiz = Quiz::latest()->first();

        $oldPath = $quiz->picture;

        Storage::disk('public')->assertExists($oldPath);

        assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'Old Title',
            'status' => false,
        ]);

        assertDatabaseHas('questions', [
            'id' => $qId,
            'question' => 'Old question',
            'type' => 'select',
        ]);

        $newFile = UploadedFile::fake()->create('newquiz.png', 100);

        $newpayload = quizPayload([
            'title' => 'New Title',
            'status' => true,
            'questions' => [
                [
                    'id' => $qId,
                    'question' => 'Updated question',
                    'type' => 'text',
                    'data' => [],
                ],
            ],
            'picture' => $newFile,
        ]);

        put(route('quizzes.update', $quiz), $newpayload)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $quiz = Quiz::latest()->first();

        assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'New Title',
            'status' => true,
        ]);

        assertDatabaseHas('questions', [
            'id' => $qId,
            'question' => 'Updated question',
            'type' => 'text',
        ]);

        assertDatabaseMissing('quizzes', [
            'id' => $quiz->id,
            'title' => 'Old Title',
            'status' => false,
        ]);

        assertDatabaseMissing('questions', [
            'id' => $qId,
            'question' => 'Old question',
            'type' => 'select',
        ]);

        $newPath = $quiz->picture;

        Storage::disk('public')->assertExists($newPath);
        Storage::disk('public')->assertMissing($oldPath);
    });

    it('prevents updating a quiz owned by another user', function () {
        $owner = createUser();
        $attacker = createUser();

        $quiz = $owner->quizzes()->create(quizPayload());

        actingAs($attacker);

        put(route('quizzes.update', $quiz), quizPayload())
            ->assertNotFound();
    });
});

describe('Quiz : Pagination', function () {
    it('returns paginated quizzes', function () {
        $user = createUser();
        actingAs($user);

        Quiz::factory()
            ->for($user)
            ->count(15)
            ->create();

        get(route('quizzes.index'))
            ->assertInertia(
                fn($page) => $page
                    ->component('Quizzes/Index')
                    ->has('quizzes.data', 6)
                    ->where('quizzes.total', 15)
            );
    });
});
