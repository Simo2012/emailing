-- ===========================
-- Table des offres de Rubizz
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.offer (
    id            int(10) unsigned    NOT NULL AUTO_INCREMENT COMMENT 'Offer unique id',
    country       char(2)             NOT NULL COMMENT 'Country code',
    date_create   datetime            NOT NULL COMMENT 'Creation date',
    date_update   datetime            NOT NULL COMMENT 'Update date',
    title         varchar(200)        NOT NULL COMMENT 'Offer title',
    subtitle      varchar(200)        NOT NULL COMMENT 'Offer subtitle',
    text          varchar(4096)       NOT NULL COMMENT 'Offer text',
    url           varchar(200)        NOT NULL COMMENT 'Offer URL (orginal one)',
    compensation  decimal(10,2)       NOT NULL COMMENT 'Compensation for thet offer',
    active        tinyint(3) unsigned NOT NULL COMMENT 'User active or not',
    platform      enum('tradedoubler', 'cake') NOT NULL COMMENT 'Name of the platform (website)',
    platform_id   varchar(200)        NOT NULL COMMENT 'Platform unique identifier',
    category      set('game', 'estimate', 'e-commerce', 'travel', 'finance', 'untertainment', 'special-offer') NOT NULL COMMENT 'List of possible tags',
    PRIMARY KEY (id),
    KEY offerCountryActive_idx (country, active, category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offers';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.offer to web@'%';
