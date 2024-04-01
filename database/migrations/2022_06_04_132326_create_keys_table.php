<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->id();

            $table->string('priv_key')->unique();
            $table->string('pub_key')->unique();
            $table->string('phone')->nullable();
            $table->bigInteger('count')->default(0);
            $table->boolean('is_donated')->default(false);

            $table->index('count');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keys', function (Blueprint $table) {
            $table->dropIndex(['count']); // Drops index 'geo_state_index'
        });

        Schema::dropIfExists('keys');
    }
}
