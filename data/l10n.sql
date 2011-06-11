DROP TABLE IF EXISTS translations;

CREATE TABLE translations (
  'id'         INTEGER,
  'name'       TEXT NOT NULL,
  'translator' TEXT NOT NULL,
  'date'       DATETIME NOT NULL default CURRENT_TIMESTAMP,
  'category'   VARCHAR(25) NOT NULL, -- po-debconf, d-i...
  'type'       VARCHAR(10) NOT NULL, -- ITT, RFR, BTS...
  'status'     VARCHAR(10) NOT NULL default 'open', -- open, closed...
  'file'       TEXT default NULL,
  'bugnr'      INTEGER UNSIGNED default NULL,
  PRIMARY KEY ('id')
);
