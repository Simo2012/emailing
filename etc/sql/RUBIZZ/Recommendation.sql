-- ===========================
-- Table des recommandations
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.recommendation (
    id           int(10)    unsigned  NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    user_id      int(10)    unsigned  NOT NULL COMMENT 'Corresponding user',
    offer_id     int(10)    unsigned  NOT NULL COMMENT 'Corresponding offer',
    type         enum('email', 'facebook', 'twitter') NOT NULL COMMENT 'Type of recommendation',
    to_send      tinyint(3) unsigned  NOT NULL DEFAULT 0 COMMENT 'Recommendation of type email sending status',
    date_create  datetime             NOT NULL COMMENT 'Creation date',

    PRIMARY KEY (id),
    KEY recommendationUser_idx (user_id),
    KEY recommendationOffer_idx (offer_id),
    CONSTRAINT recommendationUser_fk FOREIGN KEY (user_id) REFERENCES RUBIZZ.user (id) ON DELETE RESTRICT,
    CONSTRAINT recommendationOffer_fk FOREIGN KEY (offer_id) REFERENCES RUBIZZ.offer (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recommendations';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.recommendation to web@'%';

-- ===========================================================
-- Table de liaison (n,m) entre "recommendation" et "contact"
-- ===========================================================
CREATE TABLE IF NOT EXISTS RUBIZZ.recommendation_contacts (
    recommendation_id int(10) unsigned     NOT NULL COMMENT 'Recommendation id',
    contact_id        int(10) unsigned     NOT NULL COMMENT 'Contact id',
    PRIMARY KEY (recommendation_id, contact_id),
    CONSTRAINT recommendationContactRecommendation_fk FOREIGN KEY (recommendation_id) REFERENCES RUBIZZ.recommendation (id) ON DELETE CASCADE,
    CONSTRAINT recommendationContactContact_fk FOREIGN KEY (contact_id) REFERENCES RUBIZZ.contact (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Recommendations linked with contacts';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.recommendation_contacts to web@'%';
