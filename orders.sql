-- ============================================================
--  LY Jewels — Orders Table
--  Run this in phpMyAdmin or your MySQL client
-- ============================================================

CREATE TABLE IF NOT EXISTS `orders` (
  `id`                   INT(11)        NOT NULL AUTO_INCREMENT,
  `razorpay_order_id`    VARCHAR(100)   NOT NULL,
  `razorpay_payment_id`  VARCHAR(100)   DEFAULT NULL,
  `razorpay_signature`   VARCHAR(255)   DEFAULT NULL,
  `customer_name`        VARCHAR(200)   NOT NULL,
  `customer_email`       VARCHAR(200)   NOT NULL,
  `customer_phone`       VARCHAR(20)    NOT NULL,
  `customer_address`     TEXT           NOT NULL,
  `total_amount`         DECIMAL(10,2)  NOT NULL,
  `status`               ENUM('pending','paid','failed') DEFAULT 'pending',
  `cart_snapshot`        TEXT           DEFAULT NULL,
  `created_at`           TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `razorpay_order_id` (`razorpay_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
