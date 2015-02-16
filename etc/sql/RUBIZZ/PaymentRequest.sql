-- ===============================
-- Table des demandes de paiement
-- ===============================
CREATE TABLE IF NOT EXISTS RUBIZZ.payment_request (
    id           int(10) UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Unique id',
    date_create  datetime            NOT NULL COMMENT 'Creation date',
    date_update  datetime            NOT NULL COMMENT 'Update date',
    user_id      int(10) UNSIGNED    NOT NULL COMMENT 'User Id (owner)',
    amount       decimal(10,2)       NOT NULL COMMENT 'Amount required (euros)',
    status       enum('waiting', 'confirmed', 'error') NOT NULL COMMENT 'Status of the request',
    bankName     varchar(100)        NOT NULL COMMENT 'Bank name if confirmed',

    PRIMARY KEY (id),
    KEY paymentRequestUser_idx (user_id, date_create),
    KEY paymentRequestStatus_idx (status, date_create),
    CONSTRAINT paymentRequestUser_fk FOREIGN KEY (user_id) REFERENCES RUBIZZ.user (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User payment requests';

-- ==== droits ====
GRANT select, insert, update, delete ON RUBIZZ.payment_request to web@'%';
