CREATE TABLE `Products` (
  `product_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL,
  `brand` varchar(100),
  `stock_qty` int NOT NULL,
  `total_sales` int NOT NULL DEFAULT 0,
  `item_name` varchar(100),
  `item_details` text,
  `price` decimal(10,2) NOT NULL,
  `item_type` varchar(50),
  FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`),
)