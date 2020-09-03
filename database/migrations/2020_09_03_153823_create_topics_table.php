<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTopicsTable.
 */
class CreateTopicsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topics', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('slug');
			$table->integer('total_tweets')->unsigned()->default(0);
			$table->integer('last_fetched_tweets')->unsigned()->default(0);
			$table->bigInteger('last_tweet')->unsigned()->nullable();
			$table->dateTimeTz('last_mining')->nullable();
			$table->boolean('on_queue')->default(false);
			$table->timestampsTz();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('topics');
	}
}
