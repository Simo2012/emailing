-- =====================================
-- Table des taux de change des devises
-- =====================================
CREATE TABLE IF NOT EXISTS RUBIZZ.currency_rate (
    id           int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Rate unique id',
    currency     char(3)          NOT NULL COMMENT 'Currency',
    rate         decimal(40,20)   NOT NULL COMMENT 'Currency rate',
    date_rate    date             NOT NULL COMMENT 'Date of last check for update',
    date_upload  date             NOT NULL COMMENT 'Date of last update',
    PRIMARY KEY (id),
    UNIQUE KEY currencyRateCurrency_uk (currency)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Currency rate';

-- ==== Droits ====
GRANT select, insert, update, delete ON RUBIZZ.currency_rate to web@'%';
