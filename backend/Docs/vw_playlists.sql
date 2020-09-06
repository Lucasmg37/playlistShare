CREATE VIEW vw_playlist 
AS 
  SELECT
         pl.id_playlist AS vw_primary_id_playlist,
         pl.id_playlist,
         pl.st_nome, 
         pl.bl_privada, 
         pl.dt_create, 
         pl.id_usuario, 
         pl.bl_ativo, 
         pl.st_descricao, 
         pl.bl_sincronizado, 
         pl.st_idspotify, 
         pl.bl_publicedit, 
         pl.st_capa,
         pl.bl_sincronizar,
         us.st_nome AS st_nomeusuario,
         us.st_login, 
         spin.st_displayname, 
         spin.st_email, 
         qtdmu.nu_music 
  FROM   tb_playlist AS pl 
         JOIN tb_usuarios AS us 
           ON pl.id_usuario = us.id_usuario 
         LEFT JOIN tb_spotifyintegracao spin 
                ON us.id_usuario = spin.id_usuario 
         LEFT JOIN (SELECT Count(mup.id_musicplaylist) AS nu_music,
                      mup.id_playlist 
               FROM   tb_musicplaylist AS mup
                    WHERE mup.bl_ativo = 1
               GROUP  BY mup.id_playlist) AS qtdmu 
           ON qtdmu.id_playlist = pl.id_playlist 