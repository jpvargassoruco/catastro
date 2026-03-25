BEGIN;
CREATE TABLE transfer_rural_contrib_add
(
  id_predio_rural integer,
  tan_fech_ini date,
  tan_fech_fin date,
  tan_xid integer,
  UNIQUE (id_predio_rural,tan_fech_ini,tan_fech_fin,tan_xid)
)
WITH (OIDS=FALSE);
ALTER TABLE transfer_rural_contrib_add OWNER TO postgres;
END;