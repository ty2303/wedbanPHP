-- Add voucher-related columns to orders table
ALTER TABLE orders 
ADD COLUMN voucher_id INT NULL,
ADD COLUMN voucher_code VARCHAR(50) NULL,
ADD COLUMN discount_amount DECIMAL(10, 2) DEFAULT 0,
ADD COLUMN subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0,
ADD FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL;

-- Update existing orders to have subtotal equal to total_amount
UPDATE orders SET subtotal = total_amount WHERE subtotal = 0;
