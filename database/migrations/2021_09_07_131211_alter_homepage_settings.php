<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHomepageSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->string('box1');
            $table->string('box2');
            $table->string('box3');
            $table->string('box4');
            $table->string('box5');

            $table->string('url1');
            $table->string('url2');
            $table->string('url3');
            $table->string('url4');
            $table->string('url5');


            $table->longText('file1');
            $table->longText('file2');
            $table->longText('file3');
            $table->longText('file4');
            $table->longText('file5');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            //
        });
    }
}
