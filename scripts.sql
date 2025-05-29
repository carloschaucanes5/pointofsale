DELIMITER $$

CREATE TRIGGER triger_update_stock AFTER INSERT ON income_detail
FOR EACH ROW BEGIN
UPDATE  product SET stock = stock + NEW.quantity
WHERE id = NEW.product_id;
END$$
DELIMITER;


DELIMITER $$
CREATE TRIGGER triger_sale_stock AFTER INSERT ON sale_detail
FOR EACH ROW BEGIN
UPDATE  product SET stock = stock - NEW.quantity
WHERE id = NEW.product_id;
END$$
DELIMITER;