<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade'); // => users.id (UUID)
            $table->foreignUuid('book_id')->constrained()->onDelete('cascade'); // => books.id (UUID)
            $table->boolean('liked');
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_books');
    }
};
