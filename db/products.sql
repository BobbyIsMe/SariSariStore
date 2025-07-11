CREATE TABLE `Products` (
  `prod_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `stock_qty` int NOT NULL,
  `brand` varchar(100),
  `item_name` varchar(100),
  `item_details` text,
  `price` decimal(10,2) NOT NULL,
  `item_type` varchar(50),
  FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`)
)