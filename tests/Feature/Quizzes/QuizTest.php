<?php

use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

describe('Quiz Creation', function () {
    it('creates a quiz with questions and picture', function () {
        fakeStorage();

        $file = fakePicture();

        $quiz = createQuiz(createUser(), ['picture' => $file]);

        $payload = quizPayload();

        Storage::disk('public')->assertExists($quiz->picture);

        expect($quiz->picture)->toBe('quizzes/'.$file->hashName());

        expect($quiz)->title->toBe($payload['title']);
        expect($quiz)->description->toBe($payload['description']);
        expect($quiz)->status->toBe($payload['status']);
        expect($quiz->expire_date)->not->toBeNull();
        expect($quiz->slug)->not->toBeEmpty();

        assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'question' => $payload['questions'][0]['question'],
        ]);
    });

    it('fails to create a quiz with invalid data', function () {
        actingUser();
        post(route('quizzes.store'), [])
            ->assertSessionHasErrors(['title']);
    });

    it('converts status string to boolean', function () {
        createQuiz(createUser(), ['status' => 'false']);
        assertDatabaseHas('quizzes', ['status' => false]);
    });

    it('creates a quiz without questions', function () {
        $quiz = createQuiz(createUser(), ['questions' => []]);
        expect($quiz->questions()->count())->toBe(0);
    });
});

describe('Quiz : Deletion', function () {
    it('deletes a quiz', function () {
        fakeStorage();

        $file = fakePicture();

        $quiz = createQuiz(actingUser(), ['picture' => $file]);

        Storage::disk('public')->assertExists($quiz->picture);

        delete(route('quizzes.destroy', $quiz))->assertStatus(302);

        assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
        assertDatabaseMissing('questions', ['quiz_id' => $quiz->id]);
        Storage::disk('public')->assertMissing($quiz->picture);
    });

    it('prevents deleting a quiz owned by another user', function () {
        $owner = createUser();
        $quiz = createQuiz($owner);

        actingUser(createUser());

        delete(route('quizzes.destroy', $quiz))
            ->assertNotFound();
    });
});

describe('Quiz : Updating', function () {

    it('updates a quiz and its questions/picture', function () {
        fakeStorage();
        $oldPic = fakePicture();

        $quiz = createQuiz(actingUser(), [
            'title' => 'Old Title',
            'status' => false,
            'picture' => $oldPic,
            'questions' => [
                ['id' => 1, 'question' => 'Old question', 'type' => 'select'],
            ],
        ]);

        Storage::disk('public')->assertExists($quiz->picture);

        $newPic = fakePicture('newquiz.png');

        put(route('quizzes.update', $quiz), quizPayload([
            'title' => 'New Title',
            'status' => true,
            'picture' => $newPic,
            'questions' => [
                ['id' => 1, 'question' => 'Updated question', 'type' => 'text', 'data' => []],
            ],
        ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        assertDatabaseHas('quizzes', ['id' => $quiz->id, 'title' => 'New Title']);
        assertDatabaseHas('questions', ['id' => 1, 'question' => 'Updated question']);

        assertDatabaseMissing('questions', ['question' => 'Old question']);
        Storage::disk('public')->assertMissing($quiz->picture);
    });

    it('prevents updating a quiz owned by another user', function () {
        $owner = createUser();
        $quiz = createQuiz($owner);

        actingUser(createUser());

        put(route('quizzes.update', $quiz), quizPayload())
            ->assertNotFound();
    });
});

describe('Quiz : Pagination', function () {
    it('returns paginated quizzes', function () {
        $user = actingUser();

        Quiz::factory()
            ->for($user)
            ->count(15)
            ->create();

        get(route('quizzes.index'))
            ->assertInertia(
                fn ($page) => $page
                    ->component('Quizzes/Index')
                    ->has('quizzes.data', 6)
                    ->where('quizzes.total', 15)
            );
    });
});
