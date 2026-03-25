BEGIN;
CREATE TABLE transfer
(
  id serial NOT NULL,
  cod_geo character varying NOT NULL,
  id_inmu integer,
  id_proc character varying,
  tan_fech_ini date,
  tan_fech_fin date,
  tan_modo character(3),
  tan_doc character varying,
  tan_mont_usd integer,
  tan_mont_bs integer,
  tan_cara character(3), 
  tan_1id integer,
  tan_2id integer,
  tan_der_fech date,
  tan_der_num character varying,
  UNIQUE (cod_geo, id_inmu, tan_fech_ini, tan_fech_fin)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE transfer OWNER TO postgres;
END;