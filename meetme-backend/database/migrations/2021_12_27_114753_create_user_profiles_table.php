<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('user_id');
            $table->string('hometown')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob');
            $table->mediumText('about')->nullable();
            $table->mediumText('avatar')->nullable();
            $table->json('friends')->default(json_encode(["users" => []]));
            $table->json('friend_requests')->default(json_encode(["users" => []]));
            $table->json('blocked_users')->default(json_encode(["users" => []]));
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
