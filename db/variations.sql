CREATE TABLE `Variations` (
  `variation_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `variation_name` varchar(100) NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES Products(`product_id`)
);