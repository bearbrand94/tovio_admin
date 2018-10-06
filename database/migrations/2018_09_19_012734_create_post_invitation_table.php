<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_invitation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id'); // reference the post.
            $table->integer('user_id'); // the user who got invited.
            $table->integer('invitation_answer'); // 0=not answered yet; 1=accepted 2=declined
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
        Schema::dropIfExists('post_invitation');
    }
}
