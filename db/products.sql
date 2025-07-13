CREATE TABLE `Products` (
  `product_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `category_id` bigint(11) NOT NULL,
  `brand` varchar(100),
  `stock_qty` int NOT NULL,
  `total_sales` int NOT NULL DEFAULT 0,
  `item_name` varchar(100),
  `item_details` text,
  `price` decimal(10,2) NOT NULL,
  FOREIGN KEY (`category_id`) REFERENCES Categories(`category_id`),
)

CREATE TABLE `Categories` (
  `category_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(50) NOT NULL
);