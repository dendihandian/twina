<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTweetsTable.
 */
class CreateTweetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tweets', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('tweet_id')->unsigned()->nullable();
			$table->bigInteger('people_id')->unsigned()->nullable();
			$table->text('text')->nullable();
			$table->string('created_date')->nullable();

			$table->bigInteger('in_reply_to_people_id')->nullable();
			$table->bigInteger('in_reply_to_status_id')->nullable();

			$table->string('lang')->nullable();
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
		Schema::drop('tweets');
	}
}
