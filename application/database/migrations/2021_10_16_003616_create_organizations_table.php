<?php

use App\Domain\Enums\OrganizationStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')
                ->primary();
            $table->foreignUuid('owner_id')
                ->references('id')
                ->on('users');
            $table->string('corporate_name', 75);
            $table->string('fantasy_name', 75);
            $table->string('logo_url', 255);
            $table->string('website', 100);
            $table->longText('description');
            $table->enum('status', OrganizationStatusEnum::toValues());
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
        Schema::dropIfExists('organizations');
    }
}
