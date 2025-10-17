<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Question::class, 'question_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(App\Models\Answer::class, 'answer_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
