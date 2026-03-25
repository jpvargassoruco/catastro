CREATE TABLE imp_control_transfer
(
  no_orden integer PRIMARY KEY,
  form varchar, 
  fech_imp date,
  hora character(8),
  usuario varchar,
  cod_geo character varying NOT NULL,
  id_inmu integer,
  cat_val bigint,
  min_val bigint,
  min_mon varchar,
  min_fech date,
  id_comp int,
  cuota int,
  control character(8),
  observ varchar
)
WITH (OIDS=FALSE);
ALTER TABLE imp_control_transfer OWNER TO postgres;

