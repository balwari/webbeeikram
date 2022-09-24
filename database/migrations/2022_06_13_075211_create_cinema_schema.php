<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->string('name');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    
        Schema::table('timings', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::table('seat_types', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('theatres', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->string('total_seats');
            $table->integer('timings_id');
            $table->string('status')->nullable();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('timings_id')->references('id')->on('timings');
            $table->timestamps();
        });

        Schema::table('threatre_seats', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->integer('theatre_id');
            $table->integer('seat_type_id');
            $table->integer('serial_no');
            $table->string('status')->nullable();
            $table->foreign('theatre_id')->references('id')->on('theatres');
            $table->foreign('seat_type_id')->references('id')->on('seat_types');
            $table->timestamps();
        });

        Schema::table('theatre_shows', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->integer('theatre_id');
            $table->integer('timings_id');
            $table->string('status')->nullable();
            $table->foreign('theatre_id')->references('id')->on('theatres');
            $table->foreign('timings_id')->references('id')->on('timings');
            $table->timestamps();
        });

        Schema::table('shows', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->integer('movie_id');
            $table->integer('theatre_shows_id');
            $table->integer('ticket_price');
            $table->string('status')->nullable();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('theatre_shows_id')->references('id')->on('theatre_shows');
            $table->timestamps();
        });

        Schema::table('bookings', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->integer('show_id');
            $table->integer('threatre_seat_id');
            $table->string('status')->nullable();
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('threatre_seat_id')->references('id')->on('threatre_seats');
            $table->timestamps();
        });

        Schema::table('discounts', function(Blueprint $table)
        {
            $table->bigInteger('id')->unsigned();
            $table->integer('discount');
            $table->integer('threatre_seat_id');
            $table->string('status')->nullable();
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('threatre_seat_id')->references('id')->on('threatre_seats');
            $table->timestamps();
        });

        throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_cycles');
    }
}
