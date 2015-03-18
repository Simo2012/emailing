-- ===========================
-- Table des offres de Rubizz
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.track (
    id                 int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    date_create        datetime         NULL     COMMENT 'Creation date',
    date_update        datetime         NULL     COMMENT 'Update date used for stats',
    ip_address         int(10) UNSIGNED NOT NULL COMMENT 'IP address used',
    recommendation_id  int(10) UNSIGNED NOT NULL COMMENT 'Recommendation Id',
    contact_id         int(10) UNSIGNED NULL     COMMENT 'Contact Id if email',
    date_open          datetime         NULL     COMMENT 'Open date if email',
    date_click         datetime         NULL     COMMENT 'Click date',
    date_lead          datetime         NULL     COMMENT 'Lead date',
    date_sale          datetime         NULL     COMMENT 'Sale date (transformation)',
    sale_amount        decimal(10,2)    NULL     COMMENT 'Sale amount given by the website',

    PRIMARY KEY (id),
    UNIQUE KEY (recommendation_id, ip_address),
    KEY (date_update, recommendation_id),
    CONSTRAINT trackRecommendation_fk FOREIGN KEY (recommendation_id) REFERENCES RUBIZZ.recommendation (id) ON DELETE CASCADE,
    CONSTRAINT trackContact_fk FOREIGN KEY (contact_id) REFERENCES RUBIZZ.contact (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tracks of recommendations';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.track to web@'%';
