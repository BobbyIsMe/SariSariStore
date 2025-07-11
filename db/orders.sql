CREATE TABLE `orders` (
  `order_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `approved_by_staff_id` bigint(11),
  `status` varchar(50),
  `date_time_completed` datetime,
  `date_time_received` datetime,
  `total` decimal(10,2),
  FOREIGN KEY (`approved_by_staff_id`) REFERENCES Staff(`user_id`)
)

CREATE TABLE `order_products` (
  `order_id` bigint(11) NOT NULL,
  `prod_id` bigint(11) NOT NULL,
  `qty` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`, `prod_id`),
  FOREIGN KEY (`order_id`) REFERENCES Orders(`order_id`),
  FOREIGN KEY (`prod_id`) REFERENCES Products(`prod_id`)
)