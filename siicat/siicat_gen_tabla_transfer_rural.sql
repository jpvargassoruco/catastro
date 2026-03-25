BEGIN;
CREATE TABLE transfer_rural
(
  id serial PRIMARY KEY,
  id_predio_rural integer,
  id_proc character varying,
  tan_fech_ini date,
  tan_fech_fin date,
  tan_modo character(3),
  tan_doc character varying,
  tan_mont_bs integer,
  tan_mont_usd integer,
  tan_cant smallint,
  tan_cara character(3), 
  tan_1id integer,
  tan_2id integer,
  tan_xid character varying,
  UNIQUE (id_predio_rural, tan_fech_ini, tan_fech_fin)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE transfer_rural OWNER TO postgres;
END;