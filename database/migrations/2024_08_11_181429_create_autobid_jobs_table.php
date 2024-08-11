<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('autobid_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id');
            $table->integer('auction_item_id');
            $table->integer('user_id');
            $table->boolean('processed')->nullable()->default(null);
            $table->softDeletes();

            $table->index(['session_id', 'auction_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autobid_jobs');
    }
};
