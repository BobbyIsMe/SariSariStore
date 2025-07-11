CREATE TABLE `Carts` (
  `cart_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`)
)

CREATE TABLE `cart_items` (
  `cart_id` bigint(11) NOT NULL,
  `prod_id` bigint(11) NOT NULL,
  `item_qty` int NOT NULL,
  PRIMARY KEY (`cart_id`, `prod_id`),
  FOREIGN KEY (`cart_id`) REFERENCES Carts(`cart_id`),
  FOREIGN KEY (`prod_id`) REFERENCES Products(`prod_id`)
)