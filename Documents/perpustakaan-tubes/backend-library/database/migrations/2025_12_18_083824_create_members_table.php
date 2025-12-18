<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('member_code')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('member_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
