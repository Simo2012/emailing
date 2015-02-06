-- =============================
-- Table des contacts de Rubizz
-- =============================
CREATE TABLE IF NOT EXISTS RUBIZZ.contact (
    id                  int(10) UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Contact unique id',
    date_create         datetime            NOT NULL COMMENT 'Creation date',
    date_update         datetime            NOT NULL COMMENT 'Update date',
    firstname           varchar(100)        NOT NULL COMMENT 'Contact firstname',
    lastname            varchar(100)        NOT NULL COMMENT 'Contact lastname',
    email               varchar(100)        NOT NULL COMMENT 'Contact e-mail',
    subscriber          tinyint(3) unsigned NOT NULL COMMENT 'Indicate that we can send an email to him',
    direct_unsubscribe  tinyint(3) unsigned NOT NULL COMMENT 'Indicate that he dont''t want to receive further email',
    user_id             int(10) UNSIGNED    NOT NULL COMMENT 'User Id (owner)',
    PRIMARY KEY (id),
    UNIQUE KEY contactUserEmail_uk (user_id, email),
    CONSTRAINT contactUser_fk FOREIGN KEY (user_id) REFERENCES RUBIZZ.user (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User contacts';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.contact to web@'%';
