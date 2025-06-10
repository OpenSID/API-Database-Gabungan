<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\DataPresisi\Models\Kesehatan;
use Modules\DataPresisi\Models\Papan;
use Modules\DataPresisi\Models\Sandang;
use Modules\DataPresisi\Models\Tahun;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection('openkab')->hasTable('data_presisi_tahun')) {
            Schema::connection('openkab')->create('data_presisi_tahun', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->configId();
                $table->string('tahun', 4);
                $table->boolean('status')->default(true);
                $table->timesWithUserstamps();
                $table->unique(['tahun', 'config_id'], 'tahun_config_id_unique');
            });
        }


        if (Schema::connection('openkab')->hasTable('data_presisi_papan')) {
            if(!Schema::connection('openkab')->hasColumn('data_presisi_papan', 'data_presisi_tahun_id')) {
                Schema::connection('openkab')->table('data_presisi_papan', static function (Blueprint $table) {
                    $table->foreignUuid('data_presisi_tahun_id')->nullable()->after('config_id')->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                });
            }
        }

        if (Schema::connection('openkab')->hasTable('data_presisi_sandang')) {
            if(!Schema::connection('openkab')->hasColumn('data_presisi_sandang', 'data_presisi_tahun_id')) {
                Schema::connection('openkab')->table('data_presisi_sandang', static function (Blueprint $table) {
                    $table->foreignUuid('data_presisi_tahun_id')->nullable()->after('config_id')->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                });
            }
        }

        if (Schema::connection('openkab')->hasTable('data_presisi_kesehatan')) {
            if(!Schema::connection('openkab')->hasColumn('data_presisi_kesehatan', 'data_presisi_tahun_id')) {
                Schema::connection('openkab')->table('data_presisi_kesehatan', static function (Blueprint $table) {
                    $table->foreignUuid('data_presisi_tahun_id')->nullable()->after('config_id')->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                });
            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
