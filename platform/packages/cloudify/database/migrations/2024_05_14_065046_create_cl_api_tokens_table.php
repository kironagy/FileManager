<?php

use Botble\Cloudify\Enums\ApiKeyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default(ApiKeyType::EXTERNAL);
            $table->string('token');
            $table->text('abilities')->nullable();
            $table->boolean('special')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
