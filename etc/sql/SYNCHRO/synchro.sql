-- ==================
-- Table des clients
-- ==================
CREATE SCHEMA IF NOT EXISTS SYNCHRO;
CREATE TABLE SYNCHRO.advertiser (
  id        int(10) unsigned     NOT NULL COMMENT 'advertiser unique id',
  name      varchar(100)         NOT NULL COMMENT 'advertiser name',
  type      enum('client','prospect','blocked') NOT NULL COMMENT 'advertiser type',
  active    tinyint(3) unsigned  NOT NULL COMMENT 'Advertiser active or not',
  rubizz    tinyint(3) unsigned  NOT NULL COMMENT 'If usable for rubizz',
  validated tinyint(3) unsigned  NOT NULL COMMENT 'Advertiser validated or not',

  PRIMARY KEY (id),
  KEY advertiserActive_idx (type, active, rubizz, validated, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Advertisers';

-- ==== Droits ====
GRANT select, insert, update, delete ON SYNCHRO.advertiser to web@'%';

--==============================
-- Table des annonceurs (brand)
--==============================
CREATE TABLE IF NOT EXISTS SYNCHRO.brand (
  id              int(10) unsigned    NOT NULL COMMENT 'Brand unique id',
  name            varchar(100)        NOT NULL COMMENT 'Brand name',
  state           enum('complete', 'incomplete') NULL COMMENT 'Brand state',
  rubizz          tinyint(3) unsigned NOT NULL COMMENT 'Brand use rubizz',
  advertiserid    int(10) unsigned    NOT NULL COMMENT 'Advertiser id',
  countryId       int(10) unsigned    NOT NULL COMMENT 'Corresponding country',
  subsidiaryId    int(10) unsigned    NULL COMMENT 'Brand contact id',
  stateRubizz     enum('incomplete','complete') NOT NULL,

  PRIMARY KEY (id),
  CONSTRAINT brandAdvertiserId_fk FOREIGN KEY (advertiserId) REFERENCES SYNCHRO.advertiser (id),
  CONSTRAINT brandCountryId_fk FOREIGN KEY (countryId) REFERENCES SYNCHRO.country (id) ON DELETE RESTRICT,
  CONSTRAINT brandSubsidiaryId_fk FOREIGN KEY (subsidiaryId) REFERENCES SYNCHRO.subsidiary (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Brands';

-- ==== Droits ====
GRANT select, insert, update, delete ON SYNCHRO.brand to web@'%';

-- ===============
-- Table des pays
-- ===============
CREATE TABLE IF NOT EXISTS SYNCHRO.country (
    id   int(10) UNSIGNED NOT NULL COMMENT 'Country unique id',
    name varchar(100)     NOT NULL COMMENT 'Country name',
    code char(2)          NOT NULL COMMENT 'Country code',
    lang char(2)          NOT NULL COMMENT 'Country lang',

    PRIMARY KEY (id),
    KEY countryName_idx (name),
    UNIQUE KEY countryCode_uk (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries';

-- ==== Droits ====
GRANT select, insert, update, delete ON SYNCHRO.country to web@'%';

-- ==============================
-- Table des filliales de Natexo
-- ==============================
CREATE TABLE IF NOT EXISTS SYNCHRO.subsidiary (
    id                int(10) unsigned NOT NULL COMMENT 'Subsidiary unique id',
    name              varchar(100)     NOT NULL COMMENT 'Subsidiary name',
    shortname         varchar(10)      NOT NULL COMMENT 'Subsidiary short name',
    country           char(2)          NOT NULL COMMENT 'Country code',
    PRIMARY KEY (id),
    UNIQUE KEY subsidiaryShortname_uk (shortname),
    KEY subsidiaryName_idx (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Natexo subsidiaries';

-- ==== Droits ====
GRANT select, insert, update, delete ON SYNCHRO.subsidiary to web@'%';

