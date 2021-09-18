<?php

use App\Domain\Enums\PrivacyEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 45);
            $table->string('postal_code', 15);
            $table->string('country', 2);
            $table->string('state', 45);
            $table->string('city', 45);
            $table->string('neighborhood', 45);
            $table->string('street', 60);
            $table->string('number', 10);
            $table->string('complement', 60);
            $table->enum('privacy', PrivacyEnum::toValues());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
