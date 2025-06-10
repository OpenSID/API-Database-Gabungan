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
        if (! Schema::connection('openkab')->hasTable('data_presisi_kesehatan')) {
            Schema::connection('openkab')->create('data_presisi_kesehatan', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_kesehatan_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_kesehatan_keluarga');
                $table->integer('anggota_id')->nullable()->index('FK_data_presisi_kesehatan_anggota');
                $table->string('jns_ansuransi')->nullable();
                $table->string('jns_penggunaan_alat_kontrasepsi')->nullable();
                $table->string('jns_penyakit_diderita')->nullable();
                $table->string('frekwensi_kunjungan_faskes_pertahun')->nullable();
                $table->string('frekwensi_rawat_inap_pertahun')->nullable();
                $table->string('frekwensi_kunjungan_dokter_pertahun')->nullable();
                $table->string('kondisi_fisik_sejak_lahir')->nullable();
                $table->string('status_gizi_balita')->nullable();
                $table->date('tanggal_pengisian')->nullable();
                $table->string('status_pengisian')->nullable();
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
