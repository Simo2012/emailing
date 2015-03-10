-- ==============================
-- Table des mouvements
-- ==============================
CREATE TABLE IF NOT EXISTS RUBIZZ.movement (
    id                  int(10) unsigned NOT NULL COMMENT 'movement id',
    user_id             int(10) unsigned NOT NULL COMMENT 'User id',
    paymentRequest_id   int(10) unsigned          COMMENT 'Payment request id',
    commission_id       int(10) unsigned          COMMENT 'Commission id',
    movement_name       varchar(20)      NOT NULL COMMENT 'Movement Name',
    date_movement       datetime         NOT NULL COMMENT 'Movement Date',
    amount_movement     decimal(10,2)    NOT NULL COMMENT 'Amount mouvement',
    PRIMARY KEY (id),
    CONSTRAINT movementUser_fk FOREIGN KEY (user_id) REFERENCES RUBIZZ.user (id) ON DELETE RESTRICT,
    CONSTRAINT movementPaymentrequest_fk FOREIGN KEY (paymentRequest_id) REFERENCES RUBIZZ.paymentrequest (id) ON DELETE RESTRICT,
    CONSTRAINT movementCommission_fk FOREIGN KEY (commission_id) REFERENCES RUBIZZ.commission (id) ON DELETE RESTRICT,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Movements';

-- ==== Droits ====
GRANT select, insert, update, delete ON RUBIZZ.movement to web@'%';
