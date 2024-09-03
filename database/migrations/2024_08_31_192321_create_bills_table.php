<?php

use App\Domain\Entity\Enum\BillStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('auction_item_id')->constrained()->cascadeOnDelete();
            $table->string('no')->unique();
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('due_at');
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', array_map(fn($v) => $v->value, BillStatusEnum::cases()))
                ->default(BillStatusEnum::unpaid->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
