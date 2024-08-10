<?php

use App\Domain\Entity\Enum\BidTypeEnum;
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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->enum('type', array_map(fn($v) => $v->value, BidTypeEnum::cases()))->default(BidTypeEnum::manual->value);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('auction_item_id')->constrained()->cascadeOnDelete();
            $table->timestamp('bid_at')->useCurrent();

            $table->index(['auction_item_id', 'bid_at', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
