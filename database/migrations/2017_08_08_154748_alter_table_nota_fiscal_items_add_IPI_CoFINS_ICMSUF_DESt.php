<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItemsAddIPICoFINSICMSUFDESt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("nota_fiscal_itens", function(Blueprint $table) {
            $table->decimal("aliquota_cofins", 19, 2)->nullable();
            $table->decimal("aliquota_pis", 19, 2)->nullable();
            $table->decimal("valor_pis", 19, 2)->nullable();

            $table->decimal("base_calculo_icms_uf_dest", 19, 2)->nullable();

            $table->decimal("aliquota_fcp_icms_uf_dest", 19, 2)->nullable();
            $table->decimal("aliquota_icms_uf_dest", 19, 2)->nullable();
            $table->decimal("aliquota_icms_uf_interna", 19, 2)->nullable();
            $table->decimal("aliquota_icms_uf_interna_part", 19, 2)->nullable();

            $table->decimal("valor_fcp_icms_uf_dest", 19, 2)->nullable();
            $table->decimal("valor_icms_uf_dest", 19, 2)->nullable();
            $table->decimal("valor_icms_uf_remetente", 19, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("nota_fiscal_itens", function(Blueprint $table) {
            $table->dropColumn("aliquota_cofins");
            $table->dropColumn("aliquota_pis");
            $table->dropColumn("valor_pis");
            $table->dropColumn("base_calculo_icms_uf_dest");
            $table->dropColumn("aliquota_fcp_icms_uf_dest");
            $table->dropColumn("aliquota_icms_uf_dest");
            $table->dropColumn("aliquota_icms_uf_interna");
            $table->dropColumn("aliquota_icms_uf_interna_part");
            $table->dropColumn("valor_fcp_icms_uf_dest");
            $table->dropColumn("valor_icms_uf_dest");
            $table->dropColumn("valor_icms_uf_remetente");
        });
    }
}
