-- =======================================
-- Table des annonceurs de Rubizz (brand)
-- =======================================
CREATE TABLE IF NOT EXISTS RUBIZZ.brand_rubizz (
    id         int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    brand_id   int(10) UNSIGNED NOT NULL COMMENT 'Brand id',
    sector_id  int(10) UNSIGNED NOT NULL COMMENT 'Sector id',

    PRIMARY KEY (id),
    CONSTRAINT brandRubizzBrand_fk FOREIGN KEY (brand_id) REFERENCES SYNCHRO.brand (id) ON DELETE CASCADE,
    CONSTRAINT brandRubizzSector_fk FOREIGN KEY (sector_id) REFERENCES RUBIZZ.sector (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rubizz brands';

-- ==== Droits ====
GRANT select, insert, update, delete ON RUBIZZ.brand_rubizz to web@'%';

