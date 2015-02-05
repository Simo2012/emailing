-- =======================
-- Table des sessions PHP
-- =======================
CREATE SCHEMA IF NOT EXISTS SESSION;
CREATE TABLE IF NOT EXISTS SESSION.session (
    id       varchar(255)     NOT NULL COMMENT 'Session unique identifier',
    data     text             NOT NULL COMMENT 'Session data',
    updated  int(10) unsigned NOT NULL COMMENT 'Session last updated',
    lifetime mediumint        NOT NULL COMMENT 'Session lifetime',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PHP sessions';

-- ==== Droits ====
GRANT select, insert, update, delete ON SESSION.session to web@localhost;
GRANT select, insert, update, delete ON SESSION.session to web@'192.168.%';
GRANT select, insert, update, delete ON SESSION.session to web@'%';
