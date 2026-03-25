BEGIN;
CREATE TABLE transfer_contrib_add
(
  cod_geo character varying NOT NULL,  
  id_inmu integer,
  tan_fech_ini date,
  tan_fech_fin date,
  tan_xid integer,
  UNIQUE (cod_geo, id_inmu, tan_fech_ini, tan_fech_fin, tan_xid)
)
WITH (OIDS=FALSE);
ALTER TABLE transfer_contrib_add OWNER TO postgres;
END;