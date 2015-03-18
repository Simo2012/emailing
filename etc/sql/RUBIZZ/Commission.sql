-- ===========================
-- Table des commissions
-- ===========================
CREATE TABLE IF NOT EXISTS RUBIZZ.commission (
    id                 int(10) unsigned    NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    date_create        datetime            NOT NULL COMMENT 'Creation date',
    recommendation_id  int(10) unsigned    NOT NULL COMMENT 'Corresponding recommendation',
    contact_id         int(10) unsigned    NULL     COMMENT 'Corresponding contact if type email',
    transaction        varchar(255)        NOT NULL COMMENT 'Transaction id sent by the platform',
    amount             decimal(10,2)       NOT NULL COMMENT 'Calculation of the amount of a commission for the member',
    amount_rbz         decimal(10,2)       NOT NULL COMMENT 'Calculation of the amount of a commission for Rubizz',
    valid_offer        tinyint(1) unsigned NOT NULL COMMENT 'Tells if the commission is valid for the member',

    PRIMARY KEY (id),
    KEY commissionRecommendation_idx (recommendation_id),
    UNIQUE KEY commissionRecommendationTransaction_uk (recommendation_id, transaction),
    KEY commissionDatecreateRecommendation_idx (date_create, recommendation_id),
    CONSTRAINT commissionRecommendation_fk FOREIGN KEY (recommendation_id) REFERENCES RUBIZZ.recommendation (id) ON DELETE CASCADE,
    CONSTRAINT commissionContact_fk FOREIGN KEY (contact_id) REFERENCES RUBIZZ.contact (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Commissions';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.commission to web@'%';
