-- ==============================
-- Table des mouvements
-- ==============================
CREATE TABLE IF NOT EXISTS RUBIZZ.movement (
    id                  int(10) unsigned              NOT NULL COMMENT 'movement id',
    user_id             int(10) unsigned              NOT NULL COMMENT 'User id',
    payment_request_id  int(10) unsigned                       COMMENT 'Payment request id',
    commission_id       int(10) unsigned                       COMMENT 'Commission id',
    movement_type       enum('commission', 'payment') NOT NULL COMMENT 'Movement type',
    date_movement       datetime                      NOT NULL COMMENT 'Movement Date',
    amount_movement     decimal(10,2)                 NOT NULL COMMENT 'Amount mouvement',
    PRIMARY KEY (id),
    KEY DateUser_idx (user_id, date_movement),
    CONSTRAINT movementUser_fk FOREIGN KEY (user_id) REFERENCES RUBIZZ.user (id) ON DELETE RESTRICT,
    CONSTRAINT movementPaymentrequest_fk FOREIGN KEY (payment_request_id) REFERENCES RUBIZZ.payment_request (id) ON DELETE RESTRICT,
    CONSTRAINT movementCommission_fk FOREIGN KEY (commission_id) REFERENCES RUBIZZ.commission (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Movements (consolidated)';

-- ==== Droits ====
GRANT select, insert, update, delete ON RUBIZZ.movement to web@'%';
