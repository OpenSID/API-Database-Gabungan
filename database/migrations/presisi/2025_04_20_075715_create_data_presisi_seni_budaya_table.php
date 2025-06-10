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
        if (! Schema::connection('openkab')->hasTable('data_presisi_seni_budaya')) {
            Schema::connection('openkab')->create('data_presisi_seni_budaya', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->foreignUuid('data_presisi_tahun_id')->nullable()->constrained('data_presisi_tahun')->references('uuid')->cascadeOnUpdate()->cascadeOnDelete();
                $table->integer('rtm_id')->nullable()->index('FK_data_presisi_seni_budaya_rtm');
                $table->integer('keluarga_id')->nullable()->index('FK_data_presisi_seni_budaya_keluarga');
                $table->integer('anggota_id')->nullable()->index('FK_data_presisi_seni_budaya_anggota');
                $table->string('jenis_seni_yang_dikuasai')->nullable();
                $table->string('jumlah_penghasilan_dari_seni')->nullable();
                $table->date('tanggal_pengisian')->nullable();
                $table->string('status_pengisian')->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

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
