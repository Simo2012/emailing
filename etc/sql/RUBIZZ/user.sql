CREATE SCHEMA IF NOT EXISTS RUBIZZ;

-- =================================
-- Table des utilisateurs de Rubizz
-- =================================
CREATE TABLE IF NOT EXISTS RUBIZZ.user (
    id                int(10) unsigned       NOT NULL AUTO_INCREMENT COMMENT 'User unique id',
    country           char(2)                NOT NULL COMMENT 'Country code',
    date_create       datetime               NOT NULL COMMENT 'Creation date',
    date_update       datetime               NOT NULL COMMENT 'Update date',
    date_login        datetime               NOT NULL COMMENT 'Last login date',
    firstname         varchar(100)           NOT NULL COMMENT 'User firstname',
    lastname          varchar(100)           NOT NULL COMMENT 'User lastname',
    password          varchar(100)           NOT NULL COMMENT 'User password',
    email             varchar(100)           NOT NULL COMMENT 'User e-mail',
    active            tinyint(3) unsigned    NOT NULL COMMENT 'User active or not',
    use_facebook      tinyint(3) unsigned    NOT NULL COMMENT 'Indicate that he published once on Facebook',
    use_twitter       tinyint(3) unsigned    NOT NULL COMMENT 'Indicate that he published once on Twitter',
    use_email         tinyint(3) unsigned    NOT NULL COMMENT 'Indicate that he published once on Email',
    bic               varchar(4096)          NOT NULL COMMENT 'JSON : BIC (Bank Identifier Code)',
    optin_newsletter  tinyint(3) unsigned    NOT NULL COMMENT 'Indicate if he wants to receive newsletter',
    nb_contacts       smallint(5) unsigned   NOT NULL COMMENT 'Number of email contacts',
    available_amount  decimal(10,2) unsigned NOT NULL COMMENT 'Amount available immediately',
    PRIMARY KEY (id),
    UNIQUE KEY userEmail_uk (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.user to web@'%';
