-- ===========================
-- Table des offres de Rubizz
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.offer (
    id                int(10) unsigned     NOT NULL AUTO_INCREMENT COMMENT 'Offer unique id',
    country           char(2)              NOT NULL COMMENT 'Country code',
    date_create       datetime             NOT NULL COMMENT 'Creation date',
    date_update       datetime             NOT NULL COMMENT 'Update date',
    title             varchar(200)         NOT NULL COMMENT 'Offer title',
    text              varchar(4096)        NOT NULL COMMENT 'Offer text',
    url               varchar(200)         NOT NULL COMMENT 'Offer URL (orginal one)',
    compensation      decimal(10,2)        NOT NULL COMMENT 'Compensation for thet offer',
    active            tinyint(3) unsigned  NOT NULL COMMENT 'User active or not',
    date_start        datetime             NOT NULL COMMENT 'Start of the offer availability',
    date_end          datetime             NOT NULL COMMENT 'End of the offer availability',
    advertiser_cookie smallint(5) unsigned NOT NULL COMMENT 'Number of days for the cookie',
    member_cookie     smallint(5) unsigned NOT NULL COMMENT 'Number of days for the cookie',
    platform          enum('manual', 'tradedoubler', 'cake') NOT NULL COMMENT 'Name of the platform (website)',
    platform_id       varchar(200)             NULL COMMENT 'Platform unique identifier',
    category          set('game', 'estimate', 'e-commerce', 'travel', 'finance', 'untertainment', 'special-offer') NOT NULL COMMENT 'List of possible tags',
    publishing        set('email', 'facebook', 'twitter')    NOT NULL COMMENT 'List of possible publishing',
    subsidiary_id     int(10) unsigned     NOT NULL COMMENT 'Corresponding subsidiary',
    rem_type          enum('CPC', 'CPL', 'CPA%', 'CPA')      NOT NULL COMMENT 'Remuneration type',
    rem_advertiser    decimal(10,2)        NOT NULL COMMENT 'Remuneration amount or % from the advertiser',
    rem_volume        int(10) unsigned     NOT NULL COMMENT 'Number of available offers',
    rem_illimited     tinyint(3) unsigned  NOT NULL COMMENT 'If the volume is illimited',
    rem_member        decimal(10,2)        NOT NULL COMMENT 'Remuneration amount or % for the member',
    brand_id          int(10) unsigned     NOT NULL COMMENT 'Corresponding brand',

    PRIMARY KEY (id),
    KEY offerCountryActive_idx (country, active, category),
    CONSTRAINT offerSubsidiary_fk FOREIGN KEY (subsidiary_id) REFERENCES SYNCHRO.subsidiary (id) ON DELETE RESTRICT,
    CONSTRAINT offerBrand_fk FOREIGN KEY (brand_id) REFERENCES SYNCHRO.brand (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offers';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.offer to web@'%';
