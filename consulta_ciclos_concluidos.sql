-- Script SQL para buscar informações dos ciclos concluídos a partir de 2018
-- Informações solicitadas: ciclo, tanque, lote, sobrevivência, densidade do viveiro, peso médio, data da despesca, biomassa

SELECT DISTINCT
    c.numero AS ciclo,
    t.nome AS tanque,
    COALESCE(l.lote_fabricacao, 'N/A') AS lote,
    -- Pega os dados da última análise biométrica do ciclo
    (SELECT ab.sobrevivencia 
     FROM analises_biometricas ab 
     WHERE ab.ciclo_id = c.id 
     ORDER BY ab.data_analise DESC 
     LIMIT 1) AS sobrevivencia,
    -- Densidade = total_animais / área do tanque (animais por m²)
    CASE 
        WHEN t.area > 0 THEN 
            ROUND((SELECT ab.total_animais 
                   FROM analises_biometricas ab 
                   WHERE ab.ciclo_id = c.id 
                   ORDER BY ab.data_analise DESC 
                   LIMIT 1) / t.area, 2)
        ELSE 0 
    END AS densidade_viveiro,
    -- Peso médio da última análise biométrica
    (SELECT ab.peso_medio 
     FROM analises_biometricas ab 
     WHERE ab.ciclo_id = c.id 
     ORDER BY ab.data_analise DESC 
     LIMIT 1) AS peso_medio,
    -- Data da despesca (pode ser data_inicio ou data_fim da despesca)
    COALESCE(d.data_fim, d.data_inicio) AS data_despesca,
    -- Biomassa = quantidade despescada * peso médio
    CASE 
        WHEN d.qtd_despescada > 0 AND d.peso_medio > 0 THEN 
            ROUND(d.qtd_despescada * d.peso_medio / 1000, 2) -- convertendo para kg
        ELSE 
            -- Se não há despesca registrada, calcula pela biometria
            ROUND((SELECT ab.peso_total 
                   FROM analises_biometricas ab 
                   WHERE ab.ciclo_id = c.id 
                   ORDER BY ab.data_analise DESC 
                   LIMIT 1) / 1000, 2) -- convertendo para kg
    END AS biomassa_kg,
    c.data_inicio,
    c.data_fim
FROM 
    ciclos c
    INNER JOIN tanques t ON c.tanque_id = t.id
    LEFT JOIN povoamentos pov ON pov.ciclo_id = c.id
    LEFT JOIN povoamentos_lotes pl ON pl.povoamento_id = pov.id
    LEFT JOIN lotes l ON l.id = pl.lote_id
    LEFT JOIN despescas d ON d.ciclo_id = c.id
WHERE 
    c.situacao = 8 -- Situação "Encerrado"
    AND c.data_fim >= '2018-01-01' -- Ciclos concluídos a partir de 2018
    AND c.data_fim IS NOT NULL -- Garantir que o ciclo foi finalizado
    -- Filtrar apenas ciclos que têm pelo menos uma análise biométrica
    AND EXISTS (SELECT 1 FROM analises_biometricas ab WHERE ab.ciclo_id = c.id)
ORDER BY 
    c.data_fim DESC, 
    t.nome, 
    c.numero;

-- Query alternativa mais detalhada com informações adicionais
-- (descomente se precisar de mais detalhes)

/*
SELECT 
    c.numero AS ciclo,
    t.nome AS tanque,
    t.area AS area_tanque_m2,
    COALESCE(l.lote_fabricacao, 'N/A') AS lote,
    l.quantidade AS quantidade_inicial_lote,
    -- Dados da última biometria
    ultima_bio.data_analise AS data_ultima_biometria,
    ultima_bio.total_animais,
    ultima_bio.sobrevivencia,
    CASE 
        WHEN t.area > 0 AND ultima_bio.total_animais > 0 THEN 
            ROUND(ultima_bio.total_animais / t.area, 2)
        ELSE 0 
    END AS densidade_animais_por_m2,
    ultima_bio.peso_medio,
    ultima_bio.peso_total,
    -- Dados da despesca
    d.data_inicio AS inicio_despesca,
    d.data_fim AS fim_despesca,
    d.qtd_despescada,
    d.peso_medio AS peso_medio_despesca,
    -- Biomassa calculada
    CASE 
        WHEN d.qtd_despescada > 0 AND d.peso_medio > 0 THEN 
            ROUND(d.qtd_despescada * d.peso_medio / 1000, 2)
        ELSE 
            ROUND(ultima_bio.peso_total / 1000, 2)
    END AS biomassa_kg,
    c.data_inicio AS inicio_ciclo,
    c.data_fim AS fim_ciclo,
    -- Duração do ciclo em dias
    (c.data_fim - c.data_inicio) AS duracao_dias
FROM 
    ciclos c
    INNER JOIN tanques t ON c.tanque_id = t.id
    LEFT JOIN povoamentos pov ON pov.ciclo_id = c.id
    LEFT JOIN povoamentos_lotes pl ON pl.povoamento_id = pov.id
    LEFT JOIN lotes l ON l.id = pl.lote_id
    LEFT JOIN despescas d ON d.ciclo_id = c.id
    LEFT JOIN (
        SELECT DISTINCT ON (ciclo_id) 
            ciclo_id, data_analise, total_animais, sobrevivencia, peso_medio, peso_total
        FROM analises_biometricas 
        ORDER BY ciclo_id, data_analise DESC
    ) ultima_bio ON ultima_bio.ciclo_id = c.id
WHERE 
    c.situacao = 8 
    AND c.data_fim >= '2018-01-01' 
    AND c.data_fim IS NOT NULL
    AND ultima_bio.ciclo_id IS NOT NULL
ORDER BY 
    c.data_fim DESC, 
    t.nome, 
    c.numero;
*/
