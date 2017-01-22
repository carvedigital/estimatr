<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateToHourRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->tinyInteger('hours_in_day')->default(8);
            $table->decimal('new_rate', 12, 2)->default(0.00);
        });
        $estimatesList = \App\Estimates\Estimate::withTrashed()->get();
        foreach ($estimatesList as $estimate) {
            $estimate_data = null;
            if (! is_null($estimate->estimate_data) && $estimate->estimate_data) {
                $estimate_data = json_decode($estimate->estimate_data);
                foreach ($estimate_data as $key => $line_item) {
                    $estimate_data[$key]->best = (int) ($line_item->best * 8);
                    $estimate_data[$key]->standard = (int) ($line_item->standard * 8);
                    $estimate_data[$key]->worst = (int) ($line_item->worst * 8);
                }
            }
            $estimate->new_rate = ($estimate->rate / 8);
            $estimate->estimate_data = ! is_null($estimate_data) ? json_encode($estimate_data) : null;
            $estimate->save();
        }
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('rate');
        });

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE estimates RENAME COLUMN new_rate TO rate');
        \Illuminate\Support\Facades\DB::statement('DELETE FROM estimates WHERE deleted_at IS NOT NULL');

        $estimatesList = \App\Estimates\Estimate::withTrashed()->get();
        foreach ($estimatesList as $estimate) {
            $estimate->recalculateTotals();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('hours_in_day');
        });
        $estimates = \App\Estimates\Estimate::all();
        foreach ($estimates as $estimate) {
            $estimate_data = null;
            if (! is_null($estimate->estimate_data) && $estimate->estimate_data) {
                $estimate_data = json_decode($estimate->estimate_data);
                foreach ($estimate_data as $key => $line_item) {
                    $estimate_data[$key]->best = (int) $line_item->best / 8;
                    $estimate_data[$key]->standard = (int) $line_item->standard / 8;
                    $estimate_data[$key]->worst = (int) $line_item->worst / 8;
                }
            }
            $estimate->rate = floatval($estimate->rate * 8);
            $estimate->estimate_data = json_encode($estimate_data);
            $estimate->save();
        }
    }
}
