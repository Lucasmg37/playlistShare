CREATE VIEW vw_acessoplaylist AS SELECT COUNT(*) AS nu_acesso, vwpl.* FROM tb_acesso AS ac
JOIN vw_playlist AS vwpl ON ac.id_playlist = vwpl.id_playlist
WHERE vwpl.bl_ativo = 1
GROUP BY ac.id_playlist ORDER by COUNT(*) DESC