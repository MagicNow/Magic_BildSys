<?php

namespace App\Repositories\Admin;

use App\Models\Planejamento;
use InfyOm\Generator\Common\BaseRepository;

class PlanejamentoCompraRepository extends BaseRepository
{

    public static function existeItemParaComprar($insumoGrupoId = null){
        $EXISTE_ITEM_PRA_COMPRAR = "(
        SELECT
            1
        FROM
            planejamento_compras plc
        JOIN planejamentos P ON P.id = plc.planejamento_id
        JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
        AND orc.grupo_id = plc.grupo_id
        AND orc.subgrupo1_id = plc.subgrupo1_id
        AND orc.subgrupo2_id = plc.subgrupo2_id
        AND orc.subgrupo3_id = plc.subgrupo3_id
        AND orc.servico_id = plc.servico_id
        AND orc.ativo = 1
        AND orc.obra_id = P.obra_id
        ". ($insumoGrupoId?" JOIN insumos INSUM ON INSUM.id = plc.insumo_id ": "") . "
        WHERE
            (
                IFNULL((
                    SELECT
                        SUM(oci.qtd) 
                    FROM ordem_de_compra_itens oci
                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                    WHERE
                        oci.insumo_id = plc.insumo_id
                    AND oci.grupo_id = plc.grupo_id
                    AND oci.subgrupo1_id = plc.subgrupo1_id
                    AND oci.subgrupo2_id = plc.subgrupo2_id
                    AND oci.subgrupo3_id = plc.subgrupo3_id
                    AND oci.servico_id = plc.servico_id
                    AND oci.obra_id = P.obra_id
                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                ),0) < orc.qtd_total
                AND  
                IFNULL( (
                    SELECT
                        SUM(oci.total) 
                    FROM ordem_de_compra_itens oci
                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                    WHERE
                        oci.insumo_id = plc.insumo_id
                    AND oci.grupo_id = plc.grupo_id
                    AND oci.subgrupo1_id = plc.subgrupo1_id
                    AND oci.subgrupo2_id = plc.subgrupo2_id
                    AND oci.subgrupo3_id = plc.subgrupo3_id
                    AND oci.servico_id = plc.servico_id
                    AND oci.obra_id = P.obra_id
                ),0) = 0
            )
        
            AND P.id = planejamentos.id 
        
            AND plc.deleted_at IS NULL
            AND orc.qtd_total > 0
            ". ($insumoGrupoId?" AND INSUM.insumo_grupo_id = ".$insumoGrupoId." ": "") . "
            LIMIT 1
                ) IS NOT NULL";
        return $EXISTE_ITEM_PRA_COMPRAR;
    }

    public static function existeItemParaComprarComInsumoGrupo(){
        $EXISTE_ITEM_PRA_COMPRAR = "(
            SELECT
                1
            FROM
                planejamento_compras plc
            JOIN planejamentos P ON P.id = plc.planejamento_id
            JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
            AND orc.grupo_id = plc.grupo_id
            AND orc.subgrupo1_id = plc.subgrupo1_id
            AND orc.subgrupo2_id = plc.subgrupo2_id
            AND orc.subgrupo3_id = plc.subgrupo3_id
            AND orc.servico_id = plc.servico_id
            AND orc.ativo = 1
            AND orc.obra_id = P.obra_id
            JOIN insumos INSUM ON INSUM.id = plc.insumo_id
            WHERE
                (
                IFNULL((
                    SELECT
                        SUM(oci.qtd) 
                    FROM ordem_de_compra_itens oci
                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                    WHERE
                        oci.insumo_id = plc.insumo_id
                    AND oci.grupo_id = plc.grupo_id
                    AND oci.subgrupo1_id = plc.subgrupo1_id
                    AND oci.subgrupo2_id = plc.subgrupo2_id
                    AND oci.subgrupo3_id = plc.subgrupo3_id
                    AND oci.servico_id = plc.servico_id
                    AND oci.obra_id = P.obra_id
                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                ),0) < orc.qtd_total
                AND  
                IFNULL( (
                    SELECT
                        SUM(oci.total) 
                    FROM ordem_de_compra_itens oci
                    JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                    AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                    WHERE
                        oci.insumo_id = plc.insumo_id
                    AND oci.grupo_id = plc.grupo_id
                    AND oci.subgrupo1_id = plc.subgrupo1_id
                    AND oci.subgrupo2_id = plc.subgrupo2_id
                    AND oci.subgrupo3_id = plc.subgrupo3_id
                    AND oci.servico_id = plc.servico_id
                    AND oci.obra_id = P.obra_id
                ),0) = 0
            )
            
                AND P.id = planejamentos.id 
            
                AND plc.deleted_at IS NULL
                AND orc.qtd_total > 0
                AND INSUM.insumo_grupo_id = insumo_grupos.id
                LIMIT 1
        ) IS NOT NULL";
        return $EXISTE_ITEM_PRA_COMPRAR;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'tarefa',
        'data',
        'prazo',
        'planejamento_id',
        'data_fim',
        'resumo'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlanejamentoCompra::class;
    }

}
