CREATE TABLE `Notifications` (
  `notification_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `cart_id` bigint(11) NOT NULL,
  `message` text NOT NULL,
  `date_time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` boolean NOT NULL DEFAULT false,
  FOREIGN KEY (`cart_id`) REFERENCES Carts(`cart_id`)
);