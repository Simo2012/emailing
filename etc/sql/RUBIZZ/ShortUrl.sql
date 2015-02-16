-- ===========================
-- Table des offres de Rubizz
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.short_url (
    id                 int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    date_create        datetime         NOT NULL COMMENT 'Creation date',
    recommendation_id  int(10) UNSIGNED NOT NULL COMMENT 'Recommendation Id',
    contact_id         int(10) UNSIGNED NULL     COMMENT 'Contact Id if email',
    type               enum('open', 'click', 'sale') NOT NULL   COMMENT 'Link type',

    PRIMARY KEY (id),
    KEY shortUrlDatecreate_idx (date_create),
    CONSTRAINT shortUrlRecommendation_fk FOREIGN KEY (recommendation_id) REFERENCES RUBIZZ.recommendation (id) ON DELETE CASCADE,
    CONSTRAINT shortUrlContact_fk FOREIGN KEY (contact_id) REFERENCES RUBIZZ.contact (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Short urls for recommendations';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.short_url to web@'%';
