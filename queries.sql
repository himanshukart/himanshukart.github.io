    CREATE TABLE `gossip`.`users` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `user_name` VARCHAR(45) NULL,
      `password` VARCHAR(100) NULL,
      `email` VARCHAR(100) NULL,
      `browser` VARCHAR(45) NULL,
      `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
      `date_modified` DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`));


/*
    Table for subscription , which shows the subscription data

 */
    CREATE TABLE `gossip`.`subscribers` (
     `id` INT NOT NULL AUTO_INCREMENT,
     `user_id` INT NOT NULL,
     `subscriber_id` VARCHAR(100) NULL,
     `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
     `date_modified` DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      FOREIGN KEY (user_id) REFERENCES users(id));
/*
    Table for publishers
 */
    CREATE TABLE `gossip`.`publishers` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `user_id` INT NOT NULL,
      `publishers_id` VARCHAR(100) NULL,
      `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
      `date_modified` DATETIME DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (`id`),
       FOREIGN KEY (user_id) REFERENCES users(id));
/*
    Table for notification
 */
    CREATE TABLE `gossip`.`notification` (
       `id` INT NOT NULL AUTO_INCREMENT,
       `user_id` INT NOT NULL,
       `message` VARCHAR(1000) NULL,
       `read_status` BOOLEAN,
       `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
       `date_modified` DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (user_id) REFERENCES users(id));
