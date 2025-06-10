<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection('openkab')->hasTable('data_presisi_jaminan_sosial')) {
            Schema::connection('openkab')->create('data_presisi_jaminan_sosial', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->foreignUuid('data_presisi_tahun_id')->nullable()->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_jaminan_sosial_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_jaminan_sosial_keluarga');
                $table->integer('anggota_id')->nullable()->index('FK_data_presisi_jaminan_sosial_anggota');
                $table->string('jns_bantuan', 30)->nullable();
                $table->string('jns_gangguan_mental', 50)->nullable();
                $table->string('terapi_gangguan_mental', 50)->nullable();
                $table->timesWithUserstamps();

                // Add foreign key constraints
                $table->foreign('rtm_id')->references('id')->on('tweb_rtm')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('keluarga_id')->references('id')->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('anggota_id')->references('id')->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
