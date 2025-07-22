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

DELIMITER $$
CREATE TRIGGER delete_income_detail
AFTER INSERT ON income_detail
FOR EACH ROW
BEGIN
 DELETE FROM income_detail WHERE quantity < 1 AND id <> NEW.id;
END$$
DELIMITER;

CREATE TABLE cash_openings ( id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, users_id BIGINT UNSIGNED NOT NULL, start_amount DECIMAL(10,2) NOT NULL, opened_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, cashbox_name VARCHAR(100) NOT NULL, location VARCHAR(100) DEFAULT NULL, observations TEXT DEFAULT NULL, status ENUM('open', 'closed') DEFAULT 'open', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, CONSTRAINT fk_cash_openings_users FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE ); 

CREATE TABLE cash_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    users_id BIGINT UNSIGNED NOT NULL,
    cash_opening_id BIGINT UNSIGNED DEFAULT NULL,
    type varchar(10) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE movement_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    type ENUM('ingreso', 'egreso', 'neutro') NOT NULL,
    affects_cash BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO movement_types (code, name, type, affects_cash) VALUES
('ING-001', 'Venta de productos', 'ingreso', true),
('ING-002', 'Aporte del propietario', 'ingreso', true),
('ING-003', 'Reintegro de cliente', 'ingreso', true),
('ING-004', 'Cobro de crédito', 'ingreso', true),
('ING-005', 'Ingreso por servicios', 'ingreso', true),

('EGR-001', 'Compra a proveedor', 'egreso', true),
('EGR-002', 'Pago de servicios públicos', 'egreso', true),
('EGR-003', 'Pago de arriendo', 'egreso', true),
('EGR-004', 'Compra de papelería e insumos', 'egreso', true),
('EGR-005', 'Pago de nómina', 'egreso', true),
('EGR-006', 'Devolución a proveedor', 'egreso', true),
('EGR-007', 'Retiro de dinero del dueño', 'egreso', true),

('PRO-001', 'Ajuste por inventario', 'neutro', false),
('PRO-002', 'Entrada por muestra de proveedor', 'ingreso', false),
('PRO-003', 'Salida por vencimiento', 'egreso', false),
('PRO-004', 'Merma o deterioro', 'egreso', false),
('PRO-005', 'Salida por promoción/regalo', 'egreso', false);