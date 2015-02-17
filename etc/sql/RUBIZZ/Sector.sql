-- ========================================
-- Table des secteurs d'activité de Rubizz
-- ========================================
CREATE TABLE IF NOT EXISTS RUBIZZ.sector (
    id      int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    name_en varchar(100)     NOT NULL                COMMENT 'Sector name EN',
    name_fr varchar(100)     NOT NULL                COMMENT 'Sector name FR',
    PRIMARY KEY (id),
    UNIQUE KEY sectorNameEn_uk (name_en),
    UNIQUE KEY sectorNameFr_uk (name_fr)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Activity sectors';

-- ==== Droits ====
GRANT select, insert, update, delete ON RUBIZZ.sector to web@'%';

-- ==== Valeurs ====
insert into RUBIZZ.sector (name_en, name_fr)
values ('Bank / Finance / Insurance', 'Bank / Finance / Assurance'),
    ('Beauty / Health', 'Beauté / Santé'),
    ('Car', 'Voiture'),
    ('Dating', 'Rencontre'),
    ('Entertainment', 'Loisirs'),
    ('Flowers and gifts', 'Fleurs et cadeaux'),
    ('Food and Beverage', 'Aliments et boissons'),
    ('High Tech', 'High tech'),
    ('Home and Garden', 'Maison et jardinage'),
    ('Other', 'Autre'),
    ('Psychic', 'Psychologie'),
    ('Publisher', 'Annonceur'),
    ('Real Estate', 'Immobilier'),
    ('Retail', 'Vente au détail'),
    ('Telephony', 'Téléphonie'),
    ('Training / Education', 'Formation / Education'),
    ('Travel', 'Voyages'),
    ('Association', 'Association');

