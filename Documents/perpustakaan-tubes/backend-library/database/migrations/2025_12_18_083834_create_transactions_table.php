<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('transaction_code');
            $table->index('status');
            $table->index('borrow_date');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
